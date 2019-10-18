<?php

require_once "../../scripts/dbcomm.php";
//create db connection
$dbcomm = new dbcomm();

if (isset($_POST['deleteCategoryID'])) {
    $categoryID = $_POST['deleteCategoryID'];
    $dbcomm->deleteCategoryByCategoryID($categoryID);
}