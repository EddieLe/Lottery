<?php
require_once 'MyPDO.php';

function selectResult()
{
    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($row);
    echo json_encode($row);

}
selectResult();