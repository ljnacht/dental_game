<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");

	$pageTitle = "Tutorial";
	require("header.php");

	$_SESSION["gameAttemptID"] = uniqid();

?>
<div class="container">
  <div class="row rules-row">
    <div class="col-md-12">
      <h1> University of Pittsburgh </br> Dental School Pathology Game </h1>
      <!-- This is the title of the game -->
      <h2> Rules of the Game </h2>
    </div>
  </div>

  <div class="row rules-row">
    <div class="col-md-4 level-col">
      <h3> Level 1 </h3>
			<div class="question-box rules">
	      <ul class="instructions">
	        <li>You will be asked to match the diagnosis to an image. </br> </br></li>
	        <li>There will be 5 questions in this level.  </br> </br></li>
	        <li>There is only one correct answer to each question. </br> </br></li>
	        <li>If you choose 1 incorrect image, it will bring up a new question.  </br> </br></li>
	        <li>After 2 failed questions, you will be kicked out of the level. </br> </br></li>
	        <li>If you answer all 5 questions correctly, you will receive a bonus.</li>
	      </ul>
			</div>
    </div>

    <div class="col-md-4 level-col">
      <h3> Level 2 </h3>
			<div class="question-box rules">
	      <ul class="instructions">
	        <li>You will be asked to click on all the images that match the diagnosis. </br> </br></li>
	        <li>There will be 4 questions in this level.  </br> </br></li>
	        <li>There are 1-4 correct answers to each question.  </br> </br></li>
	        <li>If you choose 2 incorrect images, it will bring up a new question. </br> </br> </li>
	        <li>After 1 failed question, you will be kicked out of the level.  </br> </br></li>
	        <li>If you answer 3 out of 4 questions correctly, you will receive a bonus.</li>
	      </ul>
			</div>
    </div>

    <div class="col-md-4 level-col">
      <h3> Level 3 </h3>
			<div class="question-box rules">
	      <ul class="instructions">
	        <li>You will be asked to match the diagnosis to the appropriate image.   </br> </br></li>
	        <li>There will be 3 questions in this level.  </br> </br></li>
	        <li>If you match the images incorrectly, you will be kicked out of the level.  </br> </br></li>
	        <li>If you answer all 3 questions correctly, you will receive a bonus.</li>
	      </ul>
			</div>
    </div>
  </div>
</div>


<?php
	require("footer.php");
?>
