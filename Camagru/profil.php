<?php
session_start();
include 'co.php';
if (!isset($_SESSION['id']))
    header('Location:index.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Css/profil.css">
	<link rel="icon" href="https://img.pngio.com/letter-c-logos-logo-image-free-logo-png-free-letter-c-logo-png-1032_1142.png">
</head>
<body>
<header>
	<nav class="a">
		<ul>
			<li style="float:left"><a href="camagru.php">Camagru</a></li>
			<li><a href="logout.php">Log Out</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="camera.php">Camera</a></li>
		</ul>
	</nav>
</header>
<section>
	<center>
		<div class="nav">
			<ul>
				<li><a href="modifUsername.php">Modifier Username</a></li>
				<li><a href="modifEmail.php">Modifier Email</a></li>
				<li><a href="modifPass.php">Modifier Password</a></li>
				<li><a href="cmt.php">Notification</a></li>
				<li><a href="r.php">Commenter</a></li>
			</ul>
		</div>
	</center>
</section>
<footer>
	<div class="war">
		<h1>Camagru</h1>
		<div class="copy">Copyright © 2019. Tous droits réservés.</div>
	</div>
</footer>
</body>
</html>