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

    //This function will determine if the selected Image is correct or not.  If it is correct it sends the info to update score as true and brings up a new question.  If it is false it 		sends the info to updateScoreData as //false and opens a new question.
    $(document).ready(function () {

        $("input[class='imageSelector']").each(function (index, element) {
            $(this).click(function () {
                var selImageID = element.id.replace('btn', '');
                var questionID = imageList[0].questionID;
                var isAnswerCorrect = checkIfCorrect(selImageID);
                if (isAnswerCorrect) {
                    $(this).css({"background-color": "#00ff00"});
                    alert("That is correct!");
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


</script>

<?php
$db = new DbUtilities;
?>


<h3 id="questionHint"> 
    <!--
    The following php code brings up the different questions and hints tied to each question.
    -->
    <?php
    $question = new Question($_SESSION["gameAttemptID"], 1);
    echo("Which of these images represents " . $question->getDiagnosisName() . "?<br />");
    $hint = ("Hint: " . $question->getHint() . "");
    ?>
</h3>

<p>
<div class = "hint">
    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title=  "<?php echo htmlspecialchars($hint); ?>"; data-original-title="Tooltip on top">Hint</button>
</div>
</p>


<div class="scroll">
    <div class="innerscroll">
        <!--
        The following php code creates an image container and slider that contain the images  that are randomly generated from the database and based off of the question and answer.
        -->
        <?php
        $imageList = $question->getImageList();
        array_splice($imageList, 4);
        shuffle($imageList);
        for ($i = 0; $i < count($imageList); $i++) {
            echo("<div id='div" . $imageList[$i]->getImageID() . "' class='imageContainer'>");

            echo("<p><input type='button' id='btn" . $imageList[$i]->getImageID() . "' value='SELECT' class='imageSelector' />");

            //echo("<input type='button' id='btnPreview" . $imageList[$i]->getImageID() . "' value='ZOOM IN' class='imageViewer' onclick='showImagePreview(\"" . $imageList[$i]->getImageFullPath() . "\")'; /></p>");
            //This enables the user to zoom into the picture (it opens up a thumbnail of the image).
            echo("<img class='intense' src='" . str_replace("'", "&#39;", $imageList[$i]->getThumbnailFullPath()) . "' height='250' id='img'" . $imageList[$i]->getImageID() . "' />");

            if ($imageList[$i]->isCorrectChoice() == 1 && isset($_SESSION["debug"])) {
                echo("<br />correct<br />");
            }
            echo("</div>");
        }
        echo("<div id='dialog-confirm' title='Important'><p><span class='ui-icon ui-icon-alert' style='visibility: hidden; float:left; margin:0 7px 20px 0;'></span></p></div>");
        ?>
    </div>
</div>

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