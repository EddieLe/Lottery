<?php
require_once 'MyPDO.php';
require_once 'OpenResultInsert.php';

function manually()
{
    $time = time();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $number = json_encode([$_POST['one'], $_POST['two'], $_POST['three'], $_POST['four'], $_POST['five']]);

    $sql = "UPDATE `Lottery` SET `number`= :number, `flag`= 1 WHERE `stopTime`<= :time AND `endTime` > :time";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':number' => $number, ':time' => $time]);

    $sql = "SELECT * FROM `Lottery` WHERE `stopTime`<= :time AND `endTime` > :time";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':time' => $time]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //執行比對
    comparison($row);
    header("location:Open.php");

}
manually();