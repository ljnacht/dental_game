<?php

// reset level access for cheating.
require("classes/security.php");
require("classes/dbutils.php");
require("classes/question.php");
require("header.php");

$pageTitle = "Caught Cheating";
?>
<script language="javascript" type="text/javascript">
    hidePlay();
</script>

<h1> You were caught cheating, you must start over  </h1>
<br/>
<p class="center"> 
    <a href="gameattempt.php" class="btn btn-primary">Start Over</a>
    <a href="practice.php" class="btn btn-primary">Practice Mode</a> 
</p>	
<?php

require("footer.php");
?>