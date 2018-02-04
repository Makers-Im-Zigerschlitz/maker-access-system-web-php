<?php

include "../includes/logincheck.inc.php";
if ($_SESSION["level"] < 3) {
    header("Location: ../noaccess.php");
    die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.$language.inc.php";

$sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);

if (filter_input(INPUT_POST, 'filename') == "") {
    header("Location: ../");
    die();
}

$dname = explode(".", $_FILES["file"]["name"]);
$dnamesize = count($dname);
$upfile_ext = $dname[$dnamesize - 1];
$newfilename = str_replace(" ", "", htmlspecialchars($_FILES["file"]["name"]));

//Check for duplicates
$db = new PDO('mysql:host=localhost;dbname=' . $mysqldb, $mysqluser, $mysqlpass);
$stmt = $db->prepare('SELECT * FROM tblUploads WHERE filename=:fn');
$stmt->bindValue(':fn', $newfilename, PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    header("Location: ../index.php?message=file_exists");
    die();
}
if ($_FILES["file"]["size"] > 0 && $upfile_ext == "pdf") {
    copy($_FILES["file"]["tmp_name"], "../../fileadmin/documents/" . $newfilename);
    $stmt = $db->prepare("INSERT INTO tblUploads (filename,title,uploader) VALUES (:fn,:title,:uploader)");
    $stmt->bindValue(':fn', $newfilename, PDO::PARAM_STR);
    $stmt->bindValue(':title', filter_input(INPUT_POST, 'filename'), PDO::PARAM_STR);
    $stmt->bindValue(':uploader', $_SESSION["username"], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        header("Location: ../index.php?message=uploadok");
    } else {
        echo mysqli_error($sqlconn);
    }
} else {
    header("Location: ../index.php?message=wrongext&filext=$upfile_ext");
}
?>
