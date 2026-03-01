<?php
require "data.php";
require "functions.php";

if (isset($_GET['search'])) {
	$movies = searchMovies();
} else {
	$movies = getMovies();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Movies</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<main class="main">
		<?php require "header.php"; ?>
		<form class="form">
			<label class="visually-hidden" for="search">Search</label>
			<input type="search" class="form-control" id="search" name="search" placeholder="Search">
		</form>
		<section class="movies">
			<?php foreach ($movies as $movie) : ?>
				<?php if (file_exists("posters/{$movie['id']}.jpg")) : ?>
					<a class="movie border-0 p-0" href="movie.php?id=<?php echo $movie['id']; ?>">
						<img class="movie-poster" src="posters/<?php echo $movie['id']; ?>.jpg" alt="<?php echo $movie['title']; ?>">
					</a>
				<?php else : ?>
					<a class="movie" href="movie.php?id=<?php echo $movie['id']; ?>">
						<?php echo $movie['title']; ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>
	</main>
</body>

</html>