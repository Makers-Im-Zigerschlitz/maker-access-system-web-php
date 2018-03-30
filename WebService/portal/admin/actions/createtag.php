<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.$language.inc.php";
include "../../includes/functions.inc.php";
$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("INSERT INTO tblTags(tagID,uid) VALUES (:tagID,:uid)");
$stmt->bindValue(':tagID', filter_input(INPUT_POST, 'tagID'), PDO::PARAM_STR);
$stmt->bindValue(':uid', filter_input(INPUT_POST, 'user'), PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0) {
	header("Location: ../index.php?site=tags&message=tagcreated");
}
?>
