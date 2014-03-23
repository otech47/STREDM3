<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = setQuery($con);

echo json_encode($resultArray);

?>
