var allowedErrors = new Array(3, 2, 1);

var score = 0;
var UName = "";
var curUser = "";

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

/*
 function checkIfCorrect(imageID) {
 correctImage = getCorrectImage();
 console.log("CORRECT: " + correctImage.imageID);
 if (correctImage != null) {
 if (correctImage.imageID == imageID) {
 return true;
 }
 }
 return false;
 }*/

function currentDateTime() {
    var dateObject = new Date();
    var currentDateTime = dateObject.getFullYear();
    m = dateObject.getMonth();
    m = m + 1;
    currentDateTime += "-" + ('0' + m).slice(-2);
    currentDateTime += "-" + ('0' + dateObject.getDate()).slice(-2);
    currentDateTime += " " + ('0' + dateObject.getHours()).slice(-2);
    currentDateTime += ":" + ('0' + dateObject.getMinutes()).slice(-2);
    currentDateTime += ":" + ('0' + dateObject.getSeconds()).slice(-2);
    return currentDateTime;
}

function checkIfCorrect(imageID) {
    correctImageList = getCorrectImageList();
    for (var i = 0; i < correctImageList.length; i++) {
        if (correctImageList[i].imageID == imageID) {
            console.log("CORRECT: " + correctImageList[i].imageID);
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

function getCorrectImageList() {
    correctImageList = new Array();
    for (var i = 0; i < imageList.length; i++) {
        if (imageList[i].isCorrect) {
            correctImageList.push(imageList[i]);
        }
    }
    return correctImageList;
}

function getDistractorImageList() {
    for (var i = 0; i < imageList.length; i++) {
        console.log(imageList[i].diagnosis);
    }
}

function updateAccess(userID, attemptID, levelAccess) {
    var accessData = {};
    accessData.userID = userID;
    accessData.attemptID = attemptID;
    accessData.levelAccess = levelAccess;

    $.post("rest/updateaccess.php", accessData).done(function (data) {
        //console.log("Data Loaded: " + data);
    });
}

// This function gathers the userId, selectedImageID, and if the question was answered correctly or not and sends all of this information to the updatescore.php file to be input into the database.
function updateScoreData(attemptID, userID, questionID, selectedImageID, isBonus, levelID, scoreRecieved, startDate, endDate) {
    var scoreData = {};
    scoreData.userID = userID;
    scoreData.selectedImageID = selectedImageID;
    scoreData.questionID = questionID;
    scoreData.scoreRecieved = scoreRecieved;
    scoreData.startDate = startDate;
    scoreData.endDate = endDate;

    if (isBonus) {
        scoreData.isBonus = 1;
    }
    else {
        scoreData.isBonus = 0;
    }

    scoreData.attemptID = attemptID;
    scoreData.levelID = levelID;
    $.post("rest/updatescore.php", scoreData).done(function (data) {
        //console.log("Data Loaded: " + data);
    });
}

function updateQuestionCompletion(attemptID, userID, questionID, levelID) {
    var scoreData = {};
    scoreData.userID = userID;
    scoreData.questionID = questionID;
    scoreData.attemptID = attemptID;
    scoreData.levelID = levelID;
    $.post("rest/updatequestioncomplete.php", scoreData).done(function (data) {
        //console.log("Data Loaded: " + data);
    });
}

function calcPoints(scoreArray){
    var start = scoreArray.startDateTime;
    var end = scoreArray.endDateTime;
    var levelID = scoreArray.levelID;
    var gracePeriod; // amount of time user has before point degradation begins
    var secondsSpent;
    var pointsLostPerSecond = 10;
    var pointsToLose = 0; 
    var finalPoints = scoreArray.scoreRecieved;
    var pointLimit = 25; // the lowest amount of points allowed.
    
    if(levelID == 1){
        gracePeriod = 25;
    } else if (levelID == 2){
        gracePeriod = 50;
    } else if (levelID == 3) {
        gracePeriod = 75;
    } else {
        gracePeriod = 25;
    }
    
    secondsSpent = findDifference(convertDate(end), convertDate(start));
    secondsSpent = secondsSpent - gracePeriod;
    
    if(secondsSpent < 0){
        return finalPoints;
    } else {
        pointsToLose = secondsSpent * pointsLostPerSecond; 
        finalPoints = finalPoints - pointsToLose;
        if(finalPoints < pointLimit){
            return pointLimit;
        }else{
            return finalPoints;
        }
    }
    
}

function convertDate(givenDate){
    var tempDate = givenDate.split(/[- :]/);
    var javaDate = new Date(tempDate[0], tempDate[1]-1, tempDate[2], tempDate[3], tempDate[4], tempDate[5]);
    return javaDate;
}

function findDifference(endDate, startDate){
    var difference = (endDate.getTime() - startDate.getTime())/1000;
    return difference;
}

/** This function takes in the attempts, userID, and level ID
 It proceeds to display the number of correct answers per level and the number of 
 attempts you have tried.
 
 If the number of correct attempts on any individual level meets a certain amount
 designated by that level the user has won that level
 **/

function getScoreData(attemptID, userID, levelID) {
    var scoreData = {};
    scoreData.userID = userID;
    scoreData.levelID = levelID;
    scoreData.attemptID = attemptID;
    $.post("rest/getscore.php", scoreData).done(function (data) {
        var jsonData = JSON.parse(data);
        console.log(JSON.parse(data));
        var numberCorrect = 0;
        var questionsCompleted = jsonData.questionComplete.length;
        for (var i = 0; i < jsonData.score.length; i++) {
            if (jsonData.score[i].scoreRecieved > 0) {
                numberCorrect++;
                score += +parseInt(calcPoints(jsonData.score[i]));
                //score += +parseInt(jsonData.score[i].scoreRecieved);
            }
        }

        //score = numberCorrect * pointMultiplier;
        $('#scoreDisplay').html("Number of attempts on Level " + levelID + ": " + jsonData.score.length + ". Questions completed: " + questionsCompleted + ". Correct choices: " + numberCorrect + ". Your score: " + score);

        var numberIncorrect = jsonData.score.length - numberCorrect;
        console.log("ALLOWED: " + allowedErrors[levelID - 1]);
        console.log("INCORRECT: " + numberIncorrect);
        if (allowedErrors[levelID - 1] <= numberIncorrect) {
            //alert("You screwed up, we are kicking you out. Your final score was " + score);
            document.getElementById("dialog-confirm").innerHTML = "You screwed up, we are kicking you out. Your final score was " + score;
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 200,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "gameattempt.php?levelID=" + levelID;
                                        }
                                    }
                                });
                            });
        }

        else {
            if (levelID == 1 && questionsCompleted == 8) {
                if (numberIncorrect == 0) {
                    updateAccess(userID, attemptID, 'level2.php');
                    updateScoreData(attemptID, userID, null, null, true, levelID, 1000, currentDateTime(), currentDateTime());
                    score += 1000;
                    //alert("You have passed Level 1 and gotten a perfect score, here is a bonus!!! Your score now is " + score);
                    document.getElementById("dialog-confirm").innerHTML = "You have passed Level 1 and gotten a perfect score, here is a bonus!!! Your score now is " + score;
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 200,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "level2.php";
                                        }
                                    }
                                });
                            });
                } else {
                    updateAccess(userID, attemptID, 'level2.php');
                    //alert("You have passed Level 1!");
                    document.getElementById("dialog-confirm").innerHTML = "You have passed Level 1";                    
					$(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 180,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "level2.php";
                                        }
                                    }
                                });
                            });
                }
            }
            if (levelID == 2 && questionsCompleted == 6) {
                if (numberIncorrect == 0) {
                    updateAccess(userID, attemptID, 'level3.php');
                    updateScoreData(attemptID, userID, null, null, true, levelID, 3000, currentDateTime(), currentDateTime());
                    score += 3000;
                    //alert("You have passed Level 2 and gotten a perfect score, here is a bonus!!! " + score);
                    document.getElementById("dialog-confirm").innerHTML = "You have passed Level 2 and gotten a perfect score, here is a bonus!!! Your score now is " + score;
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 200,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "level3.php";
                                        }
                                    }
                                });
                            });
                } else {
                    setSession("levelAccess", "level3.php");
                    updateAccess(userID, attemptID, 'level3.php');
                    //alert("You have passed Level 2!");
                    document.getElementById("dialog-confirm").innerHTML = "You have passed Level 2!";
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 180,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "level3.php";
                                        }
                                    }
                                });
                            });
                }
            }

            // Level 3 actually has 3 "questions", but each of those questions contains 6 individual questions
            if (levelID == 3 && questionsCompleted == 18) {
                if (numberIncorrect == 0) {
                    updateAccess(userID, attemptID, 'win.php');
                    updateScoreData(attemptID, userID, null, null, true, levelID, 5000, currentDateTime(), currentDateTime());
                    score += 5000;
                    //alert("You have passed Level 3 and gotten a perfect score, here is a bonus!!! " + score);
                    document.getElementById("dialog-confirm").innerHTML = "You have passed Level 3 and gotten a perfect score, here is a bonus!!! " + score;
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 200,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "win.php";
                                        }
                                    }
                                });
                            });
                } else {
                    updateAccess(userID, attemptID, 'win.php');
                    //alert("You have passed Level 3!");
					document.getElementById("dialog-confirm").innerHTML = "You have passed Level 3!";
                            $(function () {
                                $("#dialog-confirm").dialog({
                                    resizable: false,
                                    closeOnEscape: false,
                                    open: function (event, ui) {
                                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                    },
                                    height: 180,
                                    modal: true,
                                    buttons: {
                                        "Ok": function () {
                                            $(this).dialog("close");
											document.location.href = "win.php";
                                        }
                                    }
                                });
                            });
                }
            }
        }

    });
}



// This function allows a user to zoom in on a picture **REPLACED BY INTENSIFY**
function showImagePreview(imagePath) {
    $('#overlay').append("<div id='zoomControls'><input type='button' id='btnCloseZoom' value='Close'></div>");
    $('#overlay').append("<img src= '" + imagePath + "' alt='Test Image' title='Test Image' style='width: 100%;' />");
    $('#overlay').css({
        'display': 'block',
        'width': '80%',
        'height': '80%',
        'position': 'fixed',
        'top': '50%',
        'left': '50%',
        'transform': 'translate(-50%, -50%)'
    });

    $('#zoomControls').css({
        'text-align': 'right'
    });

    $('#btnCloseZoom').click(function () {
        $('#overlay').empty();
        $('#overlay').css({
            'display': 'none'
        });
    });
}

function setSession(name, value) {
    var sendData = {};
    sendData.name = name;
    sendData.value = value;
    $.post("rest/setsession.php", sendData).done(function () {


    });
}


function getFileName() {
    // gets the full url
    var url = document.location.href;
    // removes the anchor at the end, if there is one
    url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
    // removes the query after the file name, if there is one
    url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
    // removes everything before the last slash in the path
    url = url.substring(url.lastIndexOf("/") + 1, url.length);
    return url;
}

// Hides the play link if user is engaged within a level of the game
function hidePlay() {
    if (getFileName() == "level1.php" || getFileName() == "level2.php" || getFileName() == "level3.php" || getFileName("win.php") || getFileName("caught_cheating.php")) {
        document.getElementById("playGame").style.visibility = "hidden";
    }
}

//gets top five scores for leaderboard
function getTopFive() {
    var targetDiv = document.getElementById("standings");
	var stage = "TOPFIVE";
	var scoreData = {};
    scoreData.userID = stage;
    scoreData.levelID = stage;
    scoreData.attemptID = stage;
    $.post("rest/getscore.php", scoreData).done(function (data) {
        var jsonData = JSON.parse(data);
        //console.log(JSON.parse(data));
        for (var i = 0; i < jsonData.score.length; i++) {
			var uid = {};
			uid.ID=jsonData.score[i].userID;
			//$.post("rest/getuserlist.php", uid, function (dataArray) {
			//	var jsonData2 = JSON.parse(dataArray);
			//	targetDiv.innerHTML+= jsonData2[0].name + "<br>";
			//});
			targetDiv.innerHTML+= jsonData.score[i].Score + "<br>";
        }
	});
}

// **IMAGE INTENSIFY FUNCTION** This function allows a user to zoom in on a picture
//https://github.com/tholman/intense-images code by Timothy Holman
window.requestAnimFrame = (function () {
    return  window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            function (callback) {
                window.setTimeout(callback, 1000 / 60);
            };
})();

window.cancelRequestAnimFrame = (function () {
    return window.cancelAnimationFrame ||
            window.webkitCancelRequestAnimationFrame ||
            window.mozCancelRequestAnimationFrame ||
            window.oCancelRequestAnimationFrame ||
            window.msCancelRequestAnimationFrame ||
            clearTimeout
})();


var Intense = (function () {

    'use strict';

    var KEYCODE_ESC = 27;

    // Track both the current and destination mouse coordinates
    // Destination coordinates are non-eased actual mouse coordinates
    var mouse = {xCurr: 0, yCurr: 0, xDest: 0, yDest: 0};

    var horizontalOrientation = true;

    // Holds the animation frame id.
    var looper;

    // Current position of scrolly element
    var lastPosition, currentPosition = 0;

    var sourceDimensions, target;
    var targetDimensions = {w: 0, h: 0};

    var container;
    var containerDimensions = {w: 0, h: 0};
    var overflowArea = {x: 0, y: 0};

    // Overflow variable before screen is locked.
    var overflowValue;

    var active = false;

    /* -------------------------
     /*          UTILS
     /* -------------------------*/

    // Soft object augmentation
    function extend(target, source) {

        for (var key in source)
            if (!(key in target))
                target[ key ] = source[ key ];

        return target;
    }

    // Applys a dict of css properties to an element
    function applyProperties(target, properties) {

        for (var key in properties) {
            target.style[ key ] = properties[ key ];
        }
    }

    // Returns whether target a vertical or horizontal fit in the page.
    // As well as the right fitting width/height of the image.
    function getFit(
            source) {

        var heightRatio = window.innerHeight / source.h;

        if ((source.w * heightRatio) > window.innerWidth) {
            return {w: source.w * heightRatio, h: source.h * heightRatio, fit: true};
        } else {
            var widthRatio = window.innerWidth / source.w;
            return {w: source.w * widthRatio, h: source.h * widthRatio, fit: false};
        }
    }

    /* -------------------------
     /*          APP
     /* -------------------------*/

    function startTracking(passedElements) {

        var i;

        // If passed an array of elements, assign tracking to all.
        if (passedElements.length) {

            // Loop and assign
            for (i = 0; i < passedElements.length; i++) {
                track(passedElements[ i ]);
            }

        } else {
            track(passedElements);
        }
    }

    function track(element) {

        // Element needs a src at minumun.
        if (element.getAttribute('data-image') || element.src || element.href) {
            element.addEventListener('click', function (e) {
                if (element.tagName === 'A') {
                    e.preventDefault();
                }
                if (!active) {
                    init(this);
                }
            }, false);
        }
    }

    function start() {
        loop();
    }

    function stop() {
        cancelRequestAnimFrame(looper);
    }

    function loop() {
        looper = requestAnimFrame(loop);
        positionTarget();
    }

    // Lock scroll on the document body.
    function lockBody() {

        overflowValue = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
    }

    // Unlock scroll on the document body.
    function unlockBody() {
        document.body.style.overflow = overflowValue;
    }

    function setState(element, newClassName) {
        if (element) {
            element.classList.remove("loading");
            element.classList.remove("viewing");
            element.className += " " + newClassName;
        } else {
            // Remove element with class .view
            var elems = document.querySelectorAll(".viewing");

            [].forEach.call(elems, function (el) {
                el.classList.remove("viewing");
            });
        }
    }

    function createViewer(title, caption) {

        /*
         *  Container
         */
        var containerProperties = {
            'backgroundColor': 'rgba(0,0,0,0.8)',
            'width': '100%',
            'height': '100%',
            'position': 'fixed',
            'top': '0px',
            'left': '0px',
            'overflow': 'hidden',
            'zIndex': '999999',
            'margin': '0px',
            'webkitTransition': 'opacity 150ms cubic-bezier( 0, 0, .9, 1 )',
            'MozTransition': 'opacity 150ms cubic-bezier( 0, 0, .9, 1 )',
            'transition': 'opacity 150ms cubic-bezier( 0, 0, .9, 1 )',
            'webkitBackfaceVisibility': 'hidden',
            'opacity': '0'
        };
        container = document.createElement('figure');
        container.appendChild(target);
        applyProperties(container, containerProperties);

        var imageProperties = {
            'cursor': 'url( "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3Q0IyNDI3M0FFMkYxMUUzOEQzQUQ5NTMxMDAwQjJGRCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo3Q0IyNDI3NEFFMkYxMUUzOEQzQUQ5NTMxMDAwQjJGRCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjdDQjI0MjcxQUUyRjExRTM4RDNBRDk1MzEwMDBCMkZEIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjdDQjI0MjcyQUUyRjExRTM4RDNBRDk1MzEwMDBCMkZEIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+soZ1WgAABp5JREFUeNrcWn9M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" ) 25 25, no-drop'
        };
        applyProperties(target, imageProperties);

        /*
         *  Caption Container
         */
        var captionContainerProperties = {
            'fontFamily': 'Georgia, Times, "Times New Roman", serif',
            'position': 'fixed',
            'bottom': '0px',
            'left': '0px',
            'padding': '20px',
            'color': '#fff',
            'wordSpacing': '0.2px',
            'webkitFontSmoothing': 'antialiased',
            'textShadow': '-1px 0px 1px rgba(0,0,0,0.4)'
        };
        var captionContainer = document.createElement('figcaption');
        applyProperties(captionContainer, captionContainerProperties);

        /*
         *  Caption Title
         */
        if (title) {
            var captionTitleProperties = {
                'margin': '0px',
                'padding': '0px',
                'fontWeight': 'normal',
                'fontSize': '40px',
                'letterSpacing': '0.5px',
                'lineHeight': '35px',
                'textAlign': 'left'
            };
            var captionTitle = document.createElement('h1');
            applyProperties(captionTitle, captionTitleProperties);
            captionTitle.innerHTML = title;
            captionContainer.appendChild(captionTitle);
        }

        if (caption) {
            var captionTextProperties = {
                'margin': '0px',
                'padding': '0px',
                'fontWeight': 'normal',
                'fontSize': '20px',
                'letterSpacing': '0.1px',
                'maxWidth': '500px',
                'textAlign': 'left',
                'background': 'none',
                'marginTop': '5px'
            };
            var captionText = document.createElement('h2');
            applyProperties(captionText, captionTextProperties);
            captionText.innerHTML = caption;
            captionContainer.appendChild(captionText);
        }

        container.appendChild(captionContainer);

        setDimensions();

        mouse.xCurr = mouse.xDest = window.innerWidth / 2;
        mouse.yCurr = mouse.yDest = window.innerHeight / 2;

        document.body.appendChild(container);
        setTimeout(function () {
            container.style[ 'opacity' ] = '1';
        }, 10);
    }

    function removeViewer() {

        unlockBody();
        unbindEvents();
        stop();
        document.body.removeChild(container);
        active = false;
        setState(false);
    }

    function setDimensions() {

        // Manually set height to stop bug where
        var imageDimensions = getFit(sourceDimensions);
        target.width = imageDimensions.w;
        target.height = imageDimensions.h;
        horizontalOrientation = imageDimensions.fit;

        targetDimensions = {w: target.width, h: target.height};
        containerDimensions = {w: window.innerWidth, h: window.innerHeight};
        overflowArea = {x: containerDimensions.w - targetDimensions.w, y: containerDimensions.h - targetDimensions.h};

    }

    function init(element) {

        setState(element, 'loading');
        var imageSource = element.getAttribute('data-image') || element.src || element.href;
        var title = element.getAttribute('data-title') || element.title;
        var caption = element.getAttribute('data-caption');

        var img = new Image();
        img.onload = function () {

            sourceDimensions = {w: img.width, h: img.height}; // Save original dimensions for later.
            target = this;
            createViewer(title, caption);
            lockBody();
            bindEvents();
            loop();

            setState(element, 'viewing');
        }

        img.src = imageSource;
    }

    function bindEvents() {

        container.addEventListener('mousemove', onMouseMove, false);
        container.addEventListener('touchmove', onTouchMove, false);
        window.addEventListener('resize', setDimensions, false);
        window.addEventListener('keyup', onKeyUp, false);
        target.addEventListener('click', removeViewer, false);
    }

    function unbindEvents() {

        container.removeEventListener('mousemove', onMouseMove, false);
        container.removeEventListener('touchmove', onTouchMove, false);
        window.removeEventListener('resize', setDimensions, false);
        window.removeEventListener('keyup', onKeyUp, false);
        target.removeEventListener('click', removeViewer, false)
    }

    function onMouseMove(event) {

        mouse.xDest = event.clientX;
        mouse.yDest = event.clientY;
    }

    function onTouchMove(event) {

        event.preventDefault(); // Needed to keep this event firing.
        mouse.xDest = event.touches[0].clientX;
        mouse.yDest = event.touches[0].clientY;
    }

    // Exit on excape key pressed;
    function onKeyUp(event) {

        event.preventDefault();
        if (event.keyCode === KEYCODE_ESC) {
            removeViewer();
        }
    }

    function positionTarget() {

        mouse.xCurr += (mouse.xDest - mouse.xCurr) * 0.05;
        mouse.yCurr += (mouse.yDest - mouse.yCurr) * 0.05;

        if (horizontalOrientation === true) {

            // HORIZONTAL SCANNING
            currentPosition += (mouse.xCurr - currentPosition);
            if (mouse.xCurr !== lastPosition) {
                var position = parseFloat(currentPosition / containerDimensions.w);
                position = overflowArea.x * position;
                target.style[ 'webkitTransform' ] = 'translate(' + position + 'px, 0px)';
                target.style[ 'MozTransform' ] = 'translate(' + position + 'px, 0px)';
                target.style[ 'msTransform' ] = 'translate(' + position + 'px, 0px)';
                lastPosition = mouse.xCurr;
            }
        } else if (horizontalOrientation === false) {

            // VERTICAL SCANNING
            currentPosition += (mouse.yCurr - currentPosition);
            if (mouse.yCurr !== lastPosition) {
                var position = parseFloat(currentPosition / containerDimensions.h);
                position = overflowArea.y * position;
                target.style[ 'webkitTransform' ] = 'translate( 0px, ' + position + 'px)';
                target.style[ 'MozTransform' ] = 'translate( 0px, ' + position + 'px)';
                target.style[ 'msTransform' ] = 'translate( 0px, ' + position + 'px)';
                lastPosition = mouse.yCurr;
            }
        }
    }

    function main(element) {

        // Parse arguments
        if (!element) {
            throw 'You need to pass an element!';
        }

        startTracking(element);
    }

    return extend(main, {
        resize: setDimensions,
        start: start,
        stop: stop
    });
})();