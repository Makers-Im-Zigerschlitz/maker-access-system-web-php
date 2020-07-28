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
$stmt = $db->prepare("DELETE FROM tblTags WHERE tagID=:id");
$stmt->bindValue(':id', filter_input(INPUT_GET, 'tagID'), PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
    $stmt = $db->prepare("DELETE FROM tblPermissions WHERE tagID=:id");
    $stmt->bindValue(':id', filter_input(INPUT_GET, 'tagID'), PDO::PARAM_STR);
    $stmt->execute();
    header("Location: ../index.php?site=tags&message=tagdeleted&permdeleted=".$stmt->rowCount());

} else {
    echo "Es ist ein Fehler aufgetreten: ".mysqli_error($sqlconn);
    echo "<p><a href='../'>".$dict["Nav_Return_Home"]."</a></p>";
    die();
}
 ?>
