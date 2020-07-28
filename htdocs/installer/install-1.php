<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Maker Access System - Step 1</title>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
  </head>
  <body>
    <div class="container">
      <div class="jumbotron">
        <h1 class="display-4">MySQL-Configuration</h1>
        <hr class="my-4">
        <?php if(!isset($_POST["sent"])) : ?>
        <form class="" action="install-1.php" method="post">
          <div class="form-group">
            <label for="dbServer">MySQL-Server</label>
            <input type="text" class="form-control" name="dbServer" id="dbServer" placeholder="Enter Database Server">
          </div>
          <div class="form-group">
            <label for="dbUser">MySQL Username</label>
            <input type="text" class="form-control" name="dbUser" id="dbUser" placeholder="Enter MySQL Username">
          </div>
          <div class="form-group">
            <label for="dbPassword">MySQL Password</label>
            <input type="password" class="form-control" name="dbPassword" id="dbPassword" placeholder="Enter MySQL Password">
          </div>
          <div class="form-group">
            <label for="dbName">MySQL Database</label>
            <input type="text" class="form-control" name="dbName" id="dbName" placeholder="Enter Database Name">
          </div>
          <hr class="my-4">
          <div class="form-group">
            <label for="sysPW">Password of Adminuser</label>
            <input type="text" class="form-control" name="sysPW" id="sysPW" placeholder="Enter Admin Password">
          </div>
          <div class="form-group">
            <label for="OrganizationName">Name of Organization (Title of Pages, etc.)</label>
            <input type="text" class="form-control" name="OrganizationName" id="OrganizationName" placeholder="Enter Name of Organization">
          </div>
          <div class="form-group">
            <label for="Slogan">Enter Slogan of your Organization</label>
            <input type="text" class="form-control" name="Slogan" id="Slogan" placeholder="Enter Slogan for Site">
          </div>
          <div class="form-group">
            <label for="StdLang">Enter Standard Language of your Site (de/en)</label>
            <input type="text" class="form-control" name="StdLang" id="StdLang" placeholder="Enter de or en">
          </div>
          <div class="form-group">
            <input type="checkbox" class="form-check-input" name="FSSL" id="FSSL" checked>
            <label class="form-check-label" for="FSSL">Force SSL on Loginpage?</label>
          </div>
          <input type="hidden" name="sent">
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      <?php else :
        $dbserver = $_POST["dbServer"];
        $dbUser = $_POST["dbUser"];
        $dbPassword = $_POST["dbPassword"];
        $dbName = $_POST["dbName"];
        $conn = new mysqli($dbserver, $dbUser, $dbPassword, $dbName);
        // Check connection
        if ($conn->connect_error) {
           echo "<p class='lead'><div class='alert alert-danger' role='alert'>Connection failed: " . $conn->connect_error . "</div>";
           echo "<button onclick='window.history.back();' class='btn btn-warning'>Return to prevoius site</button>";
           echo "</p>";
        }
        else {
          echo "<p class='lead'><div class='alert alert-success' role='alert'>Connected successfully</div>";
          echo "<a href='install-2.php' class='btn btn-dark'>Install database</a>";
          echo "</p>";
          session_start();
          $_SESSION["dbserver"] = $dbserver;
          $_SESSION["dbUser"] = $dbUser;
          $_SESSION["dbPassword"] = $dbPassword;
          $_SESSION["dbName"] = $dbName;
          $_SESSION["sysPW"] = $_POST["sysPW"];
          $configtemp = file_get_contents("../config/config.inc.php.example");
          $configtemp = str_replace("OrganizationName", $_POST["OrganizationName"], $configtemp);
          $configtemp = str_replace("Slogan", $_POST["Slogan"], $configtemp);
          $configtemp = str_replace("localhost", $dbserver, $configtemp);
          $configtemp = str_replace("__USER__", $dbUser, $configtemp);
          $configtemp = str_replace("__PASS__", $dbPassword, $configtemp);
          $configtemp = str_replace("__DATABASE__", $dbName, $configtemp);
          if($_POST["StdLang"] == "de" || $_POST["StdLang"] == "en")
          {
            $configtemp = str_replace("__Language__", $_POST["StdLang"], $configtemp);
          }
          else {
            $configtemp = str_replace("__Language__", "en", $configtemp);
          }
          $configtemp = str_replace("_FSSLSet__", $_POST["FSSL"], $configtemp);
          file_put_contents("../config/config.inc.php", $configtemp);
          //echo $configtemp;
        }
        ?>
      <?php endif; ?>
      </div>
    </div>
  </body>
</html>
