<?php
require_once 'config.php';
require_once 'functions.php';

if (!empty($_GET['search'])) {
    $movies = getMoviesSearch($conn, $_GET['search']);
} else {
    $movies = getMovies($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies - Movie Mayhem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <form class="form form-search" method="get" action="index.php">
            <label for="search" class="visually-hidden">Search</label>
            <div class="search-bar">
                <input type="search" id="search" name="search" class="form-control" placeholder="Search movies..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Search movies">
                <button type="submit" class="button">Search</button>
            </div>
        </form>
        <section class="movies">
            <?php foreach ($movies as $movie):
                $posterPath = getPosterPath($movie['id']);
            ?>
                <a class="movie" href="movie.php?id=<?php echo (int)$movie['id']; ?>">
                    <?php if ($posterPath): ?><img class="movie-poster" src="<?php echo htmlspecialchars($posterPath, ENT_QUOTES, 'UTF-8'); ?>" alt=""><?php endif; ?>
                    <span class="movie-title"><?php echo htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="movie-year">(<?php echo htmlspecialchars($movie['release_year'], ENT_QUOTES, 'UTF-8'); ?>)</span>
                    <span class="movie-meta"><?php echo htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?> · <?php echo htmlspecialchars($movie['rating'], ENT_QUOTES, 'UTF-8'); ?>/10</span>
                </a>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
