<?php
require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->radiomixQuery(true);

echo json_encode($resultArray);

?>