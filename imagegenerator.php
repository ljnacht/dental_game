<?php

require("classes/dbutils.php");

?>
<htm>
<head>
<title>Dental Game Test</title>
</head>
<body>
<?php
    $db = new DbUtilities;
    $sql = "SELECT questionID, diagnosisName, hint, numberOfImages, imageID, imageFolder, imageName ";
    $sql .= "FROM questions q JOIN questions_images qi ON q.questionID = qi.fk_questionID ";
    $sql .= "JOIN images i ON qi.fk_imageID = i.imageID ";
    $sql .= "JOIN questions_levels ql ON q.questionID = ql.fk_questionID; ";
    // echo $sql . "<br />";

    $collectionList = $db->getDataset($sql);
    
    $prevQuestion = "";
	foreach($collectionList as &$row){
        if($row["questionID"] != $prevQuestion){
        	$path = "dentalimages/" . $row["imageFolder"] . "/" . $row["imageName"];
	        echo ("<img src='" . $path . "' width='100' /><br /><br />");
        }
        $prevQuestion = $row["questionID"];
    }
?>

    
</body>
</htm>