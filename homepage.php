<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");

	$pageTitle = "Welcome to dental pathology game";
	require("header.php");

	$_SESSION["gameAttemptID"] = uniqid();


?>
  <script>
   //hide navbar for home page only
   $(window).load(function(){
      $(".navbar").css("display","none");
    });


  </script>

	<div class="container">
    <div class="row">
      <div class="col-md-12">
        <img id="logo" src="css/images/logo_invert.png" alt="logo" width="250px" />
        <h1 class="home">University of Pittsburgh Dental School Pathology Game</h1>
      </div>
    </div><!-- end logo row-->
    <div class="row">
      <!-- MODIFY FOR XS-->
      <div class="col-sm-6 col-xs-12">
        <button class="home-btn" onclick="window.location='tutorial.php';"> INSTRUCTIONS </button>
        <button class="home-btn" onclick="window.location='practice.php';"> PRACTICE </button>
        <button class="home-btn" onclick="window.location='gameattempt.php';"> PLAY </button>

      </div>
      <div class="col-sm-6 col-xs-12">
        <button class="home-btn gray" > SCORECARD </button>
        <button class="home-btn gray" > HALL OF FAME </button>
        <button class="home-btn gray" > LOGOUT </button>

      </div>
    </div>
  </div><!-- end container-->


	<!--<h1> University of Pittsburgh Dental School Pathology Game </h1>
	<h3> Welcome to the Dental Medicine Review Game!</h3>
	<h5> If this is your first time playing, select Tutorial in the navigation bar for a description of the rules.</h5>
	<h5> To begin playing level 1, use the navigation bar and select Play. </h5>
	<h5> To practice your skills, select practice, then select the chapters you wish to review.</h5>-->
