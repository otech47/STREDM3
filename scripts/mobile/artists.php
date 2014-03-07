<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT a.id, a.artist FROM sets AS s INNER JOIN artists AS a ON s.artist_id = a.id ".
"WHERE s.is_deleted IS FALSE ORDER BY a.artist ASC";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($artistRow = mysqli_fetch_array($result))
{
	$resultArray[$i]['id'] = $artistRow['id'];
	$resultArray[$i]['artist'] = $artistRow['artist'];
	$i++;
}
echo json_encode($resultArray);

?>