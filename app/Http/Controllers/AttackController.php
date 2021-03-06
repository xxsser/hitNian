<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class AttackController extends Controller
{
    public function getAttack(Request $request){
        $this->validate($request, [
            'fid'   => 'required|integer',
        ]);
        //判断用户当日攻击次数
        $reState = collect();
        $atkNum = self::getAttackCount($request->input('fid'));
        if($atkNum['surplus'] > 0){
            //生成对年兽的随机伤害值
            $blackID = self::getBlackID();
            if(in_array($request->input('fid'),$blackID)){
                $damageValue = mt_rand(1,20);
            }else{
                $damageValue = mt_rand(config('customs.minDamage'),config('customs.maxDamage'));
            }
            //获取用户游戏数据
            $fan = \App\Gamedata::select('damages','coins')
                ->where('fan_id',$request->input('fid'))->first();
            if($fan->coins >= config('customs.maxCoins')){
                $coinValue = 0;
            }else{
                //生成年兽金币随机掉落值
                $coinValue = mt_rand(config('customs.minCoin'),config('customs.maxCoin'));
            }
            $reState->push(['damage'=>$damageValue,'coins'=>$coinValue]);
        }else{
            return $reState->push(['state'=>'over','isShare'=>$atkNum['isShare']])->collapse();
        }
        //保存单次攻击信息
        \App\Attack::insert([
            'fan_id'   =>  $request->input('fid'),
            'damage'   =>  $damageValue,
            'coin'     =>  $coinValue,
        ]);

        //将攻击值和金币相加
        if(!DB::table('gamedatas')
            ->where('fan_id', $request->input('fid'))
            ->update([
                'damages' => DB::raw('damages + '.$damageValue),
                'coins' => DB::raw('coins + '.$coinValue),
                'updated_at'=>Carbon::now(),
            ])){
            \App\Gamedata::insert([
                'fan_id'    =>  $request->input('fid'),
                'damages'   =>  $damageValue,
                'coins'     =>  $coinValue,
            ]);
        }
        return $reState->push(['state'=>'success'])->collapse();
    }

    /*
     * 获取用户当日剩余攻击次数
     * @return int
     * */
    public static function getAttackCount($fid){
        $attack_num = \App\Fan::findOrFail($fid)->attacks()->select('id')->where('created_at','>',Carbon::today())->count();
        $share_num = \App\Share::where('fan_id','=',$fid)->where('created_at','>',Carbon::today())->count();
        if(empty($share_num)){
            return ['surplus'=>config('customs.dailyAttack') - $attack_num,'isShare'=>'no'];
        }else{
            return ['surplus'=>config('customs.dailyAttack') - $attack_num + 1,'isShare'=>'yes'];
        }
    }

    /*
     * 返回黑名单用户ID
     * @return array
     * */
    protected static function getBlackID(){
        return [660,944,606,778,990,1425,725,1464,1408,1322,781];
    }
}
