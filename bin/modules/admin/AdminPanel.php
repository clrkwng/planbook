<!DOCTYPE html>
<?php
ini_set('display_errors',0);

require_once "../../scripts/dbcomm.php";
$dbcomm = new dbcomm();

if(!isset($_GET['id'])) {
    echo "<script>window.location='../error/404.php';</script>";
}
$encryptedUsername = $_GET['id'];
$encryptedUsername = str_replace("!!!", "+", $encryptedUsername);
$encryptedUsername = str_replace("$$$", "%", $encryptedUsername);
$adminUsername = openssl_decrypt($encryptedUsername, 'bf-cfb', 'adminPanelPassword');

$accountID = $dbcomm->getAccountIDByUsername($adminUsername);
$encryptedAccountID = openssl_encrypt($accountID, 'bf-ecb', 'makeNewUserPassword');
$encryptedAccountID = str_replace("+", "!!!", $encryptedAccountID);
$encryptedAccountID = str_replace("%", "$$$", $encryptedAccountID);

if(isset($_GET['delete'])) //delete the user
{
    $deleteUsername = $_GET['delete'];
    $dbcomm->deleteUserByUsername($deleteUsername);
    $alert = '<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Success!</strong>  The user has been deleted.</div>'; //successful deletion alert

}
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--<link rel="icon" href="../../favicon.ico">-->

    <!--meta tags, from bootstrap template-->

    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/admin/admin.css">
    <link rel="stylesheet" href="../../css/form-elements.css">
    <link rel="stylesheet" href="../../css/main-style.css">
    <link rel="stylesheet" href="../../css/starter-template.css">
    <script src="//use.fontawesome.com/7d70b9fab6.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="../../libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../../css/start-bootstrap-template.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/admin-panel.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]-->
    <script src="../../libs/html5shiv/dist/html5shiv.min.js"></script>
    <script src="../../libs/vendor/respond.min.js"></script>
    <!--[endif]-->

    <style>
        a:hover {
            text-decoration: none;
        }
        td#addNewUserButton{
            cursor: pointer;
        }
    </style>
</head>

<body style="background-color: #e6f7ff; padding-top:100px">

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
<!--                <li class="page-scroll">-->
<!--                    <a href="#overview">--><?// echo $dbcomm->getAccountNameByUsername($adminUsername); ?><!-- Overview</a>-->
<!--                </li>-->
                <li class="page-scroll">
                    <a href="../auth/Login.php">Logout</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<!--<br><br><br><br><br><br>-->

<div class="container">
    <div class="starter-template">
        <h1 align="center" style="font-size: 50px">Admin Panel</h1>
        <hr class="star-light">

    <? if (isset($alert))  echo $alert; ?>

    <h2 align="center" style="color:white; font-size: 20px;">Manage <b><? echo $dbcomm->getAccountNameByUsername($adminUsername); ?></b> Users</h2>
    </div>
</div>
<!--<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>-->

<!--<div id="overview"></div>-->
<!--<br><br><br><br><br><br><br>-->

<div class="container">
    <table width="100%">
        <tr style="border-bottom: 1px solid black">
            <th width="40%" style="color: black">
                User:
            </th>
            <th width="20%"style="color: black">
                Points:
            </th>
            <th width="20%"style="color: black">
                Actions:
            </th>
        </tr>
        <?php
        $users = $dbcomm->getAllUsersByAdminUsername($adminUsername);
        $userCounter = 0;
        foreach($users as $userId=>$userValues)
        {
            $userName = $userValues['username'];
            $userPoints = $userValues['total_points'];

            $encryptedRewardAdminUsername = openssl_encrypt($adminUsername, 'AES-128-CFB1', 'rewardPanelAdminPassword');
            $encryptedRewardAdminUsername = str_replace("+", "!!!", $encryptedRewardAdminUsername);
            $encryptedRewardAdminUsername = str_replace("%", "$$$", $encryptedRewardAdminUsername);

            $encryptedRewardUserUsername = openssl_encrypt($userName, 'aes-192-cfb', 'rewardPanelUserPassword');
            $encryptedRewardUserUsername = str_replace("+", "!!!", $encryptedRewardUserUsername);
            $encryptedRewardUserUsername = str_replace("%", "$$$", $encryptedRewardUserUsername);

            $encryptedHomepageUsername = openssl_encrypt($userName, 'RC4-40', 'regularUserPassword');
            $encryptedHomepageUsername = str_replace("+", "!!!", $encryptedHomepageUsername);
            $encryptedHomepageUsername = str_replace("%", "$$$", $encryptedHomepageUsername);

            $userEmail = $dbcomm->getEmailByUsername($userName);

            echo "<tr style='height:80px;' class='hoverable'>
                      <td style='vertical-align: middle; cursor: pointer;' align='left' class='clickUser' id='clickUser$userCounter'>$userName</td>
                      <td style='vertical-align: middle;' align='left'>$userPoints</td>
                      <td style='vertical-align: middle;' align='left'>
                          <a href=\"../user/Homepage.php?id=$encryptedHomepageUsername\" title='Tasks' style='color: black;'>
                              <span class='glyphicon glyphicon-calendar' style='font-size: 20px;'></span>
                          </a>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <a href=\"AddReward.php?id=$encryptedRewardAdminUsername&reward=$encryptedRewardUserUsername\" style='color: pink;' title='Rewards'>
                              <span class='glyphicon glyphicon-piggy-bank' style='font-size: 20px; text-shadow: -1px 0 dimgrey, 0 1px dimgrey, 1px 0 dimgrey, 0 -1px dimgrey;'></span>
                          </a>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <a href=\"mailto:$userEmail?subject=Planbook%20Email\" style='color: dimgrey;' title='Email'>
                              <span class='glyphicon glyphicon-envelope' style='font-size: 20px;'></span>
                          </a>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <a href=\"AdminPanel.php?id=$encryptedUsername&delete=$userName\" style='color: dimgrey;' class=\"confirmation\" title='Delete'>
                              <span class='glyphicon glyphicon-trash' style='font-size: 20px;'></span>
                          </a>
                      </td>
                  </tr>";
            $userCounter++;
        }
        ?>
        <tr style="height: 80px;">
            <td colspan="3" style="vertical-align: middle; color:darkblue" id="addNewUserButton" align="left" class="hoverable">
                <div class = "glyphicon glyphicon-plus" style="color:darkblue;"></div> New User
            </td>
        </tr>
    </table>

</div><!-- /.container -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../libs/jquery/dist/jquery.min.js"></script>
<script src="../../libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../libs/jquery-backstretch/jquery.backstretch.min.js"></script>
<script src="../../scripts/jquery/scripts.js"></script>
<script>window.jQuery || document.write('<script src="../../libs/jquery/dist/jquery.min.js"><\/script>')</script>
<script src="../https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete this user?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    var addUserButton = document.getElementById("addNewUserButton");
    addUserButton.addEventListener('click', function() {
        window.location = '../auth/CreateUser.php?id=<? echo $encryptedAccountID; ?>';
    }, false);

    var clickUsers = document.getElementsByClassName("clickUser");
    var accountUsernames = <?php echo json_encode($dbcomm->getEncodedUsernamesByAccountID($accountID)); ?>;
    for (var i = 0; i < clickUsers.length; i++) {
        clickUsers[i].addEventListener('click', function() {
            var userNum = Number((this.id).substring(9));
            window.location = '../user/ProfileOverview.php?id=' + accountUsernames[userNum] + '&isAdmin=';
        }, false);
    }
</script>
</body>
</html>