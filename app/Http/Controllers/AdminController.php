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
    public function cancheFid(Request $request){
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
