<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");	
	
	$pageTitle = "Incorrect";
	require("header.php");
?>

	<h1> Sorry, Try Again! </h1> 
	<br/>
		<p class="center"> 
		<a href="level1.php" class="btn btn-primary">Retry</a>
		<a href="practice.php" class="btn btn-primary">Practice Mode</a> 
		</p>	
<?php
	require("footer.php");
?>