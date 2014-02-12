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
"WHERE is_deleted = 0 ORDER BY popularity DESC LIMIT 0 , 20";
$result = mysqli_query($con, $sql);
$songURLArray = array();
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i]['id'] = $row['id'];
	$fullArray[$i]['artist'] = $row['artist'];
	$fullArray[$i]['event'] = $row['event'];
	$fullArray[$i]['genre'] = $row['genre'];
	$fullArray[$i]['imageURL'] = $row['imageURL'];
	$fullArray[$i]['songURL'] = $row['songURL'];

	$i++;
}

// $returnResult = stripslashes($fullArray[$j]);
echo json_encode($fullArray);

?>