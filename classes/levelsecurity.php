<?php
include("rest/getaccess.php");

// echo("CURRENT SESSION: " . $_SESSION["drupalUserID"]);
if ($accessCollection[0]["levelAccess"] != basename($_SERVER['PHP_SELF'])) {
    // echo("Incorrect Level!");
    header("Location: caught_cheating.php");
}
?>