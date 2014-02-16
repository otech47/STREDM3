<?php
session_start();
if(!session_is_registered("user")){
	header("location:/scripts/login.php");
	exit;
} else {

	$success = null;
	// if(session_is_registered("success")) {
		$success = $_SESSION['success'];
	// }

	$failure = null;
	// if(session_is_registered("failure")) {
		$failure = $_SESSION['failure'];
	// }

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

	$radiomixesArray = array();
	$sql = "SELECT * FROM radiomixes WHERE 1";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$radiomixesArray[$i] = $row;
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
  	  <h1>Login Successful</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/list.php" class="btn btn-info" role="button">Set List</a>
	  <? if($success) { ?>
		<div class="alert alert-success"><?=$success?></div>
	  <? } else if($failure) { ?>
	    <div class="alert alert-failure"><?=$failure?></div>
	  <? } ?>
	  <form action="/scripts/sets.php" role="form" method='POST' enctype='multipart/form-data'>
	  	<div class="form-group">
    	<label for="artist">Artist</label>
		  <select id="artist" name="artist" class="form-control">
		  	<option value="">Select Artist</option>
		  	<option value="new">New Artist</option>
		  	<?php foreach($artistsArray as $artist) { ?>
		  	  <option value="<?=$artist['id']?>">
		  	  	<?=$artist['artist']?>
		  	  </option>
		  	<? } ?>
		  </select>
		  <input type="text" class="form-control" id="newartist" name="newartist" style="display:none;" />
		</div>
		<div class="checkbox">
			<label>
				<input id="radiomixcheckbox" name="radiomixcheckbox" type="checkbox" value="radiomix"> Is this a Radio Mix?
			</label>
		</div>
	  	<div class="form-group" id="eventpicker">
    	<label for="event">Event</label>
		  <select id="event" name="event" class="form-control">
		  	<option value="">Select Event</option>
		  	<option value="new">New Event</option>
			<?php foreach($eventsArray as $event) { ?>
		  	  <option value="<?=$event['id']?>">
		  		<?=$event['event']?>
		  	  </option>
		  	<? } ?>
		  </select>
		  <input type="text" class="form-control" id="newevent" name="newevent" style="display:none;" />
		</div>
	  	<div class="form-group" id="radiomixpicker" style="display:none;">
    	<label for="radiomix">Radio Mix</label>
		  <select id="radiomix" name="radiomix" class="form-control">
		  	<option value="">Select Radio Mix</option>
		  	<option value="new">New Radio Mix</option>
			<?php foreach($radiomixesArray as $radiomix) { ?>
		  	  <option value="<?=$radiomix['id']?>">
		  		<?=$radiomix['radiomix']?>
		  	  </option>
		  	<? } ?>
		  </select>
		  <input type="text" class="form-control" id="newradiomix" name="newradiomix" style="display:none;" />
		</div>
	  	<div class="form-group">
    	<label for="genre">Genre</label>
		  <select id="genre" name="genre" class="form-control">
		  	<option value="">Select Genre</option>
		  	<option value="new">New Genre</option>
			<?php foreach($genresArray as $genre) { ?>
		  	  <option value="<?=$genre['id']?>">
		  		<?=$genre['genre']?>
		  	  </option>
		  	<? } ?>
		  </select>
		  <input type="text" class="form-control" id="newgenre" name="newgenre" style="display:none;" />
		</div>
		<div class="form-group">
    	  <label for="songfile">Song File</label>
		  <input type="file" id="songfile" name="songfile" class="form-control"/>
		</div>
		<div class="form-group">
    	  <label for="imagefile">Image File</label>
		  <input type="file" id="imagefile" name="imagefile" class="form-control"/>
		</div>
		<div class="form-group">
    	  <label for="source">Source</label>
		  <input type="text" id="source" name="source" class="form-control"/>
		</div>
		<div class="form-group">
    	<label for="tracklist">Track List</label>
		  <textarea rows="8" id="tracklist" name="tracklist" class="form-control"></textarea>
		</div>
		<button value="submit" class="btn btn-success">Submit</button>
	  </form>
    </div>
  </body>
  <script src="/js/jquery-1.9.1.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
  	$('#artist').change(function() {
  		var v = $(this).val();
  		if(v == "new") {
  			$('#newartist').show();
  		} else {
  			$('#newartist').hide();
  			$('#newartist').val('');
  		}
  	});
  	$('#event').change(function() {
  		var v = $(this).val();
  		if(v == "new") {
  			$('#newevent').show();
  		} else {
  			$('#newevent').hide();
  			$('#newevent').val('');
  		}
  	});
  	$('#radiomix').change(function() {
  		var v = $(this).val();
  		if(v == "new") {
  			$('#newradiomix').show();
  		} else {
  			$('#newradiomix').hide();
  			$('#newradiomix').val('');
  		}
  	});
  	$('#genre').change(function() {
  		var v = $(this).val();
  		if(v == "new") {
  			$('#newgenre').show();
  		} else {
  			$('#newgenre').hide();
  			$('#newgenre').val('');
  		}
  	});
  	$('#radiomixcheckbox').change(function() {
  		if(this.checked) {
  			$('#eventpicker').hide();
  			$('#radiomixpicker').show();
  		} else {
  			$('#eventpicker').show();  			
  			$('#radiomixpicker').hide();
  		}
  	});
  });
  </script>
</html>
