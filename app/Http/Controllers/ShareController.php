<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Session;
use DB;
class ShareController extends Controller
{
    //用户分享后处理逻辑
    public function main(Request $request ){
        \App\Fan::findOrFail($request->input('fid'));
        $shareInfo = new \App\Share;
        $num = $shareInfo::where('fan_id',$request->input('fid'))->where('created_at','>',Carbon::today())->count();
        $shareInfo::insertGetId(['fan_id'=>$request->input('fid'),'shared'=>$request->input('shared','other')]);
        if(empty($num)){
            return collect(['state'=>'success']);
        }
    }

    //用户分享链接验证跳转
    public function shareLink($fid) {
        //检查session是否有fid
        if(Session::has('logged_user')){
            //return redirect('/');
        }
        //获取用户openid
        $wechat = new \App\Http\Controllers\WeAuthController();
        $user = $wechat->wechatAuth();
        //查询是否有该用户
        if(empty(\App\Fan::where('openid',$user->openid)->first()) && \App\Fan::find($fid)){
            DB::beginTransation();
            //保存用户信息

            //给分享用户奖励50金币

            //跳转到游戏首页
        }
        return redirect('/');
    }
}
