<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

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
        $money = array();
        $gift = array();
        $countMoney = 0;
        foreach($userPrizes as $k=>$v){
            switch($v['prize']['type']) {
                case 'money' :
                    array_push($money,$v['prize']['name']);
                    $countMoney += intval($v['prize']['name']);
                    break;
                case 'gift' :
                    array_push($gift,$v['prize']['name']);
                    break;
            }
        }
        $prizes = ['money'=>$money,'gift'=>$gift];
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
            'type'  => 'required|in:money,gift',
        ], [], $attributes);
        if($request->input('key') == 888){  //验证密钥
            $up = DB::table('exchanges')->leftJoin('prizes' , 'exchanges.prize_id', '=' ,'prizes.id')
                ->where(['exchanges.fan_id'=>$request->input('fid'),'prizes.type'=>$request->input('type')])
                ->update(['exchanges.isget'=>1]);
            //$up = \App\Exchange::where(['fan_id'=>$request->input('fid'),'type'=>$request->input('type')])->unget()->update(['isget'=>1]);
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
