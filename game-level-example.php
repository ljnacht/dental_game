<?php

require("classes/dbutils.php");
require("classes/question.php");


?>
<htm>
<head>
<title>Dental Game Test</title>
<script language="javascript" src="scripts/jquery-2.1.3.min.js"></script>
<style type="text/css">
	#imageStrip{
		width: 10000px;
	}
	
	.imageContainer{
		position: relative;
		float: left;
		padding-right: 5px;
		padding-top: 5px;
	}
	
	.imageSelector{
		position: relative;
		border: 1px #000000 solid;
		background-color: #ffffff;
		border-radius: 5px;
		padding: 5px;
		margin-right: 10px;
	}
	
	.imageViewer{
		position: relative;
		border: 1px #000000 solid;
		background-color: #ffffff;
		border-radius: 5px;
		padding: 5px;
	}
	
	#responseFeedback{
		position: relative;
		padding: 20px;
		border: 1px solid #000000;
		border-radius: 10px;
		width: 200px;
		position: absolute;
		display: none;
		background-color: #000000;
		color: #ffffff;
		font-weight: normal;
		
	}
	
	.distractorContainer{
		position: relative;
		border: 1px #000000 solid;
		background-color: #ffffff;
		border-radius: 5px;
		padding: 5px;
		margin: 10px;
		width: 150px;
		clear:both;
	}
</style>

<script language="javascript" src="scripts/dental.js"></script>
<script language="javascript">
	var imageList;
	
	$( document ).ready(function() {
    	$("input[class='imageSelector']").each(function (index, element) {
            $( this ).click(function() {
            	selImageID = element.id.replace('btn', '');
				isAnswerCorrect = checkIfCorrect(selImageID);
				if(isAnswerCorrect){
					$( this ).css({"background-color": "#00ff00"});
					updateScoreData('1243',selImageID, true);
				}
				else{
					$( this ).css({"background-color": "#ff0000"});
					displayAssociatedDiagnosis(selImageID, $(this).position().left, $(this).position().top + $(this).height() + 20);
					updateScoreData('1243',selImageID, false);
				}
			});
        });
	});
	
	function updateScoreData(userID, selectedImageID, isCorrect){
		var scoreData = {};
		scoreData.userID = userID;
		scoreData.selectedImageID = selectedImageID;
		scoreData.questionID = imageList[0].questionID;
		scoreData.isCorrect = isCorrect;
		
		console.log(JSON.stringify(scoreData));
		
		$.post( "updatescore.php", scoreData)
		.done(function( data ) {
			console.log( "Data Loaded: " + data );
		});
	}	
	
</script>
</head>
<body>
<div id='imageStrip'>
<?php

$question = new Question(2);
echo("Question: Which of these images represents " . $question->getDiagnosisName() . "?<br />");
// echo("Hint: " . $question->getHint() . "<br />");

$imageList = $question->getImageList();
shuffle($imageList);

for($i=0; $i<count($imageList); $i++){
	echo("<div id='div" . $imageList[$i]->getImageID() . "' class='imageContainer'>");
	
	echo("<p><input type='button' id='btn" . $imageList[$i]->getImageID() . "' value='SELECT' class='imageSelector' />");
	
	echo("<input type='button' id='btnPreview" . $imageList[$i]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imageList[$i]->getImageFullPath() . "\")'; /></p>");
	
	echo("<img src='" . $imageList[$i]->getThumbnailFullPath() . "' height='300' id='img" . $imageList[$i]->getImageID() . "' title='" . $imageList[$i]->getHint() . "' />");
	
	echo("</div>");
}


?>

</div> 

<script language="javascript">
	imageList = <?php echo($question->toJSON());?>;
</script>

<div id="responseFeedback"></div>
</body>
</htm>