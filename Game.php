<?php
require_once 'MyPDO.php';
session_start();
//ini_set("display_errors",true);

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
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>三字遊戲</title>

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

<!-- Header -->
<div class="container">
    <div class="row header">
        <div class="col-sm-4 logo">
            <h1><a href="index.html">三字遊戲</a> <span>.</span></h1>
        </div>
        <div class="col-sm-8 call-us">
            <p>帳號: <span><?php echo $_SESSION['account'] ?></span> | 餘額: <span><?php money(); ?></span> </p>
            <div style="margin-left:auto;margin-right:2px;width:800px;">
            <table>
                <tr>
                    <td>
                        <form action="History.php" method="post">
                            <input id="btn-style" type="submit" name="open" value="歷史結果">
                        </form>
                    </td>
                    <td>
                        <form action="Bank.php" method="post">
                            <input id="btn-style" type="submit" name="open" value="轉帳頁">
                        </form>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- Coming Soon -->
<div class="coming-soon">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>三字遊戲</h2>
                    <p>選擇五個 0～9不重複號碼 下注</p>
                    <div class="timer">
<!--                        <div class="days-wrapper">-->
<!--                            <span class="days" id="hour"></span> <br>days-->
<!--                        </div>-->
                        <div class="hours-wrapper">
                            <span class="days" id="hour"></span> <br>hours
                        </div>

                        <div class="minutes-wrapper">
                            <span class="days" id="min"></span> <br>minutes
                        </div>
                        <div class="seconds-wrapper">
                            <span class="days" id="sec"></span> <br>seconds
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="container">
    <div class="row">
        <div class="col-sm-12 subscribe">
<!--            <h3>Subscribe to our newsletter</h3>-->
<!--            <p>Sign up now to our newsletter and you'll be one of the first to know when the site is ready:</p>-->
<!--            <form class="form-inline" role="form" action="assets/subscribe.php" method="post">-->
                <div class="form-group">
                    <label class="sr-only" for="subscribe-email">Email address</label>
<!--                    <input type="text" name="email" placeholder="Enter your email..." class="subscribe-email form-control" id="subscribe-email">-->
                    <form action="GameInsert.php" method="post">
                        <font face="標楷體"  color="#6600CC"  size="20">期數： <span class="number"></span></font>
                        <hr color="#00FF00" size="10">
                        <font face="標楷體"  color="#FF6600"  size="5">輸入下注金額：</font><input type="text" size="10" name="pay" value="" />
                        <br>
                        <hr color="#00FF00" size="10" width="50%">
                        <div id="lock">
                            數字一: <input type="text" size="3" name="one" value="" required pattern="[0-9]{1}"/>
                            數字二：<input type="text" size="3" name="two" value="" required pattern="[0-9]{1}"/>
                            數字三：<input type="text" size="3" name="three" value="" required pattern="[0-9]{1}"/>
                            數字四：<input type="text" size="3" name="four" value="" required pattern="[0-9]{1}"/>
                            數字五：<input type="text" size="3" name="five" value="" required pattern="[0-9]{1}"/>
                            <input type="hidden" size="3" name="gameid" value="<?php echo $data['gameID'];?>" required pattern="[0-9]{1}"/>
                            <input id="btn-style" class="btn" type="submit" value="確認" />
                        </div>

                    </form>
                </div>
            <hr color="#00FF00" size="10">
            <div class="bs-example">
                <table class="table">
                    <thead>
                    <tr>
                        <th>注單編號</th>
                        <th>數字一</th>
                        <th>數字二</th>
                        <th>數字三</th>
                        <th>數字四</th>
                        <th>數字五</th>
                        <th>下注金額</th>
                        <th>時間</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $data = detail();?>
                    <?php for ($i = 0; $i < count($data); $i++) :?>
                        <tr class="success">
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
            </div>

            <div class="success-message"></div>
            <div class="error-message"></div>
        </div>
    </div>
    <div class="row">
<!--        <div class="col-sm-12 social">-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a>-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Twitter"><i class="fa fa-twitter"></i></a>-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Dribbble"><i class="fa fa-dribbble"></i></a>-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Google Plus"><i class="fa fa-google-plus"></i></a>-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Pinterest"><i class="fa fa-pinterest"></i></a>-->
<!--            <a href="#" data-toggle="tooltip" data-placement="top" title="Flickr"><i class="fa fa-flickr"></i></a>-->
<!--        </div>-->
    </div>
</div>


<!-- Javascript -->
<!--<script src="assets/js/jquery-1.11.1.min.js"></script>-->
<!--<script src="assets/bootstrap/js/bootstrap.min.js"></script>-->
<!--<script src="assets/js/jquery.backstretch.min.js"></script>-->
<!--<script src="assets/js/jquery.countdown.min.js"></script>-->
<!--<script src="assets/js/scripts.js"></script>-->

<!--[if lt IE 10]>
<script src="assets/js/placeholder.js"></script>
<![endif]-->

</body>

</html>
