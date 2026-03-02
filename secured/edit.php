<?php
require_once 'config.php';
require_once 'functions.php';

// SECURE CODE: Access control - require login for edit
requireAuth();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$movie = getMovieById($conn, $id);

if (!$movie) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie = [
        'id'           => $id,
        'title'        => trim($_POST['title'] ?? ''),
        'genre'        => $_POST['genre'] ?? '',
        'release_year' => $_POST['release_year'] ?? '',
        'rating'       => $_POST['rating'] ?? '',
        'description'  => trim($_POST['description'] ?? '')
    ];

    if (empty($movie['title'])) $errors['title'] = 'Title is required.';
    if (empty($movie['release_year'])) $errors['release_year'] = 'Year is required.';

    if (empty($errors)) {
        updateMovie($conn, $movie);
        if (!empty($_FILES['poster']['tmp_name'])) {
            savePoster($id, $_FILES['poster']);
        }
        header('Location: movie.php?id=' . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - Movie Mayhem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <div class="form-page">
        <div class="form-card">
        <h2 class="form-title">Edit Movie</h2>
        <form class="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo (int)$movie['id']; ?>">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <?php if (!empty($errors['title'])): ?><span class="error"><?php echo htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            <label for="genre">Genre</label>
            <select id="genre" name="genre" class="form-select">
                <option value="">Select genre</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?php echo htmlspecialchars($g, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($movie['genre'] ?? '') === $g) ? 'selected' : ''; ?>><?php echo htmlspecialchars($g, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select>
            <label for="release_year">Release Year</label>
            <input type="number" id="release_year" name="release_year" class="form-control" value="<?php echo htmlspecialchars($movie['release_year'], ENT_QUOTES, 'UTF-8'); ?>" min="1900" max="2100" required>
            <?php if (!empty($errors['release_year'])): ?><span class="error"><?php echo htmlspecialchars($errors['release_year'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            <label for="rating">Rating (0–10)</label>
            <input type="number" id="rating" name="rating" class="form-control" value="<?php echo htmlspecialchars($movie['rating'], ENT_QUOTES, 'UTF-8'); ?>" min="0" max="10" step="0.1">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($movie['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            <label for="poster">Cover image (JPG or PNG) – optional, replaces existing</label>
            <input type="file" id="poster" name="poster" class="form-control" accept=".jpg,.jpeg,.png">
            <button type="submit" class="button">Update Movie</button>
        </form>
        <form class="form" method="post" action="delete.php" style="margin-top:1rem;">
            <input type="hidden" name="id" value="<?php echo (int)$movie['id']; ?>">
            <button type="submit" class="button danger">Delete Movie</button>
        </form>
        </div>
        </div>
    </main>
</body>
</html>
