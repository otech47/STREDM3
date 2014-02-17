<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$fullArray = array();
$sql = "SELECT id, artist, event, genre AS title, songURL FROM sets WHERE is_deleted = 0";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i] = $row;

	$i++;
}
$j = rand(0, count($fullArray)-1);
$newArray[0] = $fullArray[$j];

$returnResult = stripslashes($fullArray[$j]);
echo json_encode($newArray);


?>