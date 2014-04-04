<?php
require_once './basequeries.php';

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

	$baseQueries = new BaseQueries();

	$setsArray = $baseQueries->setQuery("WHERE 1 ", "ORDER BY s.is_deleted ASC, a.artist ASC, s.id ASC", null, true);
	foreach ($setsArray as $set) {
		if($set['is_deleted'] == 1) {
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
	  	<? foreach ($setsArray as $index => $set) { ?>
		  	<tr>
		  	  <td>
			  	<div id="id-<?=$set['id']?>" class="form-group">
		  		  <?=$set['id']?>
			  	</div>
		  	  </td>
		  	  <td>
			  	<div id="" class="form-group">
				  <?=$set['artist']?>
				</div>
		  	  </td>
		  	  <td>
			  	<div id="" class="form-group">
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
		  	  	<div class="form-group">
				  <button id="addtrack-<?=$set['id']?>" data-id="<?=$set['id']?>" data-title="<?=$set['artist']." - ".$set['event']?>" class="btn btn-small btn-primary" data-toggle="modal" data-target="#tracklistModal">
					Add Tracks
				  </button>
				  <textarea id="tracklist-<?=$set['id']?>" data-trackcount="<? echo count($set['tracklist']); ?>" style="display: none;">
				  	<? if(!empty($set['tracklist'])) {
				  		$joinStr = "";
				  		foreach ($set['tracklist'] as $trackIndex => $track) { 
				  			echo $joinStr;
				  			$joinStr = ",";
							echo $track;
						}
				  	} ?>
				  </textarea>
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

	<div class="modal fade" id="tracklistModal" tabindex="-1" role="dialog" aria-labelledby="tracklistModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" id="closeModal" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="tracklistModalLabel">Add Tracks</h4>
	      </div>
	      <div class="modal-body">
			<form id="trackForm" action="/scripts/tracks.php" method="POST">
				<table id="setsTable" class="table">
				  	<thead><tr><th>Tracks</th></tr></thead>
					<tbody id="trackFormBody"></tbody>
				</table>
				<input id="trackFormSetId" name="set_id" type="hidden">
			</form>
	      	<div class="btn btn-success" id="addTrack">
	      		Add A Track
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" id="closeTracks" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="saveTracks" class="btn btn-primary">Save Tracks</button>
	      </div>
	    </div>
	  </div>
	</div>
  </body>
  <script src="/js/jquery-1.9.1.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
  	var trackNo = 0;
	$('#closeTracks').click(function() {
  		$('#trackFormBody').empty();
  	});
  	$('#closeModal').click(function() {
  		$('#trackFormBody').empty();
  	});
  	$('#saveTracks').click(function() {
  		$('#trackForm').submit();
  	});
  	$("[id|='addtrack']").click(function() {
  		var id = $(this).attr('data-id');
  		$('#trackFormBody').empty();
  		$('#trackFormSetId').val(id);
  		var tracks = $('#tracklist-'+id).val().split(",");
  		var tracklistTextarea = "<tr><td><div class='form-group'><textarea"
  			+" name='tracklist' class='form-control' rows='12'>";
  		$.each(tracks, function(index, value) {
			tracklistTextarea += value.trim() + "\n";
  		});
		tracklistTextarea += "</textarea></div></td></tr>";
		$('#trackFormBody').append(tracklistTextarea);
  	});
  });
  </script>
</html>
