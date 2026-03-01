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
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // SECURE CODE: Prepared statement - prevents SQL injection
        // SECURE CODE: Password hashing with bcrypt - never store plain text
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hash]);
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
            <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
        <form class="form" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" minlength="8" required>
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" class="form-control" required>
            <button type="submit" class="button">Register</button>
        </form>
        <?php endif; ?>
        <p><a href="login.php">Already have an account? Log in</a></p>
    </main>
</body>
</html>
