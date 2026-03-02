<?php
require_once 'config.php';
require_once 'functions.php';

// VULNERABLE CODE: Search uses direct concatenation - see getMoviesSearch()
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
                <input type="search" id="search" name="search" class="form-control" placeholder="Search movies..." value="<?php echo $_GET['search'] ?? ''; ?>" aria-label="Search movies">
                <button type="submit" class="button">Search</button>
            </div>
        </form>
        <section class="movies">
            <?php foreach ($movies as $movie):
                $posterPath = getPosterPath($movie['id']);
                $mid = (int)$movie['id'];
            ?>
                <div class="movie-card">
                    <button type="button" class="movie-menu-btn" aria-label="Options" data-menu="menu-<?php echo $mid; ?>">&#8942;</button>
                    <div class="movie-menu-dropdown" id="menu-<?php echo $mid; ?>">
                        <a href="edit.php?id=<?php echo $mid; ?>">Update</a>
                        <form method="post" action="delete.php" onsubmit="return confirm('Delete this movie?');">
                            <input type="hidden" name="id" value="<?php echo $mid; ?>">
                            <button type="submit" class="movie-menu-delete">Delete</button>
                        </form>
                    </div>
                    <a class="movie" href="movie.php?id=<?php echo $mid; ?>">
                        <span class="movie-poster-slot">
                            <?php if ($posterPath): ?><img class="movie-poster" src="<?php echo htmlspecialchars($posterPath, ENT_QUOTES, 'UTF-8'); ?>" alt=""><?php endif; ?>
                        </span>
                        <span class="movie-title"><?php echo $movie['title']; ?></span>
                        <span class="movie-year">(<?php echo $movie['release_year']; ?>)</span>
                        <span class="movie-meta"><?php echo $movie['genre']; ?> · <?php echo $movie['rating']; ?>/10</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <script>
    document.querySelectorAll('.movie-menu-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation();
            var id = this.getAttribute('data-menu');
            var open = document.querySelector('.movie-menu-dropdown.is-open');
            if (open && open.id !== id) open.classList.remove('is-open');
            document.getElementById(id).classList.toggle('is-open');
        });
    });
    document.addEventListener('click', function() {
        document.querySelectorAll('.movie-menu-dropdown.is-open').forEach(function(d) { d.classList.remove('is-open'); });
    });
    document.querySelectorAll('.movie-menu-dropdown').forEach(function(d) {
        d.addEventListener('click', function(e) { e.stopPropagation(); });
    });
    </script>
</body>
</html>
