<?php
class dbcomm
{
    //connection that others can't modify
    protected $sqlconn;

    //connect on startup
    function __construct() {
        $this->connect();
    }

    //disconnect on close
    function __destruct() {
        $this->disconnect();
    }

    protected $timezone = "America/New_York";


    /*
     * GENERAL FUNCTIONS ------------------------------------------------------------
     * */

    //connect to db or die
    function connect() {
        $this->sqlconn = mysqli_connect('mysql.planbook.xyz','pb_dev1','4FEF!j1w3KUSz0M','planbook_db1');
        //$this->sqlconn = mysqli_connect('{db.host}','{db.user}','{db.password}','{db.name}');
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    //disconnect from db
    function disconnect() {
        mysqli_close($this->sqlconn);
    }

    //send query to mysql or die
    function doQuery($query) {
        $result = mysqli_query($this->sqlconn,$query) or die(mysqli_error($this->sqlconn));
        return $result;
    }

    //convert timestamp to human time
    function getHumanTimeFromTimestamp($timestamp) {
        date_default_timezone_set($this->timezone);
        $humantime = date("M j, Y (H:i:s)", $timestamp);
        return $humantime;
    }

    //make a new timestamp
    function newTimestamp() {
        return time();
    }

    function addForeignKeys() {
        // Awards
        $query = "ALTER TABLE `Awards` ADD FOREIGN KEY (`image_id`) REFERENCES `Image`(`id`)";
        $this->doQuery($query);
        // Category
        $query = "ALTER TABLE `Category` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        // Date
        $query = "ALTER TABLE `Date` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        // Frequency/Frequency_Meta -> WERE NOT INCLUDED BECAUSE WE DONT KNOW WHAT THEY DO AND HOW TO USE THEM
        // Redeem
        $query = "ALTER TABLE `Redeem` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        // Special_Goal/Special_Goal_List -> WERE NOT USED BECAUSE NOT IMPLEMENTED YET
        // Task
        $query = "ALTER TABLE `Task` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `Task` ADD FOREIGN KEY (`priority_id`) REFERENCES `Priority`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `Task` ADD FOREIGN KEY (`category_id`) REFERENCES `Category`(`id`)";
        $this->doQuery($query);
        // Template
        $query = "ALTER TABLE `Template` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `Template` ADD FOREIGN KEY (`priority_id`) REFERENCES `Priority`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `Template` ADD FOREIGN KEY (`category_id`) REFERENCES `Category`(`id`)";
        $this->doQuery($query);
        // User -> DEMOGRAPHICS WAS NOT INCLUDED BECAUSE NOT IMPLEMENTED
        $query = "ALTER TABLE `User` ADD FOREIGN KEY (`account_id`) REFERENCES `Account`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `User` ADD FOREIGN KEY (`image_id`) REFERENCES `Image`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `User` ADD FOREIGN KEY (`type_id`) REFERENCES `Type`(`id`)";
        $this->doQuery($query);
        // User_Awards
        $query = "ALTER TABLE `User_Awards` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `User_Awards` ADD FOREIGN KEY (`award_id`) REFERENCES `Awards`(`id`)";
        $this->doQuery($query);
        // User_Themes
        $query = "ALTER TABLE `User_Themes` ADD FOREIGN KEY (`user_id`) REFERENCES `User`(`id`)";
        $this->doQuery($query);
        $query = "ALTER TABLE `User_Themes` ADD FOREIGN KEY (`theme_id`) REFERENCES `Theme`(`id`)";
        $this->doQuery($query);
    }

    /*
     * Commonly Used FUNCTIONS ------------------------------------------------------------
     * */

    function getRegularTypeID() {
        $query = "SELECT `id` FROM `Type` WHERE `name`='Regular'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getAdminTypeID() {
        $query = "SELECT `id` FROM `Type` WHERE `name`='Admin'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getBronzeStarAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='bronzeStar'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getSilverStarAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='silverStar'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getGoldStarAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='goldStar'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getBronzeTrophyAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='bronzeTrophy'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getSilverTrophyAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='silverTrophy'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getGoldTrophyAwardID() {
        $query = "SELECT `id` FROM `Awards` WHERE `name`='goldTrophy'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getAccountIDByUsername($username)
    {
        $query = "SELECT `account_id` FROM `User` WHERE `username`='$username'";
        return mysqli_fetch_array($this->doQuery($query))['account_id'];
    }

    function getUserIDFromUsername($username)
    {
        $query = "SELECT `id` FROM `User` WHERE `username`='$username';";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    function getImageIDFromUsername($username) {
        $query = "SELECT `id` FROM `Image` WHERE `name`='$username'";
        return mysqli_fetch_array($this->doQuery($query))['id'];
    }

    /*
     * SIGN-UP FUNCTIONS ------------------------------------------------------------
     * */

    function checkIfAccountNameExists($accountName)
    {
        $query = "SELECT `id` FROM `Account` WHERE `name`='$accountName';";
        $result = $this->doQuery($query);

        $SQLdataarray = mysqli_fetch_array($result);
        if(count($SQLdataarray) < 1) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    function checkIfUsernameExists($username)
    {
        $query = "SELECT `id` FROM `User` WHERE `username`='$username';";
        $result = $this->doQuery($query);

        $SQLdataarray = mysqli_fetch_array($result);
        if(count($SQLdataarray) < 1) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    function checkIfPhonenumberExists($phonenumber)
    {
        $query = "SELECT `id` FROM `User` WHERE `phone_number`='$phonenumber';";
        $result = $this->doQuery($query);

        $SQLdataarray = mysqli_fetch_array($result);
        if(count($SQLdataarray) < 1) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    function checkIfEmailExists($email)
    {
        $query = "SELECT `id` FROM `User` WHERE `email`='$email';";
        $result = $this->doQuery($query);

        $SQLdataarray = mysqli_fetch_array($result);
        if(count($SQLdataarray) < 1) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    function createNewAdmin($accountName, $username, $password, $email, $phonenumber)
    {
        $query = "INSERT INTO  `Account` (`name`, `password`, `email`, `phonenumber`) VALUES ('$accountName', '$password', '$email', '$phonenumber');";
        $this->doQuery($query);

        $query = "SELECT `id` FROM `Account` WHERE `name`='$accountName'";
        $accountID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "INSERT INTO `Image` (`name`,`description`,`link`) VALUES ('$username','profile picture','../../resources/img/profile.png')";
        $this->doQuery($query);

        $imageID = $this->getImageIDFromUsername($username);

        $typeID = $this->getAdminTypeID();


        $query = "INSERT INTO `User` (`account_id`, `username`, `password`, `image_id`, `type_id`, `email`, `phone_number`) VALUES ('$accountID', '$username', '$password', '$imageID', '$typeID', '$email', '$phonenumber');";
        $this->doQuery($query);
    }

    function createNewUser($accountID, $username, $password, $email, $phonenumber)
    {
        $query = "INSERT INTO `Image` (`name`,`description`,`link`) VALUES ('$username','profile picture','../../resources/img/profile.png')";
        $this->doQuery($query);
        $imageID = $this->getImageIDFromUsername($username);

        $query = "INSERT INTO `User` (`account_id`, `username`, `password`, `image_id`, `email`, `phone_number`) VALUES ('$accountID', '$username', '$password', '$imageID', '$email', '$phonenumber');";
        $this->doQuery($query);

        $userID = $this->getUserIDFromUsername($username);

        $bronzeStarID = $this->getBronzeStarAwardID();
        $silverStarID = $this->getSilverStarAwardID();
        $goldStarID = $this->getGoldStarAwardID();
        $bronzeTrophyID = $this->getBronzeTrophyAwardID();
        $silverTrophyID = $this->getSilverTrophyAwardID();
        $goldTrophyID = $this->getGoldTrophyAwardID();

        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($bronzeStarID, $userID, '0');";
        $this->doQuery($query);
        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($silverStarID, $userID, '0');";
        $this->doQuery($query);
        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($goldStarID, $userID, '0');";
        $this->doQuery($query);
        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($bronzeTrophyID, $userID, '0');";
        $this->doQuery($query);
        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($silverTrophyID, $userID, '0');";
        $this->doQuery($query);
        $query = "INSERT INTO `User_Awards` (`award_id`, `user_id`, `quantity`) VALUES ($goldTrophyID, $userID, '0');";
        $this->doQuery($query);

        $query = "INSERT INTO `Category` (`name`,`user_id`) VALUES ('Homework','$userID')";
        $this->doQuery($query);
        $query = "INSERT INTO `Category` (`name`,`user_id`) VALUES ('Health','$userID')";
        $this->doQuery($query);
        $query = "INSERT INTO `Category` (`name`,`user_id`) VALUES ('Exercise','$userID')";
        $this->doQuery($query);
        $query = "INSERT INTO `Category` (`name`,`user_id`) VALUES ('Other','$userID')";
        $this->doQuery($query);
        $query = "INSERT INTO `Category` (`name`,`user_id`) VALUES ('Special Tasks','$userID')";
        $this->doQuery($query);

        $query = "INSERT INTO `Date` (`user_id`) VALUES ('$userID')";
        $this ->doQuery($query);
        $this->setCurrentDateByUsername($username);

        $this->setDefaultThemeByUsername($username);
    }

    function verifyAccountByAccountID($accountID)
    {
        $query = "UPDATE `Account` SET `verified`='1' WHERE `id`='$accountID'";
        return $this->doQuery($query);
    }

    /*
     * SIGN-IN FUNCTIONS ------------------------------------------------------------
     * */

    function verifyCredentials($username, $password)
    {
        $query = "SELECT `id` FROM `User` WHERE `username`='$username' AND `password`='$password';";
        $result = $this->doQuery($query);

        $SQLdataarray = mysqli_fetch_array($result);
        if(count($SQLdataarray) < 1) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    function isAccountVerified($username)
    {
        $accountID = $this->getAccountIDByUsername($username);
        $query = "SELECT `verified` FROM `Account` WHERE `id`='$accountID'";
        $verified  = mysqli_fetch_array($this->doQuery($query))['verified'];
        if ($verified > 0){
            return True;
        }
        else{
            return False;
        }
    }

    function getTypeByUsername($username)
    {
        $query = "SELECT `type_id` FROM `User` WHERE `username`='$username'";
        $typeID = mysqli_fetch_array($this->doQuery($query))['type_id'];
        $query = "SELECT `name` FROM `Type` WHERE `id`='$typeID'";
        return mysqli_fetch_array($this->doQuery($query))['name'];
    }

    /*
     * Verify FUNCTIONS ------------------------------------------------------------
     * */

    function getEmailByUsername($username) {
        $query = "SELECT `email` FROM `User` WHERE `username`='$username'";
        return mysqli_fetch_array($this->doQuery($query))['email'];
    }

    function getPhoneNumberByUsername($username) {
        $query = "SELECT `phone_number` FROM `User` WHERE `username`='$username'";
        $phonenumber = mysqli_fetch_array($this->doQuery($query))['phone_number'];
        $phonenumber = "(" . substr($phonenumber,0,3) . ") " . substr($phonenumber,3,3) . "-" . substr($phonenumber,6,4);
        return $phonenumber;
    }

    function verifyAccountByUsername($username) {
        $accountID = $this->getAccountIDByUsername($username);
        $this->verifyAccountByAccountID($accountID);
    }

    /*
     * Recovery FUNCTIONS ------------------------------------------------------------
     * */

    // check if email exists uses the function in the sign-up section

    function getUsernameByEmail($email) {
        $query = "SELECT `username` FROM `User` WHERE `email`='$email'";
        return mysqli_fetch_array($this->doQuery($query))['username'];
    }

    function resetPasswordByUsername($username, $password) {
        $query = "UPDATE `User` SET `password`='$password' WHERE `username`='$username'";
        return $this->doQuery($query);
    }

    /*
     * Admin Panel FUNCTIONS ------------------------------------------------------------
     * */

    function getAccountNameByUsername($username) {
        $accountID = $this->getAccountIDByUsername($username);
        $query = "SELECT `name` FROM `Account` WHERE `id`='$accountID'";
        return mysqli_fetch_array($this->doQuery($query))['name'];
    }

    function getAllUsersByAdminUsername($username) {
        $accountID = $this->getAccountIDByUsername($username);

        $typeID = $this->getRegularTypeID();

        $query = "SELECT * FROM `User` WHERE `account_id`='$accountID' AND `type_id`='$typeID'";
        $result = $this->doQuery($query);

        $users = Array();
        while($row = mysqli_fetch_array($result)) {
            $users[$row['id']] = Array("username"=>$row['username'], "total_points"=>$row['total_points']);
        }
        ksort($users);
        return $users;
    }

    function deleteAccountByUsername($username) {
        $accountID = $this->getAccountIDByUsername($username);

        $query = "SELECT `username` FROM `User` WHERE `account_id`='$accountID'";
        $result = $this->doQuery($query);
        while($row = mysqli_fetch_array($result)) {
            $userUsername = $row['username'];
            $this->deleteUserByUsername($userUsername);
        }
        $query = "DELETE FROM `Account` WHERE `id` = '$accountID'";
        $this->doQuery($query);
    }

    function deleteUserByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "DELETE FROM `User_Awards` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $imageID = $this->getImageIDFromUsername($username);

        $query = "DELETE FROM `Image` WHERE `id`='$imageID'";
        $this->doQuery($query);

        $query = "DELETE FROM `Redeem` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `Date` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `Category` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `Task` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `Template` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `User_Themes` WHERE `user_id`='$userID'";
        $this->doQuery($query);

        $query = "DELETE FROM `User` WHERE `username`='$username'";
        $this->doQuery($query);
    }

    function getAdminUsernameByAccountID($accountID) {
        $adminTypeID = $this->getAdminTypeID();

        $query = "SELECT `username` FROM `User` WHERE `account_id`='$accountID' AND `type_id`='$adminTypeID'";
        return mysqli_fetch_array($this->doQuery($query))['username'];
    }

    function getEncodedUsernamesByAccountID($accountID) {
        $regularTypeID = $this->getRegularTypeID();

        $query = "SELECT `username` FROM `User` WHERE `account_id`='$accountID' AND `type_id`='$regularTypeID'";
        $result = $this->doQuery($query);

        $users = Array();
        while($row = mysqli_fetch_array($result)) {
            $encryptedUsername = openssl_encrypt($row['username'], 'DES-EDE3', 'viewUserProfilePassword');
            $encryptedUsername = str_replace("+", "!!!", $encryptedUsername);
            $encryptedUsername = str_replace("%", "$$$", $encryptedUsername);
            /*
            $curUsername = $row['username'];
            $encryptedUsername = Crypto::encrypt($curUsername, true);
            */
            array_push($users, $encryptedUsername);
        }
        return $users;
    }


    function getOtherUsersOfAccountByUsername($username) {
        $accountID = $this->getAccountIDByUsername($username);

        $query = "SELECT `username`, `image_id`, `email` FROM `User` WHERE `account_id`='$accountID' AND `username`!='$username'";
        $result = $this->doQuery($query);

        $usernames = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)) {
            $imageID = $row['image_id'];
            $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
            $link = mysqli_fetch_array($this->doQuery($query))['link'];
            $usernames[$counter] = Array("name"=>$row['username'], "email"=>$row['email'], "link"=>"$link");
        }
        return $usernames;
    }

    /*
     * Awards FUNCTIONS ------------------------------------------------------------
     * */

    function getNumCurrentPointsByUsername($username) {
        $query = "SELECT `current_points` FROM `User` WHERE `username`='$username'";
        return mysqli_fetch_array($this->doQuery($query))['current_points'];
    }

    function getNumTotalPointsByUsername($username) {
        $query = "SELECT `total_points` FROM `User` WHERE `username`='$username'";
        return mysqli_fetch_array($this->doQuery($query))['total_points'];
    }

    function getNumBronzeStarsByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getBronzeStarAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getNumSilverStarsByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getSilverStarAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getNumGoldStarsByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getGoldStarAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getNumBronzeTrophiesByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getBronzeTrophyAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getNumSilverTrophiesByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getSilverTrophyAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getNumGoldTrophiesByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $awardID = $this->getGoldTrophyAwardID();

        $query = "SELECT `quantity` FROM `User_Awards` WHERE `user_id`='$userID' AND `award_id`='$awardID'";
        return mysqli_fetch_array($this->doQuery($query))['quantity'];
    }

    function getBronzeStarImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='bronzeStar'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function getSilverStarImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='silverStar'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function getGoldStarImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='goldStar'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function getBronzeTrophyImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='bronzeTrophy'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function getSilverTrophyImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='silverTrophy'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function getGoldTrophyImageSource() {
        $query = "SELECT `image_id` FROM `Awards` WHERE `name`='goldTrophy'";
        $imageID = mysqli_fetch_array($this->doQuery($query))['image_id'];
        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function convertPointsStarsTrophies($username) {
        $userID = $this->getUserIDFromUsername($username);

        $bronzeStarID = $this->getBronzeStarAwardID();
        $silverStarID = $this->getSilverStarAwardID();
        $goldStarID = $this->getGoldStarAwardID();
        $bronzeTrophyID = $this->getBronzeTrophyAwardID();
        $silverTrophyID = $this->getSilverTrophyAwardID();
        $goldTrophyID = $this->getGoldTrophyAwardID();

        $numCurrentPoints = $this->getNumCurrentPointsByUsername($username);
        $numBronzeStars = $this->getNumBronzeStarsByUsername($username);
        $numSilverStars = $this->getNumSilverStarsByUsername($username);
        $numGoldStars = $this->getNumGoldStarsByUsername($username);
        $numBronzeTrophies = $this->getNumBronzeTrophiesByUsername($username);
        $numSilverTrophies = $this->getNumSilverTrophiesByUsername($username);
        $numGoldTrophies = $this->getNumGoldTrophiesByUsername($username);

        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$bronzeStarID'";
        $PointstoBronzeStars = mysqli_fetch_array($this->doQuery($query))['amount'];
        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$silverStarID'";
        $BronzeStarstoSilverStars = mysqli_fetch_array($this->doQuery($query))['amount'];
        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$goldStarID'";
        $SilverStarstoGoldStars = mysqli_fetch_array($this->doQuery($query))['amount'];
        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$bronzeTrophyID'";
        $GoldStarstoBronzeTrophies = mysqli_fetch_array($this->doQuery($query))['amount'];
        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$silverTrophyID'";
        $BronzeTrophiestoSilverTrophies = mysqli_fetch_array($this->doQuery($query))['amount'];
        $query = "SELECT `amount` FROM `Awards` WHERE `id`='$goldTrophyID'";
        $SilverTrophiestoGoldTrophies = mysqli_fetch_array($this->doQuery($query))['amount'];

        while ($numCurrentPoints >= $PointstoBronzeStars)
        {
            $numCurrentPoints -= $PointstoBronzeStars;
            $numBronzeStars += 1;
        }
        while ($numBronzeStars >= $BronzeStarstoSilverStars)
        {
            $numBronzeStars -= $BronzeStarstoSilverStars;
            $numSilverStars += 1;
        }
        while ($numSilverStars >= $SilverStarstoGoldStars)
        {
            $numSilverStars -= $SilverStarstoGoldStars;
            $numGoldStars += 1;
        }
        while ($numGoldStars >= $GoldStarstoBronzeTrophies)
        {
            $numGoldStars -= $GoldStarstoBronzeTrophies;
            $numBronzeTrophies += 1;
        }
        while ($numBronzeTrophies >= $BronzeTrophiestoSilverTrophies)
        {
            $numBronzeTrophies -= $BronzeTrophiestoSilverTrophies;
            $numSilverTrophies += 1;
        }
        while ($numSilverTrophies >= $SilverTrophiestoGoldTrophies)
        {
            $numSilverTrophies -= $SilverTrophiestoGoldTrophies;
            $numGoldTrophies += 1;
        }

        $query = "UPDATE `User` SET `current_points`='$numCurrentPoints' WHERE `id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numBronzeStars' WHERE `award_id`='$bronzeStarID' AND `user_id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numSilverStars' WHERE `award_id`='$silverStarID' AND `user_id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numGoldStars' WHERE `award_id`='$goldStarID' AND `user_id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numBronzeTrophies' WHERE `award_id`='$bronzeTrophyID' AND `user_id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numSilverTrophies' WHERE `award_id`='$silverTrophyID' AND `user_id`='$userID'";
        $this->doQuery($query);
        $query = "UPDATE `User_Awards` SET `quantity`='$numGoldTrophies' WHERE `award_id`='$goldTrophyID' AND `user_id`='$userID'";
        $this->doQuery($query);
    }

    function getPointsOfTaskByPriority($priorityName) {
        $query = "SELECT `points` FROM `Priority` WHERE `name`='$priorityName'";
        return mysqli_fetch_array($this->doQuery($query))['points'];
    }

    /*
     * Profile FUNCTIONS ------------------------------------------------------------
     * */

    function updateEmailByUsername($username, $email) {
        $query = "UPDATE `User` SET `email`='$email' WHERE `username`='$username'";
        $this->doQuery($query);
    }

    function updatePhoneNumberByUsername($username, $phonenumber) {
        $query = "UPDATE `User` SET `phone_number`='$phonenumber' WHERE `username`='$username'";
        $this->doQuery($query);
    }

    function getProfileImageByUsername($username) {
        $imageID = $this->getImageIDFromUsername($username);

        $query = "SELECT `link` FROM `Image` WHERE `id`='$imageID'";
        return mysqli_fetch_array($this->doQuery($query))['link'];
    }

    function updateProfileImageByUsername($username, $imageSource) {
        $imageID = $this->getImageIDFromUsername($username);

        $query = "UPDATE `Image` SET `link`='$imageSource' WHERE `id`='$imageID'";
        $this->doQuery($query);
    }

    /*
     * Rewards FUNCTIONS ------------------------------------------------------------
     * */

    function getAllRewardsByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id`, `reward`,`points`,`completed`,`redeem_date` FROM `Redeem` WHERE `user_id`='$userID'";
        $result = $this->doQuery($query);

        $users = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)) {
            if($row['redeem_date'] != null) $redeemDate = intval(substr($row['redeem_date'],5,2)) . '/' . intval(substr($row['redeem_date'],8,2)) . '/' . intval(substr($row['redeem_date'],0,4));
            else                            $redeemDate = "0/0/0";
            $users[$counter] = Array("rewardID"=>$row['id'], "name"=>$row['reward'], "points"=>$row['points'], "completed"=>$row['completed'], "redeem_date"=>$redeemDate);
            $counter += 1;
        }
        return $users;
    }

    function deleteRewardByRewardID($rewardID) {
        $query = "DELETE FROM `Redeem` WHERE `id`='$rewardID'";
        $this->doQuery($query);
    }

    function redeemRewardByUsername($username, $rewardName) {
        $query = "SELECT `id`,`total_points` FROM `User` WHERE `username`='$username'";
        $userID = mysqli_fetch_array($this->doQuery($query))['id'];
        $totalPoints = mysqli_fetch_array($this->doQuery($query))['total_points'];

        $query = "SELECT `points` FROM `Redeem` WHERE `user_id`='$userID' AND `reward`='$rewardName'";
        $pointsRequired = mysqli_fetch_array($this->doQuery($query))['points'];

        if ($totalPoints >= $pointsRequired) {
            date_default_timezone_set($this->timezone);
            $datetime = date('Y-m-d H:i:s');
            $query = "UPDATE `Redeem` SET `completed`='1', `redeem_date`='$datetime' WHERE `user_id`='$userID' AND `reward`='$rewardName'";
            $this->doQuery($query);

            $pointsAfterRedemption = $totalPoints - $pointsRequired;
            $query = "UPDATE `User` SET `total_points`='$pointsAfterRedemption' WHERE `id`='$userID'";
            $this->doQuery($query);
            return true;
        }
        else {
            return false;
        }
    }

    function addRewardByUsername($username, $rewardName, $points) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "INSERT INTO `Redeem` (`user_id`,`reward`,`points`) VALUES ('$userID','$rewardName','$points')";
        $this->doQuery($query);
    }

    /*
     * Task FUNCTIONS ----------------------------------------------------------
     */

    function createTaskByUsername($username, $taskName, $categoryName, $importance, $startTime, $endTime, $date) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id` FROM `Category` WHERE `name`='$categoryName' AND `user_id`='$userID'";
        $categoryID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "SELECT `id` FROM `Priority` WHERE `name`='$importance'";
        $priorityID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "INSERT INTO `Task` (`user_id`, `task_name`, `category_id`, `priority_id`, `start_time`, `end_time`, `date`) VALUES ('$userID', '$taskName', '$categoryID', '$priorityID', '$startTime', '$endTime', '$date')";
        $this->doQuery($query);
    }

    function updateTaskByTaskID($taskID, $taskName, $categoryName, $importance, $startTime, $endTime, $date) {
        $query = "SELECT `user_id` FROM `Task` WHERE `id`='$taskID'";
        $userID = mysqli_fetch_array($this->doQuery($query))['user_id'];

        $query = "SELECT `id` FROM `Category` WHERE `name`='$categoryName' AND `user_id`='$userID'";
        $categoryID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "SELECT `id` FROM `Priority` WHERE `name`='$importance'";
        $priorityID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "UPDATE `Task` SET `task_name`='$taskName', `category_id`='$categoryID', `priority_id`='$priorityID', `start_time`='$startTime', `end_time`='$endTime', `date`='$date' WHERE `id`='$taskID'";
        $this->doQuery($query);
    }

    function completeTaskByTaskID($username, $taskID) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "UPDATE `Task` SET `completed`='1' WHERE `id`='$taskID'";
        $this->doQuery($query);

        $query = "SELECT `priority_id` FROM `Task` WHERE `id`='$taskID'";
        $priorityID = mysqli_fetch_array($this->doQuery($query))['priority_id'];

        $query = "SELECT `points` FROM `Priority` WHERE `id`='$priorityID'";
        $addPoints = mysqli_fetch_array($this->doQuery($query))['points'];

        $currentPoints = $this->getNumCurrentPointsByUsername($username);
        $finalCurrentPoints = $currentPoints + $addPoints;

        $totalPoints = $this->getNumTotalPointsByUsername($username);
        $finalTotalPoints = $totalPoints + $addPoints;

        $query = "UPDATE `User` SET `current_points`='$finalCurrentPoints', `total_points`='$finalTotalPoints' WHERE `id`='$userID'";
        $this->doQuery($query);
    }

    function getCategoriesByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `name`, `id` FROM `Category` WHERE `user_id`='$userID'";
        $result = $this->doQuery($query);

        $categories = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)) {
            $categories[$counter] = Array('name'=>$row['name'], 'id'=>$row['id']);
            $counter++;
        }
        return $categories;
    }

    function getCategoryNamesByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `name` FROM `Category` WHERE `user_id`='$userID'";
        $result = $this->doQuery($query);

        $categories = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)) {
            $categories[$counter] = $row['name'];
            $counter++;
        }
        sort($categories);
        return $categories;
    }

    /*function getTasksByCategory($username, $category){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id` FROM `Category` WHERE `name` ='$category' AND `user_id` = '$userID'";
        $categoryID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "SELECT `task_name` FROM `Task` WHERE `category_id` = '$categoryID' AND `user_id` = '$userID'";
        $result = $this->doQuery($query);

        $tasks = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)){
            $tasks[$counter]=$row['task_name'];
            $counter++;
        }
        sort($tasks);
        return $tasks;
    }*/

    function getPriorities() {
        $query = "SELECT `name` FROM `Priority`";
        $result = $this->doQuery($query);

        $priorities = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)) {
            $priorities[$counter] = $row['name'];
            $counter++;
        }
        return $priorities;
    }

    function createNewCategoryByUsername($username, $categoryName) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "INSERT INTO `Category` (`user_id`, `name`) VALUES ('$userID', '$categoryName')";
        $this->doQuery($query);
    }

    function deleteCategoryByCategoryID($categoryID) {
        $query = "DELETE FROM `Category` WHERE `id`='$categoryID'";
        $this->doQuery($query);

        date_default_timezone_set($this->timezone);
        $todayDate = date('Y-m-d');
        $query = "DELETE FROM `Task` WHERE `category_id`='$categoryID' AND `date` >= '$todayDate'";
        $this->doQuery($query);
    }

    function deleteTaskByTaskID($taskID){
        $query = "DELETE FROM `Task` WHERE `id`='$taskID'";
        $this->doQuery($query);
    }

    function getPriorityValue($priority_id){
        $query = "SELECT `name` FROM `Priority` WHERE `id` = '$priority_id'";
        return mysqli_fetch_array($this->doQuery($query))['name'];
    }

    function getTaskInfoByCategory($username, $category){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `date` FROM `Date` WHERE `user_id`='$userID'";
        $currentDate = mysqli_fetch_array($this->doQuery($query))['date'];

        $query = "SELECT `id` FROM `Category` WHERE `name` ='$category' AND `user_id` = '$userID'";
        $categoryID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "SELECT `task_name`, `priority_id`, `start_time`, `end_time`, `date`, `id` FROM `Task`  WHERE `category_id` = '$categoryID' AND `user_id` = '$userID' AND `date`='$currentDate'";
        $result = $this->doQuery($query);
        $tasks = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)){
            $tasks[$counter] = Array("taskName"=>$row['task_name'], "date"=>$row['date'], "priority"=>$this->getPriorityValue($row['priority_id']), "startTime"=>$row['start_time'], "endTime"=>$row['end_time'], "id"=>$row['id']);
            $counter++;
        }
        return $tasks;
    }

    function isCompletedByTaskID($taskID) {
        $query = "SELECT `completed` FROM `Task` WHERE `id`='$taskID'";
        $completed = mysqli_fetch_array($this->doQuery($query))['completed'];

        if($completed == 1) return true;
        else                return false;
    }

    /*
     * Date FUNCTIONS ------------------------------------------------------------
     * */

    function checkIfDateIsToday($date, $currentDate){
        if ($date == $currentDate)  return true;
        else                        return false;
    }

    function setCurrentDateByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        date_default_timezone_set($this->timezone);
        $currentDate = date('Y-m-d');
        $query = "UPDATE `Date` SET `date`='$currentDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function setDateByDate($username, $newDate) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function getDateByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `date` FROM `Date` WHERE `user_id`='$userID'";
        return mysqli_fetch_array($this->doQuery($query))['date'];
    }

    function getDayByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `date` FROM `Date` WHERE `user_id`='$userID'";
        return substr(mysqli_fetch_array($this->doQuery($query))['date'],8,2);
    }

    function incrementDateByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function decrementDateByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    /*
     * Week FUNCTIONS ------------------------------------------------------------
     * */

    function getDatesofCurrentWeekByUsername($username) {
        $numDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if($this->isCurrentYearALeapYear($username)) $numDaysInMonth[1] = 29;

        $currentDate = $this->getDateByUsername($username);
        $currentDay = $this->getDayByUsername($username);
        $currentMonth = $this->getMonthByUsername($username);
        $currentYear = $this->getYearByUsername($username);

        $firstDOTW = $this->getDOTWofFirstDayofCurrentMonth($username);
        $datesOfTheWeek = Array(Array(),Array(),Array());

        if($currentDay > 7-$firstDOTW || $currentDate == 0) {
            $weekStartDate = 1;
            for($i = 1; $i <= $numDaysInMonth[intval($currentMonth)-1]; $i++) {
                if(($i+$firstDOTW-1)%7 == 0) {
                    $weekStartDate = $i;
                }
                if($i == $currentDay) {
                    break;
                }
            }

            $this->setDayOfMonthByUsername($username, $weekStartDate);

            for($i = 0; $i < 7; $i++) {
                $date = $this->getDayByUsername($username);
                $month = $this->getMonthByUsername($username);
                $year = $this->getYearByUsername($username);

                array_push($datesOfTheWeek[0],intval($date));
                array_push($datesOfTheWeek[1],intval($month));
                array_push($datesOfTheWeek[2],intval($year));

                $this->incrementDateByUsername($username);
            }
            for($i = 0; $i < 7; $i++) {
                $this->decrementDateByUsername($username);
            }
        }
        else {
            $this->setDayOfMonthByUsername($username, 1);

            for($i = 1; $i <= 7-$firstDOTW; $i++) {
                array_push($datesOfTheWeek[0], intval($i));
                array_push($datesOfTheWeek[1], intval($currentMonth));
                array_push($datesOfTheWeek[2], intval($currentYear));
            }

            for ($i = 0; $i < $firstDOTW; $i++) {
                $this->decrementDateByUsername($username);

                $date = $this->getDayByUsername($username);
                $month = $this->getMonthByUsername($username);
                $year = $this->getYearByUsername($username);

                array_unshift($datesOfTheWeek[0], intval($date));
                array_unshift($datesOfTheWeek[1], intval($month));
                array_unshift($datesOfTheWeek[2], intval($year));
            }
            for($i = 0; $i < $firstDOTW; $i++) {
                $this->incrementDateByUsername($username);
            }
        }

        $this->setDateByDate($username, $currentDate);

        return $datesOfTheWeek;
    }

    /*function getNumberOfTasksInCategoryByDate($username, $category, $date){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `task_name` FROM `Task` WHERE `user_id`='$userID' AND `category_id`='$category' AND `date` = $date";
        $result = $this->doQuery($query);

        $taskCountInCategory = 0;
        while($row = mysqli_fetch_array($result)){
            $taskCountInCategory++;
        }
        return $taskCountInCategory;
    }*/

    function incrementWeekByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' +7 day'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function decrementWeekByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' -7 day'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    /*
     * Month FUNCTIONS ------------------------------------------------------------
     * */

    function getMonthByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `date` FROM `Date` WHERE `user_id`='$userID'";
        return substr(mysqli_fetch_array($this->doQuery($query))['date'],5,2);
    }

    function getYearByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `date` FROM `Date` WHERE `user_id`='$userID'";
        return substr(mysqli_fetch_array($this->doQuery($query))['date'],0,4);
    }

    function incrementMonthByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' +1 month'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function decrementMonthByUsername($username) {
        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d', strtotime($currentDate . ' -1 month'));
        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function getDOTWofFirstDayofCurrentMonth($username) {
        $currentDate = $this->getDayByUsername($username);

        date_default_timezone_set($this->timezone);
        $this->setDayOfMonthByUsername($username, 1);
        $dotw = date('w',strtotime($this->getDateByUsername($username)));
        $this->setDayOfMonthByUsername($username, $currentDate);

        return $dotw;
    }

    function isCurrentYearALeapYear($username) {
        $currentDate = $this->getDateByUsername($username);
        date_default_timezone_set($this->timezone);
        return date('L', $currentDate);
    }

    function getNumTasksPerDayOfCurrentMonthByUsername($username) {
        $numDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if($this->isCurrentYearALeapYear($username)) $numDaysInMonth[1] = 29;

        $userID = $this->getUserIDFromUsername($username);
        $currentDate = $this->getDateByUsername($username);

        $currentYear = intval(substr($currentDate,0,4));
        $currentMonth = intval(substr($currentDate,5,2));

        $tasksperDay = Array();
        for($i = 1; $i <= $numDaysInMonth[$currentMonth-1]; $i++) {
            $checkDate = $currentYear;
            if (strlen($checkDate) < 4) $checkDate = "0" . $checkDate;
            $checkDate .= "-".$currentMonth;
            if (strlen($checkDate) < 7) $checkDate = substr($checkDate,0,5) . "0" . substr($checkDate,5);
            $checkDate .= "-".$i;
            if (strlen($checkDate) < 10) $checkDate = substr($checkDate,0,8) . "0" . substr($checkDate,8);

            $query = "SELECT `id` FROM `Task` WHERE `user_id`='$userID' AND `date`='$checkDate'";
            $result = $this->doQuery($query);

            $taskCount = 0;
            while($row = mysqli_fetch_array($result)){
                $taskCount++;
            }
            array_push($tasksperDay,$taskCount);
        }
        return $tasksperDay;
    }

    function setDayOfMonthByUsername($username, $newDayInt) {
        $userID = $this->getUserIDFromUsername($username);

        $currentDate = $this->getDayByUsername($username);
        $difference = $newDayInt - $currentDate;
        date_default_timezone_set($this->timezone);
        $newDate = date('Y-m-d',strtotime($this->getDateByUsername($username) . ' ' . $difference . ' day'));

        $query = "UPDATE `Date` SET `date`='$newDate' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    /*
     * Template FUNCTIONS ------------------------------------------------------------
     * */

    function getAllTemplatesByUsername($username){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id`, `task_name`, `category_id`, `priority_id`, `start_time`, `end_time` FROM `Template`  WHERE `user_id` = '$userID'";
        $result = $this->doQuery($query);
        $tasks = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)){
            $categoryID = $row['category_id'];
            $query = "SELECT `name` FROM `Category` WHERE `id`='$categoryID'";
            $categoryName = mysqli_fetch_array($this->doQuery($query))['name'];
            $tasks[$counter] = Array("templateID"=>$row['id'], "templateName"=>$row['task_name'], "startHour"=>substr($row['start_time'], 0, 2), "startMin"=>substr($row['start_time'],3,2), "startAMPM"=>substr($row['start_time'],6,2), "endHour"=>substr($row['end_time'], 0, 2), "endMin"=>substr($row['end_time'],3,2), "endAMPM"=>substr($row['end_time'],6,2), "priority"=>$this->getPriorityValue($row['priority_id']), "category"=>$categoryName);
            $counter++;
        }
        return $tasks;
    }

    function addTemplateByUsername($username, $taskName, $categoryName, $importance, $startTime, $endTime) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id` FROM `Category` WHERE `name`='$categoryName' AND `user_id`='$userID'";
        $categoryID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "SELECT `id` FROM `Priority` WHERE `name`='$importance'";
        $priorityID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "INSERT INTO `Template` (`user_id`, `task_name`, `category_id`, `priority_id`, `start_time`, `end_time`) VALUES ('$userID', '$taskName', '$categoryID', '$priorityID', '$startTime', '$endTime')";
        $this->doQuery($query);
    }

    function deleteTemplateByUsername($username, $templateID) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "DELETE FROM `Template` WHERE `user_id`='$userID' AND `task_name`='$templateID'";
        $this->doQuery($query);
    }

    /*
     * Theme Functions ------------------------------------------------------------
     */

    function setDefaultThemeByUsername($username){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id` FROM  `Theme` WHERE `name`='Gold'";
        $themeID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "INSERT INTO `User_Themes` (`user_id`, `theme_id`) VALUES ('$userID', '$themeID')";
        $this->doQuery($query);
    }

    function setThemeByThemeName($username, $themeName) {
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `id` FROM  `Theme` WHERE `name`='$themeName'";
        $themeID = mysqli_fetch_array($this->doQuery($query))['id'];

        $query = "UPDATE `User_Themes` SET `theme_id`='$themeID' WHERE `user_id`='$userID'";
        $this->doQuery($query);
    }

    function getThemeByUsername($username){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `theme_id` FROM `User_Themes` WHERE `user_id`='$userID'";
        $themeID = mysqli_fetch_array($this->doQuery($query))['theme_id'];

        $query = "SELECT `color1`, `color2`, `color3`, `color4`, `color5`, `color6`, `color7`, `color8` FROM `Theme` WHERE `id` = '$themeID'";
        $result = $this->doQuery($query);
        $colors = Array();
        while($row = mysqli_fetch_array($result)){
            array_push($colors, $row['color1']);
            array_push($colors, $row['color2']);
            array_push($colors, $row['color3']);
            array_push($colors, $row['color4']);
            array_push($colors, $row['color5']);
            array_push($colors, $row['color6']);
            array_push($colors, $row['color7']);
            array_push($colors, $row['color8']);
        }
        return $colors;
    }

    function getThemeNameByUsername($username){
        $userID = $this->getUserIDFromUsername($username);

        $query = "SELECT `theme_id` FROM `User_Themes` WHERE `user_id`='$userID'";
        $themeID = mysqli_fetch_array($this->doQuery($query))['theme_id'];

        $query = "SELECT `name` FROM `Theme` WHERE `id` = '$themeID'";
        return mysqli_fetch_array($this->doQuery($query))['name'];
    }

    function getAllThemes() {
        $query = "SELECT `name`, `color1`, `color2`, `color3`, `color4`, `color5`, `color6`, `color7`, `color8` FROM `Theme`";
        $result = $this->doQuery($query);
        $themes = Array();
        $counter = 0;
        while($row = mysqli_fetch_array($result)){
            $themes[$counter] = Array();
            array_push($themes[$counter], $row['name']);
            array_push($themes[$counter], $row['color1']);
            array_push($themes[$counter], $row['color2']);
            array_push($themes[$counter], $row['color3']);
            array_push($themes[$counter], $row['color4']);
            array_push($themes[$counter], $row['color5']);
            array_push($themes[$counter], $row['color6']);
            array_push($themes[$counter], $row['color7']);
            array_push($themes[$counter], $row['color8']);
            $counter++;
        }
        return $themes;
    }

}