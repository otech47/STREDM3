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
	$_SESSION['success'] = null;

	$failure = null;
	// if(session_is_registered("failure")) {
		$failure = $_SESSION['failure'];
	// }
	$_SESSION['failure'] = null;

	$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	$artistsArray = array();
	$sql = "SELECT * FROM artists WHERE 1 order by artist";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$artistsArray[$i] = $row;
		$i++;
	}

	$eventsArray = array();
	$sql = "SELECT * FROM events WHERE is_radiomix IS FALSE order by event";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$eventsArray[$i] = $row;
		$i++;
	}

	$radiomixesArray = array();
	$sql = "SELECT * FROM events WHERE is_radiomix IS TRUE order by event";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$radiomixesArray[$i] = $row;
		$i++;
	}

	$genresArray = array();
	$sql = "SELECT * FROM genres WHERE 1 order by genre";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$genresArray[$i] = $row;
		$i++;
	}

	$directUploadsArray = array();
	$files = scandir("/home/strenbum/direct_uploads");
	foreach ($files as $key => $file) {
		if(strpos($file, '.') !== (int) 0) {
			$directUploadsArray[] = $file;
		}
	}

	$imagesArray = array();
	$sql = "SELECT i.imageURL, e.is_radiomix, e.event FROM sets AS s ".
	"INNER JOIN events AS e ON e.id = s.event_id ".
	"INNER JOIN images AS i ON i.id = e.image_id ".
	"WHERE 1 GROUP BY i.imageURL";
	// $sql = "SELECT DISTINCT s.imageURL, s.is_radiomix, e.event, r.radiomix FROM sets AS s ".
	// "LEFT JOIN events AS e ON e.id = s.event_id ".
	// "LEFT JOIN radiomixes AS r ON r.id = s.radiomix_id ".
	// "WHERE 1 order by imageURL";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$imagesArray[$i] = $row;
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
  	  <h1>Upload A Set</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/list.php" class="btn btn-info" role="button">Set List</a>
	  <? if($success) { ?>
		<div class="alert alert-success"><?=$success?></div>
	  <? } if($failure) { ?>
	    <div class="alert alert-danger"><?=$failure?></div>
	  <? } ?>
	  <form action="/scripts/sets.php" role="form" method='POST' enctype='multipart/form-data'>
	  	<input type="hidden" name="MAX_FILE_SIZE" value="1048000000">
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
		  		<?=$radiomix['event']?>
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
		<div class="checkbox">
			<label>
				<input id="directuploadcheckbox" name="directuploadcheckbox" type="checkbox" value="directupload"> Naming a Direct Upload?
			</label>
		</div>
		<div class="form-group" id="songfilepicker">
    	  <label for="songfile">Song File</label>
		  <input type="file" id="songfile" name="songfile" class="form-control"/>
		</div>
		<div class="form-group" id="directuploadpicker" style="display: none;">
    	  <label for="directupload">Uploaded Song File</label>
		  <select id="directupload" name="directupload" class="form-control">
			<?php foreach($directUploadsArray as $directUpload) { ?>
		  	  <option value="<?=$directUpload?>">
		  		<?=$directUpload?>
		  	  </option>
		  	<? } ?>
		  </select>
		</div>
		<div class="checkbox">
			<label>
				<input id="oldimagecheckbox" name="oldimagecheckbox" type="checkbox" value="oldimage"> Using an existing image?
			</label>
		</div>
		<div class="form-group" id="imagepicker">
    	  <label for="imagefile">Image File</label>
		  <input type="file" id="imagefile" name="imagefile" class="form-control"/>
		</div>
		<div class="form-group" id="oldimagepicker" style="display: none;">
    	  <label for="oldimage">Uploaded Image File</label>
		  <select id="oldimage" name="oldimage" class="form-control">
			<? foreach($imagesArray as $image) { 
				if($image['imageURL'] != null) { ?>
		  	  <option value="<?=$image['imageURL']?>">
		  	  	<? if($image['is_radiomix'] == 1) {
		  	  		echo $image['event'];
		  	  	} else {
		  	  		echo $image['event'];
		  	  	} ?>
		  	  </option>
		  	<? }
		  	} ?>
		  </select>
			<img id="thumbnail" style="width: 200px; height: 200px;">
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
  	$('#directuploadcheckbox').change(function() {
  		if(this.checked) {
  			$('#songfilepicker').hide();
  			$('#directuploadpicker').show();
  		} else {
  			$('#songfilepicker').show();  			
  			$('#directuploadpicker').hide();
  		}
  	});
  	$('#oldimagecheckbox').change(function() {
  		if(this.checked) {
  			$('#imagepicker').hide();
  			$('#oldimagepicker').show();
  			showImage();
  		} else {
  			$('#imagepicker').show();
  			$('#oldimagepicker').hide();
  		}
  	});
  	$('#oldimage').change(function() {
  		showImage();
  	});
  });
	function showImage() {
		$('#thumbnail').attr("src", "http://stredm.com/uploads/"+$('#oldimage').val());
	}
  </script>
</html>
