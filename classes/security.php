<?php
session_start();
// echo("CURRENT SESSION: " . $_SESSION["drupalUserID"]);
if(($_SESSION['drupalUserID'] == "") || ($_SESSION['drupalUserID'] == "INVALID")){
	// echo("Not logged in!");
	header("Location: authfailed.php");
}
?>