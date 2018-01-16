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
    <title><?php echo $orgname; ?> - Administration</title>
    <link rel="stylesheet" type="text/css" href="../css/adminpanel.css">
    <link rel="stylesheet" type="text/css" href="../css/normalize.css">

    <!-- javascript messages -->
    <?php
    if(isset($_GET["message"]))
    {
      if($_GET["message"] == "ucreated")
      {
        echo "<script type='text/javascript'>alert('".$dict["36"].": ".$_GET["username"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "udeleted")
      {
        echo "<script type='text/javascript'>alert('".$dict["37"].": ".$_GET["username"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "uploadok")
      {
        echo "<script type='text/javascript'>alert('".$dict["35"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "wrongext")
      {
        echo "<script type='text/javascript'>alert('".$dict["42"].": ".$_GET["filext"]."'); document.location='index.php';</script>";
      }
      elseif($_GET["message"] == "file_exists")
      {
        echo "<script type='text/javascript'>alert('".$dict["43"]."'); document.location='index.php';</script>";
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
    <h3><?php echo $dict["11"];?></h3>
    <table border>
      <tr>
        <th><?php echo $dict["7"];?></th>
        <th><?php echo $dict["8"];?></th>
        <th><?php echo $dict["9"];?></th>
        <th><?php echo $dict["1"];?></th>
        <th><?php echo $dict["40"];?></th>
        <th><?php echo $dict["41"];?></th>
        <th><?php echo $dict["11"];?></th>
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
        echo "<td><a href='actions/deleteuser.php?user=".$temp["uid"]."'>Delete</a>";
      }
     ?>
    </table>
</div>
  <div class="setframe">
      <h3><?php echo $dict["10"];?></h3>
      <form name="createuser" action="actions/createuser.php" method="post">
      <table>
        <tr><td><input required type="text" name="surname" onchange="copyuname();" placeholder="<?php echo $dict["7"];?>"></td></tr>
        <tr><td><input required type="text" name="lastname" placeholder="<?php echo $dict["8"];?>"></td></tr>
        <tr><td><input required type="date" name="birthday" placeholder="<?php echo $dict["9"];?> DD.MM.YYYY"></td></tr>
        <tr><td><input required type="text" name="phone" placeholder="<?php echo $dict["40"];?>"></td></tr>
        <tr><td><input required type="text" name="mail" placeholder="<?php echo $dict["41"];?>"></td></tr>
        <tr><td><input required type="text" name="username" placeholder="<?php echo $dict["1"];?>"></td></tr>
        <tr><td><input required type="password" name="password" placeholder="<?php echo $dict["2"];?>"></td></tr>
        <tr><td><input required type="submit" name="submit" value="<?php echo $dict["10"];?>"></td></tr>
      </table>
      </form>
  </div>
<div class="setframe">
<h3><?php echo $dict["12"];?></h3>
<form enctype="multipart/form-data" action="actions/upload.php" method="post">
  <input required type="text" name="filename" Placeholder="<?php echo $dict["13"];?>">
  <input required type="file" name="file" dropzone="copy">
  <br><input type="submit" name="upload" value="<?php echo $dict["14"];?>">
</form>
</div>
<div class="setframe">
<h3><?php echo $dict["18"];?></h3>
<table border>
  <tr>
    <th><?php echo $dict["16"];?></th>
    <th><?php echo $dict["13"];?></th>
    <th><?php echo $dict["18"];?></th>
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
  <h3><?php echo $dict["24"];?></h3>
<form action="actions/createpost.php" method="post">
<input required type="text" name="posttitle" placeholder="<?php echo $dict["25"];?>">
<br>
<!-- <input required type='textarea' name="text" placeholder="<?php echo $dict["26"];?>"> -->
<textarea required name="text" rows="8" cols="50" placeholder="<?php echo $dict["26"];?>"></textarea>
<input type="submit" name="submit" value="<?php echo $dict["27"];?>">
</form>
</div>
<div class="setframe">
<h3><?php echo $dict["28"];?></h3>
<table border>
  <tr>
    <th><?php echo $dict["25"];?></th>
    <th><?php echo $dict["26"];?></th>
    <th><?php echo $dict["29"];?></th>
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
</div>
  </body>
</html>
