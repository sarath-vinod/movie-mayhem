<?php
require_once 'config.php';
require_once 'functions.php';

// SECURE CODE: Access control - require login for delete
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        deleteMovie($conn, $id);
        $postersDir = __DIR__ . DIRECTORY_SEPARATOR . 'posters';
        foreach (['.jpg', '.png'] as $ext) {
            $path = $postersDir . DIRECTORY_SEPARATOR . $id . $ext;
            if (file_exists($path)) unlink($path);
        }
    }
}
header('Location: index.php');
exit;
