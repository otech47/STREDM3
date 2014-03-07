<?php
session_start();
$i = 0;
$deletedSets = 0;
$success = null;
$failure = null;
if(!session_is_registered("user")){
	header("location:/scripts/login.php");
	exit;
} else {

	$success = $_SESSION['success'];
	$_SESSION['success'] = null;

	$failure = $_SESSION['failure'];
	$_SESSION['failure'] = null;

	$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	$setsArray = array();
	$sql = "SELECT s.id, s.songURL, i.imageURL, s.datetime, s.popularity, s.tracklist, e.is_radiomix, s.is_deleted, a.artist, e.event, g.genre ".
			"FROM sets s ".
			"INNER JOIN artists AS a ON a.id = s.artist_id ".
			"INNER JOIN events AS e ON e.id = s.event_id ".
			"INNER JOIN images AS i ON i.id = e.image_id ".
			"INNER JOIN genres AS g ON g.id = s.genre_id ".
			"WHERE 1 ".
			"ORDER BY s.is_deleted ASC, a.artist ASC, s.id ASC";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$setsArray[$i] = $row;
		if($setsArray[$i]['is_deleted'] == 1) {
			$deletedSets++;
		}
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
  	  <h1><?=$i-$deletedSets?> Valid Sets / <?=$deletedSets?> Deleted Sets</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/upload.php" class="btn btn-info" role="button">Go to Uploads</a>
	  <? if($success) { ?>
		<div class="alert alert-success"><?=$success?></div>
	  <? } if($failure) { ?>
	    <div class="alert alert-danger"><?=$failure?></div>
	  <? } ?>
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
			  	  <? if(!$set['is_radiomix']) { ?>
			  		<?=$set['event']?>
			  	  <? } ?>
				</div>
		  	  </td>
		  	  <td>
			  	<div class="form-group">
			  	  <? if($set['is_radiomix']) { ?>
			  		<?=$set['event']?>
			  	  <? } ?>
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
		  	  <? if($set['is_deleted'] == 0) { ?>
		  	  	<form action="/scripts/delete.php" method="POST">
		  	  	<input type="hidden" name="id" value="<?=$set['id']?>"/>
		  	  	<button name="submit" class="btn btn-small btn-danger" type="submit" value="del">
		  	  		Delete
		  	  	</div>
		  	  	</form>
		  	  <? } else { ?>
		  	  	<form action="/scripts/restore.php" method="POST">
		  	  	<input type="hidden" name="id" value="<?=$set['id']?>"/>
		  	  	<button name="submit" class="btn btn-small btn-success" type="submit" value="res">
		  	  		Restore
		  	  	</div>
		  	  	</form>
		  	  <? } ?>
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
