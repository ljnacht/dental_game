<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");	
	
	$pageTitle = "Leaderboard";
	require("header.php");	
	
	$_SESSION["gameAttemptID"] = uniqid();

   
?>			
	<script src="js/tablebuilder.js" type="text/javascript"></script>
	<script src="js/dental.js" type="text/javascript"></script>	
	
	<h1> University of Pittsburgh Dental School Pathology Game </h1> <!-- This is the title of the game -->
	<h3> Top 5 High scores</h3>
	<center><div class id=standings></div></center>
	
<?php
require("footer.php");	
?>

<script language="javascript" type="text/javascript">
	window.onload = getTopFive();
	</script>