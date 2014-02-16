insert ignore into artists(artist) SELECT DISTINCT artist FROM `sets` WHERE sets.artist IS NOT NULL order by artist

insert ignore into events(event) SELECT DISTINCT event FROM `sets` WHERE sets.event IS NOT NULL order by event

insert ignore into genres(genre) SELECT DISTINCT genre FROM `sets` WHERE sets.genre IS NOT NULL order by genre


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