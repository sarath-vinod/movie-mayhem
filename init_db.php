<?php
// One-time script to create SQLite DB in temp dir (same path as data.php) and seed from movies.json
$dbFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'movie_mayhem.sqlite';

if (file_exists($dbFile)) {
    unlink($dbFile);
}
$db = new PDO('sqlite:' . $dbFile);
$db->exec('CREATE TABLE genres (id INTEGER PRIMARY KEY, name TEXT)');
$db->exec('CREATE TABLE movies (id INTEGER PRIMARY KEY, title TEXT, director TEXT, year TEXT, genre_id INTEGER)');

$genres = ['Fantasy', 'Sci-Fi', 'Drama'];
foreach ($genres as $i => $name) {
    $db->exec("INSERT INTO genres (id, name) VALUES (" . ($i + 1) . ", " . $db->quote($name) . ")");
}

$movies = json_decode(file_get_contents(__DIR__ . '/movies.json'), true);
$genreIds = [];
foreach ($genres as $i => $name) {
    $genreIds[$name] = $i + 1;
}
$stmt = $db->prepare('INSERT INTO movies (id, title, director, year, genre_id) VALUES (:id, :title, :director, :year, :genre_id)');
foreach ($movies as $m) {
    $genreId = $genreIds[$m['genre']] ?? 1;
    $stmt->execute([
        'id' => $m['id'],
        'title' => $m['title'],
        'director' => $m['director'],
        'year' => $m['year'],
        'genre_id' => $genreId
    ]);
}
echo "Database created: " . $dbFile . "\n";
