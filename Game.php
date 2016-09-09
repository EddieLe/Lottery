<?php
require_once 'MyPDO.php';
session_start();
ini_set("display_errors",true);

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}

if ($_SESSION['account'] == 'root') {
    header("location:Bank.php");
    exit;
}

function money()
{
//    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $row['count'];
}

function detail()
{
//    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `gameResult` WHERE `account` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}


function selectResult()
{
    $count = 0;
    $mypdo = new MyPDO();
    $pdo = $mypdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    var_dump($row);
//    echo json_encode($row);
//    echo $row[0]['startTime'];
//    echo $row[0]['stopTime'];
//    echo date("Y-m-d H:i:s", 1473411399). "<br>"; //start
//    echo date("Y-m-d H:i:s", 1473411419). "<br>"; //end
//    echo date("Y-m-d H:i:s", 1473411409). "<br>"; //stop
//    echo time();
    for ($i = 0; $i < count($row); $i++) {
        if (time() > $row[$i]['startTime'] && time() <= $row[$i]['stopTime']) {
            return $row[$i];
            $count ++;
        }
        if ($count == 0){
            echo "no game";
        }
    }

}
$data = selectResult();
//var_dump($data);
?>
<html>
    <head>
        <title>Game</title>
    </head>
    <body>
        <div>帳號：<?php echo $_SESSION['account'] ?></div>
        <div>目前餘額：<?php money(); ?></div>
        <form action="GameInsert.php" method="post">
            下注金額：<input type="text" size="10" name="pay" value="" />
            <br>
            期數：<?php echo $data['gameID'];?>
            數字一: <input type="text" size="3" name="one" value="" required pattern="[0-9]{1}"/>
            數字二：<input type="text" size="3" name="two" value="" required pattern="[0-9]{1}"/>
            數字三：<input type="text" size="3" name="three" value="" required pattern="[0-9]{1}"/>
            數字四：<input type="text" size="3" name="four" value="" required pattern="[0-9]{1}"/>
            數字五：<input type="text" size="3" name="five" value="" required pattern="[0-9]{1}"/>
            <input type="hidden" size="3" name="gameid" value="<?php echo $data['gameID'];?>" required pattern="[0-9]{1}"/>
            <input type="submit" value="確認" />
        </form>

        </form>
        <form action="History.php" method="post">
            <input type="submit" name="open" value="歷史結果">
        </form>
        <table width="600" border="1">
            <tr>
                <td>注單編號</td>
                <td>數字一</td>
                <td>數字二</td>
                <td>數字三</td>
                <td>數字四</td>
                <td>數字五</td>
                <td>下注金額</td>
                <td>時間</td>
            </tr>
            <?php $data = detail();?>
            <?php for ($i = 0; $i < count($data); $i++) :?>
            <tr>
                <td><?php echo $data[$i]['id']; ?></td>
                <td><?php echo $data[$i]['one']; ?></td>
                <td><?php echo $data[$i]['two']; ?></td>
                <td><?php echo $data[$i]['three']; ?></td>
                <td><?php echo $data[$i]['four']; ?></td>
                <td><?php echo $data[$i]['five']; ?></td>
                <td><?php echo $data[$i]['pay']; ?></td>
                <td><?php echo $data[$i]['date']; ?></td>
            </tr>
            <?php endfor; ?>
        </table>

        <form action="Bank.php" method="post">
            <input type="submit" value="Bank Page">
        </form>
    </body>
</html>