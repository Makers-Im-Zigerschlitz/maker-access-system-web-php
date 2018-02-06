<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        <link rel="stylesheet" type="text/css" href="../css/adminpanel.css">
        <link rel="stylesheet" type="text/css" href="../css/normalize.css">

        <script type="text/javascript">
            function copyuname() {
                document.createuser.username.value = document.createuser.surname.value.toLowerCase() + "." + document.createuser.lastname.value.toLowerCase();
            }
        </script>
    </head>
    <body>
        <div class="settings">
            <?php
            if (isset($_GET["message"])) {
                echo "<div class='notification'>";
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
                }
                echo "</div>";
            }
            ?>            
            <div class="setframe">
                <h3><?php echo $dict["User_Delete"]; ?></h3>
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
            </div>
            <div class="setframe">
                <h3><?php echo $dict["User_Create"]; ?></h3>
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
            </div>
            <div class="setframe">
                <h3><?php echo $dict["Doc_Upload_Document"]; ?></h3>
                <form enctype="multipart/form-data" action="actions/upload.php" method="post">
                    <input required type="text" name="filename" Placeholder="<?php echo $dict["Doc_Filename"]; ?>">
                    <input required type="file" name="file" dropzone="copy">
                    <br><input type="submit" name="upload" value="<?php echo $dict["Doc_Upload"]; ?>">
                </form>
            </div>
            <div class="setframe">
                <h3><?php echo $dict["Doc_Delete_Files"]; ?></h3>
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
            <div class="setframe">
                <h3><?php echo $dict["News_Create_Entry"]; ?></h3>
                <form action="actions/createpost.php" method="post">
                    <input required type="text" name="posttitle" placeholder="<?php echo $dict["News_Title"]; ?>">
                    <br>
                    <!-- <input required type='textarea' name="text" placeholder="<?php echo $dict["News_Text"]; ?>"> -->
                    <textarea required name="text" rows="8" cols="50" placeholder="<?php echo $dict["News_Text"]; ?>"></textarea>
                    <input type="submit" name="submit" value="<?php echo $dict["Post_Send"]; ?>">
                </form>
            </div>
            <div class="setframe">
                <h3><?php echo $dict["Post_Posts"]; ?></h3>
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
            </div>
            <div class="setframe">
                <h3><?php echo $dict["Dev_Create"]; ?></h3>
                <form action="actions/createdevice.php" method="post">
                    <input required type="text" name="deviceName" placeholder="<?php echo $dict["Dev_Name"]; ?>">
                    <br>
                    <textarea required name="deviceDesc" rows="4" cols="30" placeholder="<?php echo $dict["Dev_Description"]; ?>"></textarea>
                    <input type="submit" name="submit" value="<?php echo $dict["Dev_Create"]; ?>">
                </form>
            </div>
            <div class="setframe">
                <h3><?php echo $dict["Dev_Devices"]; ?></h3>
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
            </div>
        </div>
    </body>
</html>
