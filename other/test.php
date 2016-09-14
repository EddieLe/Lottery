<?php

?>
<html>
    <head>
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        <script>
//            $(document).ready(function(){
//                setInterval(function(){
//                    $.ajax({
//                        url: "show.php",
//                        type: "GET",
//                        data: {name:"eddie"},
//                        success: function(data){
////                            alert(data);
////                            data = JSON.parse(data);
////                            $.each( data, function( Key, value ){
////                                console.log(value['count']);
////                                $('.arrival-info[data-info='+Key+']').text(value['count']);
////                            });
//                        },
//                    })
//                },10000);
//            });
        </script>
        <script>
            var startDate = new Date('2010/11/15 22:59:50');
            var endDate = new Date('2010/11/15 23:00:00');
//            var spantime = (endDate - startDate)/1000;
            var spantime = 7;
            var count = 0;

            $(document).ready(function () {

                setInterval(function(){
                    spantime --;
//                    var d = Math.floor(spantime / (24 * 3600));
//                    var h = Math.floor((spantime % (24*3600))/3600);
                    var m = Math.floor((spantime % 3600)/(60));
                    var s = Math.floor(spantime % 60);

                    if(spantime>0){
//                        $("#hour").text(h+(d*24));
                        $("#min").text(m);
                        $("#sec").text(s);

                    }else{ // 避免倒數變成負的
                        spantime = 7;
                        count ++;
//                        $("#hour").text(0);
                        $("#min").text(0);
                        $("#sec").text(7);
                        $.ajax({
                            url: "show.php",
                            type: "GET",
                            data: {name:"eddie"},
                            success: function(data){
//                                alert(data);
                            },
                        })
                        setTimeout(function(){

                        },5000);
                    }

                },1000);

//                if (count >= 2) {
//                    alert("活動結束");
//                    return false;
//                }
            });
        </script>

        還有：
        <div id="hour"></div>時
        <div id="min"></div>分
        <div id="sec"></div>秒
    </body>
</html>
