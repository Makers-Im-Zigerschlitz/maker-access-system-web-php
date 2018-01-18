<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.inc.php";
include "../../includes/functions.inc.php";
echo "Creating DB";
$db = new PDO('mysql:host=localhost;dbname='.$mysqldb, $mysqluser, $mysqlpass);
echo "Database created";
$stmt = $db->prepare("INSERT INTO tblDevices(deviceName,deviceDesc) VALUES (:deviceName,:deviceDesc)");
$stmt->bindValue(':deviceName', $_POST["deviceName"], PDO::PARAM_STR);
$stmt->bindValue(':deviceDesc', $_POST["deviceDesc"], PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0) {
	header("Location: ../index.php?message=devicecreated");
}
?>