<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$fullArray = $baseQueries->setQuery(null, "ORDER BY RAND() LIMIT 10");

echo json_encode($fullArray);

?>