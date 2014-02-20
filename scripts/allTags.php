<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sqla = "SELECT DISTINCT a.artist FROM sets AS s INNER JOIN artists AS a ON s.artist_id = a.id WHERE 1";
$sqle = "SELECT DISTINCT e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id WHERE 1";
$sqlr = "SELECT DISTINCT r.radiomix FROM sets AS s INNER JOIN radiomixes AS r ON s.radiomix_id = r.id WHERE 1";
$sqlg = "SELECT DISTINCT g.genre FROM sets AS s INNER JOIN genres AS g ON s.genre_id = g.id WHERE 1";

$resulte = mysqli_query($con, $sqle);
$resulta = mysqli_query($con, $sqla);
$resultr = mysqli_query($con, $sqlr);
$resultg = mysqli_query($con, $sqlg);
$i = 0;
$resultArray = array();
while($eventRow = mysqli_fetch_array($resulte)) {
	$resultArray[$i] = $eventRow[0];
	$i++;
}
while($artistRow = mysqli_fetch_array($resulta)) {
	$resultArray[$i] = $artistRow[0];
	$i++;
}
while($radiomixRow = mysqli_fetch_array($resultr)) {
	$resultArray[$i] = $radiomixRow[0];
	$i++;
}
while($genreRow = mysqli_fetch_array($resultg)) {
	$resultArray[$i] = $genreRow[0];
	$i++;
}
sort($resultArray);
echo json_encode($resultArray);

?>