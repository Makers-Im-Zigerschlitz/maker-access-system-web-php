<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>

  <link href="css/fullcalendar.min.css" rel="stylesheet" type="text/css">
  <script src="js/moment.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/fullcalendar.js" type="text/javascript"></script>

  <?php
  include "includes/logincheck.inc.php";
  session_regenerate_id();
  include "config/config.inc.php";
  include "includes/dictionary.$language.inc.php";
  include "includes/functions.inc.php";
  $sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);

  // Change language if request available
  if (isset($_POST["chlang"])) {
      changelang($_POST["chlang"]);
      header("Location: home.php?site=settings");
  }
  ?>
  <title><?php echo $orgname; ?> - Home</title>
</head>
<body style="padding-top: 5%">
<?php
include 'includes/nav.inc.php';
if (!isset($_GET["site"])) {
    // header("Location: " . $_SERVER["REQUEST_URI"] . "?site=news");
    // NOTE: Instead of redirecting (causing redirection errors) now settings get var manual
    $_GET["site"] = "dashboard";
}
if ($_GET["site"] == "dashboard"):?>
<div class="container">
  <div class="jumbotron">
    <h1><img style="width: 7%;" src="img/logo.png" /><?php echo $orgname; ?> <br><span> <?php echo $slogan; ?></span></h1>
    <h3><?php echo $dict["Gen_Welcome"] . " " . $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?></h3>
    <h2><?php echo $motd; ?></h2>
</div>
<h1 class="bg-primary" id="news">News</h1>
<div class="row">
    <?php
    $query = "SELECT * FROM tblNews ORDER BY nid DESC";
    $result = mysqli_query($sqlconn, $query);
    while ($dataset = mysqli_fetch_assoc($result)) {
        echo "<div class='col-lg-4'>\n<h2>";
        echo $dataset["title"];
        echo "<small>\n";
        $timestamp = strtotime($dataset["timestamp"]);
        echo date("d. M. Y", $timestamp);
        echo "\n</small>\n</h2>\n";
        echo "<p>\n";
        echo str_replace("\n", "<br>", $dataset["text"]);
        echo "\n</p>\n</div>\n";
    }
    ?>
</div>
<div class="row">
  <div class="col-lg-6">
    <h1 class="bg-primary" id="bookings">Your bookings</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Start</th>
          <th>Endzeit</th>
          <th>Titel</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $query = "SELECT * FROM `tblBookings` WHERE `uid` = '". $_SESSION["uid"] ."'";
          $result = mysqli_query($sqlconn, $query);
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["start"]."</td>";
            echo "<td>".$row["end"]."</td>";
            echo "<td>".$row["title"]."</td>";
          }
         ?>
      </tbody>
    </table>
  </div>
  <div class="col-lg-6">
    <h1 class="bg-primary" id="permissions">Your permissions</h1>
    <table class="table table-striped">
      <thead>
        <th>Device</th>
      </thead>
      <tbody>
        <?php
          $query = "SELECT * FROM `tblPermissions` WHERE `uid` = ".$_SESSION["uid"].";";
          $result = mysqli_query($sqlconn, $query);
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            $query = "SELECT * FROM `tblDevices` WHERE `deviceID` = ".$row["deviceID"].";";
            $result = mysqli_query($sqlconn,$query);
            while ($devrow = mysqli_fetch_assoc($result)) {
              echo "<td class='success'>";
              echo $devrow["deviceName"];
              echo "</td>";
            }
            echo "</tr>";
          }
         ?>
      </tbody>
    </table>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h1 class="bg-primary" id="feed">Feed</h1>
    <div class="row">
    <?php
    $query = "SELECT * FROM `tblSettings` WHERE `settingName` LIKE 'RSSUrl';";
    $result = mysqli_query($sqlconn, $query);
    $feed = mysqli_fetch_assoc($result);
    $feeds = 4;
    $html = "";
    if ($feed["settingValue"] == "") {
      $feeds = 0;
    }
    $url = $feed["settingValue"];
    $xml = simplexml_load_file($url);
    for($i = 0; $i < $feeds; $i++){
        $title = $xml->channel->item[$i]->title;
        $link = $xml->channel->item[$i]->link;
        $description = $xml->channel->item[$i]->description;
        $pubDate = $xml->channel->item[$i]->pubDate;

        $pubDate = strtotime($pubDate);
        $pubDate = date("d. M. Y", $pubDate);

        $html .= "<div class='col-lg-6'>";
        $html .= "<a href='$link'><h3>$title</h3><small>$pubDate</small></a>";
        $html .= "$description";
        $html .= "</div>";
    }
    echo $html;
     ?>
     </div>
  </div>
</div>
</div>
<?php endif;
if ($_GET["site"] == "docs"):
    ?>
    <div class="container">
    <div class='row'>
        <div class="col-lg-12">
            <h2><?php echo $dict["Nav_Documents"]; ?></h2>
            <?php
            echo "<table class='table table-striped'>";
            echo "<tr><th>" . $dict["Doc_Title"] . "</th><th>" . $dict["Doc_Open_File"] . "</th></tr>";
            $query = "SELECT * FROM `tblUploads` ORDER BY `title` DESC;";
            $result = mysqli_query($sqlconn, $query);
            while ($data = mysqli_fetch_assoc($result)) {
                echo "<tr><td>";
                echo $data["title"];
                echo "</td><td>";
                echo "<a target='_blank' href='fileadmin/documents/" . $data["filename"] . "'>" . $dict["Doc_Open_File"] . "</a></td></tr>";
            }
            ?>
            </table>
        </div>
    </div>
    </div>
<?php
endif;
if ($_GET["site"] == "members"):
    ?>
    <div class="container">
    <div class='row'>
        <div class="col-lg-12">
            <h2><?php echo $dict["Nav_Members"]; ?></h2>
            <table class="table table-striped">
                <tr>
                    <th><?php echo $dict["User_Lastname"]; ?></th>
                    <th><?php echo $dict["User_Surname"]; ?></th>
                    <?php
                    if ($_SESSION["level"] > 2) {
                        echo "<th>" . $dict["User_Birthday"] . "</th>";
                    }
                    ?>
                    <th><?php echo $dict["User_Mail"]; ?></th>
                </tr>
                <?php
                $query = "SELECT `Firstname`, `Lastname`, `Birthday`, `Mail` FROM `tblMembers` ORDER BY `Lastname` ASC";
                $result = mysqli_query($sqlconn, $query);
                while ($dataset = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>";
                    echo $dataset["Lastname"];
                    echo "</td><td>";
                    echo $dataset["Firstname"];
                    echo "</td>";
                    if ($_SESSION["level"] > 2) {
                        echo "<td>";
                        echo sqltodate($dataset["Birthday"]);
                        echo "</td>";
                    }
                    echo "<td>";
                    echo "<a href='mailto:" . $dataset["Mail"] . "'>" . $dataset["Mail"] . "</a>";
                    echo "</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    </div>
    <?php
  endif;
  if ($_GET["site"] == "settings"):
      ?>
      <div class="container">
      <script type="text/javascript">
          function changePW() {
              if (document.changepw.pw1.value != document.changepw.pw2.value) {
                  alert('Die Passwörter stimmen nicht überein!');
                  return false;
              } else {
                  return true;
              }
          }
      </script>
      <div class='row'>
              <h2><?php echo $dict["Nav_Settings"]; ?></h2>
              <div class="col-lg-6">
                <h3><?php echo $dict["Login_Change_Password"]; ?></h3>
                <?php if(isset($_GET["pwchanged"])){
                  echo "<div class='alert alert-success'>";
                  echo $dict["Login_Password_Change_Success"];
                  echo "</div>";
                } ?>
                      <form class="" name="changepw" action="useractions/changepw.php" method="post" onsubmit="return changePW();">
                        <div class="form-group">
                          <input class="form-control" required type="password" id="password" name="pw1" placeholder="<?php echo $dict["Login_New_Password"]; ?>">
                          <br>
                          <input class="form-control" required type="password" name="pw2" placeholder="<?php echo $dict["Login_Repeat_Password"]; ?>">
                          <br>
                          <div class="progress">
                           <div id="password-strength-meter" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                           </div>
                         </div>
                          <!--<meter id="password-strength-meter" max="4"></meter>-->
                          <p id="password-strength-text"></p>
                          <button class="btn btn-primary" type="submit" name="submit"><?php echo $dict["Login_Change_Password"]; ?></button>
                          </div>
                      </form>
                      <script type="text/javascript">
                          var strength = {
                              0: "Worst",
                              1: "Bad",
                              2: "Weak",
                              3: "Good",
                              4: "Strong"
                          }
                          var password = document.getElementById('password');
                          var meter = document.getElementById('password-strength-meter');
                          var text = document.getElementById('password-strength-text');

                          password.addEventListener('input', function () {
                              var val = password.value;
                              var result = zxcvbn(val);

                              // Update the password strength meter
                              //meter.value = result.score;
                              if (result.score == 0) {
                                meter.setAttribute("aria-valuenow", 0);
                                meter.setAttribute("style", "width:0%");
                              }
                              else if (result.score == 1) {
                                meter.setAttribute("aria-valuenow", 25);
                                meter.setAttribute("style", "width:25%");
                              }
                              else if (result.score == 2) {
                                meter.setAttribute("aria-valuenow", 50);
                                meter.setAttribute("style", "width:50%");
                              }
                              else if (result.score == 3) {
                                meter.setAttribute("aria-valuenow", 75);
                                meter.setAttribute("style", "width:75%");
                              }
                              else if (result.score == 4) {
                                meter.setAttribute("aria-valuenow", 100);
                                meter.setAttribute("style", "width:100%");
                              }


                              // Update the text indicator
                              if (val !== "") {
                                  text.innerHTML = "Strength: " + strength[result.score];
                              } else {
                                  text.innerHTML = "";
                              }
                          });
                      </script>
                  </div>
                  <div class="col-lg-6">
                      <h3><?php echo $dict["Nav_Change_Lang"] ?></h3>
                      <form class="form-inline" action="home.php" method="post">
                        <div class="formgroup">
                          <select class="form-control" name="chlang" required>
                              <option value="de">Deutsch</option>
                              <option value="en">Englisch</option>
                          </select>
                          <input class="btn btn-primary form-control" type="submit" class="button" name="submit" value="<?php echo $dict["Nav_Change_Lang"] ?>">
                          </div>
                      </form>
                  </div>
                </div>
          </div>
      </div>
    </div>
  <?php
endif;
if ($_GET["site"] == "bookings"):
    ?>
    <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2><?php echo $dict["Bookings"]; ?></h2>
            <?php if (isset($_GET["entrycreated"])): ?>
              <div class="alert-box success">
                <p>The entry has been created!</p>
              </div>
            <?php endif; ?>
            <?php if (isset($_GET["error"])): ?>
              <div class="alert-box alert">
                <p>An error occured while performing the action!</p>
              </div>
            <?php endif; ?>
            <?php if (isset($_GET["deleted"])): ?>
              <div class="alert-box warning">
                <p>The entry has been deleted!</p>
              </div>
            <?php endif; ?>
            <script>

              $(document).ready(function() {

                $('#calendar').fullCalendar({
                  header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaWeek,agendaDay,listWeek'
                  },
                  events: {
                    url: '/API/get_bookings.php',
                    cache: true
                  },
                  resources: '/API/get_resources.php',
                  navLinks: true, // can click day/week names to navigate views
                  eventLimit: true, // allow "more" link when too many events
                  defaultView: 'agendaWeek',
                  timeFormat: 'H:mm' // uppercase H for 24-hour clock
                });
              });
              </script>
            <div id='calendar'></div>
          </div>
            <div class="col-lg-6">
              <h2>Buchung erstellen</h2>
                <form class="horizontal-form" action="useractions/create_booking.php" method="post">
                  <div class="form-group">
                  <label for="bookingUser">Benutzername</label>
                  <select class="form-control" name="bookingUser" id="bookingUser">
                    <option value="<?php echo $_SESSION["uid"] ?>"><?php echo $_SESSION["username"];?></option>
                  </select>
                  </div>
                  <div class="form-group">
                  <label for="BookingDevice">Zu buchendes Gerät</label>
                  <select class="form-control" name="BookingDevice" id="BookingDevice">
                    <?php
                      $query = "SELECT * FROM `tblDevices`";
                      $result = mysqli_query($sqlconn,$query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row["deviceID"]."'>".$row["deviceName"]."</option>";
                      }
                     ?>
                  </select>
                </div>
                  <div class="form-group">
                    <label for="startTime">Startzeit der Reservation</label>
                    <input class="form-control" type="datetime-local" placeholder="YYYY-MM-DD HH:MM:SS" name="startTime" id="startTime">
                  </div>
                  <div class="form-group">
                    <label for="startTime">Endzeit der Reservation</label>
                    <input class="form-control" type="datetime-local" placeholder="YYYY-MM-DD HH:MM:SS" name="endTime" id="endTime">
                  </div>
                    <input class="form-control btn btn-primary" type="submit" name="sbm">
                </form>
              </div>
              <div class="col-lg-6">
                <h2>Meine Reservationen</h2>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Start</th>
                      <th>Endzeit</th>
                      <th>Titel</th>
                      <th>Loeschen</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $query = "SELECT * FROM `tblBookings` WHERE `uid` = '". $_SESSION["uid"] ."'";
                      $result = mysqli_query($sqlconn, $query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$row["start"]."</td>";
                        echo "<td>".$row["end"]."</td>";
                        echo "<td>".$row["title"]."</td>";
                        echo "<td><a href='useractions/delete_booking.php?evtid=".$row["evtID"]."'>Loeschen</a></td>";
                      }
                     ?>
                  </tbody>
                </table>
              </div>
            </div>
      </div>
<?php
endif; ?>
<br>
<footer style="background-color: #4c4c4c; padding-top: 5%; padding-bottom: 5%;" class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
              <h2 class="text-info">This system was scripted with a lot of time by <a href="https://zigerschlitzmakers.ch">Makers im Zigerschlitz</a> and <a href="http://tekkie-mag.black">n15c</a></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <h4 class="text-primary"><a href="https://github.com/n15c">Visit me on GitHub</a></h4>
          </div>
          <div class="col-lg-offset-6 col-lg-2">
            <h4 class="bg-primary">Copyright (C) 2018</h4>
          </div>
        </div>
      </div>
    </footer>
</body>
</html>
