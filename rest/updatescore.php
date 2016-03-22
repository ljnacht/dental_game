<?php

require("../classes/dbutils.php");

$userID = $_POST["userID"];
$selectedImageID = $_POST["selectedImageID"];
$questionID = $_POST["questionID"];
$isBonus = $_POST["isBonus"];
$attemptID = $_POST["attemptID"];
$levelID = $_POST["levelID"];
$scoreRecieved = $_POST["scoreRecieved"];
$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];

echo("userID: " . $userID . "\n");
echo("selectedImageID: " . $selectedImageID . "\n");
echo("questionID: " . $questionID . "\n");
echo("isBonus: " . $isBonus . "\n");
echo("scoreRecieved: " . $scoreRecieved . "\n");

$sql = "INSERT INTO score (userID,questionID,imageID,isBonus,attemptID,levelID,scoreRecieved,startDateTime,endDateTime) ";
$sql .= "VALUES (?,?,?,?,?,?,?,?,?);";
// echo($sql);

$db = new DbUtilities;
$db->executeQuery($sql, "sssisiiss", array($userID, $questionID, $selectedImageID, $isBonus, $attemptID, $levelID, $scoreRecieved, $startDate, $endDate));

?>
