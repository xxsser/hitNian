<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" name="viewport" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <title>年兽大战,稀有宝物随机爆</title>
    <style>
        *{margin:0; padding:0; -webkit-tap-highlight-color:rgba(0,0,0,0);}
        body{background-image:url(../Images/startbak.jpg); background-repeat:no-repeat; background-size:100%; background-color: #e04e46; background-position:left top;}
        img{max-width:100%;}
        #hear2{width:60%; margin-top:22%; -webkit-animation-name:myfirst2; -webkit-animation-duration:5s; -webkit-animation-play-state:running; -webkit-animation-iteration-count:infinite;}
        #ranking{width:74%; position:absolute; top:20%; left:10%; display:none; z-index:10000;  padding:3%; background:url(/Images/box.png); background-repeat:no-repeat; background-size:100%;}
        @-webkit-keyframes myfirst2{
            0%   { -webkit-transform:rotate(0deg);}
            50%  { -webkit-transform:rotate(-10deg);}
            100% { -webkit-transform:rotate(0deg);}
        }
        #p2{width:90%;}
        #butt{width:100%;position:relative;}
        #m1{position:absolute;top:25%;left:5%;color:#FFF;font-size:0.7em;font-weight:bold;width:50%;}
        @media all and (max-width:320px){ #hear2{width:60%;}
            #butt{width:88%;}
            #p2{width:80%;}
        }
        #customers {  font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  width:100%;  border-collapse:collapse;  }
        #customers td, #customers th {  font-size:1em;  border:1px solid #e6c7e8;  padding:3px 7px 2px 7px;  }
        #customers th {  font-size:1.1em;  padding-top:5px;  padding-bottom:4px;  background-color: #ea7568;  color:#ffffff;  }
        #customers tr.alt td {  color:#000000;  background-color: #f26d80;  opacity: 0.5;  }
    </style>
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        wx.config({!! $js->config(['onMenuShareTimeline','onMenuShareAppMessage']) !!});
        wx.ready(function(){
            wx.onMenuShareTimeline(setShare('moment'));
            //“分享给朋友”按钮点击状态及自定义分享内容接口
            wx.onMenuShareAppMessage(setShare('friend'));
        });
        $(function(){
            $('#start').click(function(){
                window.location.href = '/nianshou'
            });
            //排行榜
            $("#open_rank").click(function(){
                $('#ranking').show();
            });
            //关闭排行榜
            $("#closeRank").click(function(){
                $("#ranking").hide();
            });
        });
        function setShare(trend){
            return {
                title: '每天摇一摇，摇出年兽大礼包', // 分享标题
                        desc: '动动手腕，攻击年兽有奖品，更有iphone62等豪礼等着你，年兽大战等你来!', // 分享描述
                    link: 'http://hdwyc.3pdj.com/sharelink/'+$('#fid').val(), // 分享链接
                    imgUrl: 'http://hdwyc.3pdj.com/Images/fx.jpg', // 分享图标
                    type: 'link', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () {
                // 用户确认分享后执行的回调函数
                $.post('/share',{'fid':$('#fid').val(),'shared':trend},function(data){
                    if(data.state == 'success'){
                        var count = $('#attackNum').text();
                        $('#attackNum').text(count+1);
                        $('#share').hide();
                    }
                });
            }, cancel: function () {}
            }
        }
    </script>
</head>
<body>
<input type="hidden" id="fid" value="{{ $user['fid'] }}" />
<div id="m1">当前参与人数{{ $userNum }}人<br />距活动结束还有{{ \Carbon\Carbon::create(2016,2,8)->diffInDays() }}天</div>
<div id="p1">
    <div style="text-align:center;margin-top: 20%">
        <img src="/Images/shou.png" id="hear2" />
    </div>
    <dl style="width:100%;color:#FFF;font-size:0.8em;text-align:center;line-height:1.5em;margin-top:2%;">
        <marquee direction="up" height="30" scrollamount="1">
            <p style="text-align: center">
                @foreach($coin_soon as $k=>$v)
                    {{ $v['fan']['nikename'] }} 攻击年兽获得了 {{ $v['coin'] }}个金币<br>
                @endforeach
            </p>
        </marquee>
    </dl>
    <div style="text-align:center;margin:3% 0 2%;" id="butt">
        <img src="/Images/start.png" style="width:28%;" id="start" />
        <img src="/Images/rank.png" style="width:28%;" id="open_rank" />
    </div>
    <div style="text-align:center;width:100%;"><img src="/Images/logo.png" style="width:60%;" /></div>
</div>
<div id="p2" style="margin:0 auto;position:relative;display:none;">
    <img src="/Images/2.png" />
    <img src="/Images/3.png" style="width:24%;position:absolute;top:77%;left:38%;" id="back" />
    <div style="text-align:center;width:100%;margin-top:28%;"><img src="/Images/logo.png" style="width:60%;" /></div>
</div>

<div id="ranking" >
    <dl style="font-size:0.8em;color:#FFF; line-height:1.8em;height:255px;overflow-y:auto;text-align: center;list-style:none">
        <h2>年兽英雄榜</h2>
        <table id="customers">
            <tr>
                <th>排名</th>
                <th>英雄昵称</th>
                <th>总伤害值</th>
            </tr>
            @foreach($rank as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value['fan']['nikename'] }}</td>
                    <td>{{ $value['damages'] }}</td>
                </tr>
            @endforeach
        </table>
    </dl>
    <div id="closeRank" style="width:100%;text-align:center;margin-top:2%;"><img src="/Images/ok.png" style="width:20%;" /></div>
</div>
</body>
</html>