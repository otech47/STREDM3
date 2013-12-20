<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$event = $_POST['event'];

$sql = "";
if($event == "") {
	$sql = "SELECT DISTINCT artist FROM sets WHERE 1";
} else {
	$sql = "SELECT DISTINCT artist FROM sets WHERE event='$event'";
}

$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($eventRow = mysqli_fetch_array($result))
{
	$resultArray[$i] = $eventRow[0];
	$i++;
}
sort($resultArray);
echo json_encode($resultArray);

?>