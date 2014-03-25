<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = eventQuery($con, true);

echo json_encode($resultArray);

?>