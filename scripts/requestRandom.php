<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = setQuery($con, null, "ORDER BY RAND() LIMIT 1");

$j = rand(0, count($resultArray)-1);

$returnResult = $resultArray[$j];
echo json_encode($returnResult);

?>
