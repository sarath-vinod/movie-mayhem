<?php
/**
 * VULNERABLE VERSION - config.php
 * VULNERABLE CODE: Uses SQLite file with no access control (equivalent to root-like usage in comments)
 * VULNERABLE CODE: No separate application user / principle of least privilege
 */
session_start();

$dbFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'vulnerable.sqlite';

try {
    $conn = new PDO('sqlite:' . $dbFile);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed. Run setup.php first: php setup.php');
}

// Genres for forms (dropdown)
$genres = ['Action', 'Sci-Fi', 'Thriller', 'Crime', 'Romance', 'Drama', 'Historical Drama'];
