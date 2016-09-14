<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Responsive Login Form</title>

    <link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<link href='http://fonts.googleapis.com/css?family=Ubuntu:500' rel='stylesheet' type='text/css'>

<div class="login">
    <div class="login-header">
        <h1>Registered</h1>
    </div>
    <div class="login-form">
        <form action="MysqlInsert.php" method="post">
            <h3>Username:</h3>
            <input type="text" placeholder="account" name="account"/><br>
            <h3>Password:</h3>
            <input type="password" placeholder="password" name="password"/>
            <br>
            <input type="submit" value="Create" class="login-button"/>
            <br>
            <a class="sign-up" href="LogIn.php">LogIn!</a>
            <br>
            <h6 class="no-access">Can't access your account?</h6>
        </form>
    </div>
</div>
<div class="error-page">
    <!--    <div class="try-again">Error: Try again?</div>-->
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>

<script src="js/index.js"></script>

</body>
</html>

