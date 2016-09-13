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
        //STOP TIME開獎 開獎不能下注
        if (time() >= $row[$i]['startTime'] && time() < $row[$i]['stopTime']) {
            $range = $row[$i]['stopTime'] - time();
            $info = [$range, $row[$i]['gameID']];
            $gameRange = json_encode($info);
            echo $gameRange;
            $count ++;
        }
    }
    if ($count == 0 ){
        $gameRange = json_encode("no game");
        echo $gameRange;
    }

}
selectResult();