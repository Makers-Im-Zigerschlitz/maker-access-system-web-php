<?php
include "../../includes/logincheck.inc.php";
include "../../config/config.inc.php";
include "../../includes/dictionary.$language.inc.php";

$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("INSERT INTO `tblTransactions` (`uid`, `description`, `amount`, `timestamp`) VALUES (:uid, :description, :amount, CURRENT_TIMESTAMP);");
$stmt->bindValue(':uid', filter_input(INPUT_POST, 'recipient'), PDO::PARAM_STR);
$stmt->bindValue(':description', filter_input(INPUT_POST, 'description'), PDO::PARAM_STR);
$stmt->bindValue(':amount', filter_input(INPUT_POST, 'amount'), PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
    header("Location: ../index.php?site=transactions&message=transaction_success");

} else {
    header("Location: ../index.php?site=transactions&message=transaction_fail");
}
 ?>
