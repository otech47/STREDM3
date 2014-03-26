<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->eventQuery();

echo json_encode($resultArray);

?>