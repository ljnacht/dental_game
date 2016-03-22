<?php

require("../classes/dbutils.php");

$userID = $_POST["userID"];
$questionID = $_POST["questionID"];
$attemptID = $_POST["attemptID"];
$levelID = $_POST["levelID"];


$sql = "INSERT INTO questioncomplete (userID,questionID,attemptID,levelID,scoreDate,scoreTime) ";
$sql .= "VALUES (?,?,?,?, CURDATE(), CURTIME());";
echo($sql);

$db = new DbUtilities;
$db->executeQuery($sql, "sssi", array($userID, $questionID, $attemptID, $levelID));

?>
