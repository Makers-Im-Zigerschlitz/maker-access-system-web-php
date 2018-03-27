<!DOCTYPE html>
<html lang="en" class="dashboard">

<head>
  <meta charset="UTF-8">
  <?php
  include "includes/logincheck.inc.php";
  if ($_SESSION["level"] < 3) {
      header("Location: noaccess.php");
      die();
  }
  include "../config/config.inc.php";
  include "../includes/dictionary.$language.inc.php";
  include "../includes/functions.inc.php";
  $db = new PDO('mysql:host=' . $mysqlhost . ';dbname=' . $mysqldb, $mysqluser, $mysqlpass);
  ?>
  <title><?php echo $orgname; ?> - <?php echo $dict["Gen_Administration"]; ?></title>
  <link rel="stylesheet" href="../css/normalize.css" type="text/css">
  <link rel='stylesheet prefetch' href='css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/style.css" type="text/css">

  <script src="js/prefixfree.min.js"></script>
    <?php
    if (!isset($_GET["site"])) {
      header("Location: index.php?site=dashboard");
    }
     ?>
     <script type="text/javascript">
         function copyuname() {
             document.createuser.username.value = document.createuser.surname.value.toLowerCase() + "." + document.createuser.lastname.value.toLowerCase();
         }
     </script>
</head>

<body>

  <header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <li class="users"><a href="javascript:window.close();">Back to MAS</a></li>
    <li class="logout warn"><a href="../login/logout.php">Log Out</a></li>
  </ul>
</header>

<nav role="navigation">
  <ul class="main">
    <li class="dashboard"><a href="?site=dashboard">Dashboard</a></li>
    <li class="users"><a href="?site=users">Manage Users</a></li>
    <li class="documents"><a href="?site=documents">Edit Documents</a></li>
    <li class="posts"><a href="?site=posts">Manage Posts</a></li>
    <li class="devices"><a href="?site=devices">Manage Devices</a></li>
    <li class="tags"><a href="?site=tags">Manage Tags</a></li>
  </ul>
</nav>
<main role="main">
  <?php
  if (isset($_GET["message"])) {
      echo "<div class='feedback'>";
      if ($_GET["message"] == "usercreated") {
          echo "<p>" . $dict["User_Create_Success"] . ": " . $_GET["username"] . "</p>";
      } elseif ($_GET["message"] == "userdeleted") {
          echo "<p>" . $dict["User_Delete_Success"] . "</p>";
      } elseif ($_GET["message"] == "uploadok") {
          echo "<p>" . $dict["Doc_Upload_Success"] . "</p>";
      } elseif ($_GET["message"] == "wrongext") {
          echo "<p>" . $dict["Doc_Upload_Filetype_Denied"] . ": " . $_GET["filext"] . "</p>";
      } elseif ($_GET["message"] == "file_exists") {
          echo "<p>" . $dict["Doc_File_Exists"] . "</p>";
      } elseif ($_GET["message"] == "filedeleted") {
          echo "<p>" . $dict["Doc_File_Deleted"] . "</p>";
      } elseif ($_GET["message"] == "devicecreated") {
          echo "<p>" . $dict["Dev_Create_Success"] . "</p>";
      } elseif ($_GET["message"] == "devicedeleted") {
          echo "<p>" . $dict["Dev_Delete_Success"] . " " . $_GET["permdeleted"] . " " . $dict["Dev_Delete_Perm_Success"] . "</p>";
      } elseif ($_GET["message"] == "postcreated") {
          echo "<p>" . $dict["Post_Create_Success"] . "</p>";
      } elseif ($_GET["message"] == "postdeleted") {
          echo "<p>" . $dict["Post_Delete_Success"] . "</p>";
      } elseif ($_GET["message"] == "tagcreated") {
            echo "<p>" . $dict["Tag_Created"] . "</p>";
      }
      echo "</div>";
  }
  ?>
  <?php if ($_GET["site"] == "dashboard"): ?>
    <section class="panel important">
      <iframe src="show_log.php" width="100%" height="500px"></iframe>
    </section>
<?php endif; ?>
<?php if ($_GET["site"] == "users"): ?>
<section class="panel important">
  <h2><?php echo $dict["User_Delete"]; ?></h2>
  <table border>
      <tr>
          <th><?php echo $dict["User_Surname"]; ?></th>
          <th><?php echo $dict["User_Lastname"]; ?></th>
          <th><?php echo $dict["User_Birthday"]; ?></th>
          <th><?php echo $dict["Login_Username"]; ?></th>
          <th><?php echo $dict["User_Mail"]; ?></th>
          <th><?php echo $dict["User_City"]; ?></th>
          <th><?php echo $dict["User_Country"]; ?></th>
          <th><?php echo $dict["User_Membership_Begin"]; ?></th>
          <th><?php echo $dict["User_Membership_End"]; ?></th>
          <th><?php echo $dict["User_Delete"]; ?></th>
      </tr>
      <?php
      $stmt = $db->query('SELECT * FROM tblMembers ORDER BY Lastname ASC');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $stmttwo = $db->prepare('SELECT * FROM tblUsers WHERE uid LIKE :uid');
          $stmttwo->bindValue(':uid', $temp['uid'], PDO::PARAM_STR);
          $stmttwo->execute();
          while ($memberdata = $stmttwo->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . $temp["Firstname"] . "</td>";
              echo "<td>" . $temp["Lastname"] . "</td>";
              echo "<td>" . sqltodate($temp["Birthday"]) . "</td>";
              echo "<td>" . $memberdata["username"] . "</td>";
              echo "<td>" . $temp["Mail"] . "</td>";
              echo "<td>" . $temp["City"] . "</td>";
              echo "<td>" . $temp["Country"] . "</td>";
              echo "<td>" . sqltodate($temp["Membership_Start"]) . "</td>";
              echo "<td>" . sqltodate($temp["Membership_End"]) . "</td>";
              echo "<td><a href='actions/deleteuser.php?user=" . $temp["uid"] . "'>Delete</a>";
          }
      }
      ?>
  </table>
</section>
<section class="panel important">
  <h2><?php echo $dict["User_Create"]; ?></h2>
  <form name="createuser" action="actions/createuser.php" method="post">
      <table>
          <tr><td><input required type="text" name="surname" onchange="copyuname();" placeholder="<?php echo $dict["User_Surname"]; ?>"></td></tr>
          <tr><td><input required type="text" name="lastname" onchange="copyuname();" placeholder="<?php echo $dict["User_Lastname"]; ?>"></td></tr>
          <tr><td><input required type="date" name="birthday" placeholder="<?php echo $dict["User_Birthday"]; ?> DD.MM.YYYY"></td></tr>
          <tr><td><input required type="text" name="phone" placeholder="<?php echo $dict["User_Phone"]; ?>"></td></tr>
          <tr><td><input required type="text" name="mail" placeholder="<?php echo $dict["User_Mail"]; ?>"></td></tr>
          <tr><td><input required type="text" name="street" placeholder="<?php echo $dict["User_Street"]; ?>"></td></tr>
          <tr><td><input required type="text" name="zip" placeholder="<?php echo $dict["User_ZIP"]; ?>"></td></tr>
          <tr><td><input required type="text" name="city" placeholder="<?php echo $dict["User_City"]; ?>"></td></tr>
          <tr><td><input required type="text" name="country" placeholder="<?php echo $dict["User_Country"]; ?>"></td></tr>
          <tr><td><input required type="date" name="mem_start" placeholder="<?php echo $dict["Membership_Start"]; ?>"></td></tr>
          <tr><td><input type="date" name="mem_end" placeholder="<?php echo $dict["Membership_End"]; ?>"></td></tr>
          <tr><td><input required type="text" name="username" placeholder="<?php echo $dict["Login_Username"]; ?>"></td></tr>
          <tr><td><input required type="password" name="password" placeholder="<?php echo $dict["Login_Passwort"]; ?>"></td></tr>
          <tr><td><input required type="submit" name="submit" value="<?php echo $dict["User_Create"]; ?>"></td></tr>
      </table>
  </form>
</section>
<?php endif; ?>
<?php if ($_GET["site"] == "documents"): ?>
<section class="panel">
  <div class="twothirds">
  <h2><?php echo $dict["Doc_Upload_Document"]; ?></h2>
  <br>
  <form enctype="multipart/form-data" action="actions/upload.php" method="post">
      <input required type="text" name="filename" Placeholder="<?php echo $dict["Doc_Filename"]; ?>">
      <input required type="file" name="file" dropzone="copy">
      <br><input type="submit" name="upload" value="<?php echo $dict["Doc_Upload"]; ?>">
  </form>
  </div>
</section>
<section class="panel">
  <div class="onethird">
  <h2><?php echo $dict["Doc_Delete_Files"]; ?></h2>
  <table border>
      <tr>
          <th><?php echo $dict["Doc_Title"]; ?></th>
          <th><?php echo $dict["Doc_Filename"]; ?></th>
          <th><?php echo $dict["Doc_Delete_Files"]; ?></th>
      </tr>
      <?php
      $stmt = $db->query('SELECT * FROM tblUploads');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>" . $temp["title"] . "</td>";
          echo "<td>" . $temp["filename"] . "</td>";
          echo "<td><a href='actions/deletefile.php?filename=" . $temp["filename"] . "'>Delete</a>";
      }
      ?>
  </table>
  </div>
</section>
<?php endif; ?>
<?php if ($_GET["site"] == "posts"): ?>
<section class="panel">
  <div class="twothirds">
  <h2><?php echo $dict["News_Create_Entry"]; ?></h2>
  <form action="actions/createpost.php" method="post">
      <input required type="text" name="posttitle" placeholder="<?php echo $dict["News_Title"]; ?>">
      <br>
      <!-- <input required type='textarea' name="text" placeholder="<?php echo $dict["News_Text"]; ?>"> -->
      <textarea required name="text" rows="8" cols="50" placeholder="<?php echo $dict["News_Text"]; ?>"></textarea>
      <input type="submit" name="submit" value="<?php echo $dict["Post_Send"]; ?>">
  </form>
  </div>
</section>
<section class="panel">
  <h2><?php echo $dict["Post_Posts"]; ?></h2>
  <table border>
      <tr>
          <th><?php echo $dict["News_Title"]; ?></th>
          <th><?php echo $dict["News_Text"]; ?></th>
          <th><?php echo $dict["Post_Delete_Post"]; ?></th>
      </tr>
      <?php
      $stmt = $db->query('SELECT * FROM tblNews ORDER BY nid DESC');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>" . $temp["title"] . "</td>";
          if (strlen($temp["text"]) > 20) {
              echo "<td>" . substr($temp["text"], 0, 20) . "...</td>";
          } else {
              echo "<td>" . $temp["text"] . "</td>";
          }
          echo "<td><a href='actions/deletepost.php?nid=" . $temp["nid"] . "'>Delete</a>";
      }
      ?>
  </table>
</section>
<?php endif; ?>
<?php if ($_GET["site"] == "devices"): ?>
<section class="panel">
  <div class="twothirds">
  <h2><?php echo $dict["Dev_Create"]; ?></h2>
  <form action="actions/createdevice.php" method="post">
      <input required type="text" name="deviceName" placeholder="<?php echo $dict["Dev_Name"]; ?>">
      <br>
      <textarea required name="deviceDesc" rows="4" cols="30" placeholder="<?php echo $dict["Dev_Description"]; ?>"></textarea>
      <input type="submit" name="submit" value="<?php echo $dict["Dev_Create"]; ?>">
  </form>
  </div>
</section>
<section class="panel">
  <h2><?php echo $dict["Dev_Devices"]; ?></h2>
  <table border>
      <tr>
          <th><?php echo $dict["Dev_ID"]; ?></th>
          <th><?php echo $dict["Dev_Name"]; ?></th>
          <th><?php echo $dict["Dev_Description"]; ?></th>
          <th><?php echo $dict["Dev_Delete"]; ?></th>
      </tr>
      <?php
      $stmt = $db->query('SELECT * FROM tblDevices ORDER BY deviceID');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>" . $temp["deviceID"] . "</td>";
          echo "<td>" . $temp["deviceName"] . "</td>";
          echo "<td>" . $temp["deviceDesc"] . "</td>";
          echo "<td><a href='actions/deletedevice.php?deviceID=" . $temp["deviceID"] . "'>Delete</a>";
          echo "</tr>";
      }
      ?>
  </table>
</section>
<?php endif; ?>
<?php if ($_GET["site"] == "tags"): ?>
<section class="panel">
  <h2>Tags</h2>
  <table border>
      <tr>
          <th><?php echo $dict["Tag_ID"]; ?></th>
          <th><?php echo $dict["Login_Username"]; ?></th>
          <th><?php echo $dict["Dev_Delete"]; ?></th>
      </tr>
      <?php
      $stmt = $db->query('SELECT tblTags.tagID, tblTags.uid, tblUsers.uid, tblUsers.username FROM tblTags, tblUsers WHERE tblTags.uid=tblUsers.uid;');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>" . $temp["tagID"] . "</td>";
          echo "<td>" . $temp["username"] . "</td>";
          echo "<td><a href='actions/deletetag.php?tagID=" . $temp["tagID"] . "'>Delete</a>";
          echo "</tr>";
      }
      ?>
  </table>
</section>
<section class="panel">
  <h2>Tag erstellen</h2>
  <div class="twothirds">
<form action="actions/createtag.php" method="post">
  <label for="tagID"><?php echo $dict["Tag_Enter_ID"]; ?></label>
  <input type="text" name="tagID" id="tagID">
  <label for='userChoose'><?php echo $dict["Tag_Assoc_User"]; ?></label>
  <select name="user" id="userChoose">
    <?php
      $stmt = $db->query('SELECT username, uid FROM tblUsers;');
      while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $temp["uid"] . "'>" . $temp["username"] . "</option>";
      }
     ?>
  </select>
  <input type="submit" value="<?php echo $dict["Tag_Create"]; ?>">
</form>
  </div>
</section>
<?php endif; ?>
</main>
</body>
</html>
