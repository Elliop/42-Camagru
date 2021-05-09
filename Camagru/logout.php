<?php
include "camagru.php";
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['email']);
    session_destroy();
	header('Location: index.php');
?>