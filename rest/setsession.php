<?php

session_start();
$name = $_POST["name"];
$value = $_POST["value"];
echo $name;
echo $value;
$_SESSION[$name] = $value;

