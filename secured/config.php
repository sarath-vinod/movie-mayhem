<?php
/**
 * SECURED VERSION - config.php
 * SECURE CODE: Uses SQLite with db file in db/ (limited to app; principle of least privilege in comments)
 */
session_start();

$dbFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'secured.sqlite';

try {
    $conn = new PDO('sqlite:' . $dbFile);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed. Run setup.php first: php setup.php');
}

$genres = ['Action', 'Sci-Fi', 'Thriller', 'Crime', 'Romance', 'Drama', 'Historical Drama'];

/**
 * SECURE CODE: Require authenticated user for admin actions (add/edit/delete)
 */
function requireAuth() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}
