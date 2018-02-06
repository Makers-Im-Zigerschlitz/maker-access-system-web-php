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
$stmt = $db->prepare("INSERT INTO tblNews (title,text,author) VALUES (?,?,?)");
$stmt->bindValue(1, filter_input(INPUT_POST, 'posttitle'), PDO::PARAM_STR);
$stmt->bindValue(2, filter_input(INPUT_POST, 'text'), PDO::PARAM_STR);
$stmt->bindValue(3, $_SESSION["username"], PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
	header("Location: ../index.php?message=postcreated");
}
?>
