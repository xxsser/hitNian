@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">奖品查询</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="form-canche">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">核销码</label>
                            <div class="col-lg-6">
                                <input type="tel" class="form-control" id="code" value="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">姓名</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="name" value="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">电话</label>
                            <div class="col-lg-6">
                                <input type="tel" class="form-control" id="phone" value="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" id="go-submit" class="btn btn-primary" >核销</button>
                                <button type="button" id="clean" class="btn btn-danger">重填</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function(){
            $('#code').focus();
            $('#clean').click(function(){
                $("input").val(null);
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#go-submit').click(function(){
                if(isNaN($('#code').val())){
                    alert("请正确输入核销码");
                    $('#code').focus();
                    $('#code').val(null);
                    return
                }
                if ($("#phone").val() != '' && !phoneCheck($("#phone").val())) {
                    alert("请输入正确的手机号码!");
                    $('#phone').focus();
                    $('#phone').val(null);
                    return;
                }
                $.ajax({
                    url: "/admin/canche_code",
                    method: 'POST',
                    data: {code: $("#code").val(),name: $("#name").val(),phone: $("#phone").val()},
                    success: function(data) {
                        switch (data.state){
                            case 'success' :
                                var str = '核销成功：('+data.gift+') ';
                                if(data.moneyCount > 0){
                                    str += '\n现金劵总金额：'+data.moneyCount+'元';
                                }
                                alert(str);
                                break;
                            case 'codeErr' :
                                alert('没有找到该核销码，或者该核销码已经被使用');
                                break;
                        }

                    },
                    error: function() {
                        alert("网络链接出错!");
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