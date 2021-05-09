<?php
session_start();
include 'co.php';
if (isset($_SESSION['id']))
	header('Location:camagru.php');
if (isset($_POST['re']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
{
	function verif($var)
	{
		$var = trim($var);
		return ($var);
	}
	$username = "";
	$email = "";
	$username = verif($_POST['username']);
	$email = verif($_POST['email']);
	$p = $_POST['password'];
	$password = hash('whirlpool',$_POST['password']);
	$token = hash('whirlpool',$_POST['email']);
	$uppercass = preg_match("/[A-Z]/", $p);
	$lowercass = preg_match("/[a-z]/", $p);
	$number = preg_match("/[0-9]/", $p);
	function isEmail($var)
	{
		return filter_var($var, FILTER_VALIDATE_EMAIL);
	}
	if (!isEmail($email))
		$em = "The email you entred is incorrect.";
	else if (strlen($p) < 8 || !$uppercass || !$lowercass || !$number)
		$pm = "Password must be at least 8 characters long contain a number and an uppercase letter.";
	else if (strlen($username) > 50)
		$um = "Your Username is too long";
	else if (strlen($email) > 50)
		$eml = "Your Email is too long";
	else if (strlen($p) > 50)
		$pml = "Your Password is too long";
	else
	{
		$select = $conn->prepare("SELECT * FROM log WHERE email=:email or name=:nam");
		$select->bindParam(':email', $email);
		$select->bindParam(':nam', $username);
		$select->execute();
		$number_row = $select->rowCount();
		if($number_row == 0)
		{
			$sql = $conn->prepare("INSERT INTO `log`(`name`, `email`, `pass`, `token`) VALUES (:name,:email,:pass,:token)");
			$sql->bindParam(':name', $username);
			$sql->bindParam(':email', $email);
			$sql->bindParam(':pass', $password);
			$sql->bindParam(':token', $token);
			$sql->execute();
			if($sql)
			{
				$to = $email;
				$subject = "Email Verfication";
				$message = "<a href='http://localhost/token.php?token=$token'>Confirm</a>";
				$headers = "From: camagru@gmail.com";
				$headers .= "MIME-Version: Camagru"."\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";

				if(mail($to,$subject,$message,$headers))
				{
					header('Location: active.php');
				}
			}
		}
		else
			$messageE = "The Username or Email address is already used. Try another one.";
	}
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
		<h3>Create an account</h3>

		<form class="form" method="POST">

			<p class="msg_err"><?php if (isset($um)) echo $um; else if (isset($messageE)) echo $messageE ?><p>
			<div class="username">
				<input type="username" name="username" placeholder="Username" class="e"  required>
			</div>

			<p class="msg_err"><?php if (isset($em)) echo $em; else if (isset($eml)) echo $eml ?><p>
			<div class="email">
				<input type="text" name="email" placeholder="Email Address" class="e"  required>
			</div>

			<p class="msg_err"><?php if (isset($pm)) echo $pm; else if (isset($pml)) echo $pml ?><p>
			<div class="password">
				<input type="password" name="password" placeholder="Password" class="e" required>
			</div>

			<div class="button">
				<button type="submit" class="b" name="re">Create</button>
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