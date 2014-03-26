<?php
require_once './checkAddSlashes.php';

require_once './basequeries.php';

$baseQueries = new BaseQueries();

$label = '';
if($_GET['get'] == 1) {
	$label = $_GET['label'];
} else {
	$label = $_POST['label'];
}
$label = checkAddSlashes($label);

$artistArray = $baseQueries->setQuery("WHERE a.artist = '$label' AND s.is_deleted IS FALSE");

$eventArray = $baseQueries->setQuery("WHERE e.event = '$label' AND e.is_radiomix = 0 AND s.is_deleted IS FALSE");

$radiomixArray = $baseQueries->setQuery("WHERE e.event = '$label' AND e.is_radiomix = 1 AND s.is_deleted IS FALSE");

$genreArray = $baseQueries->setQuery("WHERE g.genre = '$label' AND s.is_deleted IS FALSE");

$resultArray = array();
if(!empty($artistArray)) {
	foreach($artistArray as $a) {
		$resultArray[$a['id']] = $a;
	}
}
if(!empty($eventArray)) {
	foreach($eventArray as $e) {
		$resultArray[$e['id']] = $e;
	}
}
if(!empty($radiomixArray)) {
	foreach($radiomixArray as $r) {
		$resultArray[$r['id']] = $r;
	}
}
if(!empty($genreArray)) {
	foreach($genreArray as $r) {
		$resultArray[$r['id']] = $r;
	}
}
$finalResults = array();
foreach ($resultArray as $key => $value) {
	$finalResults[] = $value;
}

echo json_encode($finalResults);

?>