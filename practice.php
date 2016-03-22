<?php
require("classes/security.php");
require("classes/dbutils.php");
require("classes/question.php");

$pageTitle = "Practice";
require("header.php");

$_SESSION["gameAttemptID"] = uniqid();
?>

<h1> University of Pittsburgh </br> Dental School Pathology Game </h1>
<!-- This is the title of the game -->
<h2 class="rules">Practice</h2>

<div id='categoryPicker'>
  <form>
    <h3> Choose from the following categories to practice your skills:</h3>

    <div class="row">
      <div class="col-md-6 col-sm-12 col-xs-12">
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value="1" id="BLT"/><label onclick='' class='toggle-btn'>Bone Lesions/ Tumors</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='2' id="dentAnom"/><label onclick='' class='toggle-btn'>Dental Anomalies</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='3' id="odon"/><label onclick='' class='toggle-btn'>Odontogenic Cysts/Tumors</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='4' id="soft"/><label onclick='' class='toggle-btn'>Soft Tissue Lesions/Tumors</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='6' id="devAbn"/><label onclick='' class='toggle-btn'>Developmental Abnormalities</label></div>
        </div>
      </div> <!-- end left -->

      <div class="col-md-6 col-sm-12 col-xs-12">
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='7' id="rad"/><label onclick='' class='toggle-btn'>Radiology</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='8' id="salivary"/><label onclick='' class='toggle-btn'>Salivary</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='9' id="syndromes"/><label onclick='' class='toggle-btn'>Syndromes</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='10' id="benign"/><label onclick='' class='toggle-btn'>Benign Fibro-osseous Lesions</label></div>
        </div>
        <div class='toggle-btn-grp cssonly'>
            <div><input type='checkbox' name='categories[]' value='5' id="other"/><label onclick='' class='toggle-btn'>Other</label></div>
        </div>
      </div><!--end right -->
    </div><!--end category row-->

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <h3 style="margin-top: 20px;">How many questions would you like to practice?</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class='toggle-btn-grp cssonly' align='center'>
            <div class="test"><input type='radio' name='questioncount' value='5' /><label onclick='' class='toggle-btn'>Five</label></div>
            <div><input type='radio' name='questioncount' value='10'  /><label onclick='' class='toggle-btn'>Ten</label></div>
            <div><input type='radio' name='questioncount' value='2147483647'  /><label onclick='' class='toggle-btn'>All</label></div>
        </div>
        <div align='center' class='submit'><input type='submit' value='Let&#39;s begin!'></div>
      </div>
    </div>

    </form>
</div><!--end category picker-->


<div id="pracQues">
    <h3 id="questionLine">

    </h3>


    <div class="scroll">
        <div class="innerscroll" id='questionImages'>
            <!--
            The Javascript creates an image container and slider that contain the images that are randomly generated from the database and based off of the question and answer.
            -->
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="queModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Practice Round Completed</h4>
            </div>
            <div class="modal-body">
                <p>Congratulations, you have completed a practice round. Would you
                    like to continue practicing or play the real game?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="pracButton">Practice</button>
                <button type="button" class="btn btn-default" id="playButton">Play</button>
            </div>
        </div>

    </div>
</div>

<div id="responseFeedback"></div>

<?php
require("footer.php");
?>

<script type="text/javascript">
    var correctAns;
    var questionData = {};
    var distractorData = {};
    var numAnswered = 0;
    var numQs = 4; // use this to change number of images displayed
    var questionArray = [];


    $(function () {
        $('#pracQues').hide();
        $("#playButton").click(function () {
            window.location.href = "gameattempt.php";
        });


        $('form').submit(function () {
            if (!$('input[type=checkbox]:checked').length) {
                alert("Please select at least one category.");
                return false;
            } else if (!$('input[type=radio]:checked').length) {
                alert("Please select a question amount.");
                return false;
            } else {
                var catagoryObject = {};
                if (document.getElementById('BLT').checked) {
                    catagoryObject.BLT = $('#BLT').val();
                }
                if (document.getElementById('dentAnom').checked) {
                    catagoryObject.dentAnom = $('#dentAnom').val();
                }

                if (document.getElementById('odon').checked) {
                    catagoryObject.odon = $('#odon').val();
                }

                if (document.getElementById('soft').checked) {
                    catagoryObject.soft = $('#soft').val();
                }

                if (document.getElementById('devAbn').checked) {
                    catagoryObject.devAbn = $('#devAbn').val();
                }

                if (document.getElementById('rad').checked) {
                    catagoryObject.rad = $('#rad').val();
                }

                if (document.getElementById('salivary').checked) {
                    catagoryObject.salivary = $('#salivary').val();
                }

                if (document.getElementById('syndromes').checked) {
                    catagoryObject.syndromes = $('#syndromes').val();
                }

                if (document.getElementById('benign').checked) {
                    catagoryObject.benign = $('#benign').val();
                }

                if (document.getElementById('other').checked) {
                    catagoryObject.other = $('#other').val();
                }
                catagoryObject.limit = $('.radioCount:checked').val();
                $.post("rest/getPracticeQuestions.php", catagoryObject).done(function (data) {
                    //console.log(data);
                    questionData = JSON.parse(data);
                    //console.log(questionData);
                    if (questionData.question.length == 0) {
                        alert("The selected catagory does not presently have any questions associated with it. Pick another!");
                    } else {
                        $('#categoryPicker').hide();
                        $('#pracQues').show();
                        handleQuestion();
                    }

                });
                return false;
            }
            return false;
        });

        function handleQuestion() {
            $('#questionLine').html("Which of these images represents " + questionData.question[numAnswered].diagnosisName);
            distractorData = getDistractors(questionData.question[numAnswered].questionID, numQs - 1);

        }

        function alterPage(qArray) {
            // wipe out old questions
            $("#questionImages").html("");
            for (i = 0; i < qArray.length; i++) {
                var appendString = "<div id='div" + qArray[i].imageID + "' class='imageContainer'>";
                appendString += "<p><input type='button' id='btn" + qArray[i].imageID + "' value='SELECT' class='imageSelector' />";
                appendString += "<img class='intense' src='dentalimages/" + qArray[i].imageFolder;
                appendString += "/" + qArray[i].imageName.replace("'", "&#39;");
                appendString += "' height='250' id='img'" + qArray[i].imageID + "' />";
                appendString += "</div>";
                $("#questionImages").append(appendString);
                /*                 // this needs to be converted to javascript before it can be used.
                 if ($imageList[$i] - > isCorrectChoice() == 1 && isset($_SESSION["debug"])) {
                 $("#questionImages").append("<br />correct<br />");
                 }
                 */

            }
            $("#questionImages").append("<div id='dialog-confirm' title='Important'><p><span class='ui-icon ui-icon-alert' style='visibility: hidden; float:left; margin:0 7px 20px 0;'></span></p></div>");

            bindEventHandlers();
        }

        function getDistractors(qID, numDistractors) {
            var distractorObject = {};
            distractorObject.questionID = qID;
            distractorObject.numDistractors = numDistractors;
            $.post("rest/getDistractors.php", distractorObject).done(function (data) {
                distractorData = JSON.parse(data);
                questionArray = setupArray();
                alterPage(questionArray);
            });
        }
        function setupArray() {
            var shuffArray = [];
            console.log(distractorData);
            shuffArray.push(questionData.question[numAnswered]);
            for (i = 0; i < distractorData.distractors.length; i++) {
                shuffArray.push(distractorData.distractors[i]);
            }

            shuffle(shuffArray);
            return shuffArray;
        }
        function shuffle(a) {
            for (var k, x, i = a.length; i; k = Math.floor(Math.random() * i), x = a[--i], a[i] = a[k], a[k] = x)
                ;
            return a;
        }

        function doCleanUp() {
            numAnswered = 0;
            questionData = {};
            distractorData = {};
            questionArray = [];

            $('#categoryPicker').show();
            $('#pracQues').hide();
            $("#queModal").show();
            $("#queModal").modal();

        }

        //This function will determine if the selected Image is correct or not.
        function bindEventHandlers() {
            $("input[class='imageSelector']").each(function (index, element) {
                $(this).click(function () {
                    var selImageID = element.id.replace('btn', '');
                    if (selImageID == questionData.question[numAnswered].imageID) {
                        $(this).css({"background-color": "#00ff00"});
                        alert("That is correct!");
                        numAnswered++;
                        if (numAnswered == questionData.question.length) {
                            doCleanUp();
                        } else {
                            handleQuestion();
                        }
                    }
                    else {
                        $(this).css({"background-color": "#ff0000"});

                        for (var i = 0; i < questionArray.length; i++) {
                            if (questionArray[i].imageID == selImageID) {
                                correctAns = ("This image actually represents <br>" + questionArray[i].diagnosisName);
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
                                                $(this).dialog("close");
                                                numAnswered++;
                                                if (numAnswered == questionData.question.length) {
                                                    doCleanUp();
                                                } else {
                                                    handleQuestion();
                                                }

                                            }
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
            });
        }
    });
</script>
