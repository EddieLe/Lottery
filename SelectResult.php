<?php
require_once 'MyPDO.php';

function selectResult()
{
    $count = 0;
    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($row); $i++) {
        if (time() >= $row[$i]['startTime'] && time() < $row[$i]['stopTime']) {
            $range = $row[$i]['stopTime'] - time();
            $gameRange = json_encode($range);
            echo $gameRange;
            $count ++;
        }
    }
    if ($count == 0 ){
        echo "no game";
    }

}
selectResult();