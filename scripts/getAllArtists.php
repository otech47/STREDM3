<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT a.artist FROM sets AS s INNER JOIN artists2 AS a ON s.artist_id = a.id ".
"WHERE is_deleted = 0";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($artistRow = mysqli_fetch_array($result))
{
	$resultArray[$i] = $artistRow[0];
	$i++;
}
echo json_encode($resultArray);

?>