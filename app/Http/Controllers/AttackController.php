<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use DB;

class AttackController extends Controller
{
    public function getAttack(Request $request){
        //判断用户当日攻击次数
        $reState = collect();
        $atkNum = self::getAttackCount($request->input('fid'));
        if($atkNum['surplus'] > 0){
            //生成对年兽的随机伤害值
            $damageValue = mt_rand(config('customs.minDamage'),config('customs.maxDamage'));
            //生成年兽金币随机掉落值
            $coinValue = mt_rand(config('customs.minCoin'),config('customs.maxCoin'));
            $reState->push(['damage'=>$damageValue,'coins'=>$coinValue]);
        }else{
            return $reState->push(['state'=>'over','isShare'=>$atkNum['isShare']])->collapse();
        }
        /*
        //统计次数，向奖池投放奖品
        self::poolToGift(self::getLuckDate());
        //掉落奖品，获取奖品信息
        $gift = self::getGiftResult(self::getLuckDate());
        //奖品信息储存
        if(!empty($gift)){
            $reState->push(['gift'=>$gift['name']]);
        }
        $attackGift = new \App\Attack();
        $attackGift::insert([
            'fan_id'      =>  $request->input('fid'),
            'prize_id'      =>  $gift['id']?$gift['id']:0,
            'damage'    =>  $damageValue,
        ]);
        */
        //保存单次攻击信息
        \App\Attack::insert([
            'fan_id'   =>  $request->input('fid'),
            'damage'   =>  $damageValue,
            'coin'     =>  $coinValue,
            'prize_id' =>  0,
        ]);

        //将攻击值和金币相加
        if(!DB::table('gamedatas')
            ->where('fan_id', $request->input('fid'))
            ->update([
                'damages' => DB::raw('damages + '.$damageValue),
                'coins' => DB::raw('coins + '.$coinValue),
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

    /* *
     * 获取奖品概率数组
     * */
    private static function getLuckDate(){
        //取出奖品信息
        $pize = new \App\Prize;
        $pizes = $pize::select('id','name','factor','denominator','rate')->where('type','=','drop')->get()->all();
        //生成奖品概率
        $luckData = array();
        foreach($pizes as $pizeInfo){
            $luckInfo = [
                'id'            =>  $pizeInfo->id,
                'name'          =>  $pizeInfo->name,
                'factor'        =>  $pizeInfo->factor,
                'denominator'   =>  $pizeInfo->denominator,
                'rate'          =>  $pizeInfo->rate,
            ];
            array_push($luckData,$luckInfo);
        }
        return $luckData;
    }

    /*
     * 统计次数，向奖池投放奖品
     */
    private static function poolToGift($result){
        $today = Carbon::today()->toDateString();
        if (!Cache::has($today.'_attackCount')){
            $expiresAt = Carbon::now()->endOfDay();
            Cache::put($today.'_attackCount', 1, $expiresAt);
        }
        Cache::increment($today.'_attackCount');
        $attackCount = Cache::get($today.'_attackCount');
        foreach($result as $val){
            if($attackCount != 1 && $attackCount % $val['rate'] == 1){
                $gift = new \App\Prize;
                $current_num = $gift::find($val['id'])->num;
                if($current_num > 0){
                    $gift::where('id',$val['id'])->increment('pool');
                    $gift::where('id',$val['id'])->decrement('num');
                }
            }
        }
    }

    /**
     * 获取抽奖结果
     * @param type $coupon_data  奖品数组
     * @return int 奖品id
     */
    private function getGiftResult($result){
        shuffle($result);
        $attr = array();
        foreach ($result as $key => $val) {
            if(mt_rand(1, $val['denominator']) <= $val['factor']){
                $attr[] = $val['id'];
            }
        }
        if(empty($attr)){
            return false;
        }
        while (($item = array_pop($attr)) != null) {
            //判断奖池是否有库存
            $pize = new \App\Prize;
            $gift = $pize::where('pool','>','0')->find($item);
            if(!empty($gift)){
                return $gift;
            }
        }
        return false;
    }
}
