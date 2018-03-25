<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Refresh" content="20">
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
      include "../includes/categories.inc.php";
$db = new PDO('mysql:host='.$mysqlhost.';dbname='.$mysqldb, $mysqluser, $mysqlpass);
     ?>
    <title><?php echo $orgname; ?> - Logs</title>
    <link rel="stylesheet" type="text/css" href="../css/log_viewer.css">
     <script src="../js/logViewer.js"></script>
  </head>
  <body>
    <table class="table-fill">
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
        echo "<td class='text-left'>".$members[$logentry["uid"]]."</td>";
        echo "<td class='text-left'>".$logentry["deviceID"]."</td>";
        echo "<td class='text-left'>".$logentry["r_host"]."</td>";
        echo "<td class='text-left'>".$categories[$logentry["logCategory"]]."</td>";
        echo "</tr>";
      }
     ?>
     </tbody>
     </table>
  </body>
</html>
