<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->artistQuery();

echo json_encode($resultArray);

?>