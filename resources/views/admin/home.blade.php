@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">奖品查询</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="form-canche">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">用户核销码</label>
                            <div class="col-lg-6">
                                <input type="tel" class="form-control" id="code" value="" />
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
            $('#go-submit').click(function(){
                window.location.href = '/nian/ExpiryId/'+$('#code').val();
            });
        });
    </script>
@endsection