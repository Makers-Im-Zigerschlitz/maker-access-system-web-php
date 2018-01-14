<html>
<head>
<?php
include "../config/config.inc.php";
$language = getlang($standardlanguage);
include "../includes/dictionary.$language.inc.php";
?>
<title><?php echo $orgname; ?> Portal</title>
<!--<link rel="stylesheet" type="text/css" href="main.css">
<link rel="stylesheet" type="text/css" href="../css/normalize.css">-->
<link rel="stylesheet" type="text/css" href="login.css">
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script  src="login.js"></script>
</head>
<body>
<div class="login">
<div class="wrapper">
	<div class="container">
		<h1><?php echo $orgname; ?></h1>
		<h2><?php echo $dict["0"]; ?>:</h2>
		<form class="form" action="login_action.php" method="post">
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password" placeholder="Password">
			<button type="submit" id="login-button">Login</button>
		</form>
	</div>

	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
</div>
</body>
</html>
