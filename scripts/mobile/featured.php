<?php
require_once './../connect.php';

require_once './../basequeries.php';

$con = connect();

$fullArray = setQuery($con, null, "ORDER BY s.id ASC, sa.number ASC, s.popularity DESC LIMIT 0 , 20");

echo json_encode($fullArray);

?>