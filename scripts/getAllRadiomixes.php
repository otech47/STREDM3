<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
"WHERE s.is_deleted = 0 AND e.is_radiomix = 1";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($radiomixRow = mysqli_fetch_array($result))
{
	$resultArray[$i] = $radiomixRow[0];
	$i++;
}
echo json_encode($resultArray);

?>