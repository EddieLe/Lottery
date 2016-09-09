<?php
require_once 'MyPDO.php';
ini_set("display_errors",true);

function doLottery()
{
//    ignore_user_abort(true);
//

//    $mypdo = new MyPDO();
//    $pdo = $mypdo->pdoConnect;
//
//    for ($i = 1; $i <= 10; $i++) {
//        $times = substr((1000 + $i),-3);
//        echo $gameId = date("Ymd") . "-" . $times;
//        echo $startTime = time() + (120 * $i) . "<br>";
//        echo $endTime = ($startTime + 120) . "<br>";
//        echo $stopTime = ($endTime - 60) . "<br>";
//
//
//        $sql = "INSERT INTO `Lottery`(`gameID`, `startTime`, `endTime`, `stopTime`) VALUES (:gameId, :startTime, :endTime, :stopTime)";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute([':gameId' => $gameId,
//            ':startTime' => $startTime,
//            ':endTime' => $endTime,
//            ':stopTime' => $stopTime
//        ]);
//
//    }
//    while (true) {
    for ($i = 0; $i < 10; $i++) {
        $rand[] = $i;
    }
    shuffle($rand);
    $number = array_slice($rand, 0, 5);
    echo json_encode($number);

    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;
    $sql = "INSERT INTO `Lottery`(`number`) VALUES (:number) WHERE ";
    $pdo->query($sql);
    $pdo->execute([':number' => $number]);
//}

}
doLottery();