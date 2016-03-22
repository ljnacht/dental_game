<?php

require("image.php");

class Question{
	/* Global variables */
	private $attempt = "";
	private $questionID  = "";
	private $diagnosisName = "";
	private $hint = "";
	private $level = 0;
	private $imageList = array();
	
	function __construct($attemptID, $levelID, $maxCorrect = 1, $numOfDistractors = 5){
		$this->attempt = $attemptID;
		$db = new DbUtilities;
		/*
		$sql = "SELECT questionID, diagnosisName, hint, numberOfImages, imageID, imageFolder, imageName ";
		$sql .= "FROM questions q JOIN questions_images qi ON q.questionID = qi.fk_questionID ";
		$sql .= "JOIN images i ON qi.fk_imageID = i.imageID ";
		$sql .= "JOIN questions_levels ql ON q.questionID = ql.fk_questionID ";
		$sql .= "WHERE levelID = " . $levelID . " ";
		$sql .= "AND questionID NOT IN (SELECT questionID FROM used_question_log WHERE attemptID = '" . $attemptID . "' AND levelID = " . $levelID . ") ";
		$sql .= "ORDER BY RAND() LIMIT " . $maxCorrect . "; ";
		*/
		$sql = "SELECT questionID, diagnosisName, hint, numberOfImages ";
		$sql .= "FROM questions q ";
		$sql .= "JOIN questions_levels ql ON q.questionID = ql.fk_questionID ";
		$sql .= "WHERE levelID = " . $levelID . " ";
		$sql .= "AND questionID NOT IN (SELECT questionID FROM used_question_log WHERE attemptID = '" . $attemptID . "' AND levelID = " . $levelID . ") ";
		$sql .= "AND questionID IN (SELECT fk_questionID FROM questions_images) ";
		$sql .= "ORDER BY RAND() LIMIT 1; ";

		// echo $sql . "<br />";

		$collectionList = $db->getDataset($sql);
    
		foreach($collectionList as &$row){
			$this->questionID = $row["questionID"];
			$this->level = $levelID;
			$this->diagnosisName = $row["diagnosisName"];
			$this->hint = $row["hint"];
		}
		
		$sql = "SELECT fk_questionID, imageID, imageFolder, imageName FROM questions_images qi JOIN images i ON qi.fk_imageID = i.imageID ";
		$sql .= "WHERE fk_questionID = '" . $this->questionID . "' ORDER BY RAND() LIMIT " . $maxCorrect . "; ";
		
		// echo($sql . "<br />");
		
		$collectionList = $db->getDataset($sql);
		foreach($collectionList as &$row){
			$image = new Image($row["imageID"], $row["imageFolder"], $row["imageName"], true, $this->diagnosisName, $this->hint);
			array_push($this->imageList, $image);
			// echo($image->getImageID() . "<br />");
		}

		$this->loadDistractors($this->questionID, $numOfDistractors);
		$this->logUsedQuestion();
		
	}
	
	private function logUsedQuestion(){
		$sql = "INSERT INTO used_question_log(attemptID,questionID,levelID) VALUES (?,?,?);";
		$db = new DbUtilities;
		$db->executeQuery($sql, "ssi", array($this->attempt, $this->questionID, $this->level));
	}
	
	
	private function loadDistractors($questionID, $numOfDistractors){
		$db = new DbUtilities;
		/*
		$sql = "SELECT questionID, diagnosisName, hint, numberOfImages, imageID, imageFolder, imageName ";
		$sql .= "FROM questions q JOIN questions_images qi ON q.questionID = qi.fk_questionID ";
		$sql .= "JOIN images i ON qi.fk_imageID = i.imageID ";
		$sql .= "JOIN questions_levels ql ON q.questionID = ql.fk_questionID ";
		$sql .= "WHERE levelID = " . $levelID . " ";
		$sql .= "ORDER BY RAND() LIMIT " . $numOfDistractors . "; ";
		*/
		
		$sql = "SELECT questionID, diagnosisName, hint, imageID, imageFolder, imageName ";
		$sql .= "FROM distractors d JOIN questions q ON d.fk_distructorQuestionID = q.questionID ";
		$sql .= "JOIN questions_images qi ON q.questionID = qi.fk_questionID  ";
		$sql .= "JOIN images i ON qi.fk_imageID = i.imageID ";
		$sql .= "WHERE fk_forQuestionID = '" . $questionID . "' ";
		$sql .= "ORDER BY RAND() LIMIT " . $numOfDistractors . " ";

		// echo $sql . "<br />";

		$collectionList = $db->getDataset($sql);
		// echo("Distractors: <br />");
		foreach($collectionList as &$row){
			$image = new Image($row["imageID"], $row["imageFolder"], $row["imageName"], false, $row["diagnosisName"], $row["hint"]);
			array_push($this->imageList, $image);
			// echo($image->getImageID() . "<br />");
		}
		
		$db->closeConnection();
	}
	
	public function getQuestionID(){
		return $this->questionID;
	}
	
	public function getImageList(){
		return $this->imageList;
	}
	
	public function getHint(){
		return $this->hint;
	}
	
	public function getDiagnosisName(){
		return $this->diagnosisName;
	}
	
	public function toJSON(){
		$list = array();
		for($i=0; $i < count($this->imageList); $i++){
			$image = $this->imageList[$i];

			$list[] = array('imageID' => $image->getImageID(), 'isCorrect' => $image->isCorrectChoice(), 'diagnosis' => $image->getAssociatedDiagnosis(), 'questionID' => $this->questionID);
		}
		return json_encode($list);
	}

}


?>