<?php
// Use temp directory so the DB is always writable (avoids disk I/O errors on Windows)
$dbFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'movie_mayhem.sqlite';
$dsn = 'sqlite:' . $dbFile;

try {
  $db = new PDO($dsn);
} catch (PDOException $e) {
  $error = $e->getMessage();
  echo $error;
}

$sql = 'SELECT * FROM genres';
$result = $db->query($sql);
$genres = $result->fetchAll(PDO::FETCH_COLUMN, 1);
