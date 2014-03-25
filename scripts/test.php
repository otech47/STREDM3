<?php

require_once './connect.php';

require_once './basequeries.php';

$con = connect();

$resultArray = setQuery($con, "WHERE 1 ", "ORDER BY s.is_deleted ASC, a.artist ASC, s.id ASC", null, true);

echo json_encode($resultArray);

?>
