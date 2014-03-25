<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = radiomixQuery($con, true);

echo json_encode($resultArray);

?>