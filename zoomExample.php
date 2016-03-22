<?php
	require("classes/dbutils.php");
	require("classes/question.php");
?>

<html>
<head>
  	<title>Level 3</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="https://www.wildcardcorp.com/images/company-logos/mac-icon.png">
	<link rel="stylesheet" href="http://bootswatch.com/cerulean/bootstrap.min.css">
	<link href="levelstylesheet.css" rel="stylesheet" type="text/css"/>

  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	
	<!--[if lt IE 9]>
		<script src"http://html5shiv.googlecode.com/svn/trunk/html5.js">
		</script>
	<![endif]-->
	
	<style>
	.img-zoom {
    -webkit-transition: all .2s ease-in-out;
    -moz-transition: all .2s ease-in-out;
    -o-transition: all .2s ease-in-out;
    -ms-transition: all .2s ease-in-out;
	}
 
	.transition {
    -webkit-transform: scale(2); 
    -moz-transform: scale(2);
    -o-transform: scale(2);
    transform: scale(2);
	}
	
	</style>
	

	<script language="javascript" src="scripts/dental.js"></script>
		<script language="javascript">
			 $(document).ready(function(){
				$('.img-zoom').hover(function() {
				$(this).addClass('transition');
 
				}, function() {
				$(this).removeClass('transition');
				});
			});
			
			var imageList;
			var diagnosis = "";
			var diag2 = "";
			var success = 0;
	
			function diagnosisBtn(image){
				diagnosis = image;
				}
			function correct(image2){
				diag2 = image2;
				diag2 = diag2.replace('btn', '');
		
				if(diagnosis == diag2){
					success++;
					$('#' + image2).css({"background-color": "#00ff00"});
					$('#' + diagnosis).css({"background-color": "#00ff00"});
					//updateScoreData('1243',selImageID, true);
				}
				else{
					$('#' + image2).css({"background-color": "#ff0000"});
					$('#' + diagnosis).css({"background-color": "#ff0000"});
					for (var i = 0; i < imageList.length; i++) {
						if (imageList[i].imageID == diag2) {
							alert("This image actually represents " + imageList[i].diagnosis);
							
							window.location = 'http://studentprojects.sis.pitt.edu/student/dentalgame/level3_lose.php';
							
							//updateScoreData('1243',selImageID, false);
						}
					}
				}
				
				if(success == 6) {
					alert("You Passed!!");
				}
			}
			
				/*function updateScoreData(userID, selectedImageID, isCorrect){
					var scoreData = {};
					scoreData.userID = userID;
					scoreData.selectedImageID = selectedImageID;
					scoreData.questionID = imageList[0].questionID;
					scoreData.isCorrect = isCorrect;
					
					$.post( "updatescore.php", scoreData)
					.done(function( data ) {
						console.log( "Data Loaded: " + data );
					});
				}
				*/
    
			function showImagePreview(imageID){
				$('.overlay').append("<img src= '" + imageID + "' alt='Test Image' title='Test Image' />");
				$('.overlay').style.modal = "true";
				$('.overlay').style.visibility = "visible";
			}			
	</script>
</head>

<body>
<!-- This navagation bar is a modified template found here http://bootswatch.com/, it will enable users to easily navigate the game site -->
	<div class="navbar navbar-default">
  		<div class="navbar-header">
   		 	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
      			<span class="icon-bar"></span>
      			<span class="icon-bar"></span>
      			<span class="icon-bar"></span>
    		</button>
  		</div>
  		
  		<div class="navbar-collapse collapse navbar-responsive-collapse">
    		<ul class="nav navbar-nav">
		  		<li><a href="http://studentprojects.sis.pitt.edu/student/dentalgame/loggedin.php">Home</a></li>
			  	<li><a href="http://studentprojects.sis.pitt.edu/student/dentalgame/tutorial.php">Tutorial</a></li>
			  	<li><a href="http://studentprojects.sis.pitt.edu/student/dentalgame/practice.php">Practice</a></li>		<!-- you would need to log in first before you can do either practice or play, lets take these out of here and make a logged in screen-->
			  	<li class="active"><a href="http://studentprojects.sis.pitt.edu/student/dentalgame/level3.php">Play</a></li>
		  	</ul>
    		
    		<ul class="nav navbar-nav navbar-right">
			  	<li><a href="http://studentprojects.sis.pitt.edu/student/dentalgame/login.php">Log Out</a></li>
			</ul>
   		</div>
  	</div>
	
    <h1>Level 3: Matching Game!</h1>
	<h5>Please select the images and match it to the corresponding diagnoses.</h5>
	<h5>Please select the diagnosis before selecting the picture it matches.</h5>
	

	
	<div id='imageStrip'>
		<?php
			$question = new Question(3);
			$imageList = $question->getImageList();
			
				array_splice($imageList, 6);
			
			shuffle($imageList);

			$diagButton = $imageList;
			shuffle($diagButton);
			echo("<table id='btnTable'><tbody>");

			for($i=0; $i<count($diagButton); $i++)
				{
				echo("<input type='button'" . "id='". $diagButton[$i]->getImageID() . "' class='btnTable'" . "value='" . $diagButton[$i]->getAssociatedDiagnosis(). "' onclick='diagnosisBtn(id)'/>");
				}

			echo("</tbody></table>");
			
			echo("<br>");
			echo("<br>");

			for($i=0; $i<count($imageList); $i++){
				echo("<div id='div" . $imageList[$i]->getImageID() . "' class='imageContainer'>");
				
				echo("<p><input type='button' id='btn" . $imageList[$i]->getImageID() . "' value='SELECT' class='imageSelector' onclick='correct(id)'/>");
				
				echo("<input type='button' id='btnPreview" . $imageList[$i]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imageList[$i]->getImageFullPath() . "\")'; /></p>");
				
				echo("<img src='" . $imageList[$i]->getThumbnailFullPath() . "' class='img-zoom' height='300' id='img'" . " title='" . $imageList[$i]->getHint() . "' />");
				
				echo("</div>");
			}
			
		?>
	</div>

	
	
	
	<div id="overlay" title="Image" class="overlay">
		
	</div>
	
	<script language="javascript">
		imageList = <?php echo($question->toJSON());?>;
	</script>
	
	
</body>
</html>