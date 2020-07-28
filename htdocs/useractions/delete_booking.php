<?php
include "../includes/logincheck.inc.php";
include "../config/config.inc.php";

foreach ($_POST as $key => $value) {
  echo $key." ".$value."<br>";
}
$uid = $_SESSION["uid"];
$evtID = $_GET["evtid"];

$sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
$query = "SELECT * FROM `tblBookings` WHERE `uid` = $uid;";
$result = mysqli_query($sqlconn, $query);
$permit = false;
while ($row = mysqli_fetch_assoc($result)) {
  if ($row["evtID"] == $evtID) {
    $permit = true;
  }
}

if ($permit) {
  $db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
  $stmt = $db->prepare("DELETE FROM `tblBookings` WHERE `evtID` = :evtID ");
  $stmt->bindValue(':evtID', $evtID ,PDO::PARAM_STR);
  $stmt->execute();
  if ($stmt->rowCount()>0) {
  	header("Location: ../home.php?site=bookings&deleted");
  }
  else {
    header("Location: ../home.php?site=bookings&error");
  }
}
else {
  header("Location: ../home.php?site=bookings&error");
}

 ?>
