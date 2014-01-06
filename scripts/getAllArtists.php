<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT artist FROM sets";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($eventRow = mysqli_fetch_array($result))
{
	$resultArray[$i] = $eventRow[0];
	$i++;
}
echo json_encode($resultArray);

?>