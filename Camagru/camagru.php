<?php
include 'co.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Css/camagru.css">
	<link rel="icon" href="https://img.pngio.com/letter-c-logos-logo-image-free-logo-png-free-letter-c-logo-png-1032_1142.png">
</head>
<body>
	<header>
		<?php
	if (isset($_SESSION['id']))
		include 'header2.php';
	else
		include 'header1.php';
	?>
	</header>
	<div id="container">
		<?php
		$select = $conn->prepare("SELECT * FROM posts order by id_post desc LIMIT 0, 5");
        $select->execute();
        while ($res = $select->fetch())
        {
            echo '<div class="postes">
					<a href="post.php?post=' . $res["id_post"] . '"><img src="'. $res["image"] .'"/></a>
	  			</div>';
        }
	?>
	</div>
	<footer>
		<div class="war">
			<h1>Camagru</h1>
			<div class="copy">Copyright © 2019. Tous droits réservés.</div>
		</div>
	</footer>
	<script>
		const con = document.getElementById('container');
		var limit_start = 5;
		window.addEventListener("scroll", function (){
				if (window.scrollY + window.innerHeight >= con.clientHeight) {
				var xhttp = new XMLHttpRequest();
				var params = "limit_start=" + limit_start;
				xhttp.open("POST", "get_posts.php", true);
				xhttp.withCredentials = true;
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) 
					{
						if (this.responseText == "0")
							nb = this.responseText;
						else
							con.innerHTML += this.responseText;
					}
				}
				xhttp.send(params);
				limit_start += 5;
			}
		});
	</script>
</body>
</html>