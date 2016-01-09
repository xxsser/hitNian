<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>奖品兑换中心</title>
    <!-- Fonts -->
    <link href="/Css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="/Css/font-family.css" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link rel="stylesheet" href="Css/bootstrap.min.css">
    <style>
        body {font-family: 'Lato';}
        .fa-btn { margin-right: 6px;  }
    </style>
</head>
<body id="app-layout">
<div class="bs-docs-example">
    <table class="table">
        <thead>
        <tr>
            <th>奖品</th>
            <th>需要金币</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($gifts as $gift)
        <tr>
            <td>{{ $gift['name'] }}</td>
            <td>{{ $gift['coin'] }}</td>
            <td><button class="btn btn-warning exchange" value="{{ $gift['id'] }}">兑换</button></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
        <!-- JavaScripts -->
<script src="/Js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.exchange').click(function(){
            $.post('exprize/exchange',{pid:$(this).val()},function(data){
                switch (data.state){
                    case 'success' :
                        alert('恭喜，兑换成功！');
                        break;
                    case 'coin_lack' :
                        alert('您的金币不足');
                        break;
                    case 'noAuth' :
                        alert('您还没有登陆');
                        break;
                }
            })
        });
    })
</script>
</body>
</html>
