<?php
require("classes/security.php");
require("classes/dbutils.php");
/*require("classes/levelsecurity.php");*/
require("classes/question.php");
$pageTitle = "Level 2";
require("header.php");

$drupalUserID = $_SESSION["drupalUserID"];
if (isset($_GET["debug"])) {
    $_SESSION["debug"] = true;
}
?>

<script language="javascript" type="text/javascript">
    var imageList;
    var fail = 0;
    var correctAns;
    var success = 0;
    var correctImageList = null;
    var maxCorrect = 6;
    var baseScore = 750;
    var startDateTime = currentDateTime();
    hidePlay();

    //This function will determine if the selected Image is correct or not.  If it is correct it sends the info to update score as true and brings up a new question.  If it is false it sends the info to updateScoreData as //false and opens a new question.
    $(document).ready(function () {
        // Display score
        getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', 2);

        $("input[class='imageSelector']").each(function (index, element) {
            $(this).click(function () {
                var selImageID = element.id.replace('btn', '');
                var questionID = imageList[0].questionID;
                console.log("SELECTED: " + selImageID);
                var isAnswerCorrect = checkIfCorrect(selImageID);

                if (isAnswerCorrect) {
                    $(this).css({"background-color": "#00ff00"});
                    updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selImageID, false, 2, getScore(25), startDateTime, currentDateTime());
                    // updateQuestionCompletion('<?php echo $_SESSION["gameAttemptID"]; ?>','<?php echo $drupalUserID; ?>', questionID, 1);
                    //alert("That is correct!");
                    // window.location = 'level2.php';
                    success++;
                    // getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>','<?php echo $drupalUserID; ?>', 2);

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
                                            updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, selImageID, false, 2, 0, startDateTime, currentDateTime());  // upon selecting an incorrect answer,
                                            $(this).dialog("close");
                                            window.location = 'level2.php';
                                        },
                                    }
                                });
                            });
                            // window.location = 'level2.php'
                        }
                    }

                    //updateScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>','<?php echo $drupalUserID; ?>',questionID,selImageID, false, 2, 0);
                    fail++;
                    // getScoreData('<?php echo $_SESSION["gameAttemptID"]; ?>','<?php echo $drupalUserID; ?>', 2);

                }


                if (success == correctImageList.length) {
                    //alert("Congratulations!  You have successfully answered this question!")
                    updateQuestionCompletion('<?php echo $_SESSION["gameAttemptID"]; ?>', '<?php echo $drupalUserID; ?>', questionID, 2);
                    window.location = 'level2.php';
                }

                if (fail == 2) {
                    alert("You have gotten two wrong, please try again")
                    window.location = 'level2.php';

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



</script>

<div class="container">
  <div class="questions-div">
    <h3>
        <?php
        $maxCorrect = rand(2, 4);
        $numOfDistractors = (6 - $maxCorrect);

        $question = new Question($_SESSION["gameAttemptID"], 2, $maxCorrect, $numOfDistractors);
        echo("Click all the images that show</br> <h3 class='disease>'" . $question->getDiagnosisName() ."</h3>");
        ?>
    </h3>

    <div class="scores-parent">
      <div class="scores-child dark-pink"> # Levels Attempts: {num}</div>
      <div class="scores-child light-pink"> Questions Completed: {num}</div>
      <div class="scores-child dark-pink"> Number Correct: {num}</div>
      <div class="scores-child light-pink"> Your Score: {num}</div>
    </div>
  </div><!--end questions -->

  <div class="answers-div">
    <!--
    The following php code creates an image container and slider that contain the images  that are randomly generated from the database and based off of the question and answer.
    -->
    <?php
    $imageList = $question->getImageList();
    shuffle($imageList);
    for ($i = 0; $i < count($imageList); $i++) {
        echo("<div id='div" . $imageList[$i]->getImageID() . "' class='imageContainer'>");

        echo("<p><div class='selectors' style='width:55%;'><input type='button' id='btn" . $imageList[$i]->getImageID() . "' value='SELECT' class='imageSelector' /></div>");
        echo("<div class='selectors'style='width:45%;'><img src='dentalimages/magnify.png' class='magnify' /></div>");
        //echo("<input type='button' id='btnPreview" . $imageList[$i]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imageList[$i]->getImageFullPath() . "\")'; /></p>");

        echo("<img class='intense' src='" . str_replace("'", "&#39;", $imageList[$i]->getThumbnailFullPath()) . "' height='250' id='img'" . $imageList[$i]->getImageID() . "' />");
        if ($imageList[$i]->isCorrectChoice() == 1 && isset($_SESSION["debug"])) {
            echo("<br />correct<br />");
        }
        echo("</div>");
    }
    echo("<div id='dialog-confirm' title='Important'><p><span class='ui-icon ui-icon-alert' style='visibility: hidden; float:left; margin:0 7px 20px 0;'></span></p></div>");
    ?>
  </div>
</div><!--end container

<div class="scroll">
    <div class="innerscroll">

    </div>
</div>-->

<script language="javascript" type="text/javascript">
    imageList = <?php echo($question->toJSON()); ?>;
    correctImageList = getCorrectImageList();
    console.log(imageList);
	window.onload = function () {
        // Intensify all images on the page.
        var element = document.getElementsByClassName('intense');
        Intense(element);
    }
</script>

<?php
require("footer.php");
?>
