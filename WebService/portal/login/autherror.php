<!DOCTYPE html>
<html>
<head>
	<?php
	include "../config/config.inc.php";
	include "../includes/dictionary.$language.inc.php"; ?>
	<title><?php echo $dict["3"];?></title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,900">
	<link rel="stylesheet" href="autherror.css">
</head>
<body class="page-404">
  <div class="container">
    <div class="message animated bounceIn">
      <div class="display-table">
        <div class="display-table-cell">
          <svg class="icon icon-left icon-x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg>
        </div>
        <div class="display-table-cell">
          <h1><?php echo $dict["4"] ?></h1>
          <h2><a href="../index.php"><?php echo $dict["5"] ?></h2>
        </div>
      </div>
    </div>
  </div>
  <svg class="icons" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><symbol viewBox="0 0 512 512" id="icon-x"><title>x</title><path d="M438.393 374.595L319.757 255.977l118.62-118.63-63.782-63.74-118.6 118.618-118.62-118.603-63.768 63.73 118.64 118.63L73.62 374.626l63.73 63.768 118.65-118.66 118.65 118.645z"/></symbol></svg>
</body>
</html>
</body>
</html>
