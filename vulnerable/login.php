<?php
require_once 'config.php';

// VULNERABLE CODE: No session regeneration after login (session fixation risk)
$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // VULNERABLE CODE: SQL INJECTION - login uses direct string concatenation
        // Attacker can use: username: ' OR '1'='1' --  to bypass login
        $sql = "SELECT id, username, password FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
        try {
            $result = $conn->query($sql);
            $user = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;
            if ($user) {
                // VULNERABLE CODE: No session_regenerate_id(true) - session fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            // SQL error from injection etc.
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie Mayhem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main">
        <?php require 'header.php'; ?>
        <h2 class="form-title">Login</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form class="form" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo $_POST['username'] ?? ''; ?>" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <button type="submit" class="button">Login</button>
        </form>
        <p><a href="register.php">Don't have an account? Register</a></p>
    </main>
</body>
</html>
