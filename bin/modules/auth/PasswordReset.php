<?php
require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if(!isset($_GET['id'])) {
    echo "<script>window.location='../error/404.php';</script>";
}
$encryptedUsername = $_GET['id'];
$encryptedUsername = str_replace("!!!", "+", $encryptedUsername);
$encryptedUsername = str_replace("$$$", "%", $encryptedUsername);
$username = openssl_decrypt($encryptedUsername, 'CAST5-CBC', 'resetPasswordPassword');

if(isset($_POST['Submit'])) {
    $errorCount = 0;

    if (isset($_POST['reset-newPassword']) and isset($_POST['reset-rePassword'])) {
        $password = filter_var($_POST['reset-newPassword'], FILTER_SANITIZE_STRING);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            $errorCount++;
            $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
                .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                .'<strong>'
                .'Error: '
                .'</strong>'
                .'<span>'
                .'Password does not meet the requirements.'
                .'</span>'
                .'</div>';
        }

        $repassword = filter_var($_POST['reset-rePassword'], FILTER_SANITIZE_STRING);
        if ($repassword != $_POST['reset-newPassword']) {
            $errorCount++;
            $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
                .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                .'<strong>'
                .'Error: '
                .'</strong>'
                .'<span>'
                .'Password does not match.'
                .'</span>'
                .'</div>';
        }
    }


    if ($errorCount == 0 && isset($_POST['reset-newPassword'])) {
        $password = sha1($_POST['reset-newPassword']);
        $dbcomm->resetPasswordByUsername($username, $password);
        echo "<script>window.location = 'Login.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>

    <!-- CSS -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libs/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/form-elements.css">
    <link rel="stylesheet" href="../../css/main-style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <script src="../../libs/html5shiv/dist/html5shiv.min.js"></script>
    <script src="../../libs/vendor/respond.min.js"></script>
    <!--[endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="../../resources/img/ico/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../resources/img/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../resources/img/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../resources/img/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../../resources/img/ico/apple-touch-icon-57-precomposed.png">

</head>

<body>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="../index.html">Planbook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href ="Login.php">Login/Sign up</a>
                </li>
                <li class="page-scroll">
                    <a href="../index.html#portfolio">Activities</a>
                </li>
                <li class="page-scroll">
                    <a href="../index.html#about">About</a>
                </li>
                <li class="page-scroll">
                    <a href="../index.html#contact">Contact</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<!-- Top content -->
<div class="top-content">

    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text">
                    <h1 align="left">
                        <font color="#696969"><strong>Reset Your Password</strong></font>
                    </h1>
                    <div class="">
                        <p align="left">
                            <font color="#696969">
                                Enter your new password for <b><? echo $username; ?></b>.<br>
                                Make sure your password has at least one uppercase letter, one lowercase letter,
                                one number, and at least 8 characters long.
                            </font>
                        </p>
                    </div>
                    <? if (isset($alert)) //if the alert for creating list is set, then echo the alert
                    {
                        echo '<div>';
                        echo $alert;
                        echo '</div>';
                    }
                    ?>

                </div>

            <div class="col-sm-6 col-sm-offset-3 form-box">
                <div class="form-bottom">
                    <form role="form" method="post" class="login-form">
                        <div class="form-group">
                            <label class="sr-only" for="reset-newPassword">New Password</label>
                            <input type="password" name="reset-newPassword" placeholder="New Password..."
                                   class="form-control" id="reset-newPassword">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="reset-rePassword">Re-enter Password</label>
                            <input type="password" name="reset-rePassword" placeholder="Re-enter Password..."
                                   class="form-control" id="reset-rePassword">
                        </div>
                        <button class = "btn" type = "submit" name="Submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<!-- Javascript -->
<script src="../../libs/jquery/dist/jquery.min.js"></script>
<script src="../../libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../libs/jquery-backstretch/jquery.backstretch.min.js"></script>
<script src="../../scripts/jquery/scripts.js"></script>

<!--[if lt IE 10]-->
<script src="../../scripts/jquery/placeholder.js"></script>
<!--[endif]-->

</body>

</html>