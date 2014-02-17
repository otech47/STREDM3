<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$resultArray = array();
$sql = "SELECT s.id, a.artist, e.event, r.radiomix, g.genre, s.imageURL, s.songURL, s.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"LEFT JOIN events AS e ON e.id = s.event_id ".
"LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE is_deleted = 0 ";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$resultArray[$i]['id'] = $row['id'];
	$resultArray[$i]['artist'] = $row['artist'];
	$resultArray[$i]['event'] = $row['event'];
	$resultArray[$i]['genre'] = $row['genre'];
	$resultArray[$i]['imageURL'] = $row['imageURL'];
	$resultArray[$i]['songURL'] = $row['songURL'];
	$i++;
}

$j = rand(0, count($resultArray)-1);

$returnResult = $resultArray[$j];
echo json_encode($returnResult);


?>