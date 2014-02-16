<?php
require_once './checkAddSlashes.php';

header('Content-Type: text/plain; charset=utf-8');

session_start();


if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}


$con = mysqli_connect("localhost", "otech47_sc", "soundcloud1","otech47_soundcloud");

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

$count = array();

$sql = "SELECT count(id) as c  FROM artists WHERE 1";
$result = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($result)) {
	$count['artist'] = $row['c'];
}

$sql = "SELECT count(id) as c  FROM events WHERE 1";
$result = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($result)) {
	$count['event'] = $row['c'];
}

$sql = "SELECT count(id) as c FROM genres WHERE 1";
$result = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($result)) {
	$count['genre'] = $row['c'];
}

$sql = "SELECT count(id) as c FROM radiomixes WHERE 1";
$result = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($result)) {
	$count['radiomix'] = $row['c'];
}

if(checkId('artist', $count) && (checkId('event', $count) || checkId('radiomix', $count)) && checkId('genre', $count)) {
	$artist_id = setId('artist', $con);
	$event_id = setId('event', $con);
	$genre_id = setId('genre', $con);
	$radiomix_id = setId('radiomix', $con);
	$tracklist = checkAddSlashes($_POST['tracklist']);
	$imageURL = uploadFile('imagefile');
	$songURL = uploadFile('songfile');
	$source = checkAddSlashes($_POST['source']);
	$self_hosted = 1;
	$is_radiomix = 0;
	if(isset($_POST['radiomixcheckbox'])) {
	    $is_radiomix = 1;
	}
	if($is_radiomix == 1) {
		$event_id = 0;
	} else {
		$radiomix_id = 0;
	}

	// echo "True $artist_id \t $event_id \t $genre_id \n$tracklist";
	$sql =	"INSERT IGNORE INTO sets(artist_id, event_id, radiomix_id, genre_id, songURL, imageURL, date, source, self_hosted, is_radiomix, tracklist) ".
			"VALUES ($artist_id, $event_id, '$radiomix_id', $genre_id, '$songURL', '$imageURL', now(), '$source', $self_hosted, $is_radiomix, '$tracklist')";
			// $result = $sql;
	$result = mysqli_query($con, $sql);
	if($result) {
		$_SESSION['success'] = "Success! Set uploaded. sql: $sql";
		// throw new RuntimeException("Success! Set uploaded. sql: $sql");
		header("location:/scripts/upload.php");
	} else {
		$_SESSION['failure'] = "Failed! There were errors inserting the set into the database. sql: $sql";
		throw new RuntimeException("Failed! There were errors inserting the set into the database. sql: $sql");
	}
} else {
	header("location:/error.html");
}

function checkId($type, $count) {
	$str = checkAddSlashes($_POST[$type]);
	if(is_numeric($str)) {
		$str = (int)$str;
		$max = $count[$type];
		if($str > -1 && $str <= $max) {
			// echo "yes '$str' max: '$max' ";
			return true;
		} else {
			// echo "no '$str' max: '$max' ";
			return false;
		}
	} else if($str == "new") {
		$newstr = checkAddSlashes($_POST['new'.$type]);
		if($newstr !== '') {
			// echo "yes '$str' newstr: '$newstr' ";
			return true;
		} else {
			// echo "no '$str' newstr: '$newstr' ";
			return false;	
		}
	} else {
		return false;
	}
}

function setId($type, $con) {
	$str = checkAddSlashes($_POST[$type]);
	if($str == "new") {
		$newtype = checkAddSlashes($_POST["new$type"]);
		$plural = ($type == 'radiomix') 'es' : 's';
		$sql = "INSERT IGNORE INTO $type"."$plural($type) VALUES ('$newtype')";
		$result = mysqli_query($con, $sql);
		$sql = "SELECT id FROM ".$type."$plural WHERE $type = '$newtype'";
		$result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result)) {
			return $row['id'];
		}
	} else {
		return (int)$str;
	}
}

function uploadFile($filename) {
	try {
    
	    // Undefined | Multiple Files | $_FILES Corruption Attack
	    // If this request falls under any of them, treat it invalid.
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
	        case UPLOAD_ERR_FORM_SIZE:
	            throw new RuntimeException('Exceeded filesize limit 1.');
	        default:
	            throw new RuntimeException('Unknown errors.');
	    }

	    // You should also check filesize here. 
	    if ($_FILES[$filename]['size'] > 157286400) {
	        throw new RuntimeException('Exceeded filesize limit.');
	    }

	    // DO NOT TRUST $_FILES[$filename]['mime'] VALUE !!
	    // Check MIME Type by yourself.
	    // $finfo = new finfo(FILEINFO_MIME_TYPE);
	    // if (false === $ext = array_search(
	    //     $finfo->file($_FILES[$filename]['tmp_name']),
	    //     array(
	    //         'jpg' => 'image/jpeg',
	    //         'png' => 'image/png',
	    //         'gif' => 'image/gif',
	    //         'mp3' => 'audio/mpeg'
	    //     ),
	    //     true
	    // )) {
	    //     throw new RuntimeException('Invalid file format.');
	    // }

	    // You should name it uniquely.
	    // DO NOT USE $_FILES[$filename]['name'] WITHOUT ANY VALIDATION !!
	    // On this example, obtain safe unique name from its binary data.
	    $newFilename = sha1_file($_FILES[$filename]['tmp_name']);
	    $ext = substr($_FILES[$filename]['name'], -3);
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
	return null;
}

?>