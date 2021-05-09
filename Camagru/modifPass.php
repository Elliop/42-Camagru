<?php
session_start();
include 'co.php';
if (!isset($_SESSION['id']))
    header('Location:index.php');
if (isset($_POST['fog']))
{
	$v = $_SESSION['id'];
	$p = $_POST['npass'];
	$npass = hash('whirlpool',$_POST['npass']);
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
		$req = $conn->prepare("SELECT pass FROM log WHERE id = :id AND pass = :pass");
		$req->bindParam(':id', $v);
		$req->bindParam(':pass', $password);
    	$req->execute();
    	if($req->rowCount() == 1)
    	{
        	try
        	{
				$update = $conn->prepare("UPDATE log SET pass = :npass WHERE id = :id AND pass = :pass");
				$update->bindParam(':npass', $npass);
				$update->bindParam(':id', $v);
				$update->bindParam(':pass', $password);
				$update->execute();
				header('Location: profil.php');
        	}
        	catch(PDOExeption $e)
        	{
        	    die($e->getMessage());
			}
		}
		else
			$pm = "The password you entred is incorrect.";
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
			<li><a href="logout.php">Log Out</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="camera.php">Camera</a></li>
		</ul>
	</nav>
</header>
<section>
	<div class="all">
		<h3>New Password</h3>
		<form class="form" method="POST">
			<p class="msg_err"><?php if (isset($msg)) echo $msg ?><p>
            <div class="password">
				<input type="password" name="npass" placeholder="New Password" class="e" required>
			</div>
			<p class="msg_err"><?php if (isset($pm)) echo $pm ?><p>
			<div class="password">
				<input type="password" name="password" placeholder="Password" class="e" required>
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