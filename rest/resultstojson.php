<?php

require("../classes/dbutils.php");

$sql = $_GET["sql"];

$db = new DbUtilities;

$dataArray = $db->getDataset($sql);

echo json_encode($dataArray);

?>
