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

$posttitle = sql_replace($_POST["posttitle"]);
$posttext = sql_replace($_POST["text"]);

$query = "INSERT INTO `tblNews` (`nid`, `title`, `text`, `author`) VALUES (NULL, '".$posttitle."', '".$posttext."', '".$_SESSION["username"]."');";
$result = mysqli_query($sqlconn,$query);
if($result)
{
  header("Location: ../index.php?message=postcreated");
}
echo mysqli_error($sqlconn);
echo "<br>".$query;
?>
