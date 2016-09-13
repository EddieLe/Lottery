<?php
/**
 * Created by PhpStorm.
 * User: eddie
 * Date: 2016/9/12
 * Time: 下午 4:58
 */

//取得當期結束時間
$end_time = time() + 10;
$game_count = 5;

$count = 0;
while($count <= $game_count){
    $count ++;
    echo "第".($count)."期開始下注\n";
    $sleep_time = $end_time - time();
    sleep($sleep_time);

    echo "時間到".($count)."期開獎\n";
    flush();
    echo "休息時間 10s\n";
    flush();
    sleep(10);
    echo "取得".($count+1)."期結束時間\n";
    flush();
    $end_time = time() + 10;
}