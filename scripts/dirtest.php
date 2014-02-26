<?php
	$files = scandir("/home/strenbum/direct_uploads");
	$f2 = array();
	foreach ($files as $key => $file) {
		if(strpos($file, '.') !== (int) 0) {
			$f2[] = $file;
		}
	}
	echo json_encode($f2);

?>