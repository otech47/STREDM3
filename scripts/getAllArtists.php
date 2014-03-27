<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->artistQuery(true);

echo json_encode($resultArray);

?>