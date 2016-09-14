<?php
require_once 'MyPDO.php';
session_start();

if (isset($_POST['pay']) && isset($_POST['account'])) {
    back();
    exit;
}

function back()
{
    $pay = $_POST['pay'];
    $account = $_POST['account'];

    try {
        $myPdo = new MyPDO();
        $pdo = $myPdo->pdoConnect;
        $pdo->beginTransaction();

        $cmd = "SELECT `count` FROM `accounts` WHERE `name` = :account FOR UPDATE";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':account' => $account]);

        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        $newTotal = $row[0]['count'];

        $cmd = "UPDATE `accounts` SET `count` = `count` + :win WHERE `name` = :name";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':win' => $pay, ':name' => $account]);

        $cmd = "INSERT INTO `detail`(`name`, `total`, `result`, `win`) VALUES (:account, :total, :result, :win)";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([
            ':total' => $newTotal,
            ':account' => $account,
            ':result' => $newTotal + $pay,
            ':win' => $pay
        ]);

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollback();
        echo 'Caught exception: ', $e->getMessage();
    }
}

