<?php

// String version of query below
// SELECT s.id, group_concat(a.artist order by sa.number ASC separator ' & ') artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s INNER JOIN sets_to_artists sa ON sa.set_id = s.id INNER JOIN artists AS a ON a.id = sa.artist_id INNER JOIN events AS e ON e.id = s.event_id INNER JOIN images AS i ON i.id = e.image_id INNER JOIN genres AS g ON g.id = s.genre_id WHERE s.is_deleted IS FALSE AND a.artist = 'fedde le grand' GROUP BY s.id ORDER BY s.id ASC, sa.number ASC
class BaseQueries {

	private $con;
	private $db;

	public function __construct() {
		$this->connect();
	}

	public function __destruct() {
		$this->disconnect();
	}

	private function connect() {
		$this->db = new mysqli("localhost", "strenbum_user","passw0rd", "strenbum_stredm");

		if (mysqli_connect_errno())
		{
			die('Could not connect: ' . mysql_error());
		}
	}

	private function disconnect() {
		if($this->db) {
			$this->db->close();
		}
	}

	public function setQuery($whereClause = null, $orderClause = null, $matchField = null, $allFields = false) {
		$resultArray = array();
		$sql = "SELECT s.id, group_concat(a.artist order by sa.number ASC separator ' & ') artist, ".
		"e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s ".
		"INNER JOIN sets_to_artists sa ON sa.set_id = s.id ".
		"INNER JOIN artists AS a ON a.id = sa.artist_id ".
		"INNER JOIN events AS e ON e.id = s.event_id ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"INNER JOIN genres AS g ON g.id = s.genre_id ";
		if($whereClause != null && $whereClause != "") {
			$sql .= $whereClause;
		} else {
			$sql .= " WHERE s.is_deleted IS FALSE ";
		}
		$sql .= " GROUP BY s.id ";
		if($orderClause != null && $orderClause != "") {
			$sql .= $orderClause;
		} else {
			$sql .= " ORDER BY s.id ASC, sa.number ASC ";
		}
		$result = $this->db->query($sql);
		$sql = "SELECT s.id, group_concat(a.artist order by sa.number ASC separator ' & ') artist, ".
		"e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix ";
		if($allFields) {
			$sql .= " , s.is_deleted ";
		}
		$sql .= " FROM sets AS s ".
		"INNER JOIN sets_to_artists sa ON sa.set_id = s.id ".
		"INNER JOIN artists AS a ON a.id = sa.artist_id ".
		"INNER JOIN events AS e ON e.id = s.event_id ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"INNER JOIN genres AS g ON g.id = s.genre_id ".
		"WHERE s.id IN(";
		$joiner = "";
		$resultsFound = false;
		while($row = $result->fetch_assoc()) {
			$resultsFound = true;
			$sql .= $joiner;
			$joiner = ", ";
			$sql .= $row['id'];
		}
		if($resultsFound) {

			$sql .= ") ";
			$sql .= "GROUP BY s.id ";
			if($orderClause != null && $orderClause != "") {
				$sql .= $orderClause;
			} else {
				$sql .= " ORDER BY s.id ASC, sa.number ASC ";
			}
			$result = $this->db->query($sql);
			$resultArray = $this->fetchRows($result, $matchField, $allFields);
		}
		return $resultArray;
	}

	public function fetchRows($result, $matchField, $allFields) {
		$i = 0;
		$resultArray = array();
		while($row = $result->fetch_assoc()) {
			$resultArray[$i]['id'] = $row['id'];
			$resultArray[$i]['artist'] = $row['artist'];
			$resultArray[$i]['event'] = $row['event'];
			$resultArray[$i]['genre'] = $row['genre'];
			$resultArray[$i]['imageURL'] = $row['imageURL'];
			$resultArray[$i]['songURL'] = $row['songURL'];
			$resultArray[$i]['is_radiomix'] = $row['is_radiomix'];
			if($matchField != null) {
				$resultArray[$i]['match_type'] = $matchField;
			}
			if($allFields) {
				$resultArray[$i]['is_deleted'] = $row['is_deleted'];
			}
			$i++;
		}
		return $resultArray;
	}

	public function artistQuery($simple = false) {
		$sql = "SELECT DISTINCT a.id, a.artist FROM sets AS s ".
		"INNER JOIN sets_to_artists AS sa ON sa.set_id = s.id ".
		"INNER JOIN artists AS a ON sa.artist_id = a.id ".
		"WHERE s.is_deleted IS FALSE ".
		"ORDER BY a.artist ASC";
		$result = $this->db->query($sql);
		$i = 0;
		$resultArray = array();
		while($artistRow = $result->fetch_assoc()) {
			if($simple) {
				$resultArray[$i] = $artistRow['artist'];
			} else {
				$resultArray[$i]['id'] = $artistRow['id'];
				$resultArray[$i]['artist'] = $artistRow['artist'];
			}
			$i++;
		}
		return $resultArray;
	}

	public function eventQuery($simple = false) {
		$sql = "SELECT DISTINCT e.id, e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
		"WHERE s.is_deleted IS FALSE AND e.is_radiomix IS FALSE ORDER BY e.event ASC";
		$result = $this->db->query($sql);
		$i = 0;
		$resultArray = array();
		while($eventRow = $result->fetch_assoc()) {
			if($simple) {
				$resultArray[$i] = $eventRow['event'];
			} else {
				$resultArray[$i]['id'] = $eventRow['id'];
				$resultArray[$i]['event'] = $eventRow['event'];
			}
			$i++;
		}
		return $resultArray;
	}

	public function radiomixQuery($simple = false) {
		$sql = "SELECT DISTINCT e.id, e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
		"WHERE s.is_deleted IS FALSE AND e.is_radiomix IS TRUE ORDER BY e.event ASC";
		$result = $this->db->query($sql);
		$i = 0;
		$resultArray = array();
		while($radiomixRow = $result->fetch_assoc()) {
			if($simple) {
				$resultArray[$i] = $radiomixRow['event'];
			} else {
				$resultArray[$i]['id'] = $radiomixRow['id'];
				$resultArray[$i]['radiomix'] = $radiomixRow['event'];
			}
			$i++;
		}
		return $resultArray;
	}

	public function genreQuery($simple = false) {
		$sql = "SELECT DISTINCT g.id, g.genre FROM sets AS s INNER JOIN genres AS g ON s.genre_id = g.id ".
		"WHERE s.is_deleted IS FALSE ORDER BY g.genre ASC";
		$result = $this->db->query($sql);
		$i = 0;
		$resultArray = array();
		while($genreRow = $result->fetch_assoc()) {
			if($simple) {
				$resultArray[$i] = $genreRow['genre'];
			} else {
				$resultArray[$i]['id'] = $genreRow['id'];
				$resultArray[$i]['genre'] = $genreRow['genre'];
			}
			$i++;
		}
		return $resultArray;
	}

	public function imageQuery() {
		$sql = "SELECT i.id, i.imageURL FROM sets AS s ".
			"INNER JOIN events AS e ON e.id = s.event_id ".
			"INNER JOIN images AS i ON i.id = e.image_id ".
			"WHERE 1 GROUP BY i.imageURL";
			$result = $this->db->query($sql);
		$i = 0;
		$resultArray = array();
		while($imageRow = $result->fetch_assoc()) {
			$resultArray[$i]['id'] = $imageRow['id'];
			$resultArray[$i]['imageURL'] = $imageRow['imageURL'];
			$i++;
		}
		return $resultArray;
	}

	public function allArtists() {
		$artistsArray = array();
		$sql = "SELECT * FROM artists WHERE 1 order by artist";
		$result = $this->db->query($sql);
		$i = 0;
		while($row = $result->fetch_assoc())
		{
			$artistsArray[$i] = $row;
			$i++;
		}
		return $artistsArray;
	}

	public function allEvents() {
		$eventsArray = array();
		$sql = "SELECT e.id, e.event, i.imageURL FROM events AS e ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"WHERE is_radiomix IS FALSE order by event";
		$result = $this->db->query($sql);
		$i = 0;
		while($row = $result->fetch_assoc())
		{
			$eventsArray[$i] = $row;
			$i++;
		}
		return $eventsArray;
	}

	public function allRadiomixes() {
		$radiomixesArray = array();
		$sql = "SELECT e.id, e.event, i.imageURL FROM events AS e ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"WHERE is_radiomix IS TRUE order by event";
		$result = $this->db->query($sql);
		$i = 0;
		while($row = $result->fetch_assoc())
		{
			$radiomixesArray[$i] = $row;
			$i++;
		}
		return $radiomixesArray;
	}

	public function allGenres() {
		$genresArray = array();
		$sql = "SELECT * FROM genres WHERE 1 order by genre";
		$result = $this->db->query($sql);
		$i = 0;
		while($row = $result->fetch_assoc())
		{
			$genresArray[$i] = $row;
			$i++;
		}
		return $genresArray;
	}

	public function allImages() {
		$imagesArray = array();
		$sql = "SELECT e.id, i.imageURL, e.is_radiomix, e.event FROM events AS e ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"WHERE 1 GROUP BY i.imageURL";
		$result = $this->db->query($sql);
		$i = 0;
		while($row = $result->fetch_assoc())
		{
			$imagesArray[$i] = $row;
			$i++;
		}
		return $imagesArray;
	}

	public function run($sql) {
		return $this->db->query($sql);
	}

	public function addSet() {
		if(checkId('artist') && (checkId('event') || checkId('radiomix')) && checkId('genre')) {
			$artist_id = setId('artist', $con);
			$genre_id = setId('genre', $con);
			$tracklist = checkAddSlashes($_POST['tracklist']);

			$songURL = null;
			if(isset($_POST['directuploadcheckbox'])) {
			    $songURL = moveFile($_POST['directupload']);
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
			$result = $this->db->query(l);
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
			$result = $this->db->query(l);
			$sql = "SELECT id FROM ".$type."$plural WHERE $type = '$newtype'";
			$result = $this->db->query(l);
			while($row = $result->fetch_assoc()) {
				return $row['id'];
			}
		} else {
			return (int)$str;
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
}

?>