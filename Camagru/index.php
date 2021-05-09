<?php
session_start();
include 'co.php';
if (isset($_SESSION['id']))
	header('Location:camagru.php');
if (isset($_POST['login']))
{
	$username = $_POST['username'];
	$password = hash('whirlpool',$_POST['password']);
	if (strlen($username) > 50)
		$msg = "The username or password you entred is incorrect.";
	else if(strlen($_POST['password']) > 50)
		$msg = "The username or password you entred is incorrect.";
	else
	{
		$select = $conn->prepare("SELECT * FROM log WHERE name = :username AND pass = :pass");
		$select->bindParam(':username', $username);
		$select->bindParam(':pass', $password);
		$select->execute();
		$res = $select->fetch(PDO::FETCH_OBJ);
		$number_row = $select->rowCount();
		if($number_row > 0)
		{
			$select = $conn->prepare("SELECT var FROM log WHERE name = :username AND pass = :pass");
			$select->bindParam(':username', $username);
			$select->bindParam(':pass', $password);
			$select->execute();
			$k = $select->fetchColumn();
			if ($k == 1)
			{
				$_SESSION['id'] = $res->id;
				$_SESSION['name'] = $res->name;
				$_SESSION['email'] = $res->email;
				header('Location: camagru.php');
			}
			else
				$msg = "Please confirm your email address";
		}
		else
			$msg = "The username or password you entred is incorrect.";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="Css/style.css">
	<link rel="icon" href="https://img.pngio.com/letter-c-logos-logo-image-free-logo-png-free-letter-c-logo-png-1032_1142.png">
</head>
<body>
<header>
	<?php
		include 'header1.php';
	?>
</header>
<section>
	<div class="all">
		<a href="index.php" class="camagru">
			<p>Camagru</p>
		</a>
		<h3>Sign into your account</h3>
		<p class="msg_err"><?php if (isset($msg)) echo $msg ?><p>
		<form class="form" method="POST">
			<div class="username">
				<input type="text" name="username" placeholder="Username" class="e" required>
			</div>
			<div class="password">
				<input type="password" name="password" placeholder="Password" class="e" required>
			</div>
			<div class="button">
				<button type="submit" class="b" name="login">Login</button>
			</div>
			<div class="forget">
				<a href="forgot.php" class="f">Forgot Password</a>
			</div>
			<p class="dont">Don't have an account?<a href="register.php" class="r"> Register here</a></p>
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