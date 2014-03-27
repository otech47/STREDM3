<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->setQuery(null, "ORDER BY RAND() LIMIT 1");

echo json_encode($resultArray[0]);

?>
