<?php
session_start();
include 'co.php';
if (!isset($_SESSION['id']))
    header('Location:index.php');
$idd = $_SESSION['id'];
$select = $conn->prepare("SELECT k FROM log WHERE id = :id");
$select->bindParam(':id', $idd);
$select->execute();
$cmt = $select->fetchColumn();
if (isset($_POST['d']))
{
	$select = $conn->prepare("SELECT * FROM log WHERE id = :id");
	$select->bindParam(':id', $idd);
	$select->execute();
	$res = $select->fetch(PDO::FETCH_OBJ);

	$number_row = $select->rowCount();
	if($number_row > 0)
	{
		try
		{
			$update = $conn->prepare("UPDATE log SET `k` = 0 WHERE id = :id");
			$update->bindParam(':id', $idd);
			$update->execute();
			header('Location:profil.php');
		}
		catch(PDOExeption $e)
		{
			die($e->getMessage());
		}
	}
}
else if (isset($_POST['a']))
{
	$select = $conn->prepare("SELECT * FROM log WHERE id = :id");
	$select->bindParam(':id', $idd);
	$select->execute();
	$res = $select->fetch(PDO::FETCH_OBJ);
	$number_row = $select->rowCount();
	if($number_row > 0)
	{
		try
		{
			$update = $conn->prepare("UPDATE log SET `k` = 1 WHERE id = :id");
			$update->bindParam(':id', $idd);
			$update->execute();
			header('Location:profil.php');
		}
		catch(PDOExeption $e)
		{
			die($e->getMessage());
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
			<li><a href="logout.php">Log Out</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="camera.php">Camera</a></li>
		</ul>
	</nav>
</header>
<section>
<form method="POST">
<?php
if ($cmt == 1)
	echo '<center><button type="submit" class="bt" name="d">Desactiver</button></center>';
else if ($cmt == 0)
	echo '<center><button type="submit" class="bt" name="a">Activer</button></center>';
?>
</form>
</section>
<footer>
	<div class="war">
		<h1>Camagru</h1>
		<div class="copy">Copyright © 2019. Tous droits réservés.</div>
	</div>
</footer>
</body>
</html>