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

$query = "DELETE FROM `tblUploads` WHERE `tblUploads`.`filename` LIKE '".$_GET["filename"]."';";
// echo $query;
$result = mysqli_query($sqlconn,$query);
// TODO: Delete File
if($result)
{
  header("Location: ../");
}
else {
  echo mysqli_error($sqlconn);
}
 ?>
