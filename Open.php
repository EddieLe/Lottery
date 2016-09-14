<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
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
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
    <?php $data = detail();?>
    <?php for ($i = 0; $i < count($data); $i++) { ?>

        <form method="post" action="OpenResultInsert.php">
            開獎期數：<?php echo $data[$i]['gameID']?> 開獎區間 : <input id="datepicker1" disabled="disabled" type="text" name="start" value="<?php echo date("Y-m-d H:i:s", $data[$i]['startTime'])?>" placeholder="2014-09-18" required/>
            ～ <input id="datepicker2" disabled="disabled" type="text" name="end" value="<?php echo date("Y-m-d H:i:s", $data[$i]['stopTime'])?>" placeholder="2014-09-18" required/>

<!--            數字一: <input type="text" size="3" name="one" value="--><?php //echo $data[0];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字二：<input type="text" size="3" name="two" value="--><?php //echo $data[1];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字三：<input type="text" size="3" name="three" value="--><?php //echo $data[2];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字四：<input type="text" size="3" name="four" value="--><?php //echo $data[3];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字五：<input type="text" size="3" name="five" value="--><?php //echo $data[4];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
                <div class="arrival-info" info=<?php echo $i?>>
            數字一:  <input id="number_0" disabled="disabled" type="text" size="3" name="one" value="<?php echo json_decode($data[$i]['number'])[0];?>" required pattern="[0-9]{1}"/>
            數字二： <input id="number_1" disabled="disabled" type="text" size="3" name="two" value="<?php echo json_decode($data[$i]['number'])[1];?>" required pattern="[0-9]{1}"/>
            數字三： <input id="number_2" disabled="disabled" type="text" size="3" name="three" value="<?php echo json_decode($data[$i]['number'])[2];?>" required pattern="[0-9]{1}"/>
            數字四： <input id="number_3" disabled="disabled" type="text" size="3" name="four" value="<?php echo json_decode($data[$i]['number'])[3];?>" required pattern="[0-9]{1}"/>
            數字五： <input id="number_4" disabled="disabled" type="text" size="3" name="five" value="<?php echo json_decode($data[$i]['number'])[4];?>" required pattern="[0-9]{1}"/>
                    <input id="chek" type="submit" value="確認" />
                </div>
        </form>

        <button id="reload<?php echo $i;?>" name="<?php echo $i;?>" type="" value="開獎" />亂數開獎</button>
    <?php } ?>
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

                            if (result == "no game") {
                                $('div[class=arrival-info]').attr('disabled', 'disabled');
                                $('button[id=reload]').hide();
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

        <a href ="Game.php">上一頁</a>
    </body>
</html>
