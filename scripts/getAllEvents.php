<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->eventQuery(true);

echo json_encode($resultArray);

?>