<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";

$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("TRUNCATE tblPermissions");
$stmt->execute();

if (filter_input_array(INPUT_POST)==NULL) {
    header("Location: ../../home.php?site=access");
    die();
} else {
    foreach (filter_input_array(INPUT_POST) as $key => $value) {
        $identifier = explode("_",$key);
        $stmt = $db->prepare("INSERT INTO tblPermissions (deviceID,uid) VALUES (:deviceID,:uid)");
        $stmt->bindValue(':deviceID', $identifier[1], PDO::PARAM_STR);
        $stmt->bindValue(':uid', $identifier[0], PDO::PARAM_STR);
        $stmt->execute();
    }
    header("Location: ../../home.php?site=access");      
}
?>
