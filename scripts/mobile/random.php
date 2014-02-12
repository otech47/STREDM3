<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$fullArray = array();
$sql = "SELECT s.id, a.artist, e.event, g.genre, s.imageURL, s.songURL FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE is_deleted = 0";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i] = $row;

	$i++;
}
$j = rand(0, count($fullArray)-1);
$newArray[0] = $fullArray[$j];

$returnResult = stripslashes($fullArray[$j]);
echo json_encode($newArray);

?>