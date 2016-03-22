<?php
if( isset($_POST["levelAccess"]) )
{
    $levelAccess = $_POST["levelAccess"];
    $userID = $_POST["userID"];
    $attemptID = $_POST["attemptID"];
    require("../classes/dbutils.php");
} else {
    $userID = $_SESSION["drupalUserID"];
    $attemptID = $_SESSION["gameAttemptID"];
    $levelAccess = "level1.php";
}

// echo("userID: " . $userID . "\n");
// echo("attemptID: " . $attemptID);
// echo("levelAccess: " . $levelAccess . "\n");

$sql = "INSERT INTO pageaccess (userID,attemptID,levelAccess,accessDateTime) ";
$sql .= "VALUES (?,?,?, NOW());";

$db = new DbUtilities;
$db->executeQuery($sql, "sss", array($userID, $attemptID, $levelAccess));

/*
echo("userID: " . $userID . "<br />");
echo("attemptID: " . $attemptID . "<br />");
echo("levelAccess: " . $levelAccess  . "<br />");
echo($sql);
*/