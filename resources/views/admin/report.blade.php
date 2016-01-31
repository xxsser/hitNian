用户总数：{{ $count }} <br>
昨日新增用户数: {{ $yesterday_count }} <br>
昨日攻击掉落总金币： {{ $yesterday_attack_coin }} <br>
昨日分享送出总金币： {{ $yesterday_share_coin }} <br>
昨日发放总金币： {{ $yesterday_attack_coin+$yesterday_share_coin }} <br>
昨日参与活动人数： {{ $yesterday_join_num }} <br>
昨日分享次数：{{ $yesterday_share_num }} <br>
通过分享链接首次进入游戏用户数：{{ $yesterday_sharelink_user }} <br>

昨日兑走奖品： <br>
<table>
    @foreach($yesterday_exchang_prize as $value)
        <tr>
            <td>{{ $value['name'] }}</td>
            <td>{{ $value['COUNT(*)'] }}</td>
        </tr>
    @endforeach
</table>
