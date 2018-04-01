<?php
include "../includes/logincheck.inc.php";
include "../config/config.inc.php";
include "../includes/dictionary.$language.inc.php";

$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare("INSERT INTO `n15c_makers_auth`.`tblMessages` (`mid`, `senderUID`, `recipientUID`, `description`, `message`, `sendDate`) VALUES (NULL, :sender, :recipient, :description, :message, CURRENT_TIMESTAMP);");
$stmt->bindValue(':sender', $_SESSION["uid"], PDO::PARAM_STR);
$stmt->bindValue(':recipient', filter_input(INPUT_POST, 'recipient'), PDO::PARAM_STR);
$stmt->bindValue(':description', filter_input(INPUT_POST, 'description'), PDO::PARAM_STR);
$message = $db->quote($_POST["message"]);
//$stmt->bindValue(':message', filter_input(INPUT_POST, 'message'), PDO::PARAM_STR);
$stmt->bindValue(':message', $message, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
    header("Location: ../home.php");

} else {
    echo "Es ist ein Fehler aufgetreten: ".mysqli_error($sqlconn);
    echo "<p><a href='../'>".$dict["Nav_Return_Home"]."</a></p>";
    die();
}
 ?>
