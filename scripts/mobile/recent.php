<?php
require_once './../connect.php';

require_once './../basequeries.php';

$con = connect();

$fullArray = setQuery($con, null, "ORDER BY s.datetime DESC LIMIT 0 , 20");

echo json_encode($fullArray);

?>
