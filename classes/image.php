<?php



class Image{
	/* Global variables */
	private $imageID = "";
	private $imageFolder = "";
	private $imageName = "";
	private $fullThumbnailPath = "";
	private $fullImagePath = "";
	private $isCorrectChoice = false;
	private $associatedDiagnosis = "";
	private $hint = "";

	function __construct($_imageID, $_imageFolder, $_imageName, $_isCorrect, $_associatedDiagnosis, $_hint){
		$this->imageID = $_imageID;
		$this->imageFolder = $_imageFolder;
		$this->imageName = $_imageName;
		$this->isCorrectChoice = $_isCorrect;
		$this->associatedDiagnosis = $_associatedDiagnosis;
		$this->hint = $_hint;
		$this->fullThumbnailPath = "dentalthumbnails/" . $_imageFolder . "/" . $_imageName;
		$this->fullImagePath = "dentalimages/" . $_imageFolder . "/" . $_imageName;
		
	}
	
	public function getImageFullPath(){
		return $this->fullImagePath;
	}
	
	public function getThumbnailFullPath(){
		return $this->fullThumbnailPath;
	}
	
	public function isCorrectChoice(){
		return $this->isCorrectChoice;
	}
	
	public function getImageID(){
		return $this->imageID;
	}
	
	public function getAssociatedDiagnosis(){
		return $this->associatedDiagnosis;
	}
	
	public function getHint(){
		return $this->hint;
	}
	
	

}


?>