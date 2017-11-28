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
        <li class=""><a href="home.php?site=news"><?php echo $dict["20"]; ?></a></li>
          <li class=""><a href="home.php?site=members"><?php echo $dict["19"]; ?></a></li>
          <li class=""><a href="home.php?site=songs"><?php echo $dict["21"]; ?></a></li>
          <?php
            if($_SESSION["level"]>3)
            {
              echo "<li><a target='_blank' href='admin/'>".$dict["22"]."</a></li>";
            }
           ?>
          <li class=""><a href="login/logout.php"><?php echo $dict["23"]; ?></a></li>
          <li class=""><a href="home.php?site=settings"><?php echo $dict["31"]; ?></a></li>
      </ul>
    </section>
  </nav>
</div>
</div>
