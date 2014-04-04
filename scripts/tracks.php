<?php
require_once './checkAddSlashes.php';
require_once './basequeries.php';

header('Content-Type: text/plain; charset=utf-8');

session_start();


if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}

$baseQueries = new BaseQueries();

$tracklist = $_POST['tracklist'];
$setId = $_POST['set_id'];
// set delete flags on all in case one was removed
$baseQueries->run("UPDATE tracks AS t SET t.is_deleted = TRUE WHERE t.set_id = $setId");
// add tracks
$i = 0;
if(!empty($tracklist)) {
	$tracklist = trim($tracklist);
	$tracklist = explode("\n", $tracklist);
	array_filter($tracklist, 'trim');
}
foreach ($tracklist as $track) {
	if($track != "") {
		$trackId = $baseQueries->runInsertGetId("INSERT INTO tracks(set_id, number, track, is_deleted) ".
			"VALUES ('$setId', '$i', '$track', FALSE) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), track = '$track', is_deleted = FALSE");
		$i++;
	}
}

header("location:/scripts/list.php");

function check($err, $msg) {
	if($err) {
		echo $msg . "\r\n";
		exit;
	}
}

?>
