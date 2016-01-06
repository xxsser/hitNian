<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    protected $wechat;
    protected $user;
    public function __construct()
    {
        $this->wechat = new \App\Http\Controllers\WeAuthController;
    }
    //开始页面
    public function startIndex(){
        return view('nianShou.start',[
            'js'        =>  $this->wechat->jsSet(),
            'userNum'   =>  self::getUserCount(),
            'coin_soon' =>  self::coinSoon(),
            'rank'      =>  self::getRanking(),
        ]);
    }

    //活动首页
    public function index(){
        $this->user = $this->wechat->getWechatInfo();
        return view('nianshou.index',[
            'user'      =>  $this->user,
            'js'        =>  $this->wechat->jsSet(),
            'attackNum' =>  \App\Http\Controllers\AttackController::getAttackCount($this->user['fid']),
            'userData'  =>  \App\Gamedata::where('fan_id',$this->user['fid'])->first(),
            'gifts'  =>     \App\Prize::Exchange()->select('id','name','coin')->get(),
        ]);
    }

    //获取排名
    protected function getRanking(){
        if (Cache::has('ranking')) {
            $rank = Cache::get('ranking');
        }else{
            $rank = \App\Gamedata::with(['fan'=>function($query){
                $query->select('id','nikename');
            }])->select('fan_id','damages')->orderBy('damages','desc')->take(10)->get();
            Cache::put('ranking', $rank, 10);
        }
        return $rank;
    }

    //获取当前参与人数
    protected function getUserCount(){
        return \App\Fan::count();
    }

    //获取最近掉落金币的用户
    protected function coinSoon(){
        if (Cache::has('coinSoon')) {
            return Cache::get('coinSoon');
        }else{
            $coinSoon = \App\Attack::with([
                'fan'=>function($query){
                    $query->select('id','nikename');
                }])->select('fan_id','coin')->hascoin()->orderBy('id','desc')->take(20)->get();
            Cache::put('coinSoon', $coinSoon, 10);
        }
        return $coinSoon;
    }
}
