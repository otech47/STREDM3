<?php
require_once './checkAddSlashes.php';

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$songURL = $_POST['songURL'];
$songURL = checkAddSlashes($songURL);

$resultArray = array();
$sql = "SELECT tracklist FROM sets WHERE songURL='$songURL' AND is_deleted = 0";
$result = mysqli_query($con, $sql);
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$resultArray[$i] = $row[0];
	$i++;
}
if(strlen($resultArray[0])>5)
{
	$returnResult = nl2br($resultArray[0]);
	echo $returnResult;
}
else
{
	$returnResult = "No tracklist found";
	echo $returnResult;
}

?>