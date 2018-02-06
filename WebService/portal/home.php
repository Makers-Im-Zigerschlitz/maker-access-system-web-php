<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="main.css">
        <link href="css/normalize.css" rel="stylesheet" type="text/css" />
        <link href="css/all.css" rel="stylesheet" type="text/css" />
        <link href="css/popups.css" rel="stylesheet" type="text/css" />

        <!--<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>-->
        <script src="js/modernizr.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
        <!--<script src="js/all.js" type="text/javascript"></script>-->
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
    <body>
        <div id="wrapper">
            <?php
            include "includes/navigation.inc.php";
            if (!isset($_GET["site"])) {
                // header("Location: " . $_SERVER["REQUEST_URI"] . "?site=news");
                // NOTE: Instead of redirecting (causing redirection errors) now settings get var manual
                $_GET["site"] = "news";
            }

            if ($_GET["site"] == "news"):
                ?>
                <div class="hero">
                    <div class="row">
                        <div class="large-12 columns centered">
                            <h1><img src="img/logo.png" /><?php echo $orgname; ?> <br><span><?php echo $slogan; ?></span></h1>
                            <h3><?php echo $dict["Gen_Welcome"] . " " . $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?></h3>
                            <h2><?php echo $motd; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="news">
                    <?php
                    $query = "SELECT * FROM tblNews ORDER BY nid DESC";
                    $result = mysqli_query($sqlconn, $query);
                    while ($dataset = mysqli_fetch_assoc($result)) {
                        echo "<div class='row article'>\n<div class='large-12 columns'>\n<h2>";
                        echo $dataset["title"];
                        echo "</h2>\n<p>\n";
                        echo str_replace("\n", "<br>", $dataset["text"]);
                        echo "\n</p>\n</div>\n</div>\n";
                    }
                    echo "</div>";
                endif;
                if ($_GET["site"] == "docs"):
                    ?>
                    <div class='row'>
                        <div class="large-12 columns">
                            <h2><?php echo $dict["Nav_Documents"]; ?></h2>
                            <?php
                            /* $handle = opendir("fileadmin/sheets");
                              while($name = readdir($handle))
                              {
                              if($name !="." && $name != "..")
                              {
                              echo "<li><a href='fileadmin/sheets/".$name."'>".$name."</a></li>";
                              }
                              } */
                            echo "<table>";
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
                <?php
                endif;
                if ($_GET["site"] == "members"):
                    ?>
                    <div class='row'>
                        <div class="large-12 columns">
                            <h2><?php echo $dict["Nav_Members"]; ?></h2>
                            <table>
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
                <?php
                endif;
                if ($_GET["site"] == "settings"):
                    ?>
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
                        <div class="large-12 columns">
                            <h2><?php echo $dict["Nav_Settings"]; ?></h2>
                            <div class="usersettings">
                                <div class="settingframe">
                                    <h3><?php echo $dict["Login_Change_Password"]; ?></h3>

                                    <div id="pwchanged" class="overlay">
                                        <div class="popup">
                                            <h2><?php echo $dict["Login_Change_Password"]; ?></h2>
                                            <a class="close" href="#">&times;</a>
                                            <div class="content">
    <?php echo $dict["Login_Password_Change_Success"]; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <form name="changepw" action="useractions/changepw.php" method="post" onsubmit="return changePW();">
                                        <input required type="password" id="password" name="pw1" placeholder="<?php echo $dict["Login_New_Password"]; ?>">
                                        <input required type="password" name="pw2" placeholder="<?php echo $dict["Login_Repeat_Password"]; ?>">
                                        <meter id="password-strength-meter" max="4"></meter>
                                        <p id="password-strength-text"></p>
                                        <button type="submit" name="submit"><?php echo $dict["Login_Change_Password"]; ?></button>
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
                                            meter.value = result.score;

                                            // Update the text indicator
                                            if (val !== "") {
                                                text.innerHTML = "Strength: " + strength[result.score];
                                            } else {
                                                text.innerHTML = "";
                                            }
                                        });
                                    </script>
                                </div>
                                <div class="settingframe">
                                    <h3><?php echo $dict["Nav_Change_Lang"] ?></h3>
                                    <form action="home.php" method="post">
                                        <select name="chlang" required>
                                            <option value="de">Deutsch</option>
                                            <option value="en">Englisch</option>
                                        </select>
                                        <input type="submit" class="button" name="submit" value="<?php echo $dict["Nav_Change_Lang"] ?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endif;
                if ($_GET["site"] == "access"):
                    ?>
                    <div class='row'>
                        <div class="large-12 columns">
                            
                            <h2><?php echo $dict["Nav_Access_System"]; ?></h2>
                            <?php
                            if ($_SESSION["level"] > 2) {
                                echo("<div><p><a href='admin/show_log.php'>Recent Log activity</a></p></div>");
                            }
                            ?>
                            <form action="admin/actions/updateperm.php" method="post">
                                <table>
                                    <?php
                                    $query = "SELECT * FROM tblDevices ORDER BY deviceName";
                                    $deviceresult = mysqli_query($sqlconn, $query);
                                    echo "<th>Name</th>";
                                    while ($device = mysqli_fetch_assoc($deviceresult)) {
                                        echo "<th>";
                                        echo $device["deviceName"];
                                        echo "</th>";
                                    }
                                    $query = "SELECT * FROM tblMembers ORDER BY Lastname";
                                    $userresult = mysqli_query($sqlconn, $query);
                                    while ($user = mysqli_fetch_assoc($userresult)) {
                                        echo "<tr>";
                                        echo "<td>" . $user["Firstname"] . " " . $user["Lastname"] . "</td>";

                                        $query = "SELECT * FROM tblDevices ORDER BY deviceName";
                                        $deviceresult = mysqli_query($sqlconn, $query);
                                        while ($device = mysqli_fetch_assoc($deviceresult)) {
                                            $query = "SELECT * FROM tblPermissions WHERE uid=" . $user["uid"] . " AND deviceID=" . $device["deviceID"] . ";";
                                            $permresult = mysqli_query($sqlconn, $query);
                                            if (mysqli_num_rows($permresult) > 0) {
                                                echo "<td id='chkbxc'><input type=checkbox name='" . $user["uid"] . "_" . $device["deviceID"] . "' checked></td>";
                                            } else {
                                                echo "<td id='chkbxc'><input type=checkbox name='" . $user["uid"] . "_" . $device["deviceID"] . "' ></td>";
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                    if ($_SESSION["level"] > 2) {
                                        echo "<input class='button' type='submit' value='Update'>";
                                    }
                                    ?>
                            </form>
                        </div>
                    </div>
<?php endif; ?>
            </div>
            <footer>
                <!-- <div class="row">
                  <div class="large-12 columns">
                    <div class="row">
                      <div class="large-4 columns"> -->
                <p class="text-right">Copyright (c) 2017 Niels Scheunemann<br>All Rights Reserved.</p>
                <!-- </div>
              </div>
            </div>
          </div> -->
            </footer>
    </body>
</html>
