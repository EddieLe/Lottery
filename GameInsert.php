<?php
require_once 'MyPDO.php';
session_start();

function gameInsert()
{
    $numArray = array($_POST['one'], $_POST['two'], $_POST['three'], $_POST['four'], $_POST['five']);
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $sql = "SELECT `count` FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);

    if ($row[0]['count'] < $_POST['pay']) {
        echo "<script> alert('沒有足夠金額');</script>";
        header("refresh:0, url=Bank.php");
        exit;
    }

    if ($_POST['pay'] < 0) {
        echo "<script> alert('輸入要大於零'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    }
    if (count(array_unique($numArray)) != count($numArray)) {
        echo "<script> alert('輸入重複數字'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    }
    //扣款api
    $url = "http://192.168.62.129/api/MoneyAction.php?pay=" . $_POST['pay'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( array( "account" => $_SESSION['account']) ));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $temp = curl_exec($ch);
    curl_close($ch);

    $time = time();
    $sql = "SELECT `gameID` FROM `Lottery` WHERE `startTime` <= :time AND `stopTime` > :time ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':time' => $time]);
    $row = $stmt->fetch();
//    var_dump($row);
//    echo $row['gameID'];
//    exit;

    $sql = "INSERT INTO `gameResult`(`one`, `two`, `three`, `four`, `five`, `pay`, `result`, `account`, `result1`, `result2`, `number`, `gameID`) 
      VALUES (:one, :two, :three, :four, :five, :pay, '無', :account, '無', '無', '未開獎', :gameID)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':one' => $_POST['one'],
        ':two' => $_POST['two'],
        ':three' => $_POST['three'],
        ':four' => $_POST['four'],
        ':five' => $_POST['five'],
        ':pay' => $_POST['pay'],
        ':account' => $_SESSION['account'],
        ':gameID'=> $row['gameID']
        ]);
    if ($temp != "failed") {
        echo "<script> alert('下注成功'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    } else {
        echo "<script> alert('下注失敗'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    }

}
gameInsert();
header("location:Game.php");