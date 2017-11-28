<?php
session_start();
include "../config/config.inc.php";
$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
echo mysql_error();
$username = $_POST["username"];
$password = md5($_POST["password"]);
$query = "SELECT * FROM $mysqldb.tblUsers WHERE username LIKE '$username' AND password = '$password';";
// echo $query;
$result = mysqli_query($sqlconn,$query);
$sqlfetch = mysqli_fetch_array($result);

if ($sqlfetch["username"] == "") {
	header("Location: autherror.php");
	die();
}
else
{
	$_SESSION["username"] = $username;
	$_SESSION["level"] = $sqlfetch["level"];
	header("Location: ../home.php");
}
?>
