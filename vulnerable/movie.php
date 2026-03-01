<?php
require_once 'config.php';
require_once 'functions.php';

$id = $_GET['id'] ?? 0;
$movie = getMovieById($conn, $id);

if (!$movie) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['title']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <section class="movie-details">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a class="movie-edit" href="edit.php?id=<?php echo $movie['id']; ?>">Edit</a>
            <?php endif;
            $posterPath = getPosterPath($movie['id']);
            if ($posterPath): ?>
                <img class="movie-poster" src="<?php echo htmlspecialchars($posterPath, ENT_QUOTES, 'UTF-8'); ?>" alt="">
            <?php endif; ?>
            <h2 class="movie-title"><?php echo $movie['title']; ?> (<?php echo $movie['release_year']; ?>)</h2>
            <p><strong>Genre:</strong> <?php echo $movie['genre']; ?></p>
            <p><strong>Rating:</strong> <?php echo $movie['rating']; ?>/10</p>
            <?php if (!empty($movie['description'])): ?>
                <!-- VULNERABLE CODE: XSS - raw user input displayed, no htmlspecialchars() -->
                <div class="movie-description"><?php echo $movie['description']; ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <form class="form" method="post" action="delete.php" style="margin-top:1rem;">
                    <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">
                    <button type="submit" class="button danger">Delete Movie</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
