<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT g.id, g.genre FROM sets AS s INNER JOIN genres AS g ON s.genre_id = g.id ".
"WHERE s.is_deleted IS FALSE ORDER BY g.genre ASC";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($genreRow = mysqli_fetch_array($result))
{
	$resultArray[$i]['id'] = $genreRow['id'];
	$resultArray[$i]['genre'] = $genreRow['genre'];
	$i++;
}
echo json_encode($resultArray);

?>