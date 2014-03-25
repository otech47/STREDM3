<?php

// String version of query below
// SELECT s.id, group_concat(a.artist order by sa.number ASC separator ' & ') artist, e.event, g.genre, i.imageURL, s.songURL, e.is_radiomix FROM sets AS s INNER JOIN sets_to_artists sa ON sa.set_id = s.id INNER JOIN artists AS a ON a.id = sa.artist_id INNER JOIN events AS e ON e.id = s.event_id INNER JOIN images AS i ON i.id = e.image_id INNER JOIN genres AS g ON g.id = s.genre_id WHERE s.is_deleted IS FALSE AND a.artist = 'fedde le grand' GROUP BY s.id ORDER BY s.id ASC, sa.number ASC

function setQuery($con, $whereClause = null, $orderClause = null, $matchField = null, $allFields = false) {
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
	$result = mysqli_query($con, $sql);
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
	while($row = mysqli_fetch_array($result)) {
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
		$result = mysqli_query($con, $sql);
		$resultArray = fetchRows($result, $matchField, $allFields);
	}
	return $resultArray;
}

function fetchRows($result, $matchField, $allFields) {
	$i = 0;
	$resultArray = array();
	while($row = mysqli_fetch_array($result)) {
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

function artistQuery($con, $simple = false) {
	$sql = "SELECT DISTINCT a.id, a.artist FROM sets AS s ".
	"INNER JOIN sets_to_artists AS sa ON sa.set_id = s.id ".
	"INNER JOIN artists AS a ON sa.artist_id = a.id ".
	"WHERE s.is_deleted IS FALSE ".
	"ORDER BY a.artist ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	$resultArray = array();
	while($artistRow = mysqli_fetch_array($result)) {
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

function eventQuery($con, $simple = false) {
	$sql = "SELECT DISTINCT e.id, e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
	"WHERE s.is_deleted IS FALSE AND e.is_radiomix IS FALSE ORDER BY e.event ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	$resultArray = array();
	while($eventRow = mysqli_fetch_array($result)) {
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

function radiomixQuery($con, $simple = false) {
	$sql = "SELECT DISTINCT e.id, e.event FROM sets AS s INNER JOIN events AS e ON s.event_id = e.id ".
	"WHERE s.is_deleted IS FALSE AND e.is_radiomix IS TRUE ORDER BY e.event ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	$resultArray = array();
	while($radiomixRow = mysqli_fetch_array($result)) {
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

function genreQuery($con, $simple = false) {
	$sql = "SELECT DISTINCT g.id, g.genre FROM sets AS s INNER JOIN genres AS g ON s.genre_id = g.id ".
	"WHERE s.is_deleted IS FALSE ORDER BY g.genre ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	$resultArray = array();
	while($genreRow = mysqli_fetch_array($result)) {
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

function imageQuery($con) {
	$sql = "SELECT i.id, i.imageURL FROM sets AS s ".
		"INNER JOIN events AS e ON e.id = s.event_id ".
		"INNER JOIN images AS i ON i.id = e.image_id ".
		"WHERE 1 GROUP BY i.imageURL";
		$result = mysqli_query($con, $sql);
	$i = 0;
	$resultArray = array();
	while($imageRow = mysqli_fetch_array($result)) {
		$resultArray[$i]['id'] = $imageRow['id'];
		$resultArray[$i]['imageURL'] = $imageRow['imageURL'];
		$i++;
	}
	return $resultArray;
}

?>