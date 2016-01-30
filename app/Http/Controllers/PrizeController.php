<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use DB;
class PrizeController extends Controller
{
    //
    //用户奖品兑换页
    public function exPrize(){
        if(!Session::has('logged_user')){
            self::getWeAuth();
        }
        return view('nianshou.exprize',[
            'gifts'  =>\App\Prize::Exchange()->select('id','name','coin')->get(),
        ]);
    }

    //处理用户奖品兑换
    public function getExchange(Request $request){
        if(!Session::has('logged_user')){
            return ['state'=>'noAuth'];
        }
        $fid = Session::get('logged_user')['fid'];
        //查询用户所有金币
        $userCoin = \App\Gamedata::where('fan_id', $fid)->pluck('coins');
        //查询奖品所需金币
        $giftInfo =  \App\Prize::select('coin')->where('id',$request->input('pid'))->where('coin','<=',$userCoin)->Exchange()->first();
        if(empty($giftInfo)){
            return ['state'=>'coin_lack'];
        }
        //查询用户是否已兑换改奖品
        $is_exchanged = \App\Exchange::where(['fan_id'=>$fid,'prize_id'=>$request->input('pid')])->first();
        if(empty($is_exchanged)){
            //处理数据
            DB::beginTransaction();
            try {
                //添加兑换记录
                DB::table('exchanges')->insert([
                    'fan_id'    =>  $fid,
                    'prize_id'  =>  $request->input('pid'),
                ]);
                //扣除用户金币
                DB::table('gamedatas')->where('fan_id',$fid)->decrement('coins',$giftInfo->coin);
                //消减库存
                DB::table('prizes')->where('id',$request->input('pid'))->decrement('num');
                DB::commit();
            }catch(Exception $e) {
                DB::rollback();
            }
            return ['state'=>'success'];
        }else{
            return ['state'=>'exchanged'];
        }
    }

    //用户查询奖品页
    public function myPrize(){
        $user = self::getWeAuth();
        return view('nianShou.myGift',[
            'user'  =>  $user,
            'gifts' =>  self::getFanPrizes($user['fid'])
        ]);
    }

    //获取用户奖品查询
    public static function getFanPrizes($fid){
        $prizes = \App\Exchange::with(['prize'=>function($query){
            $query->select('id','name','rank','type');
        }])->select('id','prize_id','created_at')
            ->where('fan_id',$fid)->unget()->get();
        return $prizes->toArray();
    }

    protected static function getWeAuth(){
        $wechat = new \App\Http\Controllers\WeAuthController;
        return $wechat->getWechatInfo();
    }
}
