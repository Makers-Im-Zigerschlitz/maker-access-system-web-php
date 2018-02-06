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
$stmt = $db->prepare("DELETE FROM tblMembers WHERE uid=:uid");
$stmt->bindValue(':uid', filter_input(INPUT_GET, 'user'), PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount()>0) {
    //Delete MAS-User
    $stmt = $db->prepare("DELETE FROM tblUsers WHERE uid=:uid");
    $stmt->bindValue(':uid', filter_input(INPUT_GET, 'user'), PDO::PARAM_STR);
    $stmt->execute();
    
    //Delete Permissions
    $stmt = $db->prepare("DELETE FROM tblPermissions WHERE uid=:uid");
    $stmt->bindValue(':uid', filter_input(INPUT_GET, 'user'), PDO::PARAM_STR);
    $stmt->execute();
    $perms = $stmt->rowCount();
    
    //Delete Tags
    $stmt = $db->prepare("DELETE FROM tblTags WHERE uid=:uid");
    $stmt->bindValue(':uid', filter_input(INPUT_GET, 'user'), PDO::PARAM_STR);
    $stmt->execute();
    $tags = $stmt->rowCount();
    
    header("Location: ../index.php?message=userdeleted&permdeleted=".$perms."&tagsdeleted=".$tags);
    
} else {
    echo "Es ist ein Fehler aufgetreten: ".mysqli_error($sqlconn);
    echo "<p><a href='../'>".$dict["Nav_Return_Home"]."</a></p>";
    die();    
}
 ?>
