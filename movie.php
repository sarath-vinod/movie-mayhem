<?php
require "data.php";
require "functions.php";

if (isset($_GET['id'])) {
	$movie = getMovie($_GET['id']);

	if (!$movie) {
		header('Location: index.php');
	}
} else {
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $movie['title']; ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<main class="main">
		<?php require "header.php"; ?>
		<section class="movie-details">
			<a class="movie-edit" href="edit.php?id=<?php echo $movie['id']; ?>">Edit</a>
			<?php if (file_exists('posters/' . $movie['id'] . '.jpg')) : ?>
				<img
					class="movie-poster"
					src="posters/<?php echo $movie['id']; ?>.jpg"
					alt="<?php echo $movie['title']; ?>">
			<?php endif; ?>
			<h2 class="movie-title">
				<?php echo $movie['title']; ?>
				<span class="movie-year">(<?php echo $movie['year']; ?>)</span>
			</h2>
			<h4 class="movie-genre"><?php echo $movie['genre']; ?></h4>

			Director<br><strong><?php echo $movie['director']; ?></strong>

		</section>
	</main>
</body>

</html>