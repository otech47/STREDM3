<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT e.id, e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
"WHERE s.is_deleted IS FALSE AND e.is_radiomix IS TRUE ORDER BY e.event ASC";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($radiomixRow = mysqli_fetch_array($result))
{
	$resultArray[$i]['id'] = $radiomixRow['id'];
	$resultArray[$i]['radiomix'] = $radiomixRow['event'];
	$i++;
}
echo json_encode($resultArray);

?>