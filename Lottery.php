<?php
require_once 'MyPDO.php';
ini_set("display_errors",true);
ignore_user_abort(true);
set_time_limit(0);

function doLottery()
{
    $startTime = time();
    $totalTime = 20;
    $restTime =10;
    $openCount = 3;

    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;

    for ($i = 1; $i <= $openCount; $i++) {
        $times = substr((1000 + $i),-3);
        $gameId = date("Ymd") . "-" . $times;
        $startTime = time() + ($totalTime * ($i - 1));
        $endTime = ($startTime + $totalTime);
        $stopTime = ($endTime - $restTime);

        $sql = "INSERT INTO `Lottery`(`gameID`, `startTime`, `endTime`, `stopTime`) VALUES (:gameId, :startTime, :endTime, :stopTime)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':gameId' => $gameId,
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':stopTime' => $stopTime
        ]);

    }
    while (time() >= $startTime || time() < ($startTime + ($startTime+$totalTime*$openCount))) {

        for ($i = 0; $i < 10; $i++) {
            $rand[] = $i;
        }
        shuffle($rand);

        $result = array_slice($rand, 0, 5);
        $number = json_encode($result);

        $sql = "SELECT `startTime` FROM `Lottery`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            if ($row[$i]['startTime'] == time()) {
//                echo 123;
                $sql = "UPDATE `Lottery` SET `number` = :number WHERE `startTime` = :startTime";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':number' => $number, ':startTime' => $row[$i]['startTime']]);
            }
        }
        sleep($totalTime);

    }

}
doLottery();