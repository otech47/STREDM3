<?php
require_once './basequeries.php';
session_start();

require_once './aws.phar';

if(!session_is_registered("user")){
	header("location:/scripts/login.php");
	exit;
} else {
	$baseQueries = new BaseQueries();

	$artistsArray = $baseQueries->allArtists();

	$eventsArray = $baseQueries->allEvents();

	$radiomixesArray = $baseQueries->allRadiomixes();

	$genresArray = $baseQueries->allGenres();

	$directUploadsArray = array();
	$files = scandir("/home/strenbum/direct_uploads");
  $i = 0;
	foreach ($files as $key => $file) {
		if(strpos($file, '.') !== (int) 0) {
			$directUploadsArray[$i]['filename'] = $file;
      $i++;
		}
	}

}
?>
<!DOCTYPE html>
<html>
  <head>
  	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
  	<link href="/css/selectize.bootstrap3.css" rel="stylesheet" media="screen">
    <title>Stredm</title>
  </head>
  <body style="font-size: 16px;">
  	<div class="alert alert-success fade in">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
      <strong>Ready!</strong> The page is ready. First, choose a song file. Then, pick the artist(s). You can drag and drop these. Order will be retained.<br>
      Select an event or radio mix, and overwrite the image if it looks wrong. Choose a genre and hit submit.
    </div>
  	<div class="container">
  	  <h1>Upload A Set</h1>
	  <a href="/scripts/logout.php" class="btn btn-danger" role="button">Log Out</a>
	  <a href="/scripts/list.php" class="btn btn-info" role="button">Set List</a>
    <br>
	  <form name="set" action="/scripts/sets.php" role="form" method='POST' enctype='multipart/form-data' novalidate>
      <div class="form-group" id="directuploadpicker">
        <label for="directupload">Choose an Uploaded Song File</label>
        <input type="text" id="directupload" name="directupload" class="form-control" required>
      </div>

	  	<div class="form-group">
      	<label for="artist">Artist</label>
    		<input id="artist" name="artist" type="text" data-role="tagsinput" placeholder="Type one or more artists" required>
      </div>
  		<div class="checkbox">
  			<label>
  				<input id="radiomixcheckbox" name="radiomixcheckbox" type="checkbox" value="radiomix"> Is this a Radio Mix?
  			</label>
  		</div>
  	  <div class="form-group" id="eventpicker">
      	<label for="event">Event</label>
    		<input id="event" name="event" type="text" data-role="tagsinput" placeholder="Type an event">
        <label id="updatedEventLabel" for="updatedEventImage" style="display:none;">Update or Upload an image (optional)</label>
        <input type="file" id="updatedEventImage" name="updatedEventImage" class="form-control" style="display:none;"/>
  		</div>
  	  <div class="form-group" id="radiomixpicker" style="display:none;">
      	<label for="radiomix">Radio Mix</label>
  		  <input id="radiomix" name="radiomix" type="text" data-role="tagsinput" placeholder="Type a radio mix">
        <label for="episode">Episode Number</label>
        <input id="episode" name="episode" type="text" class="form-control" placeholder="Type an episode number or description">
        <label id="updatedRadiomixLabel" for="updatedRadiomixImage" style="display:none;">Update or Upload an image (optional)</label>
        <input type="file" id="updatedRadiomixImage" name="updatedRadiomixImage" class="form-control" style="display:none;"/>
  		</div>
  	  <div class="form-group">
      	<label for="genre">Genre</label>
  		  <input type="text" id="genre" name="genre" class="form-control" placeholder="Type a genre" required>
  		</div>
  		<!-- <div class="form-group">
      	<label for="tracklist">Track List</label>
  		  <input type="text" id="tracklist" name="tracklist" class="form-control" placeholder="Type the tracks in order (optional)">
  		</div> -->
  		<button value="submit" class="btn btn-success">Submit</button>
	  </form>
    </div>
  </body>
  <script src="/js/jquery-1.9.1.js"></script>
  <script src="/js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/selectize.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
  	$('#radiomixcheckbox').change(function() {
  		if(this.checked) {
  			$('#eventpicker').hide();
  			$('#radiomixpicker').show();
        $('#radiomix').prop('required', true);
        $('#event').removeAttr('required');
  		} else {
  			$('#eventpicker').show();  			
  			$('#radiomixpicker').hide();
        $('#event').prop('required', true);
        $('#radiomix').removeAttr('required');
  		}
  	});

    // initialize event as required
    $('#event').prop('required', true);

    $('#event').change(function() {
      showImageUpdater(true);
    });

    $('#radiomix').change(function() {
  		showImageUpdater(false);
  	});

    // $('#directupload').selectize({
    //     maxItems: 1,
    //     labelField: 'filename',
    //     valueField: 'filename',
    //     searchField: 'filename',
    //     options: <?php echo json_encode($directUploadsArray); ?>,
    // });

    $('#artist').selectize({
      plugins: ['remove_button', 'drag_drop'],
      delimiter: ',',
      persist: false,
      maxItems: null,
      labelField: 'artist',
      valueField: 'artist',
      searchField: 'artist',
      options: <?php echo json_encode($artistsArray); ?>,
      create: function(input) {
          return {
              artist: input
          }
      }
    });

    $('#event').selectize({
      persist: false,
      maxItems: 1,
      labelField: 'event',
      valueField: 'event',
      searchField: 'event',
      options: <?php echo json_encode($eventsArray); ?>,
      render: {
        item: function(item, escape) {
            return '<div>' +
                (item.event ? '<span class="event">' + escape(item.event) + '</span>' : '') +
                (item.imageURL ? '<img id="thumbnail" style="width:200px; height:200px;" src="http://stredm.com/uploads/' + escape(item.imageURL) + '">' : '') +
            '</div>';
        }
      },
      create: function(input) {
          return {
              event: input
          }
      }
    });

    $('#radiomix').selectize({
      persist: false,
      maxItems: 1,
      labelField: 'radiomix',
      valueField: 'radiomix',
      searchField: 'radiomix',
      options: <?php echo json_encode($radiomixesArray); ?>,
      render: {
        item: function(item, escape) {
            return '<div>' +
                (item.radiomix ? '<span class="radiomix">' + escape(item.radiomix) + '</span>' : '') +
                (item.imageURL ? '<img id="thumbnail" style="width:200px; height:200px;" src="http://stredm.com/uploads/' + escape(item.imageURL) + '">' : '') +
            '</div>';
        }
      },
      create: function(input) {
          return {
              radiomix: input
          }
      }
    });

    $('#genre').selectize({
      delimiter: ',',
      persist: false,
      maxItems: 1,
      labelField: 'genre',
      valueField: 'genre',
      searchField: 'genre',
      options: <?php echo json_encode($genresArray); ?>,
      create: function(input) {
          return {
              genre: input
          }
      }
    });

    // $('#tracklist').selectize({
    //   plugins: ['remove_button', 'drag_drop'],
    //   delimiter: ',',
    //   persist: false,
    //   maxItems: null,
    //   labelField: 'tracklist',
    //   valueField: 'tracklist',
    //   searchField: 'tracklist',
    //   create: function(input) {
    //       return {
    //           tracklist: input
    //       }
    //   }
    // });

  });
	function showImageUpdater(eventImage) {
    if(eventImage) {
      $('#updatedEventImage').show();
      $('#updatedEventLabel').show();
    } else {
      $('#updatedRadiomixImage').show();
      $('#updatedRadiomixLabel').show();
    }
	}
    </script>
</html>
