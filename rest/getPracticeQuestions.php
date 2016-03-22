<?php

require("../classes/dbutils.php");


$limit = $_POST["limit"];
$firstWhere = 0;

$sql = "SELECT questionID,imageID,categoryName,diagnosisName,hint,numberOfImages,imageFolder,imageName ";
$sql .= "FROM categories JOIN questions ON categoryID = fk_categoryID JOIN ";
$sql .= "questions_images ON questions.questionID = fk_questionID JOIN images ";
$sql .= "ON questions_images.fk_imageID = images.imageID WHERE categoryID = ";

foreach ($_POST as $key => $value) {
    if ($firstWhere == 0) {
        $sql .= $value;
        $firstWhere += 1;
    } else {
        $sql .= " OR categoryID = " . $value;
    }
}
$sql .= " ORDER BY RAND() LIMIT " . $limit . ";";

$db = new DbUtilities;
$questionCollection = $db->getDataset($sql);
$questionData = '"question" : ' . json_encode($questionCollection);


echo('{' . $questionData . '}');
//echo($sql);
?>

