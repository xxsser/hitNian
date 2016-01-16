var blag = true;
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //兑换按钮
    $('#exprize').click(function(){
        $('#exBox').show();
    });
    $('.exchange').click(function(){
        if(confirm('确定要兑换吗?')){
            $.post('exprize/exchange',{pid:$(this).val()},function(data){
                switch (data.state){
                    case 'success' :
                        $('#alertext').text('恭喜，兑换成功！');
                        break;
                    case 'coin_lack' :
                        $('#alertext').text('您的金币不足');
                        break;
                    case 'noAuth' :
                        $('#alertext').text('您还没有登陆');
                        break;
                    default :
                        $('#alertext').text('null');
                }
                $('#box').show();
            })
        }
    });
    $('#close-exBox').click(function(){
        $(this).parent().hide();
    });
    //攻击按钮
    $('#attack').click(function(){
        if(blag == false){
            return;
        }
        blag = false;
        $.ajax({
            url: "attack",
            data:{fid: $('#fid').val()},
            type: "POST",
            dataType:'json',
            success:function(data){
                switch (data.state){
                    case 'success' :
                        $('#musicBox').play();
                        $(".chuizi-box").show()
                        timerTotal = 20;
                        $("#chuizi").rotate({
                            duration:1000,
                            angle: 0,
                            center: ["0%", "100%"],
                            animateTo:25,
                            callback: function(){
                                //年兽效果
                                $('#boom').show();
                                $('#nianshou').css({
                                    '-webkit-animation-name':'nianAttack',
                                    '-webkit-animation-duration':'1s'
                                });
                                var damages = parseInt($('#damages').text());
                                $('#damages').text(damages + parseInt(data.damage));
                                var str = '恭喜:您对年兽造成'+data.damage+'点伤害!';
                                if(data.coins){
                                    //金币效果
                                    $('.coin-box').show();
                                    setTimeout(function(){
                                        $('.coin-box').hide();
                                    },3000)
                                    var coins = parseInt($('#coins').text());
                                    $('#coins').text(coins+parseInt(data.coins));
                                    str += '<br/>年兽掉落了<b>'+data.coins+'</b>个金币!';
                                }
                                var count = parseInt($('#attackNum').text());
                                $('#attackNum').text(count-1);
                                $('#alertext').html(str);

                                setTimeout(function(){
                                    $('#nianshou').css({
                                        '-webkit-animation-name':'nianNormen',
                                        '-webkit-animation-duration':'8s'
                                    });
                                    //隐藏爆炸
                                    $('#boom').hide();
                                    //隐藏锤子
                                    $(".chuizi-box").hide();
                                    //显示数据
                                    $('#box').show();
                                },2000);
                            }
                        });
                        break;
                    case 'over' :
                        if(data.isShare == 'no'){
                            $('#share').show();
                            return;
                        }else{
                            $('#alertext').text('今天攻击次数用完啦，记得明天再来');
                            $('#box').show();
                        }
                        break;
                }
            },
            error:function(){
                $('#alertext').text('请检查网络，重试');
                $('#box').show();
            }
        });
        setTimeout(function(){blag = true;},3000);
    });
    $(".close").click(function(){
        if(timerTotal < 100){
            timerTotal = 100;
        }
        $(this).parent().hide();
    });
    $('#gift-list-btn').click(function(){
        $(this).parent().parent().hide();
        $('.gift-list').show();
    });
});
//设置分享内容
function setShare(trend){
    return {
        title: '每天摇一摇，摇出年兽大礼包', // 分享标题
        desc: '动动手腕，攻击年兽有奖品，更有iphone6S等豪礼等着你，年兽大战等你来!', // 分享描述
        link: 'http://hdwyc.3pdj.com/nian/sharelink/'+$('#fid').val(), // 分享链接
        imgUrl: 'http://hdwyc.3pdj.com/nian/Images/fx.jpg', // 分享图标
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