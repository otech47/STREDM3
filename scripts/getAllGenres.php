<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->genreQuery(true);

echo json_encode($resultArray);

?>