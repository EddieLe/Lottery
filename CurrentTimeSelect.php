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
        if (time() > $row[$i]['startTime'] && time() <= $row[$i]['stopTime']) {
            $number = json_decode($row[$i]['number']);
            $openCurrent = [$number[0], $number[1], $number[2], $number[3], $number[4], $i];
            $gameRange = json_encode($openCurrent);
            $count ++;
            echo $gameRange;
        }
    }
    if ($count == 0 ){
        $gameRange = json_encode("no game");
        echo $gameRange;
    }

}
selectCurrentResult();