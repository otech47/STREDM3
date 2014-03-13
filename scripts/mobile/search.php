<?php
require_once './../checkAddSlashes.php';

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$label = '';
if($_POST['post'] == 1) {
	$label = $_POST['label'];
} else {
	$label = $_GET['label'];
}
$label = checkAddSlashes($label);

$artistArray = array();
$artistSql = "SELECT s.id, a.artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE a.artist LIKE '%$label%'".
"AND s.is_deleted IS FALSE ";
$artistResult = mysqli_query($con, $artistSql);
$i = 0;
while($artistRow = mysqli_fetch_array($artistResult))
{
	$artistArray[$i]['id'] = $artistRow['id'];
	$artistArray[$i]['artist'] = $artistRow['artist'];
	$artistArray[$i]['event'] = $artistRow['event'];
	$artistArray[$i]['genre'] = $artistRow['genre'];
	$artistArray[$i]['imageURL'] = $artistRow['imageURL'];
	$artistArray[$i]['songURL'] = $artistRow['songURL'];
	$artistArray[$i]['is_radiomix'] = $artistRow['is_radiomix'];
	$artistArray[$i]['match_type'] = "artist";
	$i++;
}

$eventArray = array();
$eventSql = "SELECT s.id, a.artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE e.event LIKE '%$label%'".
"AND s.is_deleted IS FALSE ".
"AND e.is_radiomix IS FALSE";
$eventResult = mysqli_query($con, $eventSql);
$i = 0;
while($eventRow = mysqli_fetch_array($eventResult))
{
	$eventArray[$i]['id'] = $eventRow['id'];
	$eventArray[$i]['artist'] = $eventRow['artist'];
	$eventArray[$i]['event'] = $eventRow['event'];
	$eventArray[$i]['genre'] = $eventRow['genre'];
	$eventArray[$i]['imageURL'] = $eventRow['imageURL'];
	$eventArray[$i]['songURL'] = $eventRow['songURL'];
	$eventArray[$i]['is_radiomix'] = $eventRow['is_radiomix'];
	$eventArray[$i]['match_type'] = "event";
	$i++;
}

$radiomixArray = array();
$radiomixSql = "SELECT s.id, a.artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE e.event LIKE '%$label%'".
"AND s.is_deleted IS FALSE ".
"AND e.is_radiomix IS TRUE";
$radiomixResult = mysqli_query($con, $radiomixSql);
$i = 0;
while($radiomixRow = mysqli_fetch_array($radiomixResult))
{
	$radiomixArray[$i]['id'] = $radiomixRow['id'];
	$radiomixArray[$i]['artist'] = $radiomixRow['artist'];
	$radiomixArray[$i]['event'] = $radiomixRow['event'];
	$radiomixArray[$i]['genre'] = $radiomixRow['genre'];
	$radiomixArray[$i]['imageURL'] = $radiomixRow['imageURL'];
	$radiomixArray[$i]['songURL'] = $radiomixRow['songURL'];
	$radiomixArray[$i]['is_radiomix'] = $radiomixRow['is_radiomix'];
	$radiomixArray[$i]['match_type'] = "radiomix";
	$i++;
}

$genreArray = array();
$genreSql = "SELECT s.id, a.artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE g.genre LIKE '%$label%'".
"AND s.is_deleted IS FALSE ";
$genreResult = mysqli_query($con, $genreSql);
$i = 0;
while($genreRow = mysqli_fetch_array($genreResult))
{
	$genreArray[$i]['id'] = $genreRow['id'];
	$genreArray[$i]['artist'] = $genreRow['artist'];
	$genreArray[$i]['event'] = $genreRow['event'];
	$genreArray[$i]['genre'] = $genreRow['genre'];
	$genreArray[$i]['imageURL'] = $genreRow['imageURL'];
	$genreArray[$i]['songURL'] = $genreRow['songURL'];
	$genreArray[$i]['is_radiomix'] = $genreRow['is_radiomix'];
	$genreArray[$i]['match_type'] = "genre";
	$i++;
}

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