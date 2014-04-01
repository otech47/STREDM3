<?php
require_once './checkAddSlashes.php';
require_once './basequeries.php';

header('Content-Type: text/plain; charset=utf-8');

session_start();


if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}

$baseQueries = new BaseQueries();

$artists = explode(',', $_POST['artist']);
$isRadiomix = $_POST['radiomixcheckbox'];
$event = $_POST['event'];
$radiomix = $_POST['radiomix'];
$directupload = $_POST['directupload'];
$genre = $_POST['genre'];
// $tracklist = explode(',', $_POST['tracklist']);
$genreId = -1;

// find event/radiomix and genre ids
$genreId = $baseQueries->runInsertGetId("INSERT INTO genres(genre) VALUES ('$genre') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");

check(($genreId == -1), "genre: ".$genre);
// replace image
$imageURL = "";
if($radiomixcheckbox == true) {
	$imageURL = uploadFile('updatedRadiomixImage');
} else {
	$imageURL = uploadFile('updatedEventImage');
}

$imageId = -1;
if($imageURL != "") {
	$imageId = $baseQueries->runInsertGetId("INSERT INTO images(imageURL) VALUES ('$imageURL') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
}

$eventId = -1;
if($radiomixcheckbox == true) {
	if($imageId >= 0) {
		$eventId = $baseQueries->runInsertGetId("INSERT INTO events(event, image_id, is_radiomix) VALUES ('$radiomix', '$imageId', true) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	} else {
		$eventId = $baseQueries->runInsertGetId("INSERT INTO events(event, is_radiomix) VALUES ('$radiomix', true) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	}
	check(($eventId == -1), "event: ".$radiomix);
} else {
	if($imageId >= 0) {
		$eventId = $baseQueries->runInsertGetId("INSERT INTO events(event, image_id, is_radiomix) VALUES ('$event', '$imageId', false) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	} else {
		$eventId = $baseQueries->runInsertGetId("INSERT INTO events(event, is_radiomix) VALUES ('$event', false) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	}
	check(($eventId == -1), "event: ".$event);
}
// move file
$songURL = moveFile($directupload);
check(($songURL == null), "file to upload: ".$directupload);

// insert set
$setId = -1;
$sql =	"INSERT IGNORE INTO sets(event_id, genre_id, songURL, datetime, popularity) ".
		"VALUES ($eventId, $genreId, '$songURL', now(), 0)";
$baseQueries->run($sql);
$setId = $baseQueries->lastId();
check(($setId == -1), "set: VALUES ($eventId, $genreId, '$songURL', now(), 0)");

// add a2s
$i = 0;
$artistId = 0;
foreach ($artists as $artist) {
	$artistId = $baseQueries->runInsertGetId("INSERT INTO artists(artist) VALUES ('$artist') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	$sql = "INSERT IGNORE INTO sets_to_artists(set_id, artist_id, number) VALUES ('$setId', '$artistId', $i)";
	$baseQueries->run($sql);
	$i++;
}

// add tracks
// $i = 0;
// foreach ($tracklist as $track) {
// 	$baseQueries->run("INSERT IGNORE INTO tracks(set_id, number, track, start_time) ".
// 		"VALUES ( $setId, $i, '$track', )");
// 	$i++;
// }

header("location:/scripts/upload.php");

function moveFile($filename) {
	if($filename != "") {
		try {
		    $newFilename = sha1_file("/home/strenbum/direct_uploads/" . $filename);
		    $ext = pathinfo($filename, PATHINFO_EXTENSION);
		    $newFilename = $newFilename . "." . $ext;
		    if (!rename(
		        	    "/home/strenbum/direct_uploads/" . $filename,
		        sprintf('./../uploads/%s', $newFilename)
		    )) {
		        throw new RuntimeException('Failed to move uploaded file.');
		    }
		    return $newFilename;
		} catch (RuntimeException $e) {
		    echo $e->getMessage();
		    return null;
		}
	}
	return null;
}

function uploadFile($filename) {
	if($_FILES[$filename]['tmp_name'] != "") {
		try {
	    
		    if (
		        !isset($_FILES[$filename]['error']) ||
		        is_array($_FILES[$filename]['error'])
		    ) {
		        throw new RuntimeException('Invalid parameters.');
		    }

		    // Check $_FILES[$filename]['error'] value.
		    switch ($_FILES[$filename]['error']) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
		            throw new RuntimeException('No file sent.');
		        case UPLOAD_ERR_INI_SIZE:
		            throw new RuntimeException('Exceeded filesize limit in php.ini.');
		        case UPLOAD_ERR_FORM_SIZE:
		            throw new RuntimeException('Exceeded filesize limit in form.');
		        default:
		            throw new RuntimeException('Unknown errors.');
		    }

		    $newFilename = sha1_file($_FILES[$filename]['tmp_name']);
		    $ext = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
		    $newFilename = $newFilename . "." . $ext;
		    if (!move_uploaded_file(
		        $_FILES[$filename]['tmp_name'],
		        sprintf('./../uploads/%s', $newFilename)
		    )) {
		        throw new RuntimeException('Failed to move uploaded file.');
		    }
		    return $newFilename;
		    // echo 'File is uploaded successfully.';

		} catch (RuntimeException $e) {

		    echo $e->getMessage();
		    return null;
		}
	}
	return null;
}

function check($err, $msg) {
	if($err) {
		echo $msg . "\r\n";
		exit;
	}
}
?>