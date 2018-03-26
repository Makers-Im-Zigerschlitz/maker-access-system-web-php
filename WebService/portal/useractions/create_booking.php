<?php
include "../includes/logincheck.inc.php";
include "../config/config.inc.php";

foreach ($_POST as $key => $value) {
  echo $key." ".$value."<br>";
}

$sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
$devID = $_POST["BookingDevice"];
$query = "SELECT * FROM `tblDevices` WHERE `deviceID` = $devID;";
$result = mysqli_query($sqlconn, $query);
$row = mysqli_fetch_assoc($result);
$devName = $row["deviceName"];
$title = $devName . " - " . $_SESSION["username"];
//$title ="test";

$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("INSERT INTO tblBookings(`uid`, `title`, `start`, `end`, `resourceId`) VALUES (:uid,:title,:start, :end, :resourceId)");
$stmt->bindValue(':uid', filter_input(INPUT_POST, 'bookingUser'), PDO::PARAM_STR);
//$stmt->bindValue(':title', filter_input(INPUT_POST, 'BookingDevice'), PDO::PARAM_STR);
$stmt->bindParam(':title', $title, PDO::PARAM_STR);
$stmt->bindParam(':resourceId', $devID, PDO::PARAM_STR);
$stmt->bindValue(':start', filter_input(INPUT_POST, 'startTime'), PDO::PARAM_STR);
$stmt->bindValue(':end', filter_input(INPUT_POST, 'endTime'), PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0) {
	header("Location: ../home.php?site=bookings&entrycreated");
}
else {
  header("Location: ../home.php?site=bookings&error");
}
 ?>
