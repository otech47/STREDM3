<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sqle = "SELECT event FROM events WHERE 1 ORDER BY event";
$sqla = "SELECT artist FROM artists WHERE 1 ORDER BY artist";

$resulte = mysqli_query($con, $sqle);
$resulta = mysqli_query($con, $sqla);
$i = 0;
$resultArray = array();
while($eventRow = mysqli_fetch_array($resulte)) {
	$resultArray[$i] = $eventRow[0];
	$i++;
}
while($artistRow = mysqli_fetch_array($resulta)) {
	$resultArray[$i] = $artistRow[0];
	$i++;
}
sort($resultArray);
echo json_encode($resultArray);

?>