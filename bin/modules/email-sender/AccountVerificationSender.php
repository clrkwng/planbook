<!DOCTYPE html>
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
$username = openssl_decrypt($encryptedUsername, 'RC4', 'sendVerificationEmailPassword');

$email = $dbcomm->getEmailByUsername($username);

if ($dbcomm->checkIfEmailExists($email)) {
    $headers = "From: noreply@planbook.xyz" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = "<html><body><div>Hello,<br><br>
        Click on the following link to verify your Planbook account.<br><br>
        <a href='http://dev1.planbook.xyz/Bitbucket/bin/modules/auth/AccountVerification.php?id=$encryptedUsername'>Verify Account</a><br><br>
        Please ignore this email if you did not create a Planbook account.<br><br>
        As always, thanks for using Planbook!<br><br>
        Planbook Services<br>
        <a href='http://dev1.planbook.xyz/Bitbucket/bin/modules/index.html'>www.planbook.com</a>
        </div></body></html>";
    mail($email, "Planbook Verification Email", $message, $headers);
}


?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Verification</title>

    <!-- CSS -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libs/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/form-elements.css">
    <link rel="stylesheet" href="../../css/main-style.css">
    <link rel="stylesheet" href="../../css/email-sender/email-sender.css">

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
                    <a href ="../auth/Login.php">Login/Sign up</a>
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
                <div class="col-sm-8 col-sm-offset-2 text">
                    <br>
                    <h4>
                        <font color="White"> Your verification email has been sent. Please check your inbox.</font>
                    </h4>
                    <p class="description">
                        <a href = "AccountVerificationSender.php?id=<? echo $encryptedUsername ?>">Resend email?</a>
                    </p>
                </div>
                <br><br><br><br>
                <div>
                    <img src="../../resources/img/email-logo.png" width="250px" height="250px">
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
<script src="../../scripts/jquery/formatPhoneInput.js"></script>

<!--[if lt IE 10]-->
<script src="../../scripts/jquery/placeholder.js"></script>
<!--[endif]-->

</body>

</html>