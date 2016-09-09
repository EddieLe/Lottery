<?php
require_once 'MyPDO.php';
ini_set("display_errors",true);

function doLottery()
{
//    ignore_user_abort(true);
    $flag = true;
    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;

    for ($i = 1; $i <= 3; $i++) {
        $times = substr((1000 + $i),-3);
        $gameId = date("Ymd") . "-" . $times;
        $startTime = time() + (120 * ($i - 1));
        $endTime = ($startTime + 120);
        $stopTime = ($endTime - 60);
        echo date("Y-m-d H:i:s", $startTime) . "<br>";
        echo date("Y-m-d H:i:s", $endTime) . "<br>";
        echo date("Y-m-d H:i:s", $stopTime) . '<br>';

//echo time();
        $sql = "INSERT INTO `Lottery`(`gameID`, `startTime`, `endTime`, `stopTime`) VALUES (:gameId, :startTime, :endTime, :stopTime)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':gameId' => $gameId,
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':stopTime' => $stopTime
        ]);

    }
    while ($flag) {
        for ($i = 0; $i < 10; $i++) {
            $rand[] = $i;
        }
        shuffle($rand);
        $result = array_slice($rand, 0, 5);
        $number = json_encode($result);

        $mypdo = new MyPDO();
        $pdo = $mypdo->pdoConnect;
        $sql = "SELECT `startTime` FROM `Lottery`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row =$stmt->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            if ($row[$i]['startTime'] == time()) {
    //            echo 123;
                $sql = "UPDATE `Lottery` SET `number` = :number WHERE `startTime` = :startTime";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':number' => $number, ':startTime' => $row[$i]['startTime']]);
            }
        }
        sleep(180);
        if (time() == 1473392866) {
            $flag = false;
        }
    }

}
doLottery();