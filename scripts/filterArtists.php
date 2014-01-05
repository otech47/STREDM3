<?php

// $con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

// if (!$con)
// {
	// die('Could not connect: ' . mysql_error());
// }

$array = $_POST['array'];
$resultArray = array("Above and Beyond");
// $sql = "SELECT DISTINCT artist FROM sets";

// $result = mysqli_query($con, $sql);
// $i = 0;
// $resultArray = array();
// while($eventRow = mysqli_fetch_array($result))
// {
	// $resultArray[$i] = $eventRow[0];
	// $i++;
// }
$finalArray = array_intersect($array, $resultArray);
echo json_encode($array);

?>