<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // VULNERABLE CODE: SQL INJECTION - direct string concatenation, no prepared statement
        // VULNERABLE CODE: Plain text password storage - no password_hash()
        // Minimal escape so normal input (e.g. O'Brien) doesn't break query; still no prepared stmt
        $u = str_replace("'", "''", $username);
        $p = str_replace("'", "''", $password);
        $sql = "INSERT INTO users (username, password) VALUES ('" . $u . "', '" . $p . "')";

        try {
            $conn->exec($sql);
            $success = 'Account created. You can now <a href="login.php">log in</a>.';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'UNIQUE') !== false) {
                $error = 'Username already taken.';
            } else {
                $error = 'Registration failed.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie Mayhem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <h2 class="form-title">Register</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
        <form class="form" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo $_POST['username'] ?? ''; ?>" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" class="form-control" required>
            <button type="submit" class="button">Register</button>
        </form>
        <?php endif; ?>
        <p><a href="login.php">Already have an account? Log in</a></p>
    </main>
</body>
</html>
