<?php
include "../config/config.inc.php";
include "../includes/functions.inc.php";
header("Content-Type: application/json; charset=UTF-8");
if (!isset($_GET["sessionID"])) {
  echo "Cannot proccess request";
  die();
}
$sessionID = $_GET["sessionID"];
$devid = $_GET["device"];
$duration = $_GET["duration"];
$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass, $mysqldb);
$query = "SELECT * FROM `tblUses` WHERE `sessionID` = '$sessionID';";
$result = mysqli_query($sqlconn, $query);
if(mysqli_num_rows($result)>0)
{
  $sessioninfo = mysqli_fetch_assoc($result);
  $query = "SELECT * FROM `tblCosts` WHERE `deviceID` = '$devid';";
  $result = mysqli_query($sqlconn,$query);
  $row = mysqli_fetch_assoc($result);
  $cost = $row["cost"];
  $usagecost = ($duration / 60) * $cost;
  $query = "UPDATE `tblUses` SET `duration` = '$duration', `cost` = '$usagecost', `active` = '0' WHERE `tblUses`.`sessionID` = '$sessionID';";
  if(mysqli_query($sqlconn, $query)){
    $response->success = true;
  }
  else {
    $response->success = false;
  }
}
else {
  $response->success = false;
}

$myJSON = json_encode($response);
echo $myJSON;
?>
