<?php
require_once 'config.php';
require_once 'functions.php';

$id = (int)($_GET['id'] ?? 0);
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
    <title><?php echo htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <section class="movie-details">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a class="movie-edit" href="edit.php?id=<?php echo (int)$movie['id']; ?>">Edit</a>
            <?php endif;
            $posterPath = getPosterPath($movie['id']);
            if ($posterPath): ?>
                <img class="movie-poster" src="<?php echo htmlspecialchars($posterPath, ENT_QUOTES, 'UTF-8'); ?>" alt="">
            <?php endif; ?>
            <h2 class="movie-title"><?php echo htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($movie['release_year'], ENT_QUOTES, 'UTF-8'); ?>)</h2>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($movie['rating'], ENT_QUOTES, 'UTF-8'); ?>/10</p>
            <?php if (!empty($movie['description'])): ?>
                <!-- SECURE CODE: XSS prevention - output escaped with htmlspecialchars -->
                <div class="movie-description"><?php echo htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <form class="form" method="post" action="delete.php" style="margin-top:1rem;">
                    <input type="hidden" name="id" value="<?php echo (int)$movie['id']; ?>">
                    <button type="submit" class="button danger">Delete Movie</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
