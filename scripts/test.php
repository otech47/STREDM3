<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$resultArray = array();
$sql = "SELECT s.id, group_concat(a.artist order by sa.number ASC separator ' & ') artist, ".
"e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN sets_to_artists sa ON sa.set_id = s.id ".
"INNER JOIN artists2 AS a ON a.id = sa.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE s.is_deleted IS FALSE ".
"GROUP BY s.id ORDER BY s.id ASC, sa.number ASC";
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
	$resultArray[$i]['is_radiomix'] = $row['is_radiomix'];
	$i++;
}

echo json_encode($resultArray);

?>
