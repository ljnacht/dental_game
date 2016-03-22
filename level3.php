<?php
require("classes/security.php");
require("classes/dbutils.php");
/*require("classes/levelsecurity.php");*/
require("classes/question.php");
$pageTitle = "Level 3";
require("header.php");

$drupalUserID = $_SESSION["drupalUserID"];
if (isset($_GET["debug"])) {
    $_SESSION["debug"] = true;
}
?>


<script language="javascript" type="text/javascript">
    var imageList;
    var correctAns;
    var colorMatch = ["#078cc2", "#006e34", "#fa877a", "#f707f9", "#f98d07", "#07f9c2"];

    // How many selections are allowed in this level
    var MAX_ALLOWED_SELECTIONS = 6;

    var selectedImagesList = []; // Stores selected images
    var selectedDiagnosisList = []; // Stores selected diagnosis

    var tempSelectedDiagnosis = ""; //Stores diagnosis before the final scoring
    var tempSelectedImage = ""; //Stores images before final scoring
    var baseScore = 1000;
    var startDateTime = currentDateTime();

    hidePlay();

    $('document').ready(function () {
        // Display score
        getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', 3);
        // questionID = imageList[0].questionID;
    });

    //Saves matches to be scored
    function saveSelectedPairs() {
        if (tempSelectedDiagnosis != "" && tempSelectedImage != "") {
            selectedImagesList.push(tempSelectedImage);
            selectedDiagnosisList.push(tempSelectedDiagnosis);
            var questionID = tempSelectedDiagnosis.split("_")[0];
            var selectedImageID = tempSelectedDiagnosis.split("_")[1];
            if (tempSelectedDiagnosis == tempSelectedImage) {
                updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selectedImageID, false, 3, getScore(25), startDateTime, currentDateTime());
                updateQuestionCompletion('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, 3);
            }
            else {
                updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selectedImageID, false, 3, 0, startDateTime, currentDateTime());
            }

            //Resets the arrays
            tempSelectedDiagnosis = "";
            tempSelectedImage = "";

            calculateScore();
            getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', 3);
        }
    }

    //Function to store the diagnosis selection
    function selectDiagnosisMatch(obj) {
        if (tempSelectedDiagnosis == "") {
            tempSelectedDiagnosis = obj.id;
            saveSelectedPairs();
            $('#' + obj.id).prop("disabled", true);
            //tests to make sure there is something in the selectedDiagnosisList, if not, uses the first color in the array.
            if (selectedDiagnosisList.length == 0) {
                $('#' + obj.id).css({"background-color": colorMatch[0]});
            }
            else {
                $('#' + obj.id).css({"background-color": colorMatch[selectedDiagnosisList.length]});
            }
        }
    }

    //Function to store the image selection
    function selectImageMatch(obj) {
        var tmp = obj.id;
        tmp = tmp.replace('btn', '');
        if (tempSelectedImage == '') {
            tempSelectedImage = tmp;
            saveSelectedPairs();
            $('#' + obj.id).prop("disabled", true);
            //Gets the color to match the diagnosis button
            $('#' + obj.id).css({"background-color": colorMatch[selectedImagesList.length - 1]});
        }
    }

    // You need to finish this function to properly calculate the scores
    function calculateScore() {
        var matchedAnswerCount = 0;
        for (var i = 0; i < selectedImagesList.length; i++) {

            //console.log(selectedImagesList[i] + " : " + selectedDiagnosisList[i]);  DEBUG

            if (selectedImagesList[i] == selectedDiagnosisList[i]) {
                matchedAnswerCount++;

            }
        }

        if (selectedImagesList.length == MAX_ALLOWED_SELECTIONS) {
            if (matchedAnswerCount == MAX_ALLOWED_SELECTIONS) {
                // You answered correctly
                //alert("You did well!");
                window.location = 'level3.php';

            }
            else {
                // You screwed up

                for (var i = 0; i < selectedImagesList.length; i++) {

                    if (selectedImagesList[i] == selectedDiagnosisList[i]) {
                        $('#' + selectedDiagnosisList[i]).css({"background-color": "#00ff00"});
                        //$('#' + 'btn' + selectedImagesList[i]).css({"background-color": "#00ff00"});
                    }
                    else {
                        $('#' + selectedDiagnosisList[i]).css({"background-color": "#ff0000"});
                    }
                }
                alert("You got " + matchedAnswerCount + " correct and " + (MAX_ALLOWED_SELECTIONS - matchedAnswerCount) + " incorrect.");
                window.location = 'level3.php';
            }
        }
    }

    function getScore(amount) {
        var value = Math.floor((Math.random() * amount) + 1);
        var sign = Math.floor((Math.random() * 2) + 1);
        if (sign) {
            return baseScore + value;
        } else {
            return baseScore + value * (-1);
        }
    }
    /*function timeHandle() {
        if (baseScore > 50) {
            baseScore = baseScore - 10;
            setTimeout(timeHandle, 1000);
        } else {
            baseScore = 50;
        }
    }*/



</script>


<h1>Level 3: Matching Game!</h1>
<h5>Please select the images and match it to the corresponding diagnoses.</h5>
<h5>Please choose the diagnosis then the correct image. </h5>

<br>

<div class="termlist">
    <?php
    $questionList = array();
    for ($i = 0; $i < 6; $i++) {
        $question = new Question($_SESSION["gameAttemptID"], 3, 1, 0);
        array_push($questionList, $question);
    }

    // array_splice($imageList, 6);

    shuffle($questionList);


    echo("<table id='btnTable'><tbody>");

    for ($i = 0; $i < count($questionList); $i++) {
        $q = $questionList[$i]; //->getImageList()[];
        $imgList = $q->getImageList();
        echo("<input type='button'" . "id='" . $q->getQuestionID() . "_" . $imgList[0]->getImageID() . "' class='btnTable'" . "value='" . $imgList[0]->getAssociatedDiagnosis() . "' onclick='selectDiagnosisMatch(this);'/>");
    }

    echo("</tbody></table>");
    echo("<br>");
    echo("<br>");
    ?>
</div>

<div class="scroll">
    <div class="innerscroll">
        <?php
        shuffle($questionList);
        for ($i = 0; $i < count($questionList); $i++) {
            $q = $questionList[$i]; //->getImageList()[];
            if (isset($q)) {
                $imgList = $q->getImageList();
                if (isset($imgList)) {
                    echo("<div id='div" . $imgList[0]->getImageID() . "' class='imageContainer'>");

                    echo("<p><input type='button' id='btn" . $q->getQuestionID() . "_" . $imgList[0]->getImageID() . "' value='SELECT' class='imageSelector' onclick='selectImageMatch(this)'/>");

                    //echo("<input type='button' id='btnPreview" . $imgList[0]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imgList[0]->getImageFullPath() . "\")'; /></p>");

                    echo("<img class='intense' src='" . str_replace("'", "&#39;", $imgList[0]->getThumbnailFullPath()) . "' height='250' id='img" . $imgList[0]->getImageID() . "' />");
                    if (isset($_SESSION["debug"])) {
                        echo("<br />" . $imgList[0]->getAssociatedDiagnosis() . "<br />");
                    }

                    echo("</div>");
                }
            }
        }
        echo("<div id='dialog-confirm' title='Important'><p><span class='ui-icon ui-icon-alert' style='visibility: hidden; float:left; margin:0 7px 20px 0;'></span></p></div>");
        ?>
    </div>
</div>

<br> <br>

<div id="overlay" title="Image" class="overlay">
</div>

<script language="javascript" type="text/javascript">
    // imageList = <?php echo($question->toJSON()); ?>;
	window.onload = function () {
        // Intensify all images on the page.
        var element = document.getElementsByClassName('intense');
        Intense(element);
    }
</script>
<?php
require("footer.php");
?>
