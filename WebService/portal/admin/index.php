<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
      include "includes/logincheck.inc.php";
      if($_SESSION["level"] <3)
      {
        header("Location: noaccess.php");
        die();
      }
      include "../config/config.inc.php";
      include "../includes/dictionary.$language.inc.php";
      include "../includes/functions.inc.php";
      $sqlconn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
     ?>
    <title><?php echo $orgname; ?> - <?php echo $dict["Gen_Administration"];?></title>
    <link rel="stylesheet" type="text/css" href="../css/adminpanel.css">
    <link rel="stylesheet" type="text/css" href="../css/normalize.css">

    <!-- javascript messages -->
    <?php
    if(isset($_GET["message"]))
    {
      if($_GET["message"] == "ucreated")
      {
        echo "<script type='text/javascript'>alert('".$dict["User_Create_Success"].": ".$_GET["username"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "udeleted")
      {
        echo "<script type='text/javascript'>alert('".$dict["User_Delete_Success"].": ".$_GET["username"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "uploadok")
      {
        echo "<script type='text/javascript'>alert('".$dict["Doc_Upload_Success"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "wrongext")
      {
        echo "<script type='text/javascript'>alert('".$dict["Doc_Upload_Filetype_Denied"].": ".$_GET["filext"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "file_exists")
      {
        echo "<script type='text/javascript'>alert('".$dict["Doc_File_Exists"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "devicecreated")
      {
        echo "<script type='text/javascript'>alert('".$dict["Dev_Create_Success"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "postcreated")
      {
        echo "<script type='text/javascript'>alert('".$dict["Post_Create_Success"]."'); document.location='index.php';</script>";
      }	  
    }
    ?>
    <script type="text/javascript">
      function copyuname() {
        document.createuser.username.value = document.createuser.surname.value.toLowerCase();
      }
    </script>
  </head>
  <body>
<div class="settings">
  <div class="setframe">
    <h3><?php echo $dict["User_Delete"];?></h3>
    <table border>
      <tr>
        <th><?php echo $dict["User_Surname"];?></th>
        <th><?php echo $dict["User_Lastname"];?></th>
        <th><?php echo $dict["User_Birthday"];?></th>
        <th><?php echo $dict["Login_Username"];?></th>
        <th><?php echo $dict["User_Phone"];?></th>
        <th><?php echo $dict["User_Mail"];?></th>
        <th><?php echo $dict["User_Delete"];?></th>
      </tr>
    <?php
      $query = "SELECT * FROM $mysqldb.tblMembers ORDER BY Lastname ASC";
      $result = mysqli_query($sqlconn,$query);
      //echo mysqli_num_rows($result);
      while ($temp = mysqli_fetch_assoc($result)) {
        $query = "SELECT * FROM $mysqldb.tblUsers WHERE uid LIKE '" . $temp["uid"] . "'";
        $result2 = mysqli_query($sqlconn,$query);
        $memberdata = mysqli_fetch_array($result2);

        echo "<tr>";
        echo "<td>".$temp["Firstname"]."</td>";
        echo "<td>".$temp["Lastname"]."</td>";
        echo "<td>".sqltodate($temp["Birthday"])."</td>";
        echo "<td>".$memberdata["username"]."</td>";
        echo "<td>".$temp["Phone"]."</td>";
        echo "<td>".$temp["Mail"]."</td>";
        echo "<td>".$temp["Street"]."</td>";
        echo "<td>".$temp["City"]."</td>";
        echo "<td>".$temp["Country"]."</td>";
        echo "<td><a href='actions/deleteuser.php?user=".$temp["uid"]."'>Delete</a>";
      }
     ?>
    </table>
</div>
  <div class="setframe">
      <h3><?php echo $dict["User_Create"];?></h3>
      <form name="createuser" action="actions/createuser.php" method="post">
      <table>
        <tr><td><input required type="text" name="surname" onchange="copyuname();" placeholder="<?php echo $dict["User_Surname"];?>"></td></tr>
        <tr><td><input required type="text" name="lastname" placeholder="<?php echo $dict["User_Lastname"];?>"></td></tr>
        <tr><td><input required type="date" name="birthday" placeholder="<?php echo $dict["User_Birthday"];?> DD.MM.YYYY"></td></tr>
        <tr><td><input required type="text" name="phone" placeholder="<?php echo $dict["User_Phone"];?>"></td></tr>
        <tr><td><input required type="text" name="mail" placeholder="<?php echo $dict["User_Mail"];?>"></td></tr>
        <tr><td><input required type="text" name="street" placeholder="<?php echo $dict["User_Street"];?>"></td></tr>
        <tr><td><input required type="text" name="city" placeholder="<?php echo $dict["User_City"];?>"></td></tr>
        <tr><td><input required type="text" name="country" placeholder="<?php echo $dict["User_Country"];?>"></td></tr>        
        <tr><td><input required type="text" name="username" placeholder="<?php echo $dict["Login_Username"];?>"></td></tr>
        <tr><td><input required type="password" name="password" placeholder="<?php echo $dict["Login_Passwort"];?>"></td></tr>
        <tr><td><input required type="submit" name="submit" value="<?php echo $dict["User_Create"];?>"></td></tr>
      </table>
      </form>
  </div>
<div class="setframe">
<h3><?php echo $dict["Doc_Upload_Document"];?></h3>
<form enctype="multipart/form-data" action="actions/upload.php" method="post">
  <input required type="text" name="filename" Placeholder="<?php echo $dict["Doc_Filename"];?>">
  <input required type="file" name="file" dropzone="copy">
  <br><input type="submit" name="upload" value="<?php echo $dict["Doc_Upload"];?>">
</form>
</div>
<div class="setframe">
<h3><?php echo $dict["Doc_Delete_Files"];?></h3>
<table border>
  <tr>
    <th><?php echo $dict["Doc_Title"];?></th>
    <th><?php echo $dict["Doc_Filename"];?></th>
    <th><?php echo $dict["Doc_Delete_Files"];?></th>
  </tr>
<?php
  $query = "select * from tblUploads";
  $result = mysqli_query($sqlconn,$query);
  while ($temp = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$temp["title"]."</td>";
    echo "<td>".$temp["filename"]."</td>";
    echo "<td><a href='actions/deletefile.php?filename=".$temp["filename"]."'>Delete</a>";
  }
 ?>
</table>
</div>
<div class="setframe">
  <h3><?php echo $dict["News_Create_Entry"];?></h3>
<form action="actions/createpost.php" method="post">
<input required type="text" name="posttitle" placeholder="<?php echo $dict["News_Title"];?>">
<br>
<!-- <input required type='textarea' name="text" placeholder="<?php echo $dict["News_Text"];?>"> -->
<textarea required name="text" rows="8" cols="50" placeholder="<?php echo $dict["News_Text"];?>"></textarea>
<input type="submit" name="submit" value="<?php echo $dict["Post_Send"];?>">
</form>
</div>
<div class="setframe">
<h3><?php echo $dict["Post_Posts"];?></h3>
<table border>
  <tr>
    <th><?php echo $dict["News_Title"];?></th>
    <th><?php echo $dict["News_Text"];?></th>
    <th><?php echo $dict["Post_Delete_Post"];?></th>
  </tr>
<?php
  $query = "SELECT * FROM tblNews ORDER BY nid DESC";
  $result = mysqli_query($sqlconn,$query);
  while ($temp = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$temp["title"]."</td>";
    if (strlen($temp["text"])>20)
    {
      echo "<td>".substr($temp["text"],0,20)."...</td>";
    }
    else
    {
      echo "<td>".$temp["text"]."</td>";
    }
    echo "<td><a href='actions/deletepost.php?nid=".$temp["nid"]."'>Delete</a>";
  }
 ?>
</table>
</div>
<div class="setframe">
  <h3><?php echo $dict["Dev_Create"];?></h3>
<form action="actions/createdevice.php" method="post">
<input required type="text" name="deviceName" placeholder="<?php echo $dict["Dev_Name"];?>">
<br>
<textarea required name="deviceDesc" rows="4" cols="30" placeholder="<?php echo $dict["Dev_Description"];?>"></textarea>
<input type="submit" name="submit" value="<?php echo $dict["Dev_Create"];?>">
</form>
</div>
<div class="setframe">
<h3><?php echo $dict["Dev_Devices"];?></h3>
<table border>
  <tr>
    <th><?php echo $dict["Dev_ID"];?></th>
    <th><?php echo $dict["Dev_Name"];?></th>
    <th><?php echo $dict["Dev_Description"];?></th>
    <th><?php echo $dict["Dev_Delete"];?></th>
  </tr>
<?php
  $query = "SELECT * FROM tblDevices ORDER BY deviceID";
  $result = mysqli_query($sqlconn,$query);
  while ($temp = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $temp["deviceID"] . "</td>";
    echo "<td>" . $temp["deviceName"] . "</td>";
    echo "<td>" . $temp["deviceDesc"] . "</td>";
    echo "<td><a href='actions/deletedevice.php?deviceID=".$temp["deviceID"]."'>Delete</a>";
    echo "</tr>";
  }
 ?>
</table>
</div>
</div>
  </body>
</html>
