<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");	
	
	$pageTitle = "Incorrect: Level 2";
	require("header.php");
?>



	<h1> Sorry, Try Again! </h1>
	<br/>
	<p class="center"> 
		<a href="level2.php" class="btn btn-primary">Retry</a>
	</p>
<?php
require("footer.php");
?>