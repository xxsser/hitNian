<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" name="viewport" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>年兽大战,稀有宝物随机爆</title>
    <link rel="stylesheet" href="/Css/style.css" />
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="Js/main.js?v=1.1"></script>
    <script type="text/javascript" src="/Js/jQueryRotateCompressed.js"></script>
    <script type="text/javascript" src="/Js/jquery-jrumble.js"></script>
    <script type="text/javascript">
        wx.config({!! $js->config(['onMenuShareTimeline','onMenuShareAppMessage']) !!});
        wx.ready(function(){
            var share = {
                title: '每天摇一摇，摇出年兽大礼包', // 分享标题
                desc: '动动手腕，攻击年兽有奖品，更有iphone62等豪礼等着你，年兽大战等你来!', // 分享描述
                link: 'http://hdwyc.3pdj.com/sharelink/'+$('#fid').val(), // 分享链接
                imgUrl: 'http://hdwyc.3pdj.com/Images/fx.jpg', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                    $.post('/share',{'fid':$('#fid').val(),'shared':'moment'},function(data){
                        if(data.state == 'success'){
                            var count = $('#attackNum').text();
                            $('#attackNum').text(count+1);
                            $('#share').hide();
                        }
                    });
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            }
            wx.onMenuShareTimeline(share);
            //“分享给朋友”按钮点击状态及自定义分享内容接口
            wx.onMenuShareAppMessage(share);
        });
    </script>
</head>
<body>
<input type="hidden" id="fid" value="{{ $user['fid'] }}" />
<div class="share" id="share"><img src="/Images/share.png" /></div>
<div class="chuizi-box">
    <img src="/Images/jida.png" id="chuizi"/>
</div>
<div class="coin-box">
    <img src="/Images/diaojinbi.png" id="jinbi"/>
</div>
<div class="gameDataBox">
    <img src="/Images/coin.png" class="imgLeft" />
    <img src="/Images/shanghai.png" class="imgRight" />
    <label class="numLeft" id="coins">{{ $userData['coins'] or 0 }}</label>
    <label class="numRight" id="damages">{{ $userData['damages'] or 0 }}</label>
</div>
<div class="attackNumBox">
    <img src="/Images/gongjicishu.png" />
    <div>剩余<label id="attackNum">{{ $attackNum['surplus'] or '0' }}</label>次攻击</div>
</div>
<div id="p1">
    <div class="center">
        <img src="/Images/nian.png" class="nian" id="nianshou" />
    </div>
<dl class="gameButton">
    <div class="center fullWidth">
        <img src="/Images/shengyu.png" class="btnLeft" id="exprize"/>
        <img src="/Images/attack.png" class="btnRight" id="attack" />
    </div>
    <div class="center fullWidth">
        <img src="/Images/logo.png" class="logo"/>
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
                    <td><button class="exchange" value="{{ $gift['id'] }}">兑换</button></td>
                </tr>
            @endforeach
        </table>
    </dl>
    <div class="close" id="close-exBox" ><img src="/Images/ok.png" /></div>
</div>
<div class="alert" id="box">
    <img src="/Images/t.png" />
    <div id="alertext" class="text">您今天的机会已用完，明天再来哦~</div>
    <img src="/Images/ok.png" class="btn" id="ok" />
</div>
<audio id="audio" width="0" height="0"><source src="sound/5018.wav" /></audio>
<div hidden>
    {{--<audio id="musicBox" controls="controls">
        <source src="Sound/shake.mp3" type="audio/mp3">
    </audio>--}}
</div>
{{--<audio id="audio" width="0" height="0" autoplay="autoplay" loop="loop"><source src="Sound/sond.mp3"></audio>--}}
</body>
</html>