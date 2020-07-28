<?php
include "../config/config.inc.php";
include "../includes/functions.inc.php";
header("Content-Type: application/json; charset=UTF-8");
if (!isset($_GET["tag"])) {
  echo "Cannot proccess request";
  die();
}
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}
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
  $deviceID = $assoc["deviceID"];
  $action = "Authentication";
  $sessionID = random_str(60);
  $response->sessionID = $sessionID;
  $query = "INSERT INTO `tblUses` (`sessionID`, `uid`, `deviceID`, `duration`, `cost`, `active`) VALUES ('$sessionID', '$uid', '$deviceID', NULL, NULL, '1');";
  mysqli_query($sqlconn,$query);
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
