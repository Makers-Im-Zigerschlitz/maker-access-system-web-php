<?php
include "../config/config.inc.php";
include "../includes/functions.inc.php";

header("Content-Type: application/json; charset=UTF-8");

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass, $mysqldb);
$query = "SELECT * FROM `tblDevices`;";
$result = mysqli_query($sqlconn, $query);
$outp = array();
$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
  $outp[$i]["id"] = $row["deviceID"];
  $outp[$i]["title"] = $row["deviceName"];
  $i++;
}
echo json_encode($outp);
?>
