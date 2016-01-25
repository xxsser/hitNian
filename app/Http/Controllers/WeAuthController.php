<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Overtrue\Wechat\Auth;
use Overtrue\Wechat\Js;

class WeAuthController extends Controller
{
    private $appId;
    private $secret;

    public function __construct()
    {
        $this->appId =  'wx4fcd9d1125da2b30';
        $this->secret = '304ed60a2c8b052cea2b9589f4427861';
    }

    //授权用户
    public function wechatAuth(){
        $auth = new Auth($this->appId, $this->secret);
        $user = $auth->authorize(); // 返回用户 Bag
        return $user;
    }

    //获取微信用户信息
    public function getWechatInfo($userInfo=false){
        if (session('logged_user')) {
            $user = session('logged_user');
        } else {
            if($userInfo==false){
                $user = self::wechatAuth();
            }else{
                $user = $userInfo;
            }
            $user->add('fid',self::getFanid( $user->all() ) );
            session(['logged_user'=>$user->all()]);
        }
        return $user;
    }

    public function jsSet(){
        $js = new Js($this->appId, $this->secret);
        return $js;
    }

    /* *
     * 获取用户id，用户不存在则自动添加
     * */
    public function getFanid($userInfo){
        $user = new \App\Fan;
        $info = $user::where('openid',$userInfo['openid'])->first();
        if(empty($info)){
            $fid = $user::insertGetId(['nikename'=>$userInfo['nickname'],
                'openid'=>$userInfo['openid'],
                'sex'=>$userInfo['sex'],
            ]);
        }else{
            $fid = $info->id;
        }
        return $fid;
    }

    /* *
     * 检查是否有该用户
     * */
    public function checkFan($openid,$fid=false){
        if($openid !== false){
            return \App\Fan::where('openid',$openid)->count();
        }else if($fid !== false){
            return \App\Fan::where('id',$fid)->count();
        }
    }
}
