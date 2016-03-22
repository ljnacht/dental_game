<?php
	require("classes/security.php");
	require("classes/dbutils.php");
	require("classes/question.php");	

	$pageTitle = "Practice";
	require("header.php");

	$_SESSION["gameAttemptID"] = uniqid();

	echo("<h1> University of Pittsburgh Dental School Pathology Game </h1>");
	echo("<h3> Practice </h3>");
	echo("<h5> Choose from the following categories to practice your skills</h5>");
	echo("<form>");
	echo("<div class='toggle-btn-grp cssonly' align='center'>");
	echo("<div><input type='checkbox' name='categories[]'/><label onclick='' class='toggle-btn'>Bone</label></div>");
	echo("</div>");
	echo("<div class='toggle-btn-grp cssonly' align='center'>");
	echo("<div><input type='checkbox' name='categories[]' value='Dental Abnormalities'/><label onclick='' class='toggle-btn'>Dental Abnormalities</label></div>");
	echo("</div>");
	echo("<div class='toggle-btn-grp cssonly' align='center'>");
	echo("<div><input type='checkbox' name='categories[]' value='Odontogenic Cysts & Tumors'/><label onclick='' class='toggle-btn'>Odontogenic Cyst & Tumors</label></div>");
	echo("</div>");
	echo("<div class='toggle-btn-grp cssonly' align='center'>");
	echo("<div><input type='checkbox' name='categories[]' value='Soft Tissue Lesions'/><label onclick='' class='toggle-btn'>Soft Tissue Lesions</label></div>");
	echo("</div>");
	echo("<h5> How many questions would you like to practice?</h5>");
	echo("<div class='toggle-btn-grp cssonly' align='center'>");
    echo("<div><input type='radio' name='questioncount' value='5'/><label onclick='' class='toggle-btn'>Five</label></div>");
    echo("<div><input type='radio' name='questioncount' value='10'/><label onclick='' class='toggle-btn'>Ten</label></div>");
    echo("<div><input type='radio' name='questioncount' value='99'/><label onclick='' class='toggle-btn'>All</label></div>");
	echo("</div>");
	echo("<div align='center' class='submit'><input type='submit' value='Let&#39;s begin!'></div>");
	echo("</form>");
	
	require("footer.php");
?>

<script type="text/javascript">
$(function() {
  $('form').submit(function(){
    $.post('/dentalgame/practice.php', function() {
      window.location = '/dentalgame/practice.php';
    });
    return false;
  });
});
</script>