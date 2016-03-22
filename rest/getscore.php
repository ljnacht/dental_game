<?php

require("../classes/dbutils.php");

$userID = $_POST["userID"];
$levelID = $_POST["levelID"];
$attemptID = $_POST["attemptID"];


/*
$sql = "SELECT questionID, imageID, isBonus, scoreDate, scoreTime, levelID, attemptID ";
$sql .= "FROM score a JOIN questions_levels b ON a.questionID = b.fk_questionID ";
$sql .= "WHERE levelID = " . $levelID . " AND attemptID = '" . $attemptID . "' AND userID = '" . $userID . "';";
*/
// echo($sql);
if($userID=="TOPFIVE"){
	$sql = "SELECT userID,SUM(scoreRecieved) AS Score FROM score GROUP BY attemptID ORDER BY Score DESC LIMIT 5;";
	$db = new DbUtilities;
	$scoreCollection = $db->getDataset($sql);
	$scoreData = '"score" : ' . json_encode($scoreCollection);
	echo('{' . $scoreData . '}');
}else{
	$sql = "SELECT questionID, imageID, isBonus, levelID, attemptID, scoreRecieved, startDateTime, endDateTime ";
	$sql .= "FROM score WHERE attemptID = '" . $attemptID . "' AND userID = '" . $userID . "';";
	
	$db = new DbUtilities;
	$scoreCollection = $db->getDataset($sql);
	$scoreData = '"score" : ' . json_encode($scoreCollection);

	$sql = "SELECT questionID, scoreDate, scoreTime, levelID, attemptID ";
	$sql .= "FROM questioncomplete WHERE levelID = " . $levelID . " AND attemptID = '" . $attemptID . "' AND userID = '" . $userID . "';";

	$questionScoreCollection = $db->getDataset($sql);

	$questionData = '"questionComplete" : ' . json_encode($questionScoreCollection);


	echo('{' . $scoreData . ', ' . $questionData . '}');
}
?>
