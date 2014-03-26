<?php
require_once './basequeries.php';

header('Content-Type: text/plain; charset=utf-8');

session_start();

if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}

$baseQueries = new BaseQueries();

$id = $_POST['id'];
$id = (int)$id;
if($_POST['submit'] == 'res') {
	$sql = "UPDATE sets SET is_deleted = 0 WHERE id = $id";
	$result = $baseQueries->run($sql);
	if($result) {
		$_SESSION['success'] = "Success! Set restored. sql: $sql";
		header("location:/scripts/list.php");
	} else {
		$_SESSION['failure'] = "Failed! There were errors restoring that set. sql: $sql";
		header("location:/scripts/list.php");
	}
} else {
	header("location:/login.html");
}

?>