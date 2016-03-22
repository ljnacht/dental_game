<?php

require("classes/dbutils.php");
session_start();
$userID = $_GET["userID"];

// echo("userID: " . $userID . "<br />");


// $sql = "SELECT * FROM mhk23_drupal_ca.users WHERE uid = " . $userID;
$sql = "SELECT * FROM drupal01.drupal_users WHERE uid = " . $userID;
// echo($sql . "<br />");

/*
$dbName = "mhk23_drupal_ca";
$dbUserName = "mhk23_readonly";
$dbPassword = "gGxVfFheS0GUwklWyhd7";
*/


$dbName = "drupal01";
$dbUserName = "drupalAdmin";
$dbPassword = "D7upa1@Dm!n";


$db = new DbUtilities($dbUserName, $dbPassword, $dbName);

$collectionList = $db->getDataset($sql);

if(count($collectionList) > 0){
	foreach($collectionList as &$row){
		$_SESSION["drupalUserID"] = $userID;
		$_SESSION["drupalUserName"] = $row["name"];
		$_SESSION["drupalUserEmail"] = $row["mail"];
	}	
	
	// print_r($_SESSION);
	// Change this to redirect to whatever page is the first landing page for the application
	header("Location: homepage.php");
}
else{
	$_SESSION["drupalUserID"] = "INVALID";
	$_SESSION["drupalUserName"] = "INVALID";
	$_SESSION["drupalUserEmail"] = "INVALID";

	// Authentication failed - redirect to auth failed page
	// You need to update that page
	header("Location: authfailed.php");
	
}



?>