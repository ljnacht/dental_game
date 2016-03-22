<?php
	session_start();
	$_SESSION["drupalUserID"] = "INVALID";
	$_SESSION["drupalUserName"] = "INVALID";
	$_SESSION["drupalUserEmail"] = "INVALID";
	
	$_SESSION["gameAttemptID"] = "";
	
	session_unset();
	session_destroy();
	
	require("header.php");
	
	
?>



	
				
				
	<h3> 
	Logged Out
	</h3>
				
	
				
	
   
<?php
require("footer.php");
?>
