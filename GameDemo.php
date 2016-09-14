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
            $new = json_encode($row[$i]);
            echo $new;
            return $row[$i];
            $count ++;
        }
    }
    if ($count == 0 ){
        echo "no game";
    }

}
//$data = selectResult();
//var_dump($data);
?>
<html>
    <head>
        <title>Game</title>
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        <div>帳號：<?php echo $_SESSION['account'] ?></div>
        <div>目前餘額：<?php money(); ?></div>
        <form action="GameInsert.php" method="post">
            下注金額：<input type="text" size="10" name="pay" value="" />
            <br>
            期數：<div class="number"></div>
            <div id="lock">
            數字一: <input type="text" size="3" name="one" value="" required pattern="[0-9]{1}"/>
            數字二：<input type="text" size="3" name="two" value="" required pattern="[0-9]{1}"/>
            數字三：<input type="text" size="3" name="three" value="" required pattern="[0-9]{1}"/>
            數字四：<input type="text" size="3" name="four" value="" required pattern="[0-9]{1}"/>
            數字五：<input type="text" size="3" name="five" value="" required pattern="[0-9]{1}"/>
                <input type="hidden" size="3" name="gameid" value="<?php echo $data['gameID'];?>" required pattern="[0-9]{1}"/>
                <input type="submit" value="確認" />
            </div>
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

    <script>
        function getdata(){
            $.get("SelectResult.php", function(data){

                console.log(data);
                result =JSON.parse(data);
                var spantime = result[0];
                var number = result[1];
                if(result == "no game") {
                    $(".number").text("中場休息");
                }
                spantime --;
                var d = Math.floor(spantime / (24 * 3600));
                var h = Math.floor((spantime % (24*3600))/3600);
                var m = Math.floor((spantime % 3600)/(60));
                var s = Math.floor(spantime % 60);

                if(spantime>=0) {
                    $("div[id=lock] input").attr('disabled', false);
                    $("#hour").text(h+(d*24));
                    $("#min").text(m);
                    $("#sec").text(s);
                    $(".number").text(number);

                } else { // 避免倒數變成負的
                    $("div[id=lock] input").attr('disabled', 'disabled');
                    $("#hour").text(0);
                    $("#min").text(0);
                    $("#sec").text(0);
                    $(".number").text("本期結束");

                }
            });
        }
        $(document).ready(function () {

            setInterval(function(){
                getdata();
            },1000);
        });

    </script>
        還有：
        <div id="hour"></div>時
        <div id="min"></div>分
        <div id="sec"></div>秒
    </body>
</html>
