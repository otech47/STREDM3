<?php
require_once './../basequeries.php';

$baseQueries = new BaseQueries();

$fullArray = $baseQueries->setQuery(null, "ORDER BY s.id ASC, sa.number ASC, s.popularity DESC LIMIT 0 , 20");

echo json_encode($fullArray);

?>