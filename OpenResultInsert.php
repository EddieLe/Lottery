<?php
require_once 'MyPDO.php';
//ini_set('display_errors', true);
session_start();

if (!isset($_SESSION['account'])) {
    header("location:LogIn.php");
}

function back($pay, $account)
{
    //贏錢api
    $url = "192.168.62.129/api/MoneyWin.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( array( "account" => $account, "pay" => $pay) ));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $temp = curl_exec($ch);
    curl_close($ch);
    echo $temp;
}

function comparison($array)
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $sql = "SELECT * FROM `gameResult`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    for ($i = 0; $i < count($data); $i++) {
        $time[] = strtotime($data[$i]['date']);

        if ($time[$i] >= $array['startTime'] && $time[$i] < $array['stopTime']) {
            $result[] = $data[$i];
        }
    }
    $number = json_decode($array['number']);
    $oneResult = array($number[0], $number[1], $number[2]);
    $twoResult = array($number[1], $number[2], $number[3]);
    $threeResult = array($number[2], $number[3], $number[4]);

    for ($i = 0; $i < count($result); $i++) {
        //先寫入有判斷區間開獎值
        $sql = "UPDATE `gameResult` SET `number` = '沒中獎' WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $result[$i]['id']]);

        //判斷由沒有全部一致
        if ($result[$i]['one'] == $number[0] && $result[$i]['two'] == $number[1] && $result[$i]['three'] == $number[2]) {
            $sql = "UPDATE `gameResult` SET `result`= '中前三全中', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back(($result[$i]['pay']) * 3, $result[$i]['account']);

            //判斷是否存在陣列
        } elseif (in_array($result[$i]['one'], $oneResult) && in_array($result[$i]['two'], $oneResult) && in_array($result[$i]['three'], $oneResult)) {
            $sql = "UPDATE `gameResult` SET `result`= '中前三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back($result[$i]['pay'], $result[$i]['account']);

        }

        if ($result[$i]['two'] == $number[1] && $result[$i]['three'] == $number[2] && $result[$i]['four'] == $number[3]) {
            $sql = "UPDATE `gameResult` SET `result1`= '中中三全中', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back(($result[$i]['pay']) * 3, $result[$i]['account']);
        } elseif (in_array($result[$i]['two'], $twoResult) && in_array($result[$i]['three'], $twoResult) && in_array($result[$i]['four'], $twoResult)) {
            $sql = "UPDATE `gameResult` SET `result1`= '中中三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back($result[$i]['pay'], $result[$i]['account']);

        }

        if ($result[$i]['three'] == $number[2] && $result[$i]['four'] == $number[3] && $result[$i]['five'] == $number[4]) {
            $sql = "UPDATE `gameResult` SET `result2`= '中後三全中', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back(($result[$i]['pay']) * 3, $result[$i]['account']);
        } elseif (in_array($result[$i]['three'], $threeResult) && in_array($result[$i]['four'], $threeResult) && in_array($result[$i]['five'], $threeResult)) {
            $sql = "UPDATE `gameResult` SET `result2`= '中後三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => '中獎']);
            back($result[$i]['pay'], $result[$i]['account']);

        }
    }
}