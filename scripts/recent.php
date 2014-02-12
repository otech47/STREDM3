<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
die('Could not connect: ' . mysql_error());
}

$sql = "SELECT date,songURL FROM sets ORDER BY date DESC";
$result = mysqli_query($con, $sql);
$songURLArray = array();
$i = 0;
while($i<10)
{
	$row = mysqli_fetch_array($result);
	$songURLArray[$i] = $row['songURL'];
	$i++;
}

for($j=0;$j<10;$j++)
{
	$returnResult .= "<iframe width='100%' height='100%' scrolling='no' frameborder='no' src=".$urlArray[$j]."&amp;show_user=false"."></iframe>";
}

echo json_encode($returnResult);

?>