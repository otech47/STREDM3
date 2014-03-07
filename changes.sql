insert ignore into artists(artist) SELECT DISTINCT artist FROM `sets` WHERE sets.artist IS NOT NULL order by artist

insert ignore into events(event) SELECT DISTINCT event FROM `sets` WHERE sets.event IS NOT NULL order by event

insert ignore into genres(genre) SELECT DISTINCT genre FROM `sets` WHERE sets.genre IS NOT NULL order by genre

insert ignore into radiomixes(radiomix) SELECT DISTINCT radiomix FROM `sets` WHERE sets.radiomix IS NOT NULL order by radiomix


UPDATE sets s
JOIN artists a ON s.artist = a.artist
SET s.artist_id = a.id
WHERE s.artist = a.artist

UPDATE sets s
JOIN genres g ON s.genre = g.genre
SET s.genre_id = g.id
WHERE s.genre = g.genre

UPDATE sets s
JOIN events e ON s.event = e.event
SET s.event_id = e.id
WHERE s.event = e.event

UPDATE sets s
JOIN radiomixes r ON s.radiomix = r.radiomix
SET s.radiomix_id = r.id
WHERE s.radiomix = r.radiomix

UPDATE sets s
JOIN radiomixes r ON s.radiomix = r.radiomix
SET s.is_radiomix = 1
WHERE s.radiomix = r.radiomix

insert ignore into events(event, is_radiomix) SELECT DISTINCT radiomix, is_radiomix FROM radiomixes WHERE 1 order by radiomix

UPDATE events e
JOIN radiomixes r ON e.event = r.radiomix
SET e.is_radiomix = 1
WHERE e.event = r.radiomix


SELECT * FROM `events` e JOIN radiomixes r ON e.event = r.radiomix Join sets s on r.id = s.radiomix_id WHERE 1

UPDATE sets s
JOIN radiomixes r ON s.radiomix_id = r.id
JOIN events e ON e.event = r.radiomix
SET s.event_id = e.id
WHERE e.event = r.radiomix

SELECT s.event_id, s.radiomix_id FROM sets s JOIN radiomixes r ON s.radiomix_id = r.id JOIN events e on s.event_id = e.id WHERE e.event = r.radiomix

UPDATE events e
JOIN sets s ON e.id = s.event_id
SET e.imageURL = s.imageURL
WHERE 1

SELECT event_id, imageURL
FROM sets s1
WHERE imageURL= 
    (SELECT imageURL
     FROM sets s2
     WHERE s1.event_id= s2.event_id
     AND s2.imageURL IS NOT NULL 
     AND s2.imageURL != '' 
     GROUP BY imageURL
     ORDER BY COUNT(imageURL) DESC
      LIMIT 1)
GROUP BY event_id, imageURL

UPDATE events e
SET e.imageURL = (
	SELECT imageURL
	FROM sets s1
	WHERE imageURL= 
	    (SELECT imageURL
	     FROM sets s2
	     WHERE s1.event_id= s2.event_id
	     AND s2.imageURL IS NOT NULL 
	     AND s2.imageURL != '' 
	     GROUP BY imageURL
	     ORDER BY COUNT(imageURL) DESC
	      LIMIT 1)
	GROUP BY event_id, imageURL) WHERE 1

SELECT DISTINCT e.imageURL FROM events e WHERE 1

UPDATE events e
INNER JOIN (
	SELECT s1.imageURL as siu, s1.event_id as sei
	FROM sets s1
	WHERE imageURL= 
	    (SELECT imageURL
	     FROM sets s2
	     WHERE s1.event_id= s2.event_id
	     AND s2.imageURL IS NOT NULL 
	     AND s2.imageURL != '' 
	     GROUP BY imageURL
	     ORDER BY COUNT(imageURL) DESC
	      LIMIT 1)
	GROUP BY event_id, imageURL) sc ON e.id = sc.sei SET e.imageURL = sc.siu


insert ignore into images(imageURL) SELECT DISTINCT imageURL FROM events WHERE 1

UPDATE events e
JOIN images i ON e.imageURL = i.imageURL
SET e.image_id = i.id
WHERE 1

insert ignore into sets_to_artists(artist, set_id) SELECT a.artist, s.id FROM sets AS s INNER JOIN artists AS a ON a.id = s.artist_id WHERE 1 IS NOT NULL order by a.artist

insert ignore into artists2(artist) SELECT sa.artist FROM sets_to_artists AS sa WHERE 1 GROUP BY sa.artist

UPDATE sets_to_artists sa
JOIN artists2 a ON sa.artist = a.artist
SET sa.artist_id = a.id
WHERE 1

