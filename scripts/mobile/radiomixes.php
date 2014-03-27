<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->radiomixQuery();

echo json_encode($resultArray);

?>