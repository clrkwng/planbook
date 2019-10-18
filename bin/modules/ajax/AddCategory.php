<?php

require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if (isset($_POST['newCategoryName']) and isset($_POST['newCategoryUsername'])) {
    $username = $_POST['newCategoryUsername'];
    $categoryName = $_POST['newCategoryName'];

    $dbcomm->createNewCategoryByUsername($username, $categoryName);
}