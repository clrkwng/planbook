<!DOCTYPE html>

<?php
require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if(!isset($_GET['id'])){
    echo "<script>window.location='../error/404.php'</script>";
}
$encryptedAccountID = $_GET['id'];
$encryptedAccountID = str_replace("!!!", "+", $encryptedAccountID);
$encryptedAccountID = str_replace("$$$", "%", $encryptedAccountID);
$accountID = openssl_decrypt($encryptedAccountID, 'bf-ecb', 'makeNewUserPassword');

$errorCount = 0;

if(isset($_POST['user-password'])) {
    $password = filter_var($_POST['user-password'], FILTER_SANITIZE_STRING);
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
}
if(isset($_POST['user-repassword']) and $_POST['user-password']) {
    $repassword = filter_var($_POST['user-repassword'], FILTER_SANITIZE_STRING);
    if($repassword != $_POST['user-password']) {
        $errorCount++;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'The passwords do not match.'
            .'</span>'
            .'</div>';
    }
}
if (isset($_POST['user-phonenumber'])){
    if(!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", $_POST['user-phonenumber'])){
        $errorCount++;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'Invalid phone number.'
            .'</span>'
            .'</div>';
    }
}
if(isset($_POST["user-email"])){
    if (!filter_var($_POST["user-email"], FILTER_VALIDATE_EMAIL)) {
        $errorCount++;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'Invalid email.'
            .'</span>'
            .'</div>';
    }
}

if ($errorCount == 0 && isset($_POST['user-password'])) {
    $username = $_POST['user-username'];
    $password = sha1($_POST['user-password']);
    $email = $_POST['user-email'];
    $phonenumber = preg_replace('/\D+/', '', $_POST['user-phonenumber']);

    if($dbcomm->checkIfUsernameExists($username)) {
        $errorCount += 1;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'Username is already in use.'
            .'</span>'
            .'</div>';
    }
    /*if($dbcomm->checkIfPhonenumberExists($phonenumber)) {
        $errorCount += 1;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'Phone number is already associated with an account.'
            .'</span>'
            .'</div>';
    }*/
    if($dbcomm->checkIfEmailExists($email)) {
        $errorCount += 1;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            .'<strong>'
            .'Error: '
            .'</strong>'
            .'<span>'
            .'Email is already associated with an account.'
            .'</span>'
            .'</div>';
    }
    if ($errorCount == 0) {
        $dbcomm->createNewUser($accountID, $username, $password, $email, $phonenumber);
        $adminUsername = $dbcomm->getAdminUsernameByAccountID($accountID);
        $encryptedUsername = openssl_encrypt($adminUsername, 'bf-cfb', 'adminPanelPassword');
        $encryptedUsername = str_replace("+", "!!!", $encryptedUsername);
        $encryptedUsername = str_replace("%", "$$$", $encryptedUsername);
        echo "<script>window.location = '../admin/AdminPanel.php?id=$encryptedUsername';</script>";
    }
}
else {
    echo "<script>document.getElementById(\"user-password\").innerHTML = \"\";</script>";
    echo "<script>document.getElementById(\"user-repassword\").innerHTML = \"\";</script>";
}

?>


<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
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
                    <a href="../index.html#portfolio">Goals</a>
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
                    <h1 align="left" style="font-size: 3em; color">
                        <font color="white">Create a New User</font>
                    </h1>

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
                        <form role="form" action="" method="post" class="login-form" id="signup-form">
                            <div class="form-group">
                                <label class="sr-only" for="user-username">Username</label>
                                <input type="text" name="user-username" placeholder="Username..."
                                       class="form-control" id="user-username">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="user-password">Password</label>
                                <input type="password" name="user-password" placeholder="Password..."
                                       class="form-control" id="user-password">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="user-repassword">Re-enter Password</label>
                                <input type="password" name="user-repassword" placeholder="Re-enter password..."
                                       class="form-control" id="user-repassword">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="user-phonenumber">Phone Number</label>
                                <input type="text" name="user-phonenumber" placeholder="Phone number..."
                                       class="form-control" id="user-phonenumber"
                                       onblur="$(this).val($(this).val().replace(/[^0-9.]/g, '')); if($(this).val().length >= 10){$(this).val('(' + $(this).val().substring(0,3) + ') ' + $(this).val().substring(3,6) + '-' + $(this).val().substring(6,10));}">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="user-email">Email</label>
                                <input type="text" name="user-email" placeholder="Email..."
                                       class="form-email form-control" id="user-email">
                            </div>
                            <button type="submit" class="btn" >Create User</button>
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
<script src="../../scripts/jquery/formatPhoneInput.js"></script>

<!--[if lt IE 10]-->
<script src="../../scripts/jquery/placeholder.js"></script>
<!--[endif]-->

</body>

</html>