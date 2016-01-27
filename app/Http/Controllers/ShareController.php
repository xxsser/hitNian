<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\URL;
use Mockery\CountValidator\Exception;
use Session;
use DB;
class ShareController extends Controller
{
    //用户分享后处理逻辑
    public function main(Request $request ){
        $this->validate($request, [
            'fid'   => 'required|integer',
        ]);
        \App\Fan::findOrFail($request->input('fid'));
        $shareInfo = new \App\Share;
        $num = $shareInfo::where('fan_id',$request->input('fid'))->where('created_at','>',Carbon::today())->count();
        $shareInfo::insertGetId(['fan_id'=>$request->input('fid'),'shared'=>$request->input('shared','other')]);
        if(empty($num)){
            return collect(['state'=>'success']);
        }
    }

    //用户分享链接验证跳转
    public function shareLink($parentid) {
        //判断父级用户金币是否超限
        $par_data = \App\Gamedata::where('fan_id',$parentid)->first();
        if($par_data->coins >= config('customs.coinLimit')){
            return redirect(URL::action('IndexController@startIndex'));
        }
        //检查session是否有fid
        if(!Session::has('logged_user')){
            //获取用户openid
            $wechat = new \App\Http\Controllers\WeAuthController();
            $user = $wechat->wechatAuth();
            //查询是否有该用户
            if(empty(\App\Fan::where('openid',$user->openid)->first()) && \App\Fan::find($parentid)){
                DB::beginTransaction();
                try{
                    //保存用户信息 获取新用户ID
                    $user_id = DB::table('fans')->insertGetId([
                        'nikename'  =>  $user->nickname,
                        'openid'    =>  $user->openid,
                        'sex'       =>  $user->sex,
                    ]);
                    //保存用户关系到关系表
                    DB::table('recommends')->insert([
                        'fan_id'    =>  $user_id,
                        'parent_id' =>  $parentid,
                    ]);
                    //给分享用户奖励50金币
                    if(DB::table('gamedatas')->where('fan_id',$parentid)->count()){
                        DB::table('gamedatas')->where('fan_id',$parentid)->increment('coins',config('customs.shareCoin'),['updated_at'=>Carbon::now()]);
                    }else{
                        DB::table('gamedatas')->insert([
                            'fan_id'    =>  $parentid,
                            'coins'     =>  config('customs.shareCoin'),
                        ]);
                    }
                    DB::commit();
                }catch(Exception $e) {
                    DB::rollback();
                }
            }
            $wechat->getWechatInfo($user);
        }
        return redirect(URL::action('IndexController@startIndex'));
    }
}
