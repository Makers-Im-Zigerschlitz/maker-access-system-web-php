<?php
include "../includes/logincheck.inc.php";
include "../config/config.inc.php";
$pw1 = $_POST["pw1"];
$pw2 = $_POST["pw2"];
if($pw1 != $pw2)
{
  echo "PW not IO";
  header("Location: ../index.php");
  die();
}
$pw1 = md5($pw1);
$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
$query = "UPDATE `tblUsers` SET `password` = '$pw1' WHERE `tblUsers`.`username` = '".$_SESSION["username"]."';";
$result = mysqli_query($sqlconn,$query);
if ($result) {
  header("Location: ../home.php?site=settings&message=pwchanged");
}
else {
  echo mysqli_error($sqlconn);
}
 ?>
