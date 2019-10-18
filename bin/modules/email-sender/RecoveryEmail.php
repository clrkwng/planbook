<?php
require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if (isset($_POST['Submit'])){
    if(isset($_POST['recovery-email'])){
        $email = $_POST['recovery-email'];
        if ($dbcomm->checkIfEmailExists($email)){
            $username = $dbcomm->getUsernameByEmail($email);
            $encryptedUsername = openssl_encrypt("$username", 'CAST5-CBC', 'resetPasswordPassword');
            $encryptedUsername = str_replace("+", "!!!", $encryptedUsername);
            $encryptedUsername = str_replace("%", "$$$", $encryptedUsername);
            $message = "<html>"
                    ."<body>"
                        ."<div>"
                            ."<span>"
                                ."Hello, $username"
                            ."</span>"
                            ."<br><br>"
                            ."<span>"
                                ."Click on the following link to reset your Planbook password."
                            ."</span>"
                            ."<br><br>"
                            ."<a href='../auth/PasswordReset.php?id=$encryptedUsername'>"
                                ."Reset Password".
                            ".</a>"
                            ."<br><br>"
                            ."<span>"
                                ."Please ignore this email if you did not request a password change."
                            ."</span>"
                            ."<br><br>"
                            ."<span>"
                                ."As always, thank you for using Planbook!"
                            ."</span>"
                            ."<br><br>"
                            ."<span>"
                                ."Planbook Services"
                            ."</span>"
                            ."<br>"
                            ."<a href='http://dev2.planbook.xyz/Bitbucket/bin/modules/index.html'>"
                                ."www.planbook.com"
                            ."</a>"
                        ."</div>"
                    ."</body>"
                ."</html>";
            $headers = "From: noreply@planbook.xyz"."\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            mail( $email, "Planbook Recovery Email", $message, $headers);

            $alert .= '<div class="alert alert-success alert-dismissible" role="alert">'
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                . '<span>'
                . 'Email has been sent.'
                . '</span>'
                . '</div>';
        }

        else{
            $alert .= '<div class="alert alert-warning alert-dismissible" role="alert">'
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                . '<strong>'
                . 'Error: '
                . '</strong>'
                . '<span>'
                . 'Associated email was not registered.'
                . '</span>'
                . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recovery</title>

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
                <div class="col-sm-6 col-sm-offset-3 text">
                    <h1 align="left">
                        <font color="#696969"><strong>Login Recovery Page</strong></font>
                    </h1>
                    <div class="">
                        <p align = "left">
                            <font color="696969">Enter your email and follow the instructions on resetting your password.</font>
                        </p>
                    </div>

                    <? if (isset($alert))
                    {
                        echo '<div>';
                        echo $alert;
                        echo '</div>';
                    }
                    ?>

                </div>

            <div class="col-sm-6 col-sm-offset-3 form-box">
                <div class="form-bottom">
                    <form role="form" action="" method="post" class="login-form">
                        <div class="form-group">
                            <label class="sr-only" for="recovery-email">Email</label>
                            <input type="email" name="recovery-email" placeholder="Email..."
                                   class="form-email form-control" id="recovery-email"
                                   value="">
                        </div>
                        <button class = "btn" type = "submit" name="Submit">Send email</button>
                    </form>
                    <div>

                    </div>
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