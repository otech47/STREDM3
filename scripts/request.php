<?php

// $con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

// if (!$con)
// {
// 	die('Could not connect: ' . mysql_error());
// }

// $label = $_POST['label'];

// $eventUrlArray = array();
// $eventArtistArray = array();
// $eventSql = "SELECT * FROM sets WHERE event='$label'";
// $eventResult = mysqli_query($con, $eventSql);
// $i = 0;
// while($eventRow = mysqli_fetch_array($eventResult))
// {
// 	$eventUrlArray[$i] = $eventRow['url'];
// 	$eventArtistArray[$i] = $eventRow['artist'];
// 	$i++;
// }

// $artistUrlArray = array();
// $artistEventArray = array();
// $artistSql = "SELECT * FROM sets WHERE artist='$label'";
// $artistResult = mysqli_query($con, $artistSql);
// $i = 0;
// while($artistRow = mysqli_fetch_array($artistResult))
// {
// 	$artistUrlArray[$i] = $artistRow['url'];
// 	$artistEventArray[$i] = $artistRow['event'];
// 	$i++;
// }

// if(empty($artistUrlArray))
// {
// 	$resultArray = array($eventUrlArray, $eventArtistArray);
// }
// else
// {
// 	$resultArray = array($artistUrlArray, $artistEventArray);
// }
$resultArray = (array("url1","url2","url3"),array("Hardwell", "Calvin Harris", "Deadmau5"));
echo $resultArray;
// if(!empty($resultArray))
// {
// 	$j = rand(0, count($resultUrlArray)-1);
// 	if(strpos($resultUrlArray[$j], 'soundcloud') !== false)
// 	{
// 		$returnResult = "<iframe id='current-result' width='100%' height='100%' scrolling='no' frameborder='no' src=".stripslashes($resultArray[$j])."&amp;auto_play=true&amp;show_user=false"."></iframe>";
// 		echo $returnResult;
// 	}
// 	else
// 	{
// 		$returnResult = "<iframe width='100%' height='100%' src='//www.mixcloud.com/widget/iframe/?feed=".stripslashes($resultArray[$j])."&mini=&stylecolor=&hide_artwork=&embed_type=widget_standard&hide_tracklist=1&hide_cover=1&autoplay=0' frameborder='0'></iframe>";
// 		echo $returnResult;
// 	}
// }
// else
// {
// 	$returnResult = "<p>No results found";
// 	echo $returnResult;
// }

?>