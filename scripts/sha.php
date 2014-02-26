<?php
	$sha = sha1($_GET['file']);
	echo json_encode($sha);
?>