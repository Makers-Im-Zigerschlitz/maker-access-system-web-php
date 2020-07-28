<?php
$db = new PDO('mysql:host=' . $mysqlhost . ';dbname=' . $mysqldb, $mysqluser, $mysqlpass);
$sqlconn = mysqli_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php"><?php echo $orgname; ?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
      <li><a href="home.php?site=dashboard"><?php echo $dict["Nav_Dashboard"]; ?></a></li>
        <li><a href="home.php?site=members"><?php echo $dict["Nav_Members"]; ?></a></li>
        <li><a href="home.php?site=docs"><?php echo $dict["Nav_Documents"]; ?></a></li>
        <li><a href="home.php?site=bookings"><?php echo $dict["Bookings"]; ?></a></li>
        <?php
          if($_SESSION["level"]>3)
          {
            echo "<li><a target='_blank' href='admin/'>".$dict["Nav_Admin_Panel"]."</a></li>";
          }
         ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
  <li><a href="home.php?site=transactions"><span class="glyphicon glyphicon-usd">
<?php
$stmt = $db->prepare('SELECT FORMAT(SUM(amount),2) total FROM tblTransactions WHERE uid = :uid');
$stmt->bindValue(':uid', $_SESSION["uid"], PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount()>0) {
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  echo $data['total'];
} else {
  echo "0";
}
?>

  </span></a></li>
  <li><a href="home.php?site=settings"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?></a></li>
  <li><a href="login/logout.php"><span class="glyphicon glyphicon-log-in"></span> <?php echo $dict["Login_Logout"]; ?></a></li>
</ul>
</div>
  </div>
</nav>
