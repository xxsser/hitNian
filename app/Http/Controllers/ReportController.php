<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private static $form;
    private static $to;

    public function __construct()
    {
        //self::$form = Carbon::yesterday();
        self::$form = Carbon::today()->subWeek();
        self::$to = Carbon::today();
    }

    public function index(){
        return view('admin.report',[
            'count' => self::getCountUser(),
            'yesterday_count' => self::getCountUser(['form'=>self::$form,'to'=>self::$to]),
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
        return \App\Attack::whereBetween('created_at',[self::$form,self::$to])->sum('coin');
    }

    private function getShareNum(){
        return \App\Share::whereBetween('created_at',[self::$form,self::$to])->count('id');
    }

    private function getShareCoinNum(){
        return self::getShareNum() * config('customs.shareCoin');
    }

    private function getAttackFanCount(){
        return DB::table('attacks')->whereBetween('created_at',[self::$form,self::$to])->count(DB::raw('DISTINCT fan_id'));
    }

    private function getShareLinkUser(){
        return DB::table('recommends')
            ->whereBetween('created_at',[self::$form,self::$to])
            ->count('id');
    }

    private function getExchangeInfo(){
        $prizes = DB::select('SELECT P.name,COUNT(*) AS pnum from exchanges EX JOIN prizes P ON EX.prize_id=P.id WHERE EX.created_at BETWEEN ? AND ? GROUP BY `prize_id`;', [self::$form,self::$to]);
        return $prizes;
    }

    public function buildCoin(){
        $coins = DB::select('SELECT fan_id,SUM(coin) AS cnum from attacks WHERE created_at > "2016-02-22" GROUP BY `fan_id`');
        //dd($coins[1]->cnum);
        foreach($coins as $coin){
            \App\Gamedata::where('fan_id',$coin->fan_id)->increment('coins',$coin->cnum);
            /*DB::table('gamedatas')
                ->where('fan_id', $coin['fan_id'])
                ->update([
                    'coins' => DB::raw('coins + '.$coin['cnum']),
                    'updated_at'=>Carbon::now(),
                ]);*/
        }
        echo 'ok';
    }

}
