<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" name="viewport" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>万悦城 - 奖品中心</title>
</head>
<style>
    *{margin:0; padding:0; -webkit-tap-highlight-color:rgba(0,0,0,0);}
    body{background-image:url(Images/bg.jpg); background-repeat:no-repeat; background-size:100%; background-color: #e04e46;}
    img{max-width:100%;}
    .giftlist{width:100%;list-style:none;color:#FFF;float:left;background:#f26d80;font-weight: bold}
    .giftlist li {margin-bottom:1%;text-align:center;}
    .giftlist li div{font-size:1em;padding:2% 0;}
</style>
<script src="Js/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<body>
<br>
<div style="width:100%;margin-bottom:2%;color:#FFF;font-weight:bold;font-size:1.2em;text-align:center;padding-top:55%;">我的奖品</div>
<div style="width:84%;margin:0 auto;background:#c3406a;padding:1%;font-size:1em;">
    <ul class="giftlist">
        @if(empty($gifts))
            <li>
                <div>您还没有奖品，快去击打年兽拿奖品吧！</div>
            </li>
        @else
            @foreach($gifts as $k=>$v)
                <li>
                    <div> {{ $v['created_at'] }}<br/>{{ $v['prize']['name'] }}</div>
                </li>
                <hr>
            @endforeach
                <li>
                    <div>您的兑奖码ID: {{ $user['fid'] }}<br/>请在兑奖时出示该二维码</div>
                    <div style="text-align: center;">
                        <img src="http://qr.topscan.com/api.php?text={{ URL::action('AdminController@cancheWithQr',['id'=>Session::get('logged_user')['fid']]) }}" />
                    </div>
                </li>
        @endif
    </ul>
    <div style="clear:both;"></div>
</div>
<br>
<div style="text-align:center;width:100%;margin-top:1%;"><a href="http://www.029qq.net/2015/10/wych/"><img src="Images/lianjie.png" style="width:50%;" /></a></div>
<div style="text-align:center;width:100%;margin-top:1%;"><img src="Images/logo.png" style="width:30%;" /></div>

</body>
</html>
