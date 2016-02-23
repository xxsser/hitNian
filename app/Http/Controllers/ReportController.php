<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(){
        return view('admin.report',[
            'count' => self::getCountUser(),
            'yesterday_count' => self::getCountUser(['form'=>Carbon::yesterday(),'to'=>Carbon::today()]),
            'yesterday_attack_coin' => self::getAttackCoinNum(),
            'yesterday_share_coin' => self::getShareCoinNum(),
            'yesterday_join_num' => self::getAttackFanCount(),
            'yesterday_share_num' => self::getShareNum(),
            'yesterday_sharelink_user' => self::getShareLinkUser(),
            'yesterday_exchang_prize' => self::getExchangeInfo(),
        ]);
    }

    private function getCountUser($range=''){
        if(is_array($range)){
            return \App\Fan::whereBetween('created_at',[$range['form'],$range['to']])->count('id');
        }
        return \App\Fan::count('id');
    }

    private function getAttackCoinNum(){
        return \App\Attack::whereBetween('created_at',[Carbon::yesterday(),Carbon::today()])->sum('coin');
    }

    private function getShareNum(){
        return \App\Share::whereBetween('created_at',[Carbon::yesterday(),Carbon::today()])->count('id');
    }

    private function getShareCoinNum(){
        return self::getShareNum() * config('customs.shareCoin');
    }

    private function getAttackFanCount(){
        return DB::table('attacks')->whereBetween('created_at',[Carbon::yesterday(),Carbon::today()])->count(DB::raw('DISTINCT fan_id'));
    }

    private function getShareLinkUser(){
        return DB::table('recommends')
            ->whereBetween('created_at',[Carbon::yesterday(),Carbon::today()])
            ->count('id');
    }

    private function getExchangeInfo(){
        $prizes = DB::select('SELECT P.name,COUNT(*) AS pnum from exchanges EX JOIN prizes P ON EX.prize_id=P.id WHERE EX.created_at BETWEEN ? AND ? GROUP BY `prize_id`;', [Carbon::yesterday(),Carbon::today()]);
        return $prizes;
    }

    public function buildCoin(){
        $coins = DB::select('SELECT fan_id,SUM(coin) AS cnum from attacks WHERE created_at > "2016-02-22" GROUP BY `fan_id`');
        dd($coins);
        foreach($coins as $coin){
            DB::table('gamedatas')
                ->where('fan_id', $coin['fan_id'])
                ->update([
                    'coins' => DB::raw('coins + '.$coin['cnum']),
                    'updated_at'=>Carbon::now(),
                ]);
        }
    }

}
