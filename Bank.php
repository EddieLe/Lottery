<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}
function money()
{
    session_start();
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
    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `detail` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

function logout()
{
    session_destroy();
}

if (isset($_POST['logout'])) {
    logout();
    header("location:SignIn.php");
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
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
                <h1><a href="index.html">轉帳頁</a> <span>.</span></h1>
            </div>
            <div class="col-sm-8 call-us">
                <p>帳號: <span><?php echo $_SESSION['account'] ?></span> | 餘額: <span><?php money(); ?></span> </p>
                <form action="" method="post">
                    <input id="btn-style" type="submit" name="logout" value="登出" />
                </form>
            </div>
            <div class="container">
                <form action="MysqlTrs.php" method="post">
                    轉帳選擇: <select name="trs">
                        　<option value="in">轉入</option>
                        　<option value="out">轉出</option>
                    </select>
                    輸入金額: <input type="text" name="money" value="" />
                    <input id="btn-style" type="submit" value="確認" />
                </form>
                <hr>
                <?php if ($_SESSION['account'] == 'root') {?>
                    <form action="Open.php" method="post">
                        <input id="btn-style" type="submit" name="open" value="開獎頁">
                    </form>
                <?php } else {?>
                <table Width="250px">
                    <tr>
                        <td>
                            <form action="" method="post">
                                <input id="btn-style" type="submit" name="logout" value="二字遊戲" />
                            </form>
                        </td>
                        <td>
                            <form action="Game.php" method="post">
                                <input id="btn-style" type="submit" name="logout" value="三字遊戲" />
                            </form>
                        </td>
                    </tr>
                </table>
                <?php } ?>
                <hr>
                <div class="bs-example">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>金額</th>
                            <th>提款</th>
                            <th>存款</th>
                            <th>下注</th>
                            <th>贏額</th>
                            <th>餘額</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $data = detail();?>
                        <?php for ($i = 0; $i < count($data); $i++): ?>
                            <tr class="warning">
                                <th><?php echo $data[$i]['total'] ?></th>
                                <th><?php echo $data[$i]['take'] ?></th>
                                <th><?php echo $data[$i]['save'] ?></th>
                                <th><?php echo $data[$i]['play'] ?></th>
                                <th><?php echo $data[$i]['win'] ?></th>
                                <th><?php echo $data[$i]['result'] ?></th>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>


<!--<html>-->
<!--    <head>-->
<!--        <title>Bank</title>-->
<!--    </head>-->
<!--    <body>-->
<!--    <div>帳號：--><?php //echo $_SESSION['account'] ?><!--</div>-->
<!--    <div>目前餘額：--><?php //money(); ?><!--</div>-->
<!--        <form action="MysqlTrs.php" method="post">-->
<!--            轉帳選擇: <select name="trs">-->
<!--                        　<option value="in">轉入</option>-->
<!--                        　<option value="out">轉出</option>-->
<!--                    </select>-->
<!--            輸入金額: <input type="text" name="money" value="" />-->
<!--            <input type="submit" value="確認" />-->
<!--        </form>-->
<!--    --><?php //if ($_SESSION['account'] == 'root') {?>
<!--        <form action="Open.php" method="post">-->
<!--            <input type="submit" name="open" value="開獎頁">-->
<!--        </form>-->
<!--    --><?php //} else {?>
<!--        <form action="Game.php" method="post">-->
<!--            <input type="submit" name="logout" value="三字遊戲" />-->
<!--        </form>-->
<!--    --><?php //} ?>
<!--        <form action="" method="post">-->
<!--            <input type="submit" name="logout" value="登出" />-->
<!--        </form>-->
<!--    <table width="300" border="1">-->
<!--        <tr>-->
<!--            <th>金額</th>-->
<!--            <th>提款</th>-->
<!--            <th>存款</th>-->
<!--            <th>下注</th>-->
<!--            <th>贏額</th>-->
<!--            <th>餘額</th>-->
<!--        </tr>-->
<!--        --><?php //$data = detail();?>
<!--        --><?php //for ($i = 0; $i < count($data); $i++): ?>
<!--            <tr>-->
<!--                <th>--><?php //echo $data[$i]['total'] ?><!--</th>-->
<!--                <th>--><?php //echo $data[$i]['take'] ?><!--</th>-->
<!--                <th>--><?php //echo $data[$i]['save'] ?><!--</th>-->
<!--                <th>--><?php //echo $data[$i]['play'] ?><!--</th>-->
<!--                <th>--><?php //echo $data[$i]['win'] ?><!--</th>-->
<!--                <th>--><?php //echo $data[$i]['result'] ?><!--</th>-->
<!--            </tr>-->
<!--        --><?php //endfor; ?>
<!--    </table>-->
<!---->
<!--    </body>-->
<!--</html>-->