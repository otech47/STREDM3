<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$fullArray = array();
$sql = "SELECT id, artist, event, genre AS title, url AS songURL FROM sets WHERE popularity > 10";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i]['id'] = $row['id'];
	$fullArray[$i]['artist'] = $row['artist'];
	$fullArray[$i]['event'] = $row['event'];
	$fullArray[$i]['title'] = $row['title'];
	$fullArray[$i]['songURL'] = $row['songURL'];

	$i++;
}
$j = rand(0, count($fullArray)-1);
$newArray[0] = $fullArray[$j];

$returnResult = stripslashes($fullArray[$j]);
echo json_encode($newArray);


?>