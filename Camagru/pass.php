<?php
session_start();
include 'co.php';
if (isset($_SESSION['id']))
	header('Location:camagru.php');
if (isset($_GET['token']) && isset($_POST['fog']))
{
	$token = $_GET['token'];
	$p = $_POST['password'];
	$password = hash('whirlpool',$_POST['password']);
	$uppercass = preg_match("/[A-Z]/", $p);
	$lowercass = preg_match("/[a-z]/", $p);
	$number = preg_match("/[0-9]/", $p);
	if (strlen($p) < 8 || !$uppercass || !$lowercass || !$number)
		$msg = "Password must be at least 8 characters long contain a number and an uppercase letter.";
	else if (strlen($p) > 50)
		$msg = "Your Password is too long";
    else
    {
		$req = $conn->prepare("SELECT pass FROM log WHERE token = :token");
		$req->bindParam(':token', $token);
		$req->execute();
		if($req->rowCount() == 1)
		{
        	try
       		{
				$update = $conn->prepare("UPDATE log SET pass=:pass WHERE token=:token");
				$update->bindParam(':pass', $password);
				$update->bindParam(':token', $token);
				$update->execute();
				header('Location: index.php');
        	}
        	catch(PDOExeption $e)
        	{
        	    die($e->getMessage());
			}
		}
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pass</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Css/style.css">
	<link rel="icon" href="https://img.pngio.com/letter-c-logos-logo-image-free-logo-png-free-letter-c-logo-png-1032_1142.png">
</head>
<body>
<header>
	<nav>
		<ul>
			<li style="float:left"><a href="camagru.php">Camagru</a></li>
			<li><a href="index.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
		</ul>
	</nav>
</header>
<section>
	<div class="all">
		<a href="index.php" class="camagru">
			<p>Camagru</p>
		</a>
		<h3>New Password</h3>
		<p class="msg_err"><?php if (isset($msg)) echo $msg ?><p>
		<form class="form" method="POST">
			<div class="password">
				<input type="password" name="password" placeholder="New Password" class="e" required>
			</div>
			<div class="button">
				<button type="submit" class="b" name="fog">Change</button>
			</div>
		</form>
	</div>
</section>
<footer>
	<div class="war">
		<h1>Camagru</h1>
		<div class="copy">Copyright © 2019. Tous droits réservés.</div>
	</div>
</footer>
</body>
</html>