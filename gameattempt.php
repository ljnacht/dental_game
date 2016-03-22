<?php
require("classes/security.php");
require("classes/dbutils.php");
$_SESSION["gameAttemptID"] = uniqid();
include("rest/updateaccess.php");

$levelID = 1;

if (isset($_GET["levelID"])) {
    $levelID = $_GET["levelID"];
    header("Location: incorrect_level.php");
} else {
    header("Location: level1.php");
}

?>