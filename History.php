<?php
require_once 'MyPDO.php';
session_start();

function history()
{
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

function detail()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery` WHERE `gameID` = :gameID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':gameID' => $_POST['gameID']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $detail[] = $row;
    }

    return $detail;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bank</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Lato:400,700'>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    <style>
        #btn-style{
            border : solid 0px #000000;
            border-radius : 5px;
            moz-border-radius : 5px;
            font-size : 20px;
            color : #ffffff;
            padding : 1px 17px;
            background : #ff8c00;
            background : -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff8c00), color-stop(100%,#ff3503));
            background : -moz-linear-gradient(top, #ff8c00 0%, #ff3503 100%);
            background : -webkit-linear-gradient(top, #ff8c00 0%, #ff3503 100%);
            background : -o-linear-gradient(top, #ff8c00 0%, #ff3503 100%);
            background : -ms-linear-gradient(top, #ff8c00 0%, #ff3503 100%);
            background : linear-gradient(top, #ff8c00 0%, #ff3503 100%);
            filter : progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff8c00', endColorstr='#ff3503',GradientType=0 );

        }
    </style>

</head>

    <body>
    <div class="container">
        <div class="row header">
            <div class="col-sm-4 logo">
                <h1><a href="index.html">歷史開獎</a> <span>.</span></h1>
            </div>
            <div class="col-sm-8 call-us">
                <p>帳號: <span><?php echo $_SESSION['account'] ?></span> </p>
                <form action="Game.php" method="post">
                    <input id="btn-style" type="submit" name="logout" value="遊戲" />
                </form>
            </div>
            <div class="container">

                <hr>
                <div class="bs-example">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>注單編號</th>
                            <th>開獎狀態</th>
                            <th>中獎人</th>
                            <th>數字一</th>
                            <th>數字二</th>
                            <th>數字三</th>
                            <th>數字四</th>
                            <th>數字五</th>
                            <th>下注金額</th>
                            <th colspan="3">開獎結果</th>
                            <th>時間</th>
                        </tr>
                        <?php $data = history();?>
                        <?php for ($i = 0; $i < count($data); $i++) :?>
                            <tr>
                                <td><?php echo $data[$i]['gameID']; ?></td>
                                <td><?php echo $data[$i]['number']; ?></td>
                                <td><?php echo $data[$i]['account']; ?></td>
                                <td><?php echo $data[$i]['one']; ?></td>
                                <td><?php echo $data[$i]['two']; ?></td>
                                <td><?php echo $data[$i]['three']; ?></td>
                                <td><?php echo $data[$i]['four']; ?></td>
                                <td><?php echo $data[$i]['five']; ?></td>
                                <td><?php echo $data[$i]['pay']; ?></td>
                                <td Width="60"><?php echo $data[$i]['result']; ?></td>
                                <td Width="60"><?php echo $data[$i]['result1']; ?></td>
                                <td Width="60"><?php echo $data[$i]['result2']; ?></td>
                                <td><?php echo $data[$i]['date']; ?></td>
                            </tr>
                        <?php endfor; ?>
                    </table>
                    <form method="post" action="">
                        注單編號：<input type="text" name="gameID" value="">
                        <input type="submit" name="sub" value="查詢">
                        <div class="bs-example">
                            <table class="table">
                            <tr>
                                <td>注單編號</td>
                                <td>數字一</td>
                                <td>數字二</td>
                                <td>數字三</td>
                                <td>數字四</td>
                                <td>數字五</td>
                                <td>時間</td>
                            </tr>
                            <?php $data = detail();?>
                            <?php for ($i = 0; $i < count($data); $i++) :?>
                                <tr>
                                    <td><?php echo $data[$i]['gameID']; ?></td>
                                    <td><?php echo json_decode($data[$i]['number'])[0]; ?></td>
                                    <td><?php echo json_decode($data[$i]['number'])[1]; ?></td>
                                    <td><?php echo json_decode($data[$i]['number'])[2]; ?></td>
                                    <td><?php echo json_decode($data[$i]['number'])[3]; ?></td>
                                    <td><?php echo json_decode($data[$i]['number'])[4]; ?></td>
                                    <td><?php echo $data[$i]['date']; ?></td>
                                </tr>
                            <?php endfor; ?>
                            </table>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
