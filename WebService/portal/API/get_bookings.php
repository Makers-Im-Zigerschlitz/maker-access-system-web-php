<?php
include "../config/config.inc.php";
include "../includes/functions.inc.php";

header("Content-Type: application/json; charset=UTF-8");

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass, $mysqldb);
$query = "SELECT * FROM `tblBookings`;";
$result = mysqli_query($sqlconn, $query);
$outp = array();
while ($row = mysqli_fetch_assoc($result)) {
  $outp[] = $row;
}

echo json_encode($outp);
?>
