<?php
require_once './../checkAddSlashes.php';

require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$artist = checkAddSlashes($_GET['artist']);
$event = checkAddSlashes($_GET['event']);
$radiomix = checkAddSlashes($_GET['radiomix']);
$genre = checkAddSlashes($_GET['genre']);


$artistArray = array();
if($artist != '') {
	$artistArray = $baseQueries->setQuery("WHERE a.artist = '$artist' AND s.is_deleted IS FALSE", "ORDER BY a.artist ASC", null, true);
}

$eventArray = array();
if($event != '') {
	$eventArray = $baseQueries->setQuery("WHERE e.event = '$event' AND e.is_radiomix IS FALSE AND s.is_deleted IS FALSE", "ORDER BY e.event ASC", null, true);
}

$radiomixArray = array();
if($radiomix != '') {
	$eventArray = $baseQueries->setQuery("WHERE e.event = '$radiomix' AND e.is_radiomix IS TRUE AND s.is_deleted IS FALSE", "ORDER BY e.event ASC", null, true);
}

$genreArray = array();
if($genre != '') {
	$genreArray = $baseQueries->setQuery("WHERE g.genre = '$genre' AND s.is_deleted IS FALSE", "ORDER BY g.genre ASC", null, true);
}

$resultArray = array();

if(!empty($artistArray)) {
	$i = 0;
	foreach($artistArray as $a) {
		$resultArray[$a['id']] = $a;
		$i++;
	}
}
if(!empty($eventArray)) {
	$i = 0;
	foreach($eventArray as $e) {
		$resultArray[$e['id']] = $e;
		$i++;
	}
}
if(!empty($radiomixArray)) {
	$i = 0;
	foreach($radiomixArray as $r) {
		$resultArray[$r['id']] = $r;
		$i++;
	}
}
if(!empty($genreArray)) {
	$i = 0;
	foreach($genreArray as $g) {
		$resultArray[$g['id']] = $g;
		$i++;
	}
}
$finalResults = array();
foreach ($resultArray as $key => $value) {
	$finalResults[] = $value;
}

echo json_encode($finalResults);

?>