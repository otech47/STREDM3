<?php
require_once './checkAddSlashes.php';

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$label = $_POST['label'];
$label = checkAddSlashes($label);

$eventUrlArray = array();
$eventArtistArray = array();
$eventSql = "SELECT * FROM sets AS s INNER JOIN events AS e ON e.id = s.event_id WHERE e.event = '$label' AND is_deleted = 0 AND self_hosted = 0";
$eventResult = mysqli_query($con, $eventSql);
$i = 0;
while($eventRow = mysqli_fetch_array($eventResult))
{
	$eventUrlArray[$i] = $eventRow['songURL'];
	$eventArtistArray[$i] = $eventRow['artist'];
	$i++;
}

$artistUrlArray = array();
$artistEventArray = array();
$artistSql = "SELECT * FROM sets AS s INNER JOIN artists AS a ON a.id = s.artist_id WHERE a.artist='$label' AND is_deleted = 0 AND self_hosted = 0";
$artistResult = mysqli_query($con, $artistSql);
$i = 0;
while($artistRow = mysqli_fetch_array($artistResult))
{
	$artistUrlArray[$i] = $artistRow['songURL'];
	$artistEventArray[$i] = $artistRow['event'];
	$i++;
}

if(empty($artistUrlArray))
{
	$resultArray = array($eventUrlArray, $eventArtistArray);
}
else
{
	$resultArray = array($artistUrlArray, $artistEventArray);
}

echo json_encode($resultArray);

?>