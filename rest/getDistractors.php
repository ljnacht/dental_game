<?php

require("../classes/dbutils.php");


$questionID = $_POST["questionID"];
$numOfDistractors = $_POST["numDistractors"];

$sql = "SELECT questionID, diagnosisName, hint, imageID, imageFolder, imageName ";
$sql .= "FROM distractors d JOIN questions q ON d.fk_distructorQuestionID = q.questionID ";
$sql .= "JOIN questions_images qi ON q.questionID = qi.fk_questionID  ";
$sql .= "JOIN images i ON qi.fk_imageID = i.imageID ";
$sql .= "WHERE fk_forQuestionID = '" . $questionID . "' ";
$sql .= "ORDER BY RAND() LIMIT " . $numOfDistractors . " ";

$db = new DbUtilities;
$questionCollection = $db->getDataset($sql);
$distractionData = '"distractors" : ' . json_encode($questionCollection);


echo('{' . $distractionData . '}');
//echo($sql);
?>

