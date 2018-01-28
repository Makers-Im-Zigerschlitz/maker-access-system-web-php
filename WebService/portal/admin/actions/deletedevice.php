<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.inc.php";

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);

$query = "DELETE FROM `tblDevices` WHERE `tblDevices`.`deviceID` = '".$_GET["deviceID"]."';";
$result = mysqli_query($sqlconn,$query);
if(!$result)
{
  echo "Es ist ein Fehler aufgetreten: ".mysqli_error($sqlconn);
  echo "<p><a href='../'>".$dict["Nav_Return_Home"]."</a></p>";
  die();
}
else
{
  $query = "DELETE FROM `tblDevices` WHERE `tblDevices`.`deviceID` = '".$_GET["user"]."';";
  $result = mysqli_query($sqlconn,$query);
  if($result)
  {
    header("Location: ../index.php");
  }
}
 ?>
