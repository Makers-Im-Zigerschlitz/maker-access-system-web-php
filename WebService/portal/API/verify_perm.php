<?php
include "../config/config.inc.php";
include "../includes/functions.inc.php";
$tag = $_GET["tag"];
$devid = $_GET["device"];
$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass, $mysqldb);
$query = "SELECT * FROM tblPermissions INNER JOIN tblTags ON tblPermissions.uid=tblTags.uid WHERE tblPermissions.deviceID = $devid AND tblTags.tagID = $tag;";
$result = mysqli_query($sqlconn, $query);
if(mysqli_num_rows($result)>0)
{
  $response->permit = "true";
  $assoc = mysqli_fetch_assoc($result);
  $uid = $assoc["uid"];
  $action = "Authentication";
}
else {
  $response->permit = "false";
  $action = "Auth Error";
}

$rhost = whatsMyIP();

$query = "INSERT INTO `tblLogs` (`timestamp`, `action`, `uid`, `deviceID`, `r_host`, `logCategory`) VALUES (CURRENT_TIMESTAMP, '$action', '$uid', '$devid', '$rhost', 2);";
mysqli_query($sqlconn, $query);
$myJSON = json_encode($response);
echo $myJSON;
?>
