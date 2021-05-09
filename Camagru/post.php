<?php
include 'co.php';
session_start();
// Like
    function get_nbr_like($conn)
    {
        $select = $conn->prepare("SELECT COUNT(*) FROM likes WHERE id_post = :id_post");
        $select->bindParam(':id_post', $_GET['post']);
        $select->execute();
        return ($select->fetchColumn());
    }
    function isUserLikedPost($conn,$id_post)
    {
       $userLikedPost = 0;
       $select = $conn->prepare("SELECT COUNT(*) FROM likes WHERE id_post = :id_post AND id_user = :id_user");
       $select->bindParam(':id_post', $id_post);
       $select->bindParam(':id_user', $_SESSION['id']);
       $select->execute();
       $likes = (int) intval($select->fetchColumn());
       if ($likes >= 1)
           return (1);
       return (0);
    }
    if (isset($_POST['like']))
    {
        if (!isUserLikedPost($conn, $_GET['post']))
        {
            $select = $conn->prepare("INSERT INTO likes(id_user, id_post) values(:id_user, :id_post)");
            $select->bindParam(':id_user', $_SESSION['id']);
            $select->bindParam(':id_post', $_GET['post']);
            $select->execute();
        }
    }
    if (isset($_POST['unlike']))
    {
        $select = $conn->prepare("DELETE FROM likes where id_post = :id_post AND id_user = :id_user");
        $select->bindParam(':id_user', $_SESSION['id']);
        $select->bindParam(':id_post', $_GET['post']);
        $select->execute();
    }
// Image
    if (isset($_GET['post']))
    {
        $select = $conn->prepare("SELECT * FROM posts WHERE id_post = :id_post");
        $select->bindParam(':id_post', $_GET['post']);
        $select->execute();
        if ($select->rowCount() != 0)
        {
            if ($res = $select->fetch())
                $src = $res['image'];
            $select2 = $conn->prepare("SELECT * FROM likes WHERE id_post = :id_post AND id_user = :id_user");
            $select2->bindParam(':id_post', $_GET['post']);
            $select2->bindParam(':id_user', $_SESSION['id']);
            $select2->execute();
            $like = 0;
            if ($select2->rowCount() != 0)
                $like = 1;
        }
        else
            header('Location:index.php');
    }
    else
        header('Location:camagru.php');
// Get Email
$select = $conn->prepare("SELECT id_user FROM posts WHERE id_post = :id_post");
$select->bindParam(':id_post', $_GET['post']);
$select->execute();
$tett = $select->fetchColumn();
$select = $conn->prepare("SELECT email FROM log WHERE id = :id");
$select->bindParam(':id', $tett);
$select->execute();
$eemail = $select->fetchColumn();
// CMT
$select = $conn->prepare("SELECT cmt FROM log WHERE id = :id");
$select->bindParam(':id', $tett);
$select->execute();
$cmt = $select->fetchColumn();
// k
$select = $conn->prepare("SELECT k FROM log WHERE id = :id");
$select->bindParam(':id', $tett);
$select->execute();
$k = $select->fetchColumn();
// Comnt
    if (isset($_POST['comnt']) && isset($_POST['add_cmt']) && $k == 1)
    {
        if (strlen($_POST['comnt']) <= 50)
		{
            $select = $conn->prepare("INSERT INTO coment(id_user, id_post, comnt) values(:id_user, :id_post, :comnt)");
            $select->bindParam(':id_user', $_SESSION['id']);
            $select->bindParam(':id_post', $_GET['post']);
            $select->bindParam(':comnt', $_POST['comnt']);
            $select->execute();
            if($select && $cmt == 1)
            {
                $idPost = $_GET['post'];
                $to = $eemail;
                $subject = "Email Commentaire";
                $message = "Vous avez un nouveau commentaire";
                $headers = "From: camagru@gmail.com";

                mail($to,$subject,$message,$headers);
            }
        }
        else
            $m = "Your Comment is too long";
    }
// Supp
    if (isset($_POST['supp']))
    {
        $select = $conn->prepare("DELETE FROM posts WHERE id_post = :id_post");
        $select->bindParam(':id_post', $_GET['post']);
        $select->execute();
        header('Location: camagru.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="Css/post.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
<section>
    <form method="POST" class="bs">
        <?php
        if ($tett == $_SESSION['id'])
            echo '<center><button type="submit" name="supp" id="supp">supp</button></center>';
        ?>
    </form>
    <center>
    <p class="msg_err"><?php if (isset($m)) echo $m ?><p>
    </center>
	<div class="postes">
        <img src="<?php echo $src ?>"/>
        <form method="POST" class="frm">
        <?php
        if (isset($_SESSION['id']))
        {
            echo get_nbr_like($conn);
            if ($like == 0)
                echo '<button type="submit" name="like"><i class="fa fa-thumbs-up"></i></button>';
            else
                echo '<button type="submit" name="unlike"><i class="fa fa-thumbs-down"></i></button>';
        ?>
        <br>
        <input type="text" name="comnt" class="f">
        <button type="submit" name="add_cmt" class="b">Add</button>
        </form>
        <div class="comnt">
        <?php
            $select = $conn->prepare("SELECT * from coment where id_post = :id_post");
            $select->bindParam(':id_post',  $_GET['post']);
            $select->execute();
            while ($res = $select->fetch())
            {
                echo '<p class="pt">' . htmlspecialchars($res['comnt']) . '</p>';
            }
        }
        ?>
        </div>
	</div>
</section>
</body>
</html>