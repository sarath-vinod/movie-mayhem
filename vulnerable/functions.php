<?php
/**
 * VULNERABLE VERSION - functions.php
 * VULNERABLE CODE: Intentionally vulnerable queries (string concatenation, no prepared statements)
 * $conn is PDO (SQLite). Schema: title, genre, release_year, rating, description, created_by
 */

function getMovies($conn) {
    $sql = "SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies ORDER BY title";
    $result = $conn->query($sql);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

// VULNERABLE CODE: SQL INJECTION - search uses direct string concatenation, no prepared statement
function getMoviesSearch($conn, $search) {
    $sql = "SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies WHERE title LIKE '%" . $search . "%' OR genre LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%' ORDER BY title";
    $result = $conn->query($sql);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

function getMovieById($conn, $id) {
    // VULNERABLE CODE: SQL INJECTION - id concatenated directly
    $sql = "SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies WHERE id = " . $id;
    $result = $conn->query($sql);
    $row = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;
    return $row ?: null;
}

function addMovie($conn, $data) {
    // VULNERABLE CODE: SQL INJECTION - direct concatenation
    $title = str_replace("'", "''", $data['title']);
    $genre = str_replace("'", "''", $data['genre'] ?? '');
    $year = (int)($data['release_year'] ?? 0);
    $rating = (float)($data['rating'] ?? 0);
    $desc = str_replace("'", "''", $data['description'] ?? '');
    $created_by = (int)($data['created_by'] ?? $_SESSION['user_id'] ?? 1);
    $sql = "INSERT INTO movies (title, genre, release_year, rating, description, created_by) VALUES ('$title', '$genre', $year, $rating, '$desc', $created_by)";
    $conn->exec($sql);
    return (int)$conn->lastInsertId();
}

function updateMovie($conn, $data) {
    // VULNERABLE CODE: SQL INJECTION - concatenated values
    $title = str_replace("'", "''", $data['title']);
    $genre = str_replace("'", "''", $data['genre'] ?? '');
    $year = (int)($data['release_year'] ?? 0);
    $rating = (float)($data['rating'] ?? 0);
    $desc = str_replace("'", "''", $data['description'] ?? '');
    $id = (int)$data['id'];
    $sql = "UPDATE movies SET title = '$title', genre = '$genre', release_year = $year, rating = $rating, description = '$desc' WHERE id = $id";
    $conn->exec($sql);
    return $id;
}

function deleteMovie($conn, $id) {
    $sql = "DELETE FROM movies WHERE id = " . (int)$id;
    return $conn->exec($sql) > 0;
}

/** Return relative path to poster image (jpg or png) if it exists, else null. */
function getPosterPath($id) {
    $base = __DIR__ . DIRECTORY_SEPARATOR . 'posters' . DIRECTORY_SEPARATOR . (int)$id;
    if (file_exists($base . '.jpg')) return 'posters/' . (int)$id . '.jpg';
    if (file_exists($base . '.png')) return 'posters/' . (int)$id . '.png';
    return null;
}

/** Save uploaded poster for movie $id. Accepts jpg, jpeg, png. Returns true if saved. */
function savePoster($id, $file) {
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) return false;
    $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'posters';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $path = $dir . DIRECTORY_SEPARATOR . (int)$id . '.' . $ext;
    foreach (['.jpg', '.png'] as $old) {
        $oldPath = $dir . DIRECTORY_SEPARATOR . (int)$id . $old;
        if (file_exists($oldPath)) unlink($oldPath);
    }
    return move_uploaded_file($file['tmp_name'], $path);
}
