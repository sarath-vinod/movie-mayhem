<?php
require "data.php";
require "functions.php";

$movie = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$movie = sanitize($_POST);
	$errors = validate($movie);

	if (count($errors) === 0) {
		$id = addMovie($_POST);

		header("Location: movie.php?id=" . $id);
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Movie</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<main class="main">
		<?php require "header.php"; ?>
		<h2 class="form-title">New Movie</h2>
		<form class="form" method="post" enctype="multipart/form-data" novalidate>
			<?php require "inputs.php"; ?>
			<button type="submit" class="button">Add Movie</button>
		</form>
	</main>
</body>

</html>