<html>
<head>
<?php
include "../config/config.inc.php";
$language = getlang($standardlanguage);
include "../includes/dictionary.$language.inc.php";
?>
<title><?php echo $orgname; ?> Portal</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="stylesheet" type="text/css" href="../css/normalize.css">
</head>
<body>
<div class="login">
<h1><?php echo $orgname; ?>:</h1>
<h2><?php echo $dict["0"]; ?>:</h2>
<form action="login_action.php" method="post">
<table>
		<tr>
			<th><?php echo $dict["1"];?>:</th>
			<td><input type="text" name="username"></td>
		</tr>
		<tr>
			<th><?php echo $dict["2"];?>:</th>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="<?php echo $dict["39"];?>" name="submit"></td>
		</tr>
	</form>
</table>
</div>
</body>
</html>
