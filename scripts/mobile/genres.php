<?php
require_once './../connect.php';

require_once './../basequeries.php';

$con = connect();

$resultArray = genreQuery($con);

echo json_encode($resultArray);

?>