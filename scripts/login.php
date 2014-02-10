<?php

$user = $_POST['user'];
$pass = $_POST['pass'];

if(!$user) {
	$user = $_GET['user'];
}

if(!$pass) {
	$pass = $_GET['pass'];
}


if($user === "otech47" && $pass === "lagomar2010") {
	session_register("user");
	session_register("pass");
	header("Location:/scripts/upload.php");
} else {
	header("Location:/error.html");
}

?>