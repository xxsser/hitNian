<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.home');
    }

    public function cancheCode(Request $request){
        $attributes = [
            'code' => '核销码',
            'phone' => '手机号码',
        ];
        $this->validate($request, [
            'code' => 'required|integer',
            'phone'=> 'regex:/^1[34578][0-9]{9}$/'
        ], [], $attributes);
        $stateRe = collect();
        $attack = new \App\Attack;
        //掉落信息
        $attack_info = $attack::where(['id'=>($request->input('code') - 10000),'isget'=>0])->first();
        if(!$attack_info){
            return $stateRe->push(['state'=>'codeErr'])->collapse();
        }
        //奖品信息
        $gift_info = $attack_info->prize;
        //查询所有同类奖品 id
        $gifts = $attack->join('prizes','attacks.prize_id','=','prizes.id')
            ->where(['attacks.fan_id'=>$attack_info->fan_id,'prizes.rank'=>$gift_info->rank])
            ->lists('prizes.name','attacks.id');
        $ids = array();
        $gift_name = array();
        if($gift_info->rank == 4){
            $moneyCount = 0;
        }
        foreach($gifts as $key=>$value){
            array_push($ids,$key);
            array_push($gift_name,$value);
            //现金奖统计
            if(isset($moneyCount)){
                $moneyCount += intval($value);
            }
        }
        //更新领取状态
        $update = $attack::whereIn('id',$ids)->update(['isget'=>1]);
        if($update){
            $stateRe->push(['gift'=>$gift_name]);
            $stateRe->push(['state'=>'success']);
            if(isset($moneyCount)){
                $stateRe->push(['moneyCount'=>$moneyCount]);
            }
        }
        //输入用户手机，则保存用户
        if($request->input('phone') != ''){
            self::saveUserInfo($attack_info->fid,$request->input('phone'),$request->input('name'));
        }
        return $stateRe->collapse();
    }

    /* *
     * 二维码核销页面
     * */
    public function cancheWithQr($fid){
        $userPrizes =  \App\Http\Controllers\PrizeController::getFanPrizes($fid);
        $prizes = array();
        $countMoney = 0;
        foreach($userPrizes as $k=>$v){
            array_push($prizes,$v['prize']);
            $countMoney += intval($v['prize']['name']);
            asort($prizes);
        }
        return view('admin.cancheWithQr',compact('prizes','countMoney','fid'));
    }

    /* *
     * 二维码核销操作
     * */
    public function cacheFid(Request $request){
        $attributes = [
            'fid' => '用户ID',
            'phone' => '手机号码',
        ];
        $this->validate($request, [
            'fid'   => 'required|integer',
            'phone' => 'regex:/^1[34578][0-9]{9}$/',
        ], [], $attributes);
        if($request->input('key') == 888){
            $up = \App\Exchange::where(['fan_id'=>$request->input('fid'),'isget'=>0])->update(['isget'=>1]);
            if($up > 0 ){
                if($request->input('phone') != ''){
                    self::saveUserInfo($request->input('fid'),$request->input('phone'),$request->input('name'));
                }
                return ['state'=>'success'];
            }else{
                return ['state'=>'noup'];
            }
        }
        return ['state'=>'keyErr'];
    }

    protected function saveUserInfo($fid,$phone,$name=''){
        $userInfo = new \App\Userinfo();
        if(!$userInfo::where('fan_id',$fid)->count()){
            $userInfo::insert(['fan_id'=>$fid,'phone'=>$phone,'name'=>$name]);
        }
    }
}
