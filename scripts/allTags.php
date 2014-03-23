<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sqla = "SELECT DISTINCT a.artist FROM sets AS s INNER JOIN artists2 AS a ON s.artist_id = a.id WHERE 1";
$sqle = "SELECT DISTINCT e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id WHERE e.is_radiomix = 0";
$sqlr = "SELECT DISTINCT e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id WHERE e.is_radiomix = 1";
$sqlg = "SELECT DISTINCT g.genre FROM sets AS s INNER JOIN genres AS g ON s.genre_id = g.id WHERE 1";

$resulte = mysqli_query($con, $sqle);
$resulta = mysqli_query($con, $sqla);
$resultr = mysqli_query($con, $sqlr);
$resultg = mysqli_query($con, $sqlg);
$i = 0;
$resultArray = array();
while($artistRow = mysqli_fetch_array($resulta)) {
	$resultArray[0][$i] = $artistRow[0];
	$i++;
}
sort($resultArray[0]);
$i = 0;
while($eventRow = mysqli_fetch_array($resulte)) {
	$resultArray[1][$i] = $eventRow[0];
	$i++;
}
sort($resultArray[1]);
$i = 0;
while($radiomixRow = mysqli_fetch_array($resultr)) {
	$resultArray[2][$i] = $radiomixRow[0];
	$i++;
}
sort($resultArray[2]);
$i = 0;
while($genreRow = mysqli_fetch_array($resultg)) {
	$resultArray[3][$i] = $genreRow[0];
	$i++;
}
sort($resultArray[3]);
echo json_encode($resultArray);

?>