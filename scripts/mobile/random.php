<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$fullArray = array();
$sql = "SELECT s.id, a.artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
"INNER JOIN artists AS a ON a.id = s.artist_id ".
"INNER JOIN events AS e ON e.id = s.event_id ".
"INNER JOIN images AS i ON i.id = e.image_id ".
"INNER JOIN genres AS g ON g.id = s.genre_id ".
"WHERE is_deleted IS FALSE ";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i]['id'] = $row['id'];
	$fullArray[$i]['artist'] = $row['artist'];
	$fullArray[$i]['event'] = $row['event'];
	$fullArray[$i]['genre'] = $row['genre'];
	$fullArray[$i]['imageURL'] = $row['imageURL'];
	$fullArray[$i]['songURL'] = $row['songURL'];
	$fullArray[$i]['is_radiomix'] = $row['is_radiomix'];

	$i++;
}
$newArray = array();
for($i=0; $i<10; $i++) {
	$j = rand(0, count($fullArray)-1);
	$notExists = true;
	foreach ($newArray as $key => $value) {
		if($value['id'] == $fullArray[$j]['id']) {
			$notExists = false;
		}
	}
	if($notExists) {
		$newArray[$i] = $fullArray[$j];
	} else {
		$i--;
	}
}

echo json_encode($newArray);

?>