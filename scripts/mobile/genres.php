<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->genreQuery();

echo json_encode($resultArray);

?>