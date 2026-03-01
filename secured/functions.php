<?php
/**
 * SECURED VERSION - functions.php
 * SECURE CODE: All queries use PDO prepared statements (parameter binding)
 * $conn is PDO (SQLite). Schema: title, genre, release_year, rating, description, created_by
 */

function getMovies($conn) {
    $sql = "SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies ORDER BY title";
    $result = $conn->query($sql);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

// SECURE CODE: Prepared statement with parameter binding - prevents SQL injection
function getMoviesSearch($conn, $search) {
    $stmt = $conn->prepare("SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies WHERE title LIKE ? OR genre LIKE ? OR description LIKE ? ORDER BY title");
    $term = '%' . $search . '%';
    $stmt->execute([$term, $term, $term]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// SECURE CODE: Prepared statement for single movie - id is bound parameter
function getMovieById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, title, genre, release_year, rating, description, created_by, created_at FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

// SECURE CODE: Audit log for add, edit, delete
function auditLog($conn, $action, $tableName, $recordId = null, $details = '') {
    $userId = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $conn->prepare("INSERT INTO audit_log (user_id, action, table_name, record_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $action, $tableName, $recordId, $details, $ip]);
}

function addMovie($conn, $data) {
    $created_by = (int)($_SESSION['user_id'] ?? 1);
    $stmt = $conn->prepare("INSERT INTO movies (title, genre, release_year, rating, description, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['genre'] ?? null,
        (int)($data['release_year'] ?? 0),
        (float)($data['rating'] ?? 0),
        $data['description'] ?? '',
        $created_by
    ]);
    $id = (int)$conn->lastInsertId();
    auditLog($conn, 'add', 'movies', $id, "Added: " . $data['title']);
    return $id;
}

function updateMovie($conn, $data) {
    $stmt = $conn->prepare("UPDATE movies SET title = ?, genre = ?, release_year = ?, rating = ?, description = ? WHERE id = ?");
    $stmt->execute([
        $data['title'],
        $data['genre'] ?? null,
        (int)($data['release_year'] ?? 0),
        (float)($data['rating'] ?? 0),
        $data['description'] ?? '',
        $data['id']
    ]);
    auditLog($conn, 'edit', 'movies', (int)$data['id'], "Updated: " . $data['title']);
    return (int)$data['id'];
}

function deleteMovie($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $affected = $stmt->rowCount();
    if ($affected > 0) {
        auditLog($conn, 'delete', 'movies', $id, "Deleted movie id $id");
    }
    return $affected > 0;
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
