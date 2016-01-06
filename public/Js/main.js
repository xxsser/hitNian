var SHAKE_THRESHOLD = 3000;
var last_update = 0;
var x, y, z, last_x, last_y, last_z;
var last_time = 0;
var media;

function init(){
    last_update=new Date().getTime();
    media= document.getElementById("musicBox");
    if (window.DeviceMotionEvent) {
        window.addEventListener('devicemotion',deviceMotionHandler, false);
    } else{
        alert('not support mobile event');
    }
}
function deviceMotionHandler(eventData) {
    var acceleration =eventData.accelerationIncludingGravity;
    var curTime = new Date().getTime();
    if ((curTime - last_update)> 100) {
        var diffTime = curTime -last_update;
        last_update = curTime;
        x = acceleration.x;
        y = acceleration.y;
        z = acceleration.z;
        var speed = Math.abs(x +y + z - last_x - last_y - last_z) / diffTime * 10000;
        if (speed > SHAKE_THRESHOLD) {
            if ((last_time != 0 && (curTime - last_time < 3000))) {
                return;
            }
            if(!media.src){
                media.src="Sound/shake.mp3";
            }
            media.play();
            last_time = curTime;
            $.post('/attack',{'fid': $('#fid').val()},function(data){
                switch (data.state){
                    case 'success' :
                        var str = '恭喜:您对年兽造成'+data.damage+'点伤害!';
                        if(data.coins){
                            str += '<br/>年兽掉落了<b>'+data.coins+'</b>个金币!';
                        }
                        var count = $('#attackNum').text();
                        $('#attackNum').text(count-1);
                        $('#alertext').html(str);
                        $('#box').show();
                        //alert(str)
                        break;
                    case 'over' :
                        if(data.isShare == 'no'){
                            $('#share').show();
                        }else{
                            $('#alertext').text('今天攻击次数用完啦，记得明天再来');
                            $('#box').show();
                        }
                        break;
                    default :
                        alert('请检查网络，重试');
                }
            });
        }
        last_x = x;
        last_y = y;
        last_z = z;
    }
}
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //兑换按钮
    $('#exprize').click(function(){
        $('#exBox').show();
        $('.exchange').click(function(){
            $.post('/exprize/exchange',{pid:$(this).val()},function(data){
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
        });
        $('#close-exBox').click(function(){
            $(this).parent().hide();
        });
    });
    //攻击按钮
    $('#attack').click(function(){
        $.post('/attack',{'fid': $('#fid').val()},function(data){
            switch (data.state){
                case 'success' :
                    $(".chuizi-box").show()
                    $("#chuizi").rotate({
                        duration:1000,
                        angle: 0,
                        center: ["0%", "100%"],
                        animateTo:25,
                        callback: function(){
                            //年兽效果
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
                                //隐藏锤子
                                $(".chuizi-box").hide();
                                //显示数据
                                $('#box').show();
                            },1000);
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
                default :
                    $('#alertext').text('请检查网络，重试');
                    $('#box').show();
            }
        });
    });
    $("#ok").click(function(){
        $(this).parent().hide();
    });


})