<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.$language.inc.php";

$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("DELETE FROM tblTransactions WHERE transactionid=:id");
$stmt->bindValue(':id', filter_input(INPUT_GET, 'transactionid'), PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
    header("Location: ../index.php?site=transactions&message=transactiondeleted_success");
} else {
    header("Location: ../index.php?site=transactions&message=transactiondeleted_fail");
}
 ?>
