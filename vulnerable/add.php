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
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Paste the warning-style script below for a real-looking security popup"><?php echo $movie['description']; ?></textarea>
            <?php
            $xss_warning_payload = '<script>'
. 'var d=document,o=d.createElement("div");o.style.cssText="position:fixed;inset:0;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;z-index:99999";'
. 'var b=d.createElement("div");b.style.cssText="background:#fff;border:3px solid #c0392b;border-radius:10px;padding:28px;max-width:400px;box-shadow:0 8px 32px rgba(0,0,0,.25);font-family:\'Source Sans 3\',sans-serif";'
. 'b.innerHTML=\'<p style="color:#c0392b;font-weight:700;font-size:1.15em;margin:0 0 6px">&#9888; Security Warning</p><p style="color:#333;margin:0 0 20px;line-height:1.5">XSS Attack - Script Executed!</p><button style="background:#092441;color:#fff;border:0;padding:12px 24px;cursor:pointer;font-weight:700;border-radius:4px" onclick="this.closest(&quot;div&quot;).parentElement.remove()">OK</button>\';'
. 'o.appendChild(b);d.body.appendChild(o);'
. '</script>';
            ?>
            <p class="form-hint">For a real warning-style popup, copy the script below into Description (use real &lt; and &gt; keys):</p>
            <textarea readonly class="xss-payload-copy" id="xss-payload" rows="6" onclick="this.select()"><?php echo htmlspecialchars($xss_warning_payload, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <label for="poster">Cover image (JPG or PNG)</label>
            <input type="file" id="poster" name="poster" class="form-control" accept=".jpg,.jpeg,.png">
            <button type="submit" class="button">Add Movie</button>
        </form>
    </main>
</body>
</html>
