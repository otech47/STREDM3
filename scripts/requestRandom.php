<?php

$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$resultArray = array();
$sql = "SELECT songURL FROM sets WHERE 1";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$fullArray[$i] = $row[0];
	$i++;
}
$j = rand(0, count($fullArray)-1);
if(strpos($fullArray[$j], 'soundcloud') !== false)
{
	$returnResult = "<iframe id='current-result' width='100%' height='100%' scrolling='no' frameborder='no' src=".stripslashes($fullArray[$j])."&amp;auto_play=true&amp;show_user=false"."></iframe>";
	echo json_encode($returnResult);
}
else
{
	$returnResult = "<iframe width='100%' height='100%' src='//www.mixcloud.com/widget/iframe/?feed=".stripslashes($resultArray[$j])."&mini=&stylecolor=&hide_artwork=&embed_type=widget_standard&hide_tracklist=1&hide_cover=1&autoplay=1' frameborder='0'></iframe>";
	echo json_encode($returnResult);
}


?>