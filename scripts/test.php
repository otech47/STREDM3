<?php

require_once './basequeries.php';

$baseQueries = new BaseQueries();

$resultArray = $baseQueries->setQuery("WHERE 1 ", "ORDER BY s.is_deleted ASC, a.artist ASC, s.id ASC", null, true);

echo json_encode($resultArray);

?>
