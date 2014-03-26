<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resulta = $baseQueries->artistQuery(true);
$resulte = $baseQueries->eventQuery(true);
$resultr = $baseQueries->radiomixQuery(true);
$resultg = $baseQueries->genreQuery(true);
$i = 0;
$resultArray = array();
foreach($resulta as $artistRow) {
	$resultArray[0][$i] = $artistRow;
	$i++;
}
sort($resultArray[0]);
$i = 0;
foreach($resulte as $eventRow) {
	$resultArray[1][$i] = $eventRow;
	$i++;
}
sort($resultArray[1]);
$i = 0;
foreach($resultr as $radiomixRow) {
	$resultArray[2][$i] = $radiomixRow;
	$i++;
}
sort($resultArray[2]);
$i = 0;
foreach($resultg as $genreRow) {
	$resultArray[3][$i] = $genreRow;
	$i++;
}
sort($resultArray[3]);
echo json_encode($resultArray);

?>