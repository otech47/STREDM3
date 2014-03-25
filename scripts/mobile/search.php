<?php
require_once './../checkAddSlashes.php';

require_once './../connect.php';

require_once './../basequeries.php';

$con = connect();

$label = '';
if($_POST['post'] == 1) {
	$label = $_POST['label'];
} else {
	$label = $_GET['label'];
}
$label = checkAddSlashes($label);

$artistArray = setQuery($con, "WHERE a.artist LIKE '%$label%' AND s.is_deleted IS FALSE", null, "artist");

$eventArray = setQuery($con, "WHERE e.event LIKE '%$label%' AND e.is_radiomix IS FALSE AND s.is_deleted IS FALSE", null, "event");

$radiomixArray = setQuery($con, "WHERE e.event LIKE '%$label%' AND e.is_radiomix IS TRUE AND s.is_deleted IS FALSE", null, "event");

$genreArray = setQuery($con, "WHERE g.genre LIKE '%$label%' AND s.is_deleted IS FALSE", null, "genre");

$resultArray = array();
$artistCount = count($artistArray);
$newArtistArray = array();
$eventCount = count($eventArray);
$newEventArray = array();
$radiomixCount = count($radiomixArray);
$newRadiomixArray = array();
$genreCount = count($genreArray);
$newGenreArray = array();

$total = 0;
$lastTotal = 0;
$i = 0;
while ($total < 12) {
	if($i < $artistCount) {
		$newArtistArray[] = $artistArray[$i];
		$lastTotal++;
	}
	if($i < $eventCount) {
		$newEventArray[] = $eventArray[$i];
		$lastTotal++;
	}
	if($i < $radiomixCount) {
		$newRadiomixArray[] = $radiomixArray[$i];
		$lastTotal++;
	}
	if($i < $genreCount) {
		$newGenreArray[] = $genreArray[$i];
		$lastTotal++;
	}
	if($lastTotal == $total) {
		break;
	}
	$total = $lastTotal;
	$i++;
}

$finalResults = array();
if(!empty($newArtistArray)) {
	foreach($newArtistArray as $a) {
		$finalResults[] = $a;
	}
}
if(!empty($newEventArray)) {
	foreach($newEventArray as $e) {
		$finalResults[] = $e;
	}
}
if(!empty($newRadiomixArray)) {
	foreach($newRadiomixArray as $r) {
		$finalResults[] = $r;
	}
}
if(!empty($newGenreArray)) {
	foreach($newGenreArray as $g) {
		$finalResults[] = $g;
	}
}

echo json_encode($finalResults);

?>