<?php

function connect() {
	$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	return $con;
}

?>