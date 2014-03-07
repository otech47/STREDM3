<?php
require_once './../checkAddSlashes.php';

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$artist = checkAddSlashes($_GET['artist']);
$event = checkAddSlashes($_GET['event']);
$radiomix = checkAddSlashes($_GET['radiomix']);
$genre = checkAddSlashes($_GET['genre']);


$artistArray = array();
if($artist != '') {
	$artistSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
	"INNER JOIN artists AS a ON a.id = s.artist_id ".
	"LEFT JOIN events AS e ON e.id = s.event_id ".
	"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
	"INNER JOIN genres AS g ON g.id = s.genre_id ".
	"WHERE a.artist = '$artist'".
	"AND is_deleted = 0 ";
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
}

$eventArray = array();
if($event != '') {
	$eventSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
	"INNER JOIN artists AS a ON a.id = s.artist_id ".
	"LEFT JOIN events AS e ON e.id = s.event_id ".
	"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
	"INNER JOIN genres AS g ON g.id = s.genre_id ".
	"WHERE e.event = '$event'".
	"AND is_deleted = 0 ";
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
}

$radiomixArray = array();
if($radiomix != '') {
	$radiomixSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
	"INNER JOIN artists AS a ON a.id = s.artist_id ".
	"LEFT JOIN events AS e ON e.id = s.event_id ".
	"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
	"INNER JOIN genres AS g ON g.id = s.genre_id ".
	"WHERE r.radiomix = '$radiomix'".
	"AND is_deleted = 0 ";
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
}

$genreArray = array();
if($genre != '') {
	$genreSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
	"INNER JOIN artists AS a ON a.id = s.artist_id ".
	"LEFT JOIN events AS e ON e.id = s.event_id ".
	"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
	"INNER JOIN genres AS g ON g.id = s.genre_id ".
	"WHERE g.genre = '$genre'".
	"AND is_deleted = 0 ";
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