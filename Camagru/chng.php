<?php
session_start();
include 'co.php';
if (isset($_SESSION['id']))
	header('Location:camagru.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
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
		<h3>Email has been sent to change your password</h3>
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