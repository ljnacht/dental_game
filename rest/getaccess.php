<?php
if (isset($_POST["userID"])) {
    $userID = $_POST["userID"];
    $attemptID = $_POST["attemptID"];
    require("../classes/dbutils.php");
} else {
    $userID = $_SESSION["drupalUserID"];
    $attemptID = $_SESSION["gameAttemptID"];
}

$sql = "Select * ";
$sql .= "FROM pageaccess ";
$sql .= "WHERE  userID = '" . $userID . "' AND attemptID = '" . $attemptID . "' ";
$sql .= "AND accessDateTime = (SELECT max(accessDateTime) ";
$sql .= "FROM pageaccess ";
$sql .= "WHERE userID = '" . $userID . "' AND attemptID = '" . $attemptID . "')";

$db = new DbUtilities;
$accessCollection = $db->getDataset($sql);
