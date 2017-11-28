<?php
session_start();
global $mainpath;
$mainpath = $_SERVER["REQUEST_URI"];
if(isset($_SESSION["username"]))
{
	echo "Logged in!";
	header("Location: home.php");
}
else
{
	echo "Logged out!";
	header("Location: login/login.php");
}
?>
