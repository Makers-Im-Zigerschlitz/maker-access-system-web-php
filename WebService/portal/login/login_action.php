<?php
session_start();
include "../config/config.inc.php";
include "../includes/functions.inc.php";
$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
$username = $_POST["username"];
$password = md5($_POST["password"]);
$rhost = whatsMyIP();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    //you need to exit the script, if there is an error
    exit();
}
$query = "SELECT * FROM $mysqldb.tblUsers WHERE username LIKE '$username' AND password = '$password';";
//echo $query;
$result = mysqli_query($sqlconn,$query);
$sqlfetch = mysqli_fetch_array($result);

if ($sqlfetch["username"] == "") {
  $query = "INSERT INTO `tblLogs` (`timestamp`, `action`, `deviceID`, `r_host`, `logCategory`) VALUES (CURRENT_TIMESTAMP, 'Login Error', 'WebUI', '$rhost', 1);";
  mysqli_query($sqlconn, $query);
	header("Location: autherror.php");
	die();
}
else
{
  $_SESSION["uid"] = $sqlfetch["uid"];

  $query = "SELECT * FROM $mysqldb.tblMembers WHERE uid LIKE '" . $_SESSION["uid"] . "'";
  $result = mysqli_query($sqlconn,$query);
  $memberdata = mysqli_fetch_array($result);

  $_SESSION["firstname"] = $memberdata["Firstname"];
  $_SESSION["lastname"] = $memberdata["Lastname"];
	$_SESSION["username"] = $username;
	$_SESSION["level"] = $sqlfetch["level"];

  $uid = $_SESSION["uid"];
  $query = "INSERT INTO `tblLogs` (`timestamp`, `action`, `uid`, `deviceID`, `r_host`, `logCategory`) VALUES (CURRENT_TIMESTAMP, 'Login', '$uid', 'WebUI', '$rhost', 1);";
  mysqli_query($sqlconn, $query);
	header("Location: ../home.php");
}
?>
