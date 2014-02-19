<?php
require_once './checkAddSlashes.php';

header('Content-Type: text/plain; charset=utf-8');

session_start();


if(!session_is_registered("user")) {
	header("location:/scripts/login.php");
	exit;
}

$con = mysqli_connect("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

if(checkId('artist') && (checkId('event') || checkId('radiomix')) && checkId('genre')) {
	$artist_id = setId('artist', $con);
	$genre_id = setId('genre', $con);
	$tracklist = checkAddSlashes($_POST['tracklist']);

	$songURL = null;
	if(isset($_POST['directuploadcheckbox'])) {
		$moveURL = markAsMoved($_POST['directupload'], $con);
	    $songURL = moveFile($moveURL);
	} else {
		$songURL = uploadFile('songfile');
	}

	$imageURL = null;
	if(isset($_POST['oldimagecheckbox'])) {
		$imageURL = $_POST['oldimage'];
	} else {
		$imageURL = uploadFile('imagefile');
	}

	$is_radiomix = 0;
	if(isset($_POST['radiomixcheckbox'])) {
	    $is_radiomix = 1;
	}

	if($is_radiomix == 1) {
		$event_id = 0;
		$radiomix_id = setId('radiomix', $con);
	} else {
		$event_id = setId('event', $con);
		$radiomix_id = 0;
	}

	// echo "True $artist_id \t $event_id \t $genre_id \n$tracklist";
	$sql =	"INSERT IGNORE INTO sets(artist_id, event_id, radiomix_id, genre_id, songURL, imageURL, datetime, is_radiomix, tracklist) ".
			"VALUES ($artist_id, $event_id, '$radiomix_id', $genre_id, '$songURL', '$imageURL', now(), $is_radiomix, '$tracklist')";
			// $result = $sql;
	$result = mysqli_query($con, $sql);
	if($result) {
		$_SESSION['success'] = "Success! Set uploaded. sql: $sql";
		// throw new RuntimeException("Success! Set uploaded. sql: $sql");
		header("location:/scripts/upload.php");
	} else {
		$_SESSION['failure'] = "Failed! There were errors inserting the set into the database. sql: $sql";
		header("location:/scripts/upload.php");
	}
} else {
	echo checkId('artist') . ":" . checkId('event') . ":" . checkId('radiomix') . ":" . checkId('genre');
	echo "\r\n";
	echo ini_get('upload_max_filesize');
	echo "\r\n";
	echo ini_get('post_max_size');
	echo "\r\n";
	echo ini_get('max_execution_time');
	echo "\r\n";
	echo ini_get('max_input_time');
	echo "\r\n";
	echo ini_get('memory_limit');

	// header("location:/scripts/upload.php");
}

function checkId($type) {
	$str = checkAddSlashes($_POST[$type]);
	if(is_numeric($str)) {
		$str = (int)$str;
		if($str > -1) {
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
		$plural = ($type == 'radiomix')? 'es' : 's';
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

function markAsMoved($id, $con) {
	$sql = "UPDATE direct_uploads SET is_moved = 1 WHERE id=$id";
	$result = mysqli_query($con, $sql);
	$sql = "SELECT path FROM direct_uploads WHERE id=$id";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		return $row['path'];
	}
}

function moveFile($filename) {
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
	    // echo 'File is uploaded successfully.';

	} catch (RuntimeException $e) {

	    echo $e->getMessage();
	    return null;
	}
	return null;
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
	            throw new RuntimeException('Exceeded filesize limit in php.ini.');
	        case UPLOAD_ERR_FORM_SIZE:
	            throw new RuntimeException('Exceeded filesize limit in form.');
	        default:
	            throw new RuntimeException('Unknown errors.');
	    }

	    // You should also check filesize here. 
	    // if ($_FILES[$filename]['size'] > 157286400) {
	    //     throw new RuntimeException('Exceeded filesize limit.');
	    // }

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
	return null;
}

?>