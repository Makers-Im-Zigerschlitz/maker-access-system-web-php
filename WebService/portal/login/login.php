<html>
<head>
<?php
$filename = "../installer";
if (file_exists($filename)) {
    echo("Please delete the installation folder before you continue!");
	die();
}
include "../config/config.inc.php";
if($fhttps == true)
{
  if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
    $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
   {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
 }
}
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
		<h2><?php echo $dict["Login_Prompt"]; ?>:</h2>
		<form class="form" action="login_action.php" method="post">
			<input type="text" name="username" placeholder="<?php echo $dict["Login_Username"]; ?>">
			<input type="password" name="password" placeholder="<?php echo $dict["Login_Passwort"]; ?>">
			<button type="submit" id="login-button"><?php echo $dict["Login_Logon"]; ?></button>
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
