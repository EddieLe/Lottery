<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:LogIn.php");
}

function detail()
{
    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `Lottery`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}
?>

    <head>
        <title>開獎頁</title>
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
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
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
                <h1><a href="index.html">開獎頁</a> <span>.</span></h1>
            </div>
            <div class="col-sm-8 call-us">
                <p>帳號: <span><?php echo $_SESSION['account'] ?></span></p>
                <form action="Game.php" method="post">
                    <input id="btn-style" type="submit" name="logout" value="回前頁" />
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 subscribe ">
                <?php $data = detail();?>
                <?php for ($i = 0; $i < count($data); $i++) { ?>

                    <form method="post" action="Manually.php">
                        <h4>
                            <span class="text-primary bg-warning">開獎期數：</span><?php echo $data[$i]['gameID']?> <span class="text-primary bg-warning">開獎區間</span> : <input id="datepicker1" disabled="disabled" type="text" name="start" value="<?php echo date("Y-m-d H:i:s", $data[$i]['startTime'])?>" placeholder="2014-09-18" required/>
                            ～ <input id="datepicker2" disabled="disabled" type="text" name="end" value="<?php echo date("Y-m-d H:i:s", $data[$i]['stopTime'])?>" placeholder="2014-09-18" required/>

                            <div class="arrival-info" info=<?php echo $i?>>
                                <span class="text-primary bg-warning">數字一: </span> <input id="number_0" disabled="disabled" type="text" size="3" name="one" value="<?php echo json_decode($data[$i]['number'])[0];?>" required pattern="[0-9]{1}"/>
                                <span class="text-primary bg-warning">數字二： </span> <input id="number_1" disabled="disabled" type="text" size="3" name="two" value="<?php echo json_decode($data[$i]['number'])[1];?>" required pattern="[0-9]{1}"/>
                                <span class="text-primary bg-warning">數字三： </span> <input id="number_2" disabled="disabled" type="text" size="3" name="three" value="<?php echo json_decode($data[$i]['number'])[2];?>" required pattern="[0-9]{1}"/>
                                <span class="text-primary bg-warning">數字四： </span> <input id="number_3" disabled="disabled" type="text" size="3" name="four" value="<?php echo json_decode($data[$i]['number'])[3];?>" required pattern="[0-9]{1}"/>
                                <span class="text-primary bg-warning">數字五： </span> <input id="number_4" disabled="disabled" type="text" size="3" name="five" value="<?php echo json_decode($data[$i]['number'])[4];?>" required pattern="[0-9]{1}"/>
<!--                                <span class="text-primary bg-warning">數字一: </span> <input id="number_0"  type="text" size="3" name="one" value="--><?php //echo json_decode($data[$i]['number'])[0];?><!--" required pattern="[0-9]{1}"/>-->
<!--                                <span class="text-primary bg-warning">數字二： </span> <input id="number_1" type="text" size="3" name="two" value="--><?php //echo json_decode($data[$i]['number'])[1];?><!--" required pattern="[0-9]{1}"/>-->
<!--                                <span class="text-primary bg-warning">數字三： </span> <input id="number_2" type="text" size="3" name="three" value="--><?php //echo json_decode($data[$i]['number'])[2];?><!--" required pattern="[0-9]{1}"/>-->
<!--                                <span class="text-primary bg-warning">數字四： </span> <input id="number_3" type="text" size="3" name="four" value="--><?php //echo json_decode($data[$i]['number'])[3];?><!--" required pattern="[0-9]{1}"/>-->
<!--                                <span class="text-primary bg-warning">數字五： </span> <input id="number_4" type="text" size="3" name="five" value="--><?php //echo json_decode($data[$i]['number'])[4];?><!--" required pattern="[0-9]{1}"/>-->
                                <input class="btn btn-primary" id="chek" type="submit" value="確認" />
                            </div>
                        </h4>
                    </form>

                    <button class="btn btn-primary" id="reload<?php echo $i;?>" name="<?php echo $i;?>" type="" value="開獎" />亂數開獎</button>
                    <hr>
                <?php } ?>
            </div>

            <div class="success-message"></div>
            <div class="error-message"></div>
        </div>
    </div>
    <div class="row">

    </div>
        <script>
            $(document).ready(function(){

                $("button[id^=reload]").hide();
                $("input[id=chek]").hide();
                $("button[id^=reload]").click(function(){

                    var id = $(this).attr('name');
                    $.ajax({
                        url: "Result.php",
                        success: function(data){
                            var result = JSON.parse(data);
                            $.each(result, function(key, value){
                                console.log(key);
                                if(key < 5) {
                                    $('.arrival-info[info=' + id + ']').find('input[id=number_' + key + ']').val(value);
                                }
                            });
                        },
                    });
                });
            });
            function getdata(){
                $.get("CurrentTimeSelect.php", function(data) {
                    console.log(data);

                    $.ajax({
                        url: "CurrentTimeSelect.php",
                        success: function(data){

                            var result = JSON.parse(data);

                            if (result[0] == "free") {
                                $('div[info=' + result[1] + '] input').attr('disabled', false);
                                $('button[id=reload' + result[1] + ']').show();
                                $('div[info=' + result[1] + '] input[id=chek]').show();
                            }

                            if (result == "no game") {
                                $('div[class=arrival-info] input').attr('disabled', 'disabled');
                                $('button[id^=reload]').hide();
                                $('div[info] input[id=chek]').hide();
                            } else {
                                $.each(result, function (key, value) {

                                    $('div[info=' + result[5] + '] input').attr('disabled', "disabled");

                                    if (data != "no game") {
                                        $('div[info=' + result[5] + '] input').attr('disabled', false);
                                        $('button[id=reload' + result[5] + ']').show();
                                        $('div[info=' + result[5] + '] input[id=chek]').show();

                                        if (key < 5) {
                                            $('.arrival-info[info=' + result[5] + ']').find('input[id=number_' + key + ']').val(value);
                                        }
                                    }

                                });
                            }
                        },
                    });
                });
            }
            setInterval(function() {
                getdata();
            },1000);

        </script>
    </body>
</html>
