<?php
session_start();
include 'co.php';
if (!isset($_SESSION['id']))
	header('Location:index.php');
$t = 0;
$emoji = "";
$fp = "";
$msg = "";
// Add
	function add_post($user_id,$image)
	{
		global $conn;
		$sql = $conn->prepare("INSERT INTO `posts`(`id_user`, `image`) VALUES (:id_user,:image)");
		$sql->bindParam(':id_user', $user_id);
		$sql->bindParam(':image', $image);
		$sql->execute();
	}
// Sticker
if (isset($_POST['sticker']) && $_POST['sticker'])
{
	$emoji = '';
	if (in_array($_POST['sticker'], ['E1','E2','E3','E4','E5','E6'])) {
		$emoji = "image/" . $_POST['sticker'] . ".png";
	}
}
// Check
function fileCheck($fp)
{
	$image_info = getimagesize($fp);
	$width = $image_info[0];
	$height = $image_info[1];
	$allowed = array('image/jpeg', 'image/png');
	if ($height <= 1250 && $width <= 1250)
		if (in_array(mime_content_type($fp), $allowed)) // check bnr frmt
			return (true);
	return (false);
}
// Camera
$allowed = array('jpg', 'jpeg', 'png');
if (isset($_POST['imgBase64']) && isset($_POST['extension']) && (in_array($_POST['extension'], $allowed) || $_POST['extension'] == '0'))
{
	$extension = $_POST['extension'];
	$dontCheck = false;
	if ($extension == '0') // snap
	{
		$extension = 'jpeg';
		$dontCheck = true;
	}
	$rawData = $_POST['imgBase64'];
	$baseType = $extension == 'jpg' ? 'jpeg' : $extension;
	$rawData = str_replace('data:image/'.$baseType.';base64,', '', $rawData);
	$rawData = str_replace(' ', '+', $rawData);
	$unencoded = base64_decode($rawData);
	$datime = date("Y-m-d-H.i.s", time() ) ; # - 3600*7
	$userid  = $_SESSION['id'];

	// Name & save the image file 
	$fp = 'image/'.$datime.'-'.$userid.'.'.$extension;
	file_put_contents($fp, $unencoded);

	if ($dontCheck || fileCheck($fp)) // dont check for snap | filcheck for upload
	{
		// if Sticker
		if (!empty($emoji))
		{
			$fp_image = $extension == 'png' ? imagecreatefrompng($fp) : imagecreatefromjpeg($fp);
			$current_image = imagecreatefrompng($emoji);
			imagecopy($fp_image, $current_image, 10, 10, 0, 0, 50, 50);
			imagepng($fp_image, $fp);
		}
		add_post($_SESSION["id"],$fp); // Add
		// Side
		$req = $conn->prepare("SELECT varrrr FROM posts WHERE varrrr = 0 AND `image` = :img");
		$req->bindParam(':img', $fp);
		$req->execute();
		if($req->rowCount() == 1)
		{
			try
			{
				$isSnap = $dontCheck ? 1 : 0;
				$update = $conn->prepare("UPDATE posts SET varrrr = $isSnap WHERE `image` = :img");
				$update->bindParam(':img', $fp);
				$update->execute();
			}
			catch(PDOExeption $e)
			{
				die($e->getMessage());
			}
		}
	}
	else
		$msg = "Your file is invalid for some reason. Please check again before uploading.";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Css/camera.css">
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
	<div class="right">
		<?php
			$uid = $_SESSION["id"];
			$select = $conn->prepare("SELECT * FROM posts WHERE id_user= $uid AND varrrr= 1");
			$select->execute();
			while ($res = $select->fetch())
			{
				echo '<div class="postes">
					<a href="post.php?post=' . $res["id_post"] . '"><img width="300" height="240" src="'. $res["image"] .'"/></a>
					</div>';
			}
		?>
	</div> 
	<div class="r">
		<video id="video" width="600" height="480" ></video>
		<?php
			if (isset($_POST['E1']))
			{
				$t = 1;
				echo '<img class="E1" id="stickers" src="image/E1.png" class>';
			}
			else if (isset($_POST['E3']))
			{
				$t = 1;
				echo '<img class="E3" id="stickers" src="image/E3.png" class>';
			}
			else if (isset($_POST['E4']))
			{
				$t = 1;
				echo '<img class="E4" id="stickers" src="image/E4.png" class>';
			}
			else if (isset($_POST['E2']))
			{
				$t = 1;
				echo '<img class="E2" id="stickers" src="image/E2.png" class>';
			}
			else if (isset($_POST['E5']))
			{
				$t = 1;
				echo '<img class="E5" id="stickers" src="image/E5.png" class>';
			}
			else if (isset($_POST['E6']))
			{
				$t = 1;
				echo '<img class="E6" id="stickers" src="image/E6.png" class>';
			}
		?>
	</div>
	<div class="diiv">
		<center>
		<p class="addEmo">Add Emoji!</p>
		<form method="POST">
		<button type="submit" name="E1" class="tt">Emoji 1</button>
		<br>
		<button type="submit" name="E2" class="tt">Emoji 2</button>
		<br>
		<button type="submit" name="E3" class="tt">Emoji 3</button>
		<br>
		<button type="submit" name="E4" class="tt">Emoji 4</button>
		<br>
		<button type="submit" name="E5" class="tt">Emoji 5</button>
		<br>
		<button type="submit" name="E6" class="tt">Emoji 6</button>
		</form>
		</center>
	</div>
	<br>
	<button id="snap" <?php if ($t == 0) echo 'disabled' ?>>Snap Photo</button>
	<button type="button" class="upload" name="upload_snap" id="up_file-1" >Upload Photo</button>
	<br>
	<div class="r">
		<canvas id="canvas"  width="640px" height="480px" ></canvas>
		<?php
			if (isset($_POST['E1']))
			{
				echo '<img class="E1" id="sticker" src="image/E1.png" class>';
			}
			else if (isset($_POST['E3']))
			{
				echo '<img class="E3" id="sticker" src="image/E3.png" class>';
			}
			else if (isset($_POST['E4']))
			{
				echo '<img class="E4" id="sticker" src="image/E4.png" class>';
			}
			else if (isset($_POST['E2']))
			{
				echo '<img class="E2" id="sticker" src="image/E2.png" class>';
			}
			else if (isset($_POST['E5']))
			{
				echo '<img class="E5" id="sticker" src="image/E5.png" class>';
			}
			else if (isset($_POST['E6']))
			{
				echo '<img class="E6" id="sticker" src="image/E6.png" class>';
			}
		?>
	</div>
	<form>
		<p class="msg_err"><?php if (isset($msg)) echo $msg ?><p>
	</form>
	<input type="file" name="file" id="file">
	<button type="button" name="upload_file" id="up_file-2">Upload File</button>
	<script src="Js/cam.js"></script>
</section>
<footer>
	<div class="war">
		<h1>Camagru</h1>
		<div class="copy">Copyright © 2019. Tous droits réservés.</div>
	</div>
</footer>
</body>
</html>