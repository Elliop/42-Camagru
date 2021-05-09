<?php
session_start();
include 'co.php';
if (isset($_SESSION['id']))
	header('Location:camagru.php');
if (isset($_POST['fog']))
{
	$email = $_POST['email'];
	$token = hash('whirlpool',$_POST['email']);
	$select = $conn->prepare("SELECT * FROM log WHERE email= :email");
	$select->bindParam(':email', $email);
	$select->execute();
	$number_row = $select->rowCount();
	if($number_row > 0)
	{
		$to = $email;
		$subject = "Change The Password";
		$message = "<a href='http://localhost/pass.php?token=$token'>Change</a>";
		$headers = "From: camagru@gmail.com";
		$headers .= "MIME-Version: Camagru"."\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
		if(mail($to,$subject,$message,$headers))
		{
			header('Location: chng.php');
		}
	}
	else
		$msg = "The email you entred is incorrect.";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
		<h3>Change Password</h3>
		<p class="msg_err"><?php if (isset($msg)) echo $msg ?><p>
		<form class="form" method="POST">
			<div class="email">
				<input type="email" name="email" placeholder="Email Address" class="e" required>
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