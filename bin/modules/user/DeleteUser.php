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
$username = openssl_decrypt($encryptedUsername, 'RC4', 'deleteUserPassword');
$encryptedUsername = str_replace("+", "!!!", $encryptedUsername);
$encryptedUsername = str_replace("%", "$$$", $encryptedUsername);

if($dbcomm->checkIfUsernameExists($username)){
    $dbcomm->deleteUserByUsername($username);
}

?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Delete user</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libs/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/form-elements.css">
    <link rel="stylesheet" href="../../css/main-style.css">
    <link rel="stylesheet" href="../../css/user/delete-user.css">

</head>
<body bgcolor="#7FB3D5">

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

<div id="container" align="center">

    <br><br><br><br><br><br><br><br><br>

    <h2>The user has been successfully deleted.</h2>

    <br><br>

    <p>Thank you for using Planbook.</p>

</div>



<script src="../../libs/jquery/dist/jquery.min.js"></script>
<script src="../../libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../libs/jquery-backstretch/jquery.backstretch.min.js"></script>
<script src="../../scripts/jquery/scripts.js"></script>


</body>
</html>