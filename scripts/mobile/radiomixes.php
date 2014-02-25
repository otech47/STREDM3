<?php

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$sql = "SELECT DISTINCT r.id, r.radiomix FROM sets AS s INNER JOIN radiomixes AS r ON s.radiomix_id = r.id WHERE is_deleted = 0 ORDER BY r.radiomix ASC";
$result = mysqli_query($con, $sql);
$i = 0;
$resultArray = array();
while($radiomixRow = mysqli_fetch_array($result))
{
	$resultArray[$i]['id'] = $radiomixRow['id'];
	$resultArray[$i]['radiomix'] = $radiomixRow['radiomix'];
	$i++;
}
echo json_encode($resultArray);

?>