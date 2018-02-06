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
$stmt = $db->prepare("INSERT INTO tblUsers (username,password,level) VALUES (?,?,1)");
$stmt->bindValue(1, filter_input(INPUT_POST, 'username'), PDO::PARAM_STR);
$stmt->bindValue(2, md5(filter_input(INPUT_POST, 'password')), PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0) {
    foreach($db->query('SELECT * FROM tblUsers WHERE username LIKE "'.filter_input(INPUT_POST, 'username').'";') as $row) {
        $uid=$row['uid'];
        $stmt = $db->prepare("INSERT INTO tblMembers (uid,Firstname,Lastname,Birthday,Phone,Mail,Street,ZIP,City,Country,Membership_Start,Membership_End) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bindValue(1, $uid, PDO::PARAM_STR);
        $stmt->bindValue(2, filter_input(INPUT_POST, 'surname'), PDO::PARAM_STR);
        $stmt->bindValue(3, filter_input(INPUT_POST, 'lastname'), PDO::PARAM_STR);
        $stmt->bindValue(4, filter_input(INPUT_POST, 'birthday'), PDO::PARAM_STR);
        $stmt->bindValue(5, filter_input(INPUT_POST, 'phone'), PDO::PARAM_STR);
        $stmt->bindValue(6, filter_input(INPUT_POST, 'mail'), PDO::PARAM_STR);
        $stmt->bindValue(7, filter_input(INPUT_POST, 'street'), PDO::PARAM_STR);
        $stmt->bindValue(8, filter_input(INPUT_POST, 'zip'), PDO::PARAM_STR);
        $stmt->bindValue(9, filter_input(INPUT_POST, 'city'), PDO::PARAM_STR);
        $stmt->bindValue(10, filter_input(INPUT_POST, 'country'), PDO::PARAM_STR);
        $stmt->bindValue(11, filter_input(INPUT_POST, 'mem_start'), PDO::PARAM_STR);
        $stmt->bindValue(12, filter_input(INPUT_POST, 'mem_end'), PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount()>0) {
            header("Location: ../index.php?message=usercreated&username=".filter_input(INPUT_POST, 'username'));
        }
    }
}
 ?>
