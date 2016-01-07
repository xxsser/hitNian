@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">奖品列表</div>
                <div class="panel-body">
                    <ul>
                        @if($prizes)
                            @foreach( $prizes as $prize)
                                <li>{{ $prize['name'] }}</li>
                            @endforeach
                            <hr>
                            <li>代金劵总金额:{{ $countMoney }} 元</li>
                        @else
                            <li>该用户没有奖品可兑换</li>
                        @endif
                    </ul>
                </div>
            </div>
            @if($prizes)
            <div class="panel panel-default">
                <div class="panel-heading">填写信息</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="form-canche">
                        <input type="hidden" id="fid" value="{{ $fid }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">姓名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="name" value="" />
                            </div>
                        </div>

                        <div class="form-group control-group">
                            <label class="col-md-4 control-label">电话</label>
                            <div class="col-md-6">
                                <input type="tel" class="form-control" id="phone" value="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">密钥</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="key" value="" />
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="col-md-4 col-md-offset-5">
                                <button type="button" id="go-submit" class="btn btn-primary" >核销</button>
                                <button type="button" id="clean" class="btn">重填</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function(){
            $('#clean').click(function(){
                $("input").val(null);
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#go-submit').click(function(){
                if ($("#phone").val() != '' && !phoneCheck($("#phone").val())) {
                    alert("请输入正确的手机号码!");
                    $('#phone').focus();
                    $('#phone').val(null);
                    return;
                }
                $.ajax({
                    url: "/admin/canche_fid",
                    method: 'POST',
                    data: {fid: $("#fid").val(),name: $("#name").val(),phone: $("#phone").val(),key:$('#key').val()},
                    success: function(data) {
                        switch (data.state){
                            case 'success' :
                                alert('核销成功')
                                location.reload();
                                break;
                            case 'codeErr' :
                                alert('没有找到该核销码，或者该核销码已经被使用');
                                break;
                            case 'keyErr' :
                                alert('密钥填写错误，请检查');
                                break;
                            case 'noup' :
                                alert('没有任何更新，请检查用户是否有奖品');
                                break;
                            default :
                                alert(data);
                        }
                    },
                    error: function() {
                        alert('请检查网络');
                    }
                })
            });
        });
        //手机号码验证
        function phoneCheck(phoneNum) {
            var pattern  = /^1[34578]\d{9}$/;
            if (pattern.test(phoneNum)) {
                return true;
            }
            return false
        }
    </script>
@endsection