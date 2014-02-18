<?php

header('Content-Type: text/plain; charset=utf-8');

session_start();


if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

$id = $_POST['id'];
$id = (int)$id;
if($_POST['submit'] == 'del') {
	$sql = "UPDATE sets SET is_deleted = 1 WHERE id = $id";
	$result = mysqli_query($con, $sql);
	if($result) {
		$_SESSION['success'] = "Success! Set deleted. sql: $sql";
		header("location:/scripts/list.php");
	} else {
		$_SESSION['failure'] = "Failed! There were errors deleting that set. sql: $sql";
		header("location:/scripts/list.php");
	}
} else {
	header("location:/login.html");
}

?>