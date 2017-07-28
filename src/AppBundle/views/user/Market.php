<!DOCTYPE html>

<?php

if(!isset($_GET['id'])){
    die("Error: The id was not set.");
}
$encryptedUsername = $_GET['id'];
$encryptedUsername = str_replace("!!!", "+", $encryptedUsername);
$encryptedUsername = str_replace("$$$", "%", $encryptedUsername);
$username = openssl_decrypt($encryptedUsername,'CAST5-ECB','toMarketPassword');

require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if (isset($_POST['doneButton'])) {
    $unencryptedRewardName = str_replace("XyzYx", " ", $_POST['rewardName']);
    $dbcomm->redeemRewardByUsername($username, $unencryptedRewardName);
}

?>


<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Market</title>

    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../libs/fullpage-js/dist/jquery.fullpage.min.js" />
    <link rel="stylesheet" type="text/css" href="../../css/fullpage-style.css" />
    <link rel="stylesheet" href="../../libs/w3-css/w3.css">
    <link rel="stylesheet" type="text/css" href="../../css/user/market.css"/>

</head>
<body>

<table width="100%" style="height: 100%; text-align: center;">
    <tr>
        <td width="15%"></td>
        <td height="25%">
            <h1>Market</h1>
            <p style="font-size: 25px;">Redeem your prize now!</p>
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
            <button onclick="window.location='Homepage.php?id=<? echo $encryptedHomeUsername ?>#3rdPage'" class="w3-button w3-circle w3-teal" style="transform: translateX(-50%) rotate(180deg); width: 190px; height: 180px; font-size: 75px;">➜&nbsp;&nbsp;&nbsp;</button>
        </td>
        <td rowspan="2" valign="top" style="overflow-y: auto;">
            <br><br>
            <table id="rewardsTable" border="1px solid black">
                <tr id="tableHeader">
                    <td width="50%">
                        <p>Reward</p>
                    </td>
                    <td width="20%">
                        <p>Points</p>
                    </td>
                    <td width="15%">
                        <p>Status</p>
                    </td>
                    <td width="15%">
                        <p>Date</p>
                    </td>
                </tr>

                <?php
                $rewards = $dbcomm->getAllRewardsByUsername($username);
                $userCounter = 0;
                foreach ($rewards as $rewardId => $rewardValues) {
                    $rewardName = $rewardValues['name'];
                    $rewardPoints = $rewardValues['points'];
                    $rewardCompleted = $rewardValues['completed'];
                    $rewardRedeemDate = $rewardValues['redeem_date'];

                    if ($rewardRedeemDate == "//") {
                        $rewardRedeemDate = "---";
                    }

                    echo "<tr style='height: 40px;'>
                                <td>$rewardName</td>
                                <td>$rewardPoints</td>";

                    if ($rewardCompleted == 1) {
                        echo "<td><span class='glyphicon glyphicon-ok' style='font-size: 25px; color: green;'></span></td>";
                    } else {
                        $encyptedRewardName = str_replace(" ", "XyzYx", $rewardName);
                        echo "<td>
                                    <form role='form' action=\"Market.php?id=$encryptedUsername\" method='post'>
                                        <input type='text' name='rewardName' value='$encyptedRewardName' style='display: none;'>
                                        <button type='submit' name='doneButton' id='doneButton'>Redeem?</button>
                                    </form>
                              </td>";
                    }
                    echo "      <td>$rewardRedeemDate</td>
                          </tr>";
                }
                ?>

            </table>
        </td>
        <td width="15%" style="text-align: right" valign="center" id="awardsSideBar">
            <br>
            <p style="display: inline-block; height: 45px;"><? echo $dbcomm->getNumCurrentPointsByUsername($username); ?>&nbsp;</p><div style="font-size: 18px; display:inline-block; max-width: 60px; text-align: left;"> Total Points&nbsp;</div>
            <p><? echo $dbcomm->getNumBronzeStarsByUsername($username); ?> <img src="<? echo $dbcomm->getBronzeStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumSilverStarsByUsername($username); ?> <img src="<? echo $dbcomm->getSilverStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumGoldStarsByUsername($username); ?> <img src="<? echo $dbcomm->getGoldStarImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumBronzeTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getBronzeTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumSilverTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getSilverTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <p><? echo $dbcomm->getNumGoldTrophiesByUsername($username); ?> <img src="<? echo $dbcomm->getGoldTrophyImageSource(); ?>" width="50" height="50">&nbsp;</p>
            <br>
        </td>
    </tr>
    <tr><td></td><td></td></tr>
</table>

</body>
</html>