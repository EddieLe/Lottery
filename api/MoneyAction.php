<?php
require_once 'MyPDO.php';
session_start();

if (isset($_GET['pay'])) {
    moneyAction();
}

function moneyAction()
{

    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $pdo->beginTransaction();

    try {
        $sql = "SELECT `count` FROM `accounts` WHERE `name` = :account FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':account' => $_POST['account']]);

        //取出當下total
        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        $newTotal = $row[0]['count'];

        $cmd = "UPDATE `accounts` SET `count` = `count` - :take WHERE `name` = :account";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':take' => $_GET['pay'], ':account' => $_POST['account']]);

        $cmd = "INSERT INTO `detail`(`name`, `total`, `play`, `result`) VALUES (:account, :total, :play, :result)";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([
            ':play' => $_GET['pay'],
            ':total' => $newTotal,
            ':account' => $_POST['account'],
            ':result' => $newTotal - $_GET['pay']
        ]);

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollback();
        echo "failed";
        echo 'Caught exception: ', $e->getMessage();
    }
}

