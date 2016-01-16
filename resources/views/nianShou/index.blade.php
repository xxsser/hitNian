<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" name="viewport" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>年兽大战,稀有宝物随机爆</title>
    <link rel="stylesheet" href="Css/style.css?v=011" />
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        wx.config({!! $js->config(['onMenuShareTimeline','onMenuShareAppMessage']) !!});
        wx.ready(function(){
            wx.onMenuShareTimeline(setShare('moment'));
            wx.onMenuShareAppMessage(setShare('friend'));
        });
    </script>
</head>
<body>
<canvas id="canvas">Canvas is not supported in your browser.</canvas>
<input type="hidden" id="fid" value="{{ $user['fid'] }}" />
<div class="share" id="share"><img src="Images/share.png" /></div>
<div class="chuizi-box">
    <img src="Images/jida.png" id="chuizi"/>
</div>
<div style="position: absolute; top: 0 ;left: 0; margin-top: 20%">
    <img src="Images/boom.png" />
</div>
<div class="coin-box">
    <img src="Images/diaojinbi.png" id="jinbi"/>
</div>
<div class="gameDataBox">
    <img src="Images/coin.png" class="imgLeft" />
    <img src="Images/shanghai.png" class="imgRight" />
    <label class="numLeft" id="coins">{{ $userData['coins'] or 0 }}</label>
    <label class="numRight" id="damages">{{ $userData['damages'] or 0 }}</label>
</div>
<div class="attackNumBox">
    <img src="Images/gongjicishu.png" />
    <div>剩余<label id="attackNum">{{ $attackNum['surplus'] or 0 }}</label>次攻击</div>
</div>
<div id="p1">
    <div class="center">
        <img src="Images/nian.png" class="nian" id="nianshou" />
    </div>
<dl class="gameButton">
    <div class="center fullWidth">
        <img src="Images/shengyu.png" class="btnLeft" id="exprize"/>
        <img src="Images/attack.png" class="btnRight" id="attack" />
    </div>
    <div class="center fullWidth">
        <img src="Images/logo.png" class="logo"/>
    </div>
    </dl>
</div>
<div class="exchange-box" id="exBox" >
    <dl>
        <h2>奖品兑换</h2>
        <table id="customers">
            <tr>
                <th>奖品</th>
                <th>需求金币</th>
                <th>兑换</th>
            </tr>
            @foreach($gifts as $gift)
                <tr>
                    <td>{{ $gift['name'] }}</td>
                    <td>{{ $gift['coin'] }}</td>
                    <td><button class="exchange btn-short" value="{{ $gift['id'] }}">兑换</button></td>
                </tr>
            @endforeach
        </table>
    </dl>
    <div class="close" id="close-exBox" ><img src="Images/ok.png" /></div>
    <div class="center">
        <button class="btn-long" id="gift-list-btn">查看所有奖品</button>
    </div>
    <div class="center">
        <a href="http://mp.weixin.qq.com/s?__biz=MzA4NTU1NzA5OA==&mid=207414262&idx=1&sn=3923b1d296e5e250b09269110cd9f427&scene=1&srcid=0110gZ8qcnYGQPiAdb7pnO0i#rd"><button class="btn-long">我的奖品</button></a>
    </div>
</div>
<div class="alert" id="box">
    <img src="Images/t.png" />
    <div id="alertext" class="text">您今天的机会已用完，明天再来哦~</div>
    <img src="Images/ok.png" class="btn close" />
</div>
<div class="gift-list">
    <img src="Images/jpnr.png" />
    <img src="Images/3.png" class="back close" />
</div>
{{--<audio id="audio" width="0" height="0"><source src="sound/5018.wav" /></audio>--}}
<div hidden>
    {{--<audio id="musicBox" controls="controls">
        <source src="Sound/shake.mp3" type="audio/mp3">
    </audio>--}}
</div>
{{--<audio id="audio" width="0" height="0" autoplay="autoplay" loop="loop"><source src="Sound/sond.mp3"></audio>--}}
</body>
<script type="text/javascript" src="Js/jquery.min.js"></script>
<script type="text/javascript" src="Js/jQueryRotateCompressed.js"></script>
<script type="text/javascript" src="Js/jquery-jrumble.js"></script>
<script type="text/javascript" src="Js/fireworks.js"></script>
<script type="text/javascript" src="Js/main.js?v=1.2"></script>
</html>