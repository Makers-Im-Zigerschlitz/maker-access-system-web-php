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

$query = "INSERT INTO `tblUsers` (`username`, `password`, `level`) VALUES ('".$_POST["username"]."', '".md5($_POST["password"])."', '1');";
$result = mysqli_query($sqlconn,$query);
if(!$result)
{
  echo "Es ist ein Fehler aufgetreten: ".mysqli_error($sqlconn);
  echo "<p><a href='../'>".$dict["5"]."</a></p>";
  die();
}
else
{
  $query = "SELECT * FROM $mysqldb.tblUsers WHERE username LIKE '". $_POST["username"] ."';";
  $result = mysqli_query($sqlconn,$query);
  $sqlfetch = mysqli_fetch_array($result);
  $uid = $sqlfetch["uid"];

  // $query = "INSERT INTO `tblMembers` (`mid`, `surname`, `lastname`, `birthday`, `username`) VALUES (NULL,'".$_POST["surname"]."','".$_POST["lastname"]."','".datetosql($_POST["birthday"])."','".$_POST["username"]."')";
  $query = "INSERT INTO `tblMembers` (`uid`, `Firstname`, `Lastname`, `Birthday`, `Phone`, `Mail`)";
  //$query .="VALUES ('$uid','".$_POST["surname"]."','".$_POST["lastname"]."','".datetosql($_POST["birthday"])."','".$_POST["phone"]."','".$_POST["mail"]."')"; //No conversion of date needed
  $query .="VALUES ('$uid','".$_POST["surname"]."','".$_POST["lastname"]."','".$_POST["birthday"]."','".$_POST["phone"]."','".$_POST["mail"]."')";
  $result = mysqli_query($sqlconn,$query);
  echo mysqli_error($sqlconn);
  if($result)
  {
    header("Location: ../index.php?message=ucreated&username=".$_POST["username"]);
  }
}
 ?>
