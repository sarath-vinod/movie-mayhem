<header class="header">
    <h1 class="header-title">Movie Mayhem</h1>
    <nav class="nav">
        <a class="nav-link" href="../index.php">Home</a>
        <a class="nav-link" href="index.php">Movies</a>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a class="nav-link" href="add.php">Add Movie</a>
            <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>)</a>
        <?php else: ?>
            <a class="nav-link" href="login.php">Login</a>
            <a class="nav-link" href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
