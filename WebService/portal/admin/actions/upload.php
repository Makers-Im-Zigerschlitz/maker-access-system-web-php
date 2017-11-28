<?php
include "../includes/logincheck.inc.php";
if($_SESSION["level"] <3)
{
  header("Location: ../noaccess.php");
  die();
}
include "../../config/config.inc.php";
include "../../includes/dictionary.inc.php";

$sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);

if ($_POST["filename"] == "")
{
  header("Location: ../");
  die();
}

$dname = explode(".",$_FILES["file"]["name"]);
$dnamesize = count($dname);
$upfile_ext = $dname[$dnamesize-1];
$newfilename = str_replace(" ","",htmlspecialchars($_FILES["file"]["name"]));

// NOTE: Check for Duplicate
$query = "SELECT * FROM `tblUploads` WHERE filename = '$newfilename';";
$result = mysqli_query($sqlconn, $query);
if (mysqli_num_rows($result) > 0) {
  header("Location: ../index.php?message=file_exists");
  die();
}
echo $query;
echo $upfile_ext;
if($_FILES["file"]["size"]>0 && $upfile_ext =="pdf")
{
  copy($_FILES["file"]["tmp_name"],"../../fileadmin/sheets/".$newfilename);
  $query = "INSERT INTO `tblUploads` (`upid`, `filename`, `title`, `uploader`) VALUES (NULL, '".$newfilename."', '".$_POST["filename"]."', '".$_SESSION["username"]."');";
  $result = mysqli_query($sqlconn,$query);
  if($result)
  {
    header("Location: ../index.php?message=uploadok");
  }
  else {
    echo mysqli_error($sqlconn);
  }
}
else {
  header("Location: ../index.php?message=wrongext&filext=$upfile_ext");
}
 ?>
