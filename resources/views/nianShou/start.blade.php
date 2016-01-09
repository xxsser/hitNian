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
        body{background-image:url(Images/startbak.jpg); background-repeat:no-repeat; background-size:100%; background-color: #e04e46; background-position:left top;}
        img{max-width:100%;}
        #hear2{width:60%; margin-top:22%; -webkit-animation-name:myfirst2; -webkit-animation-duration:5s; -webkit-animation-play-state:running; -webkit-animation-iteration-count:infinite;}
        #ranking,#explain{width:70%; position:absolute; top:20%; left:10%; display:none; z-index:999;  padding:5%; background:url(Images/box.png); background-repeat:no-repeat; background-size:100%;}
        @-webkit-keyframes myfirst2{
            0%   { -webkit-transform:rotate(0deg);}
            50%  { -webkit-transform:rotate(-10deg);}
            100% { -webkit-transform:rotate(0deg);}
        }
        #p2{width:90%;}
        #butt{width:100%;position:relative;}
        #m1{position:absolute;top:20%;left:5%;color:#FFF;font-size:0.7em;width:35%;background: black;opacity: 0.7;border-top-left-radius: 20px;border-bottom-right-radius:20px; padding: 5px;text-align: center}
        @media all and (max-width:320px){ #hear2{width:60%;}
            #butt{width:88%;}
            #p2{width:80%;}
        }
        #customers {  font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  width:100%;  border-collapse:collapse;  }
        #customers td, #customers th {  font-size:1em;  border:1px solid #e6c7e8;  padding:3px 7px 2px 7px;  }
        #customers th {  font-size:1.1em;  padding-top:5px;  padding-bottom:4px;  background-color: #ea7568;  color:#ffffff;  }
        #customers tr.alt td {  color:#000000;  background-color: #f26d80;  opacity: 0.5;  }
    </style>
    <script src="Js/jquery.min.js"></script>
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
                window.location.href = 'nianshou'
            });
            //排行榜
            $("#open_rank").click(function(){
                $('#ranking').show();
            });
            //关闭排行榜

            $(".close").click(function(){
                $(this).parent().hide();
            });

            $('#guize').click(function(){
                $('#explain').show();
            });
        });
        function setShare(trend){
            return {
                title: '每天摇一摇，摇出年兽大礼包', // 分享标题
                        desc: '动动手腕，攻击年兽有奖品，更有iphone62等豪礼等着你，年兽大战等你来!', // 分享描述
                    link: 'http://hdwyc.3pdj.com/sharelink/'+$('#fid').val(), // 分享链接
                    imgUrl: 'http://hdwyc.3pdj.comImages/fx.jpg', // 分享图标
                    type: 'link', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () {
                // 用户确认分享后执行的回调函数
                $.post('share',{'fid':$('#fid').val(),'shared':trend},function(data){
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
<div  id="guize" style="position: absolute; right: 0px;top: 3%;">
    <img src="Images/yxgz.png" style="width: 80%"/>
</div>
<div id="m1">当前参与人数{{ $userNum }}人<br />活动剩余{{ \Carbon\Carbon::create(2016,2,8)->diffInDays() }}天</div>
<div id="p1">
    <div style="text-align:center;margin-top: 20%">
        <img src="Images/shou.png" id="hear2" />
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
    <div style="width:100%;text-align:center;margin:3% 0 2%;" id="butt">
        <img src="Images/start.png" style="width:28%;" id="start" />
        <img src="Images/rank.png" style="width:28%;" id="open_rank" />
    </div>
    <div style="text-align:center;width:100%;"><img src="Images/logo.png" style="width:60%;" /></div>
</div>
<div id="p2" style="margin:0 auto;position:relative;display:none;">
    <img src="Images/2.png" />
    <img src="Images/3.png" style="width:24%;position:absolute;top:77%;left:38%;" id="back" />
    <div style="text-align:center;width:100%;margin-top:28%;"><img src="Images/logo.png" style="width:60%;" /></div>
</div>

<div id="ranking" style="height: 50%" >
    <dl style="font-size:0.8em;color:#FFF; line-height:1.8em;height:65%;overflow-y:auto;text-align: center;list-style:none">
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
    <div class="close" style="width:100%;text-align:center;margin-top:2%;"><img src="Images/ok.png" style="width:30%;" /></div>
</div>

<div id="explain" style="height: 50%">
    <dl style="font-size:0.8em;color:#FFF; font-weight: bold; line-height:1.8em;height: 65%;overflow-y:auto;">
        <dd>1.每人每天可以攻击年兽两次。将页面分享给好友或者朋友圈可以增加一次攻击机会。</dd>
        <dd>2.每次攻击年兽将随机掉落金币，每次攻击金币<img src="Images/coin.png" style="width: 25%" />和伤害值<img src="Images/shanghai.png" style="width: 25%" />予以累加。</dd>
        <dd>3.好友点击通过您的分享首次进入游戏，可获赠50金币。</dd>
        <dd>4.金币可在游戏中兑换礼品。</dd>
        <dd>5.英雄榜伤害排名5分钟刷新一次，最终排名以活动结束时为准。</dd>
        <hr>
        <dd>1.现金券累计满50元以上，一周内未兑换，自动清零。</dd>
        <dd>2.实物奖累计满200元以上，一周内未兑换，自动清零</dd>
        <dd>3.奖品领取时间：逢周六、周日        上午9:30-12:00</dd>
        <dd>4.获得奖金与实物奖品的小伙伴需在指定兑奖时间至华东万悦城兑奖处兑换。</dd>
        <dd>5.获得的奖金兑换后可在万悦城任何店铺无门槛消费使用（包括服饰、餐饮、电影院、水吧、摊位等所有万悦城商户）</dd>
        <dd>6.实物奖现金券享受批发价后使用，满260元使用50元，以此类推。实物现金券满50元(及倍数)起兑。</dd>
        <dd>7.每个人每个手机号只能参加一次兑奖，对违反规定者将取消其兑奖资格，没收手机并处罚。</dd>
        <dd>9.用户可点击“西安华东万悦城”微信公众账号页面下方“兑奖中心”查看所获现金及实物礼品。</dd>
        <dd>10.活动期间，用户全程免费参与，获奖用户将获得唯一兑换码和二维码，兑换码和二维码仅限本人兑换，转发无效。</dd>
        <dd>11.获得奖金与礼品仅限活动时间内有效，活动时间结束后未兑换过期作废，视为自愿放弃。</dd>
        <dd>12.活动详细规则以店内公告为准；</dd>
        <dd>13.中奖价值超过1000元以上，按照国家相关法律法规中奖人需缴纳金额20%的个人所得税。</dd>
        <dd>14.获得奖金满十元倍数起兑，不找零，不退现，可累计使用。</dd>
        <dd>15.线上游戏参与时间：2016年1月10日-2016年1月31日</dd>
        <dd>16.客服电话：029-68290999</dd>
        <dd>17.地址：长乐西路朝阳门外300米（原华东品牌服饰加盟广场）</dd>
        <dd>18.商户优惠券的使用，以店内通知为准</dd>
        <dd style="margin-top:5%;"><a href="tel:029-68290999"><img src="images/bh.png" style="width:30%;margin:0 20% 0 10%;" /></a><a href="http://map.qq.com/?l=329065895"><img src="images/dh.png" style="width:30%;" /></a></dd>
    </dl>
    <div class="close" style="width:100%;text-align:center;margin-top:2%;"><img src="images/ok.png" style="width:30%;" /></div>
</div>
</body>
</html>