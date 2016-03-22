<?php

require("../classes/dbutils.php");
$userID="";
$userID = $_POST["ID"];

$sql = "SELECT name FROM drupal01.drupal_users WHERE uid = " . $userID;

/*
$dbName = "mhk23_drupal_ca";
$dbUserName = "mhk23_readonly";
$dbPassword = "gGxVfFheS0GUwklWyhd7";
*/

$dbName = "drupal01";
$dbUserName = "drupalAdmin";
$dbPassword = "D7upa1@Dm!n";

$db = new DbUtilities($dbUserName, $dbPassword, $dbName);

$dataArray = $db->getDataset($sql);

echo json_encode($dataArray);

?>