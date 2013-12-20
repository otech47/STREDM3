<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$url = $_POST['url'];
$resultArray = array();
$sql = "SELECT tracklist FROM sets WHERE url='$url'";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$resultArray[$i] = $row[0];
	$i++;
}
if(strlen($resultArray[0])>5)
{
	$returnResult = nl2br($resultArray[0]);
	echo $returnResult;
}
else
{
	$returnResult = "No tracklist found";
	echo $returnResult;
}

?>