<?php
require_once './checkAddSlashes.php';

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$label = '';
if($_GET['get'] == 1) {
	$label = $_GET['label'];
} else {
	$label = $_POST['label'];
}
$label = checkAddSlashes($label);

$eventArray = array();
$eventSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"LEFT JOIN events AS e ON e.id = s.event_id ".
"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE e.event = '$label'".
"AND is_deleted = 0 ";
$eventResult = mysqli_query($con, $eventSql);
$i = 0;
while($eventRow = mysqli_fetch_array($eventResult))
{
	$eventArray[$i]['id'] = $eventRow['id'];
	$eventArray[$i]['artist'] = $eventRow['artist'];
	$eventArray[$i]['event'] = $eventRow['event'];
	$eventArray[$i]['radiomix'] = $eventRow['radiomix'];
	$eventArray[$i]['genre'] = $eventRow['genre'];
	$eventArray[$i]['imageURL'] = $eventRow['imageURL'];
	$eventArray[$i]['songURL'] = $eventRow['songURL'];
	$eventArray[$i]['is_radiomix'] = $eventRow['is_radiomix'];
	$i++;
}

$artistArray = array();
$artistSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"LEFT JOIN events AS e ON e.id = s.event_id ".
"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE a.artist = '$label'".
"AND is_deleted = 0 ";
$artistResult = mysqli_query($con, $artistSql);
$i = 0;
while($artistRow = mysqli_fetch_array($artistResult))
{
	$artistArray[$i]['id'] = $artistRow['id'];
	$artistArray[$i]['artist'] = $artistRow['artist'];
	$artistArray[$i]['event'] = $artistRow['event'];
	$artistArray[$i]['radiomix'] = $artistRow['radiomix'];
	$artistArray[$i]['genre'] = $artistRow['genre'];
	$artistArray[$i]['imageURL'] = $artistRow['imageURL'];
	$artistArray[$i]['songURL'] = $artistRow['songURL'];
	$artistArray[$i]['is_radiomix'] = $artistRow['is_radiomix'];
	$i++;
}

$radiomixArray = array();
$radiomixSql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"LEFT JOIN events AS e ON e.id = s.event_id ".
"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE a.artist = '$label'".
"AND is_deleted = 0 ";
$radiomixResult = mysqli_query($con, $radiomixSql);
$i = 0;
while($radiomixRow = mysqli_fetch_array($radiomixResult))
{
	$radiomixArray[$i]['id'] = $radiomixRow['id'];
	$radiomixArray[$i]['artist'] = $radiomixRow['artist'];
	$radiomixArray[$i]['event'] = $radiomixRow['event'];
	$radiomixArray[$i]['radiomix'] = $radiomixRow['radiomix'];
	$radiomixArray[$i]['genre'] = $radiomixRow['genre'];
	$radiomixArray[$i]['imageURL'] = $radiomixRow['imageURL'];
	$radiomixArray[$i]['songURL'] = $radiomixRow['songURL'];
	$radiomixArray[$i]['is_radiomix'] = $radiomixRow['is_radiomix'];
	$i++;
}

$resultArray = array();
if(empty($artistArray))
{
	$resultArray = $eventArray;
}
else
{
	$resultArray = $artistArray;
}

echo json_encode($resultArray);

?>