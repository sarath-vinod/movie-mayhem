<?php
require_once 'config.php';
require_once 'functions.php';

// VULNERABLE CODE: No access control - add movie without requiring login
$errors = [];
$movie = ['title' => '', 'genre' => '', 'release_year' => '', 'rating' => '', 'description' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie = [
        'title'        => $_POST['title'] ?? '',
        'genre'        => $_POST['genre'] ?? '',
        'release_year' => $_POST['release_year'] ?? '',
        'rating'       => $_POST['rating'] ?? '',
        'description'  => $_POST['description'] ?? '',
        'created_by'   => $_SESSION['user_id'] ?? 1
    ];

    if (empty($movie['title'])) $errors['title'] = 'Title is required.';
    if (empty($movie['release_year'])) $errors['release_year'] = 'Year is required.';

    if (empty($errors)) {
        $id = addMovie($conn, $movie);
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
    <title>Add Movie - Movie Mayhem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <h2 class="form-title">Add Movie</h2>
        <form class="form" method="post" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo $movie['title']; ?>" required>
            <?php if (!empty($errors['title'])): ?><span class="error"><?php echo $errors['title']; ?></span><?php endif; ?>
            <label for="genre">Genre</label>
            <select id="genre" name="genre" class="form-select">
                <option value="">Select genre</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?php echo $g; ?>" <?php echo ($movie['genre'] === $g) ? 'selected' : ''; ?>><?php echo $g; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="release_year">Release Year</label>
            <input type="number" id="release_year" name="release_year" class="form-control" value="<?php echo $movie['release_year']; ?>" min="1900" max="2100" required>
            <?php if (!empty($errors['release_year'])): ?><span class="error"><?php echo $errors['release_year']; ?></span><?php endif; ?>
            <label for="rating">Rating (0–10)</label>
            <input type="number" id="rating" name="rating" class="form-control" value="<?php echo $movie['rating']; ?>" min="0" max="10" step="0.1" placeholder="e.g. 8.5">
            <label for="description">Description (XSS test: try &lt;script&gt;alert(1)&lt;/script&gt;)</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo $movie['description']; ?></textarea>
            <label for="poster">Cover image (JPG or PNG)</label>
            <input type="file" id="poster" name="poster" class="form-control" accept=".jpg,.jpeg,.png">
            <button type="submit" class="button">Add Movie</button>
        </form>
    </main>
</body>
</html>
