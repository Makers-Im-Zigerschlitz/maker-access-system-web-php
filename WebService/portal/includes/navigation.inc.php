<div class="contain-to-grid">
<div class=""></div>
  <div class="navbar">
  <nav class="top-bar" data-topbar>
    <ul class="title-area">
      <li class="name">
        <h1><a href="./"><?php echo $orgname; ?></a></h1>
      </li>
      <li class="toggle-topbar menu-icon">
        <a href="#">
          <span>Menu</span>
        </a>
      </li>
    </ul>
    <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">
        <li class=""><a href="home.php?site=news"><?php echo $dict["Nav_News"]; ?></a></li>
          <li class=""><a href="home.php?site=members"><?php echo $dict["Nav_Members"]; ?></a></li>
          <li class=""><a href="home.php?site=docs"><?php echo $dict["Nav_Documents"]; ?></a></li>
          <li class=""><a href="home.php?site=bookings"><?php echo $dict["Bookings"]; ?></a></li>
          <?php
            if($_SESSION["level"]>3)
            {
              echo "<li><a target='_blank' href='admin/'>".$dict["Nav_Admin_Panel"]."</a></li>";
            }
           ?>
          <li class=""><a href="login/logout.php"><?php echo $dict["Login_Logout"]; ?></a></li>
          <li class=""><a href="home.php?site=settings"><?php echo $dict["Nav_Settings"]; ?></a></li>
      </ul>
    </section>
  </nav>
</div>
</div>
