<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.inc.php";
include "../../includes/functions.inc.php";

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);

$deviceName = sql_replace($_POST["deviceName"]);
$deviceDesc = sql_replace($_POST["deviceDesc"]);

$query = "INSERT INTO `tblDevices` (`deviceName`, `deviceDesc`) VALUES ('".$deviceName."', '".$deviceDesc."');";
$result = mysqli_query($sqlconn,$query);
if($result)
{
  header("Location: ../index.php");
}
echo mysqli_error($sqlconn);
echo "<br>".$query;
?>
