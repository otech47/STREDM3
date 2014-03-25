<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = setQuery($con, null, "ORDER BY RAND() LIMIT 1");

echo json_encode($resultArray[0]);

?>
