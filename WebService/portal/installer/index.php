<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Maker Access System - Installation</title>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <?php
      $depError = false;
    ?>
  </head>
  <body>
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Maker Access System - Installation</h1>
    <p class="lead">Welcome to the MAS-Installer. This will create a config with your settings and test your database connection.</p>
      <hr class="my-4">
      <p class="lead">
        <h4>Dependency check:</h4>
        <table class="table table-dark">
          <thead>
            <tr>
              <th scope="col">Dependency</th>
              <th scope="col">OK</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">MYSQLi</th>
              <td>
                <?php
                if (function_exists('mysqli_connect') == false) {
                  echo "<p>Not Available</p>";
                  $depError = true;
                }
                else {
                  echo "<p>OK</p>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <th scope="row">PDO</th>
              <td>
                <?php
                if (extension_loaded ('PDO') == false) {
                  echo "<p>Not Available</p>";
                  $depError = true;
                }
                else {
                  echo "<p>OK</p>";
                }
                ?>
              </td>
            </tr>
          </tbody>
        </table>
      </p>
      <hr class="my-4">
    <p class="lead">
      <div class="row justify-content-center">
  <div class="col-4">
    <a class="btn btn-primary btn-lg <?php if ($depError == true) {echo "disabled";}?>" role="button" href="install-1.php">Start installation</a>
  </div>
</div>
    </p>
  </div>
</div>
  </body>
</html>
