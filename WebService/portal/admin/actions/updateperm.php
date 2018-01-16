<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
$query = "TRUNCATE tblPermissions;";
mysqli_query($sqlconn,$query);

foreach ($_POST as $key => $value) {
  //echo "$key $value<br>";
  //echo "$key $value<br>";
  $identifier = explode("_",$key);
  //echo "UID = $identifier[0], DevID = $identifier[1]";
  $query = "INSERT INTO `tblPermissions` (`deviceID`, `uid`) VALUES ('". $identifier[1] ."', '". $identifier[0] ."');";
  mysqli_query($sqlconn,$query);
  //echo $query;
  header("Location: ../../home.php?site=access");
}
?>
