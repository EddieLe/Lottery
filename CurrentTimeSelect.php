<?php
require_once 'MyPDO.php';

function selectCurrentResult()
{
    $count = 0;
    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($row); $i++) {

        if (time() > $row[$i]['stopTime'] && time() < $row[$i]['endTime']) {
            if (time() == $row[$i]['endTime'] - 1) {
                $number = json_decode($row[$i]['number']);
                $openCurrent = [$number[0], $number[1], $number[2], $number[3], $number[4], $i];
                $gameRange = json_encode($openCurrent);
                $count ++;
                echo $gameRange;
            } else {
                echo json_encode(["free", $i]);
                exit;
            }
        }

    }
    if ($count == 0 ){
        $gameRange = json_encode("no game");
        echo $gameRange;
    }

}
selectCurrentResult();