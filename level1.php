<?php
require("classes/security.php");
require("classes/dbutils.php");
require("classes/levelsecurity.php");
require("classes/question.php");
$pageTitle = "Level 1";
require("header.php");
$drupalUserID = $_SESSION["drupalUserID"];
if (isset($_GET["debug"])) {
    $_SESSION["debug"] = true;
}
//var_dump($_SESSION) // for testing
?>


<script language="javascript" type="text/javascript">
    var imageList;
    var correctAns;
    var baseScore = 500;
    var startDateTime = currentDateTime();
    hidePlay();

    //This function will determine if the selected Image is correct or not.  If it is correct it sends the info to update score as true and brings up a new question.  If it is false it 		sends the info to updateScoreData as //false and opens a new question.
    $(document).ready(function () {
        // Display score
        getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', 1); //Loads the session/game id and the user id, sets the level to 1.
        $("input[class='imageSelector']").each(function (index, element) {
            $(this).click(function () {
                var selImageID = element.id.replace('btn', '');
                var questionID = imageList[0].questionID;
                var isAnswerCorrect = checkIfCorrect(selImageID);
                if (isAnswerCorrect) {
                    $(this).css({"background-color": "#00ff00"});
                    // Sends the score data to the updatescore.php file in the rest folder
                    updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selImageID, false, 1, getScore(25), startDateTime, currentDateTime()); //25 represents the variation in randomness

                    updateQuestionCompletion('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, 1);
                    //alert("That is correct!");
                    window.location = 'level1.php';
                }
                else {
                    $(this).css({"background-color": "#ff0000"});

                    for (var i = 0; i < imageList.length; i++) {
                        if (imageList[i].imageID == selImageID) {
                            correctAns = ("This image actually represents <br>" + imageList[i].diagnosis);
                            document.getElementById("dialog-confirm").innerHTML = correctAns;
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

                                            updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selImageID, false, 1, 0, startDateTime, currentDateTime());  // upon selecting an incorrect answer,
                                            $(this).dialog("close");
                                            window.location = 'level1.php';
                                        }
                                    }
                                });
                            });
                        }
                    }
                }
            });
        });
    });



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
    function hintPointDeduction() {
        baseScore = baseScore / 2;
        if (baseScore < 50) {
            baseScore = 50;
        }
    }


</script>

<?php
$db = new DbUtilities;
?>
<div class="container">
  <div class="questions-div">
    <div class="question-box">
      <h1 class="level-num"> Level 1: Multiple Choice </h1>
      <h3>
          <!--
          The following php code brings up the different questions and hints tied to each question.
          -->
          <?php
          $question = new Question($_SESSION["gameAttemptID"], 1);
          echo("Which of these images represents: </br> <h3 class='disease'>" . $question->getDiagnosisName() . "?<br /> </h3>");
          $hint = ("Hint: " . $question->getHint() . "");
          ?>
      </h3>
      <div class = "hint">
          <button type="button" class="hintBtn" data-toggle="tooltip" data-placement="top" title=  "<?php echo htmlspecialchars($hint); ?>"; data-original-title="Tooltip on top">HINT</button>
      </div>
    </div>

    <div class="scores-parent">
      <div class="scores-child dark"> # Levels Attempts: {num}</div>
      <div class="scores-child"> Questions Completed: {num}</div>
      <div class="scores-child dark"> Number Correct: {num}</div>
      <div class="scores-child"> Your Score: {num}</div>
    </div>
  </div><!--end questions -->

  <div class="answers-div">
    <!--
    The following php code creates an image container and slider that contain the images  that are randomly generated from the database and based off of the question and answer.
    -->
    <?php
    $imageList = $question->getImageList();
    array_splice($imageList, 4);
    shuffle($imageList);
    for ($i = 0; $i < count($imageList); $i++) {
        if($i==0 || $i==2){
          echo("<div class='answers-row'>");
        }
          echo("<div id='div" . $imageList[$i]->getImageID() . "' class='imageContainer'>");

          echo("<div class='selectors' style='width:55%;'><p><input type='button' id='btn" . $imageList[$i]->getImageID() . "' value='SELECT' class='imageSelector' /></div>");
          echo("<div class='selectors'style='width:45%;'><img src='dentalimages/magnify.png' class='magnify' /></div>");

          //echo("<input type='button' id='btnPreview" . $imageList[$i]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imageList[$i]->getImageFullPath() . "\")'; /></p>");
          //This enables the user to zoom into the picture (it opens up a thumbnail of the image).
          echo("<img class='intense' src='" . str_replace("'", "&#39;", $imageList[$i]->getThumbnailFullPath()) . "' height='250' id='img'" . $imageList[$i]->getImageID() . "' />");

          if ($imageList[$i]->isCorrectChoice() == 1 && isset($_SESSION["debug"])) {
              echo("<br />correct<br />");
          }
          echo("</div>");
        if($i==1 || $i==3){
          echo("</div>");
        }
    }
    echo("<div id='dialog-confirm' title='Important'><p><span class='ui-icon ui-icon-alert' style='visibility: hidden; float:left; margin:0 7px 20px 0;'></span></p></div>");
    ?>
  </div>
</div><!--end container -->










<script language="javascript" type="text/javascript">
    imageList = <?php echo($question->toJSON()); ?>;
    window.onload = function () {
        // Intensify all images on the page.
        var element = document.getElementsByClassName('intense');
        Intense(element);
    }
</script>

<div id="responseFeedback"></div>



<?php
require("footer.php");
?>
