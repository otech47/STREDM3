<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
die('Could not connect: ' . mysql_error());
}

$sql = "SELECT date,url FROM sets ORDER BY popularity ASC";
$result = mysqli_query($con, $sql);
$urlArray = array();
$i = 0;
while($i<10)
{
	$row = mysqli_fetch_array($result);
	$urlArray[$i] = $row['url'];
	$i++;
}

for($j=0;$j<10;$j++)
{
	$returnResult .= "<iframe width='100%' height='100%' scrolling='no' frameborder='no' src=".$urlArray[$j]."&amp;show_user=false"."></iframe>";
}

echo json_encode($returnResult);

?>