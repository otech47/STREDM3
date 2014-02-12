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

	$artistsArray = array();
	$sql = "SELECT * FROM artists WHERE 1";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$artistsArray[$i] = $row;
		$i++;
	}

	$eventsArray = array();
	$sql = "SELECT * FROM events WHERE 1";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$eventsArray[$i] = $row;
		$i++;
	}

	$genresArray = array();
	$sql = "SELECT * FROM genres WHERE 1";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$genresArray[$i] = $row;
		$i++;
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
		    <th>Source</th>
		  </tr>
		</thead>
		<tbody>
	  	<? foreach ($setsArray as $set) { ?>
		  <form action="/scripts/sets.php" role="form" method='POST' enctype='multipart/form-data'>
			<input type="hidden" name="id" value="<?=$set['id']?>"/>
		  	<tr>
		  	  <td>
			  	<div class="form-group">
		  		  <?=$set['id']?>
			  	</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
				  <select id="artist" name="artist" class="form-control">
				  	<?php foreach($artistsArray as $artist) { ?>
				  	  <option value="<?=$artist['id']?>" <?=($artist['artist'] == $set['artist'])?'selected':'';?>>
				  	  	<?=$artist['artist']?>
				  	  </option>
				  	<? } ?>
				  </select>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
				  <select id="event" name="event" class="form-control">
				  	<?php foreach($eventsArray as $event) { ?>
				  	  <option value="<?=$event['id']?>" <?=($event['event'] == $set['event'])?'selected':'';?>>
				  	  	<?=$event['event']?>
				  	  </option>
				  	<? } ?>
				  </select>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
				  <select id="genre" name="genre" class="form-control">
				  	<?php foreach($genresArray as $genre) { ?>
				  	  <option value="<?=$genre['id']?>" <?=($genre['genre'] == $set['genre'])?'selected':'';?>>
				  	  	<?=$genre['genre']?>
				  	  </option>
				  	<? } ?>
				  </select>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <input class="form-control" type="text" id="songURL" name="songURL" value="<?=$set['songURL']?>"/>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <input class="form-control" type="text" id="imageURL" name="imageURL" value="<?=$set['imageURL']?>"/>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <input class="form-control" type="text" id="source" name="source" value="<?=$set['source']?>"/>
			  	</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <input class="form-control" type="text" id="tracklist" name="tracklist" value="<?=$set['tracklist']?>"/>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
		  		  <?=$set['radiomix']?>
			  	</div>
		  	  </td>
		  	</tr>
		  </form>
	  	<? } ?>
		</tbody>
	  </table>
    </div>
  </body>
  <script src="/js/jquery-1.9.1.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</html>
