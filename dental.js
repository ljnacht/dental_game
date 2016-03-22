var allowedErrors = new Array(3, 2, 1);

var score = 0;
var pointMultiplier = 500;

function displayAssociatedDiagnosis(imageID, leftX, topX) {
	for (var i = 0; i < imageList.length; i++) {
		if (imageList[i].imageID == imageID) {
			$('#responseFeedback').html("What the hell were you thinking?  This image represents <b>" + imageList[i].diagnosis + "</b>");
			$('#responseFeedback').css({
				"display": "block",
				"left": leftX,
				"top": topX
			});
		}
	}
	return "";
}

function checkIfCorrect(imageID) {
	correctImage = getCorrectImage();
	if (correctImage != null) {
		if (correctImage.imageID == imageID) {
			return true;
		}
	}
	return false;
}

function getCorrectImage() {
	for (var i = 0; i < imageList.length; i++) {
		if (imageList[i].isCorrect) {
			return imageList[i];
		}
	}
	return null;
}

function getDistractorImageList() {
	for (var i = 0; i < imageList.length; i++) {
		console.log(imageList[i].diagnosis);
	}
}


// This function gathers the userId, selectedImageID, and if the question was answered correctly or not and sends all of this information to the updatescore.php file to be input into the database.
function updateScoreData(attemptID, userID, selectedImageID, isCorrect) {
	var scoreData = {};
	scoreData.userID = userID;
	scoreData.selectedImageID = selectedImageID;
	scoreData.questionID = imageList[0].questionID;
	if(isCorrect){
		scoreData.isCorrect = 1;	
	}
	else{
		scoreData.isCorrect = 0;
	}
	
	scoreData.attemptID = attemptID;
	$.post("rest/updatescore.php", scoreData).done(function(data) {
		console.log("Data Loaded: " + data);
	});
}


/** This function takes in the attempts, userID, and level ID
It proceeds to display the number of correct answers per level and the number of 
attempts you have tried.

If the number of correct attempts on any individual level meets a certain amount
designated by that level the user has won that level
**/

function getScoreData(attemptID, userID, levelID){
	var scoreData = {};
	scoreData.userID = userID;
	scoreData.levelID = levelID;
	scoreData.attemptID = attemptID;
	$.post("rest/getscore.php", scoreData).done(function(data) {
		var jsonData = JSON.parse(data)
		// console.log(JSON.parse(data));
		var numberCorrect = 0;
		for(var i = 0; i<jsonData.length; i++){
			if(jsonData[i].isCorrect == 1){
				numberCorrect++;
			}
		}
		score = numberCorrect * pointMultiplier;
		$('#scoreDisplay').html("Number of attempts on level " + levelID + " is " + jsonData.length + ". Correct choices: " + numberCorrect + " Your score: " + score);
		
		var numberIncorrect = jsonData.length - numberCorrect;
		console.log("ALLOWED: " + allowedErrors[levelID - 1]);
		console.log("INCORRECT: " + numberIncorrect);
		if(allowedErrors[levelID - 1] <= numberIncorrect){
			alert("You screwed up, we are kicking you out. Your final score was " + score);
			document.location.href = "gameattempt.php?levelID="+levelID;
		}
		
		if(levelID == 1 && jsonData.length == 5){
			if (numberCorrect == 5){
				score += 300;
				alert("You have passed Level 1 and gotten a perfect, here is a bonus!!! Your score now is " + score);
				document.location.href = "level2.php";
			}else{
				alert("You have passed Level 1!");
				document.location.href = "level2.php";
			}
		}
		/*if(levelID == 2 && jsonData.length == 4){
			if (numberCorrect == 4){
				score += 3000;
				alert("You have passed Level 2 and gotten a perfect, here is a bonus!!! " + score );
				document.location.href = "level3.php";
			}else{
				alert("You have passed Level 2!");
				document.location.href = "level3.php";
			}
		}
		if(levelID == 3 && jsonData.length == 3){
			if (numberCorrect == 3){
				score += 3000;
				alert("You have passed Level 3 and gotten a perfect, here is a bonus!!! " + score );
				document.location.href = "level3.php";
			}else{
				alert("You have passed Level 3!");
				document.location.href = "win.php";
			}
		}*/
	});
}



// This function allows a user to zoom in on a picture
function showImagePreview(imagePath) {
	$('#overlay').append("<div id='zoomControls'><input type='button' id='btnCloseZoom' value='Close'></div>");
	$('#overlay').append("<img src= '" + imagePath + "' alt='Test Image' title='Test Image' style='width: 100%;' />");
	$('#overlay').css({
		'display':'block',
		'width': '80%',
		'height': '80%',
		'position': 'fixed',
		'top': '50%',
		'left': '50%',
		'transform': 'translate(-50%, -50%)'
	});
	
	$('#zoomControls').css({
		'text-align' : 'right'
	});
	
	$('#btnCloseZoom').click(function(){
		$('#overlay').empty();
		$('#overlay').css({
			'display':'none'
		});
	});
}