<?php
session_start();
if(isset($_SESSION["username"]))
{
  include "../config/config.inc.php";
  include "../includes/functions.inc.php";
  $sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
  $rhost = whatsMyIP();
  $uid = $_SESSION["uid"];
  $query = "INSERT INTO `tblLogs` (`timestamp`, `action`, `uid`, `deviceID`, `r_host`, `logCategory`) VALUES (CURRENT_TIMESTAMP, 'Logout', '$uid', 'WebUI', '$rhost', 1);";
  mysqli_query($sqlconn, $query);
}

session_destroy();
header("Location: ../index.php");
?>
