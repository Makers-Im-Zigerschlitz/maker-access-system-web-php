<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
<?php
session_start();
if (!isset($_SESSION["dbserver"])) {
  echo "Query not defined!";
  die();
}
$dbServer = $_SESSION["dbserver"];
$dbUser = $_SESSION["dbUser"];
$dbPassword = $_SESSION["dbPassword"];
$dbName = $_SESSION["dbName"];
$sqlconn = mysqli_connect($dbServer, $dbUser, $dbPassword, $dbName);
$query = file_get_contents("schema.sql");

$adminpw = md5($_SESSION["sysPW"]);
$query .= "INSERT INTO `tblUsers` (`uid`, `username`, `password`, `level`) VALUES ('0', 'admin', '" . $adminpw . "', '4');";
mysqli_multi_query($sqlconn, $query);
echo mysqli_error($sqlconn);
header("Location: install-3.php");
?>
  </body>
</html>
