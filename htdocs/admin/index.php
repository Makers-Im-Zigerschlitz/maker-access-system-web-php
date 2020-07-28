<!DOCTYPE html>
<html lang="en" class="logs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  include "includes/logincheck.inc.php";
  if ($_SESSION["level"] < 3) {
      header("Location: noaccess.php");
      die();
  }
  include "../config/config.inc.php";
  include "../includes/dictionary.$language.inc.php";
  include "../includes/functions.inc.php";
  include "../includes/categories.inc.php";
  $db = new PDO('mysql:host=' . $mysqlhost . ';dbname=' . $mysqldb, $mysqluser, $mysqlpass);
  $sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
  ?>
  <title><?php echo $orgname; ?> - <?php echo $dict["Gen_Administration"]; ?></title>
  <link rel="stylesheet" href="../css/normalize.css" type="text/css">
  <link rel='stylesheet prefetch' href='css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
  <script src="../js/jquery.min.js" type="text/javascript"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
  <script src="../js/logViewer.js"></script>
  <script src="js/prefixfree.min.js"></script>
    <?php
    if (!isset($_GET["site"])) {
      header("Location: index.php?site=logs");
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
    <li class="logs"><a href="?site=logs">Logs</a></li>
    <li class="users"><a href="?site=users">Manage Users</a></li>
    <li class="documents"><a href="?site=documents">Edit Documents</a></li>
    <li class="posts"><a href="?site=posts">Manage Posts</a></li>
    <li class="devices"><a href="?site=devices">Manage Devices</a></li>
	  <li class="permissions"><a href="?site=permissions">Manage Permissions</a></li>
    <li class="tags"><a href="?site=tags">Manage Tags</a></li>
    <li class="transactions"><a href="?site=transactions">Manage Transactions</a></li>
  </ul>
</nav>
<main role="main">
  <?php
  if (isset($_GET["message"])) {
      echo "<div class='feedback'>";
      if ($_GET["message"] == "usercreated") {
          echo $dict["User_Create_Success"] . ": " . $_GET["username"];
      } elseif ($_GET["message"] == "userdeleted") {
          echo $dict["User_Delete_Success"];
      } elseif ($_GET["message"] == "uploadok") {
          echo $dict["Doc_Upload_Success"];
      } elseif ($_GET["message"] == "wrongext") {
          echo $dict["Doc_Upload_Filetype_Denied"] . ": " . $_GET["filext"];
      } elseif ($_GET["message"] == "file_exists") {
          echo $dict["Doc_File_Exists"];
      } elseif ($_GET["message"] == "filedeleted") {
          echo $dict["Doc_File_Deleted"];
      } elseif ($_GET["message"] == "devicecreated") {
          echo $dict["Dev_Create_Success"];
      } elseif ($_GET["message"] == "devicedeleted") {
          echo $dict["Dev_Delete_Success"] . " " . $_GET["permdeleted"] . " " . $dict["Dev_Delete_Perm_Success"];
      } elseif ($_GET["message"] == "postcreated") {
          echo $dict["Post_Create_Success"];
      } elseif ($_GET["message"] == "postdeleted") {
          echo $dict["Post_Delete_Success"];
      } elseif ($_GET["message"] == "tagcreated") {
          echo $dict["Tag_Created"];
      } elseif ($_GET["message"] == "tagdeleted") {
            echo $dict["Tag_Deleted"];
      } elseif ($_GET["message"] == "permmodified") {
            echo $dict["Dev_Modify_Perm_Success"];
      } elseif ($_GET["message"] == "transaction_success") {
            echo $dict["Trans_Add_Money_Success"];
      } elseif ($_GET["message"] == "transaction_fail") {
            echo $dict["Trans_Transaction_Fail"];
      } elseif ($_GET["message"] == "transactiondeleted_success") {
            echo $dict["Trans_Delete_Transaction_Success"];
      } elseif ($_GET["message"] == "transactiondeleted_fail") {
            echo $dict["Trans_Delete_Transaction_Fail"];


      } else {
			echo "Message not defined";
	  }
      echo "</div>";
  }
  ?>
  <?php if ($_GET["site"] == "logs"): ?>
    <section class="panel important">
      <table class="table-fill" id="logbook">
      <thead>
        <tr>
          <th class="text-left">Status</th>
          <th class="text-left">Log ID</th>
          <th class="text-left">Timestamp</th>
          <th class="text-left">Action</th>
          <th class="text-left">User</th>
          <th class="text-left">Device ID</th>
          <th class="text-left">IP Address</th>
		  <th class="text-left">
            Category
              <select id="catSelect" onchange="catChange();" name="category" value="Unknown">
                <option value="all">All</option>
                <?php
                  foreach ($categories as $key => $value) {
                    if (isset($_GET["cat"])) {
                      if ($_GET["cat"] == $key) {
                        echo "<option selected='selected' value='$key'>$value</option>";
                      }
                      else {
                        echo "<option value='$key'>$value</option>";
                      }
                    }
                    else {
                      echo "<option value='$key'>$value</option>";
                    }
                  }
                 ?>
              </select>
          </th>
        </tr>
      </thead>
    <?php
    $stmt = $db->query('SELECT uid,username FROM tblUsers');
    while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $members[$temp["uid"]] = $temp["username"];
      }
      if (!isset($_GET["cat"])) {
        $stmt = $db->query('SELECT * FROM tblLogs ORDER BY logID DESC');
      }
      else {
        $tempcat = $_GET["cat"];
        $stmt = $db->query('SELECT * FROM tblLogs WHERE logCategory = '.$tempcat.' ORDER BY logID DESC');
      }

      echo "<tbody class='table-hover'>";
        while ($logentry = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        if (strpos($logentry["action"], "Error") !== false) {
            echo "<td><img class='status' src='../img/error.png' alt=''></td>";
        }
        else {
          echo "<td><img class='status' src='../img/ok.png' alt=''></td>";
        }
        echo "<td class='text-left'>".$logentry["logID"]."</td>";
        echo "<td class='text-left'>".$logentry["timestamp"]."</td>";
        echo "<td class='text-left'>".$logentry["action"]."</td>";
        echo "<td class='text-left'>";
        if (isset($members[$logentry["uid"]])){
          echo $members[$logentry["uid"]];
        } else {
          echo "";
        }
        echo "</td>";
        echo "<td class='text-left'>".$logentry["deviceID"]."</td>";
        echo "<td class='text-left'>".$logentry["r_host"]."</td>";
        echo "<td class='text-left'>".$categories[$logentry["logCategory"]]."</td>";
        echo "</tr>";
      }
     ?>
     </tbody>
     </table>
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
              echo "<td><a href='actions/deleteuser.php?user=" . $temp["uid"] . "'><i class='fas fa-trash'></i> Delete</a>";
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
          echo "<td><a href='actions/deletefile.php?filename=" . $temp["filename"] . "'>Delete</a></td>";
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
<?php if ($_GET["site"] == "permissions"): ?>

<section class="panel">
  <div class="twothirds">
  	<h2><?php echo $dict["Nav_Access_System"]; ?></h2>
	<?php
	if ($_SESSION["level"] > 2) {
		echo('<div><p><a href="?site=logs">Recent Log activity</a></p></div>');
		echo('<form action="actions/updateperm.php" method="post">');
	}
	?>
		<table>
			<th>Name</th>
			<?php
			$stmt = $db->query('SELECT * FROM tblDevices ORDER BY deviceName ASC');
			while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<th>" . $temp['deviceName'] . "</th>";
			}

			$stmtuser = $db->query('SELECT * FROM tblMembers ORDER BY Lastname ASC');
			while ($user = $stmtuser->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>";
				echo "<td>" . $user['Firstname'] . " " . $user['Lastname'] . "</td>";

				$substmt = $db->query('SELECT * FROM tblDevices ORDER BY deviceName ASC');
				while ($dev = $substmt->fetch(PDO::FETCH_ASSOC)) {
					$stmtperm = $db->prepare('SELECT * FROM tblPermissions WHERE uid=:uid AND deviceID=:devid');
					$stmtperm->bindValue(':uid', $user['uid'], PDO::PARAM_STR);
					$stmtperm->bindValue(':devid', $dev['deviceID'], PDO::PARAM_STR);
					$stmtperm->execute();
					$row_count = $stmtperm->rowCount();
					if ($row_count > 0) {
						echo "<td id='chkbxc'><input type=checkbox name='" . $user["uid"] . "_" . $dev["deviceID"] . "' checked></td>";
					} else {
						echo "<td id='chkbxc'><input type=checkbox name='" . $user["uid"] . "_" . $dev["deviceID"] . "' ></td>";
					}
				}
				echo "</tr>";
			}
		echo "</table>";
		if ($_SESSION["level"] > 2) {
		echo "<input class='button' type='submit' value='Update'>";
		echo "</form>";
		}
		?>
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
<?php if ($_GET["site"] == "transactions"): ?>
<section class="panel important">
  <div class="twothirds">
  <h2><?php echo $dict["Trans_Name"]; ?></h2>
  <table border>
      <tr>
          <th><?php echo $dict["Trans_ID"]; ?></th>
          <th>User ID</th>
          <th><?php echo $dict["Trans_Amount"]; ?></th>
          <th><?php echo $dict["Trans_Description"]; ?></th>
          <th><?php echo $dict["Gen_Timestamp"]; ?></th>
          <th>Actions</th>
      </tr>
      <?php
      $stmt_trans = $db->query('SELECT * FROM tblTransactions ORDER BY timestamp');
      if ($stmt_trans->rowCount()>0) {
        while ($temp_trans = $stmt_trans->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $temp_trans["transactionid"] . "</td>";
            echo "<td>" . $temp_trans["uid"] . "</td>";
            echo "<td>" . $temp_trans["amount"] . "</td>";
            echo "<td>" . $temp_trans["description"] . "</td>";
            echo "<td>" . $temp_trans["timestamp"] . "</td>";
            echo "<td><a href='actions/deletetransaction.php?transactionid=" . $temp_trans["transactionid"] . "'><i class='fas fa-trash'></i> ".$dict["Trans_Delete_Transaction"]."</a></td>";
            echo "</tr>";
        }
      } else {
          echo "<tr><td colspan='5'>No Transactions found.</td></tr>";
  }
      ?>
  </table>
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#addMoney"><?php echo $dict["Trans_Add_Money"]?></button>
  <div id="addMoney" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $dict["Trans_Add_Money"]?></h4>
        </div>
        <div class="modal-body">
          <form class="" action="actions/add_money.php" method="post">
            <div class="form-group">
              <label for="recipient">Recipient:</label>
              <select class="form-control" name="recipient" id="recipient">
                  <?php
                    $query = "SELECT * FROM `tblMembers`;";
                    $result = mysqli_query($sqlconn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='".$row["uid"]."'>\n";
                      echo $row["Firstname"]." ".$row["Lastname"];
                      echo "\n</option>\n";
                    }
                  ?>
              </select>
            </div>
            <div class="form-group">
              <label for="description">Description:</label>
              <select id="description" name="description" class="form-control">
                <option selected value="Cash">Cash</option>
                <option value="Bank payment">Bank payment</option>
              </select>
            </div>
            <div class="form-group">
              <label for="amount">Amount:</label>
              <textarea id="amount" class="form-control" type="text" name="amount" placeholder="Amount"></textarea>
            </div>
            <input class="form-control btn btn-primary" type="submit" name="submit">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  </div>
</section>
<?php endif; ?>
</main>
</body>
</html>
