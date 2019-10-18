<!DOCTYPE html>

<?php

if(!isset($_GET['id'])){
    echo "<script>window.location='../error/404.php';</script>";
}
$encryptedUsername = $_GET['id'];
$encryptedUsername = str_replace("!!!", "+", $encryptedUsername);
$encryptedUsername = str_replace("$$$", "%", $encryptedUsername);
$username = openssl_decrypt($encryptedUsername,'CAST5-ECB','toMarketPassword');

require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if (isset($_GET['rewardName'])) {
    $unencryptedRewardName = str_replace("XyzYx", " ", $_GET['rewardName']);
    if (!$dbcomm->redeemRewardByUsername($username, $unencryptedRewardName)) {
        $alert = '<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Error!</strong>  Insufficient points to redeem.</div>';
    }
}

?>


<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Redeem</title>

    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../libs/fullpage-js/dist/jquery.fullpage.min.js" />
    <link rel="stylesheet" type="text/css" href="../../css/fullpage-style.css" />
    <link rel="stylesheet" href="../../libs/w3-css/w3.css">
    <link rel="stylesheet" type="text/css" href="../../css/user/redeem.css"/>


    <!-- Favicon -->
    <link rel="shortcut icon" href="../../resources/img/ico/favicon.png">
</head>
<body>

<table width="100%" style="height: 100%; text-align: center;">
    <tr>
        <td width="15%"></td>
        <td height="25%">
            <h1>Redeem</h1>
            <p style="font-size: 25px;">Get your prize now!</p>
            <? if (isset($alert))  echo $alert; ?>
        </td>
        <td width="15%"></td>
    </tr>
    <tr>
        <td width="15%" height="71%">
            <div class="toAwards" id="toAwards1">T</div>
            <div class="toAwards" id="toAwards2">O</div>
            <div class="toAwards" id="toAwards3">T</div>
            <div class="toAwards" id="toAwards4">H</div>
            <div class="toAwards" id="toAwards5">E</div>
            <div class="toAwards" id="toAwards6">A</div>
            <div class="toAwards" id="toAwards7">W</div>
            <div class="toAwards" id="toAwards8">A</div>
            <div class="toAwards" id="toAwards9">R</div>
            <div class="toAwards" id="toAwards10">D</div>
            <div class="toAwards" id="toAwards11">S</div>
            <div style="height:30px;"></div>
            <?php
            $encryptedHomeUsername = openssl_encrypt($username,'RC4-40','regularUserPassword');
            $encryptedHomeUsername = str_replace("+", "!!!", $encryptedHomeUsername);
            $encryptedHomeUsername = str_replace("%", "$$$", $encryptedHomeUsername);
            ?>
            <button onclick="window.location='Homepage.php?id=<? echo $encryptedHomeUsername ?>#3rdPage'" class="w3-button w3-circle" style="transform: translateX(-50%) rotate(180deg); width: 190px; height: 180px; font-size: 75px;background-color: #3f5a73; color: white;">➜&nbsp;&nbsp;&nbsp;</button>
        </td>
        <td rowspan="2" valign="top" style="overflow-y: auto;">
            <br><br>

            <?php
            $rewards = $dbcomm->getAllRewardsByUsername($username);
            if(count($rewards) > 0) {
                echo "<table id='rewardsTable' border='1px solid black'>" .
                    "<tr id='tableHeader'>" .
                    "<td width='50%'>" .
                    "<p>Reward</p>" .
                    "</td>" .
                    "<td width='20%'>" .
                    "<p>Points</p>" .
                    "</td>" .
                    "<td width='15%'>" .
                    "<p>Status</p>" .
                    "</td>" .
                    "<td width='15%'>" .
                    "<p>Date</p>" .
                    "</td>" .
                    "</tr>";


                $userCounter = 0;
                foreach ($rewards as $rewardId => $rewardValues) {
                    $rewardName = $rewardValues['name'];
                    $rewardPoints = $rewardValues['points'];
                    $rewardCompleted = $rewardValues['completed'];
                    $rewardRedeemDate = $rewardValues['redeem_date'];

                    if ($rewardRedeemDate == "0/0/0") {
                        $rewardRedeemDate = "---";
                    }

                    echo "<tr style='height: 40px;'>
                                <td>$rewardName</td>
                                <td>$rewardPoints</td>";

                    if ($rewardCompleted == 1) {
                        echo "<td>
                                <span class='glyphicon glyphicon-ok' style='font-size: 25px; color: green;'></span>
                              </td>";
                    } else {
                        $encyptedRewardName = str_replace(" ", "XyzYx", $rewardName);
                        echo "<td>
                                    <a href='Redeem.php?id=$encryptedUsername&rewardName=$encyptedRewardName'>
                                        <button type='button' id='doneButton' class='confirmRedeem' title='Redeem'>Redeem?</button>
                                    </a>
                              </td>";
                    }
                    echo "      <td>$rewardRedeemDate</td>
                          </tr>";
                }

                echo "</table>";
            }
            else {
                echo "<br><br><br>";
                echo "<h2>Your admin has not set any awards for you yet.</h2>";
            }

            ?>

        </td>
        <td width="15%" style="text-align: right" valign="center" id="awardsSideBar">
            <br>
            <p style="display: inline-block; height: 45px;"><? echo $dbcomm->getNumTotalPointsByUsername($username); ?>&nbsp;</p><div style="font-size: 18px; display:inline-block; max-width: 60px; text-align: left;"> Total Points&nbsp;</div>
            <p><? echo $dbcomm->getNumBronzeStarsByUsername($username); ?> <img src="<? echo $dbcomm->getBronzeStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumSilverStarsByUsername($username); ?> <img src="<? echo $dbcomm->getSilverStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumGoldStarsByUsername($username); ?> <img src="<? echo $dbcomm->getGoldStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumBronzeTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getBronzeTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumSilverTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getSilverTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumGoldTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getGoldTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <br>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>
<script src="../../libs/jquery/dist/jquery.min.js"></script>
<script src="../../libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../libs/jquery-backstretch/jquery.backstretch.min.js"></script>
<script src="../../scripts/jquery/scripts.js"></script>

<script>
    var elems = document.getElementsByClassName('confirmRedeem');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to redeem this task?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

</body>
</html>