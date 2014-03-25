<?php
require_once './../connect.php';

require_once './../basequeries.php';

$con = connect();

$fullArray = setQuery($con, null, "ORDER BY RAND() LIMIT 10");

echo json_encode($fullArray);

?>