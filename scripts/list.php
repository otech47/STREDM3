<?php
session_start();
if(!session_is_registered("user")){
	header("location:/scripts/login.php");
	exit;
} else {

	$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	$setsArray = array();
	$sql = "SELECT s.id, s.radiomix, s.songURL, s.imageURL, s.date, s.popularity, s.source, s.tracklist, a.artist, e.event, g.genre ".
			"FROM sets s ".
			"INNER JOIN artists a ON a.id = s.artist_id ".
			"INNER JOIN events e ON e.id = s.event_id ".
			"INNER JOIN genres g ON g.id = s.genre_id ".
			"WHERE 1";
	$result = mysqli_query($con, $sql);
	$i = 0;
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
  <body>
  	<div class="container">
  	  <h1>Login Successful</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/upload.php" class="btn btn-info" role="button">Go to Uploads</a>
	  <table id="setsTable" class="table">
	  	<thead>
		  <tr>
		  	<th>#</th>
		    <th>Artist</th>
		    <th>Event</th>
		    <th>Genre</th>
		    <th>Song URL</th>
		    <th>Image URL</th>
		    <th>Source</th>
		    <th>Radio Mix</th>
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
		  	  <td>
			  	<div class="form-group">
			  	  <?=$set['source']?>
			  	</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
		  		  <?=$set['radiomix']?>
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
