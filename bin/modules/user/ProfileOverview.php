<?php
ini_set('display_errors',0);
require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if(!isset($_GET['id']))
{
    echo "<script>window.location='../error/404.php';</script>";
}

$encryptedUsername = $_GET['id'];
$encryptedUsername = str_replace("!!!", "+", $encryptedUsername);
$encryptedUsername = str_replace("$$$", "%", $encryptedUsername);
$username = openssl_decrypt($encryptedUsername, 'DES-EDE3', 'viewUserProfilePassword');
$encryptedUsername = str_replace("+", "!!!", $encryptedUsername);
$encryptedUsername = str_replace("%", "$$$", $encryptedUsername);

if(isset($_GET['isAdmin'])) {
    $isAdmin = true;
}


if (isset($_POST['Submit1']) and isset($_FILES['image'])) {
    if ($_FILES['image']['size'] == 0 and $_FILES['image']['error'] == 0) {
        $alert = '<div class="alert alert-danger alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            . '<strong>'
            . 'Error: '
            . '</strong>'
            . '<span>'
            . 'No file was selected'
            . '</span>'
            . '</div>';
    }
    else {
        chdir("../../resources/img/profilePictures/");
        $cwd = getcwd();
        $path = $cwd . '/'.$username;
        //echo "<script>console.log('$path');</script>";
        if (mkdir("$path")){
            //echo "<script>console.log('Huzzah!');</script>";
        }
        else {
            //echo "<script>console.log('...');</script>";
        }

        $allowed_ext= array('jpg','jpeg','png','gif');
        $file_name =$_FILES['image']['name'];
        //   $file_name =$_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.',$file_name)));

        $file_size=$_FILES['image']['size'];
        $file_tmp= $_FILES['image']['tmp_name'];
        //echo $file_tmp;echo "<br>";

        $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        $data = file_get_contents($file_tmp);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        //echo "<script>console.log('$base64');</script>";
        //echo "Base64 is ".$base64;

        if(in_array($file_ext,$allowed_ext) === false) {
            $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                . '<strong>'
                . 'Error: '
                . '</strong>'
                . '<span>'
                . 'Invalid file extension.'
                . '</span>'
                . '</div>';
        }

        if($file_size > 2097152) {
            $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                . '<strong>'
                . 'Error: '
                . '</strong>'
                . '<span>'
                . 'File is too large. Must be less than two megabytes.'
                . '</span>'
                . '</div>';
        }
        if(!isset($alert)) {
            move_uploaded_file($file_tmp, "$path".'/'.$file_name);
            //echo "<script>console.log('The file was uploaded');</script>";
            $dbcomm->updateProfileImageByUsername($username,"../../resources/img/profilePictures/".$username."/".$file_name);
        }
    }
}

if (isset($_POST['Submit2']) and isset($_POST['email'])) {
    $errorCount = 0;
    $email = $_POST["email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorCount++;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            . '<strong>'
            . 'Error: '
            . '</strong>'
            . '<span>'
            . 'Invalid email.'
            . '</span>'
            . '</div>';
    }
    if ($dbcomm->checkIfEmailExists($email)) {
        $errorCount += 1;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            . '<strong>'
            . 'Error: '
            . '</strong>'
            . '<span>'
            . 'Email is already associated with an account.'
            . '</span>'
            . '</div>';
    }
    if ($errorCount == 0) {
        $dbcomm->updateEmailByUsername($username, $email);
    }
}

if (isset($_POST['Submit3']) and isset($_POST['phonenumber'])) {
    $errorCount = 0;
    $phonenumber = $_POST["phonenumber"];
    $phonenumber = preg_replace('/\D+/', '', $phonenumber);
    if (isset($_POST['signup-phonenumber'])){
        if(!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", $_POST['signup-phonenumber'])){
            $errorCount++;
            $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                .'<span aria-hidden="true">&times;</span>'
                .'</button>'
                . '<strong>'
                . 'Error: '
                . '</strong>'
                . '<span>'
                . 'Invalid phone number.'
                . '</span>'
                . '</div>';
        }
    }
    if($dbcomm->checkIfPhonenumberExists($phonenumber)) {
        $errorCount += 1;
        $alert .= '<div class="alert alert-danger alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            .'<span aria-hidden="true">&times;</span>'
            .'</button>'
            . '<strong>'
            . 'Error: '
            . '</strong>'
            . '<span>'
            . 'Phone number is already associated with an account.'
            . '</span>'
            . '</div>';
    }
    if ($errorCount == 0) {
        $dbcomm->updatePhoneNumberByUsername($username, $phonenumber);
    }
}

if(isset($_GET['themeName'])) {
    $themeName = str_replace("_"," ",$_GET['themeName']);
    $dbcomm->setThemeByThemeName($username, $themeName);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="../../libs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libs/font-awesome/css/font-awesome.min.css">
    <!--    <link rel="stylesheet" href="../../css/form-elements.css">-->
    <!--    <link rel="stylesheet" href="../../css/main-style.css">-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <!--[endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="../../resources/img/ico/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../resources/img/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../resources/img/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../resources/img/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../../resources/img/ico/apple-touch-icon-57-precomposed.png">

    <style>
        #profileImage {
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid black;
            width: 150px;
            height: 150px;
        }

        #switchUserProfileImage, #switchUserProfileImageButton {
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid black;
            width: 50px;
            height: 50px;
        }
        .otherUsersProfileImages {
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid black;
            width: 50px;
            height: 50px;
        }
        #selfProfileImage {
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid black;
            width: 70px;
            height: 70px;
        }


        #mainNav, #mainNavContainer {
            height: 100px;
            padding-top: 11px;
        }
        .navbar-custom {
            background: #3f5a73;
            font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 700;
            font-size: 1.3em;
            border: none;
        }

        p, td {
            font-size: 20px;
            color: #5e5e5e;
        }
        #profileImageButton, #changeEmailButton, #changePhoneNumberButton {
            cursor: pointer;
        }
    </style>

</head>

<body>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
    <div class="container" id="mainNavContainer">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="../index.html"><font style="color:white; font-size: 2em;">Planbook</font></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ol class="nav navbar-nav navbar-right">
                <li>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="switchUserProfileImageButton">
                            <img src="<? echo $dbcomm->getProfileImageByUsername($username); ?>" id="switchUserProfileImage" style="position:relative; top: -7px; left: -12px;">
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li style="padding-left: 20px; color: black; font-size: 16px;"><? echo $dbcomm->getAccountNameByUsername($username); ?> Users</li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <table style="color: black;">
                                    <tr>
                                        <td rowspan="3" style='padding-left: 10px; padding-right: 10px;'><img src='<? echo $dbcomm->getProfileImageByUsername($username); ?>' id="selfProfileImage"></td>
                                        <td valign="bottom" style="padding-right: 10px; font-size: 15px;"><? echo $username; ?></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="padding-right: 10px; font-size: 15px;"><? echo $dbcomm->getEmailByUsername($username); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="height: 50px; padding-right: 10px;">
                                            <?php
                                            $encyptedProfileOverviewUsername = openssl_encrypt($username, 'DES-EDE3', 'viewUserProfilePassword');
                                            $encyptedProfileOverviewUsername = str_replace("+", "!!!", $encyptedProfileOverviewUsername);
                                            $encyptedProfileOverviewUsername = str_replace("%", "$$$", $encyptedProfileOverviewUsername);
                                            ?>
                                            <button onclick="window.location='ProfileOverview.php?id=<? echo $encyptedProfileOverviewUsername; ?>';" style="border-radius: 5px; border: none; background-color: #337ab7; color: white; font-size: 15px;">My Account</button>
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <?php
                            $switchUsers = $dbcomm->getOtherUsersOfAccountByUsername($username);
                            for ($i = 0; $i < count($switchUsers); $i++) {
                                $switchUserUsername = $switchUsers[$i]['name'];
                                $switchUserEmail = $switchUsers[$i]['email'];
                                $switchUserProfileImage = $switchUsers[$i]['link'];
                                echo "<li role='separator' class='divider'></li>";
                                echo "<li>
                                        <a href='../auth/Login.php?switchUser=$switchUserUsername'>
                                            <table>
                                                <tr>
                                                    <td rowspan='2' style='padding-right: 20px;'><img src='$switchUserProfileImage' class='otherUsersProfileImages'></td>
                                                    <td valign='bottom' style='padding-right: 10px; font-size: 15px;'>$switchUserUsername</td>
                                                </tr>
                                                <tr>
                                                    <td valign='top' style='padding-right: 10px; font-size: 15px;'>$switchUserEmail</td>
                                                </tr>
                                            </table>
                                        </a>
                                      </li>";
                            }
                            ?>
                            <li role='separator' class='divider'></li>
                            <li><a href="../auth/Login.php">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ol>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<!-- Top content -->
<div class="top-content">
    <div class="inner-bg" style="padding-top: 120px;">
        <div class="container">
            <div class="row">
                <h1 style="display: inline; float: left">
                    <font color="#696969"><strong>Profile Overview</strong></font>
                </h1>
                <?php
                $encryptedBackUsername = "";
                $upFolder = "";
                if(isset($isAdmin)) {
                    $path = "admin/AdminPanel.php?id=";
                    $encryptedBackUsername = openssl_encrypt($username, 'bf-cfb', 'adminPanelPassword');
                    $encryptedBackUsername = str_replace("+", "!!!", $encryptedBackUsername);
                    $encryptedBackUsername = str_replace("%", "$$$", $encryptedBackUsername);
                }
                else {
                    $path = "user/Homepage.php?id=";
                    $encryptedBackUsername = openssl_encrypt($username, 'RC4-40', 'regularUserPassword');
                    $encryptedBackUsername = str_replace("+", "!!!", $encryptedBackUsername);
                    $encryptedBackUsername = str_replace("%", "$$$", $encryptedBackUsername);
                }
                ?>
                <button onclick="window.location='../<? echo $path . $encryptedBackUsername; ?>';" type="button" style="display: inline-block; float: right; width: 80px; height: 40px; background-color: #1f6377; border-radius: 10px; color: #fff; border: none;">
                    Go Back
                </button>
            </div>
            <div class="row">
                <? if (isset($alert))  echo $alert; ?>
            </div>
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 text">
                    <table id="profileTable" width="100%" style="line-height: 2;">
                        <tr>
                            <td></td>
                            <td>
                                <img src="<? echo $dbcomm->getProfileImageByUsername($username); ?>" id="profileImage">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="40%" style="height: 20px;"></td>
                            <td width="20%" id="profileImageButton" data-toggle="modal" data-target="#profileImageModal" rowspan="2">
                                <div class="glyphicon glyphicon-wrench" style="color: #5e5e5e; display: inline-block;"></div>
                                <p style="display: inline-block;">&nbsp;Profile Image&nbsp;&nbsp;&nbsp;</p>
                            </td>
                            <td width="40%" rowspan="2"></td>
                        </tr>
                        <tr>
                            <td align="left" rowspan="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username: <? echo $username; ?><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Account Name: <? echo $dbcomm->getAccountNameByUsername($username); ?><br>
                                <div class="glyphicon glyphicon-wrench" style="color: #5e5e5e; display: inline-block;" id="changeEmailButton" data-toggle="modal" data-target="#changeEmailModal"></div>
                                Email: <? echo $dbcomm->getEmailByUsername($username); ?><br>
                                <div class="glyphicon glyphicon-wrench" style="color: #5e5e5e; display: inline-block;" id="changePhoneNumberButton" data-toggle="modal" data-target="#changePhoneNumberModal"></div>
                                Phone: <? echo $dbcomm->getPhoneNumberByUsername($username); ?><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Points: <? echo $dbcomm->getNumTotalPointsByUsername($username); ?><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%" align="center">
                                    <tr>
                                        <td colspan="3">
                                            Current Points: <? echo $dbcomm->getNumCurrentPointsByUsername($username); ?>
                                        </td>
                                    </tr>
                                    <tr><td height="10px"></td></tr>
                                    <tr>
                                        <td height="50%">
                                            <? echo $dbcomm->getNumBronzeStarsByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getBronzeStarImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                        <td>
                                            <? echo $dbcomm->getNumSilverStarsByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getSilverStarImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                        <td>
                                            <? echo $dbcomm->getNumGoldStarsByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getGoldStarImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                    </tr>
                                    <tr><td height="20px"></td></tr>
                                    <tr>
                                        <td height="50%">
                                            <? echo $dbcomm->getNumBronzeTrophiesByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getBronzeTrophyImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                        <td>
                                            <? echo $dbcomm->getNumSilverTrophiesByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getSilverTrophyImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                        <td>
                                            <? echo $dbcomm->getNumGoldTrophiesByUsername($username); ?>&nbsp;&nbsp;<img src="<? echo $dbcomm->getGoldTrophyImageSource(); ?>" width="50px" height="50px">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td height="10px" colspan="3"></td></tr>
                        <tr>
                            <td align="left" colspan="3">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Theme:
                                &nbsp;&nbsp;
                                <select title="Themes" id="listOfThemeNames" style="font-size: 0.75em; display: inline-block;" onchange="changeTheme()">
                                    <?php
                                    $themes = $dbcomm->getAllThemes();
                                    for ($i = 0; $i < count($themes); $i++) {
                                        $themeName = $themes[$i][0];
                                        $noSpaceThemeName = str_replace(" ","_",$themeName);
                                        echo "<option id='themeName$i' value='$noSpaceThemeName'>$themeName</option>";
                                    }
                                    ?>
                                </select>
                                <table width="640px" style="float: right;">
                                    <tr>
                                        <?php
                                        $colors = $dbcomm->getThemeByUsername($username);
                                        for ($i = 0; $i < 8; $i++) {
                                            $styleTag = "";
                                            if($i == 0) $styleTag = "border-top-left-radius: 15px; border-bottom-left-radius: 15px;";
                                            elseif($i == 7) $styleTag = "border-top-right-radius: 15px; border-bottom-right-radius: 15px;";
                                            echo "<td width='12.5%' height='45px' style='background-color: $colors[$i]; $styleTag'></td>";
                                        }
                                        ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td height="20px" colspan="3"></td></tr>
                        <tr>
                            <td colspan="3" align="left">
                                &nbsp;&nbsp;&nbsp;
                                <button id='deleteUserButton' style="background-color: indianred; color: white; border-radius: 10px; border: none; font-size: 16px;"
                                        onclick="if (!confirm('Are you sure you want to delete this user?\n\nWARNING: This process can not be undone.')){e.preventDefault();}else{deleteUser();}">Delete User</button>
                            </td>
                        </tr>
                    </table>
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

<script>
    function deleteUser() {
        <?php
        $encryptedDeleteUsername = openssl_encrypt($username, 'RC4', 'deleteUserPassword');
        $encryptedDeleteUsername = str_replace("+", "!!!", $encryptedDeleteUsername);
        $encryptedDeleteUsername = str_replace("%", "$$$", $encryptedDeleteUsername);
        ?>
        window.location='DeleteUser.php?id=<? echo $encryptedDeleteUsername; ?>';
    }

    function changeTheme() {
        var newThemeName = $('#listOfThemeNames').val();
        newThemeName = newThemeName.replace(" ","_");
        window.location='ProfileOverview.php?id=<? echo $encryptedUsername; ?>&themeName=' + newThemeName;
    }

    $(document).ready(function() {
        <?php
        $currentThemeName = $dbcomm->getThemeNameByUsername($username);
        $noSpaceCurrentThemeName = str_replace(" ","_", $currentThemeName);
        ?>
        $("#listOfThemeNames").val('<? echo $noSpaceCurrentThemeName; ?>');

    });
</script>

</body>

<div class="modal fade" id="profileImageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Change Profile Image</h2>
            </div>
            <div class="modal-body">
                <table width="80%" align="center">
                    <tr>
                        <td>
                            <form enctype="multipart/form-data" action="ProfileOverview.php?id=<? echo $encryptedUsername; ?>" method="post">
                                <input style="line-height: 1em;" type="file" name="image" accept="image/*">
                                <br>
                                <input type="submit" name="Submit1" class="btn btn-default">
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeEmailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Change Email</h2>
            </div>
            <div class="modal-body">
                <table width="80%" align="center">
                    <tr>
                        <td>
                            <form enctype="multipart/form-data" action="ProfileOverview.php?id=<? echo $encryptedUsername; ?>" method="post">
                                <input style="line-height: 1em;" type="email" name="email" value="" placeholder="New Email...">
                                &nbsp;&nbsp;&nbsp;
                                <input style="line-height: 1.5em;" type="submit" name="Submit2" class="btn btn-default">
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePhoneNumberModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Change Profile Image</h2>
            </div>
            <div class="modal-body">
                <table width="80%" align="center">
                    <tr>
                        <td>
                            <form enctype="multipart/form-data" action="ProfileOverview.php?id=<? echo $encryptedUsername; ?>" method="post">
                                <input style="line-height: 1em;" type="text" name="phonenumber" value="" placeholder="New Phone Number..."
                                       onblur="$(this).val($(this).val().replace(/[^0-9.]/g, '')); if($(this).val().length >= 10){$(this).val('(' + $(this).val().substring(0,3) + ') ' + $(this).val().substring(3,6) + '-' + $(this).val().substring(6,10));}">
                                &nbsp;&nbsp;&nbsp;
                                <input style="line-height: 1.5em;" type="submit" name="Submit3" class="btn btn-default">
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>