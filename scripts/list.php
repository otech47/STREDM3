<?php
session_start();
$i = 0;
if(!session_is_registered("user")){
	header("location:/scripts/login.php");
	exit;
} else {

	$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	$setsArray = array();
	$sql = "SELECT s.id, s.songURL, s.imageURL, s.date, s.popularity, s.tracklist, s.is_radiomix, a.artist, e.event, g.genre, r.radiomix ".
			"FROM sets s ".
			"INNER JOIN artists a ON a.id = s.artist_id ".
			"LEFT JOIN events e ON e.id = s.event_id ".
			"LEFT JOIN radiomixes r ON r.id = s.radiomix_id ".
			"INNER JOIN genres g ON g.id = s.genre_id ".
			"WHERE is_deleted = 0 ".
			"ORDER BY a.artist, s.id";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$setsArray[$i] = $row;
		$i++;
	}

}
?>
<!DOCTYPE html>
<html>
  <head>
  	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <title>Stredm</title>
  </head>
  <body style="font-size: 16px;">
  	<div class="container">
  	  <h1><?=$i?> Sets Uploaded</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/upload.php" class="btn btn-info" role="button">Go to Uploads</a>
	  <table id="setsTable" class="table">
	  	<thead>
		  <tr>
		  	<th>#</th>
		    <th>Artist</th>
		    <th>Event</th>
		    <th>Radio Mix</th>
		    <th>Genre</th>
		    <th>Song URL</th>
		    <th>Image URL</th>
		  </tr>
		</thead>
		<tbody>
	  	<? foreach ($setsArray as $set) { ?>
		  	<tr>
		  	  <td>
			  	<div class="form-group">
		  		  <?=$set['id']?>
			  	</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
				  <?=$set['artist']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <?=$set['event']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <?=$set['radiomix']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
				  <?=$set['genre']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <?=$set['songURL']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <?=$set['imageURL']?>
				</div>
		  	  </td>
		  	</tr>
	  	<? } ?>
		</tbody>
	  </table>
    </div>
  </body>
  <script src="/js/jquery-1.9.1.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</html>
