<?php
/**
 * One-time setup: creates db/ folder and SQLite databases for both vulnerable and secured versions.
 * Movies table: id, title, genre, release_year, rating, description, created_by, created_at
 * Run once: php setup.php
 * If DBs already exist, tables are ensured but existing data (including registered users) is kept.
 * No MySQL/XAMPP required.
 */

$baseDir = __DIR__;
$dbDir = $baseDir . DIRECTORY_SEPARATOR . 'db';

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

// ---------- VULNERABLE DB ----------
$vulnDb = $dbDir . DIRECTORY_SEPARATOR . 'vulnerable.sqlite';
$vulnNew = !file_exists($vulnDb);
$pdo = new PDO('sqlite:' . $vulnDb);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS movies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    genre TEXT,
    release_year INTEGER,
    rating REAL,
    description TEXT,
    created_by INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)');

if ($vulnNew) {
    $pdo->exec("INSERT INTO users (id, username, password) VALUES (1, 'alice', 'demo'), (2, 'bob', 'demo'), (3, 'charlie', 'demo')");
    $pdo->exec("INSERT INTO movies (title, genre, release_year, rating, description, created_by) VALUES
('The Dark Knight', 'Action', 2008, 9.0, 'Batman faces the Joker in Gotham City.', 1),
('Inception', 'Sci-Fi', 2010, 8.8, 'A thief who steals corporate secrets through dream-sharing technology.', 1),
('Interstellar', 'Sci-Fi', 2014, 8.6, 'A team travels through a wormhole in space to ensure humanity''s survival.', 2),
('Parasite', 'Thriller', 2019, 8.5, 'A poor family schemes to become employed by a wealthy household.', 3),
('The Godfather', 'Crime', 1972, 9.2, 'The aging patriarch of an organized crime dynasty transfers control to his son.', 1),
('Avengers: Endgame', 'Action', 2019, 8.4, 'The Avengers assemble once more to reverse Thanos'' actions.', 2),
('Titanic', 'Romance', 1997, 7.9, 'A seventeen-year-old aristocrat falls in love with a kind but poor artist.', 3),
('Joker', 'Drama', 2019, 8.3, 'A mentally troubled comedian descends into madness.', 1),
('The Matrix', 'Sci-Fi', 1999, 8.7, 'A hacker discovers the truth about his reality.', 2),
('Gladiator', 'Historical Drama', 2000, 8.5, 'A former Roman General seeks revenge.', 3)");
    echo "Created and seeded: $vulnDb\n";
} else {
    echo "Database exists (kept existing data): $vulnDb\n";
}
unset($pdo);

// ---------- SECURED DB ----------
$secDb = $dbDir . DIRECTORY_SEPARATOR . 'secured.sqlite';
$secNew = !file_exists($secDb);
$pdo = new PDO('sqlite:' . $secDb);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS movies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    genre TEXT,
    release_year INTEGER,
    rating REAL,
    description TEXT,
    created_by INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS audit_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    action TEXT NOT NULL,
    table_name TEXT NOT NULL,
    record_id INTEGER,
    details TEXT,
    ip_address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)');

if ($secNew) {
    $hash = password_hash('demo', PASSWORD_DEFAULT);
    $pdo->prepare('INSERT INTO users (id, username, password) VALUES (1, ?, ?)')->execute(['alice', $hash]);
    $pdo->prepare('INSERT INTO users (id, username, password) VALUES (2, ?, ?)')->execute(['bob', $hash]);
    $pdo->prepare('INSERT INTO users (id, username, password) VALUES (3, ?, ?)')->execute(['charlie', $hash]);
    $pdo->exec("INSERT INTO movies (title, genre, release_year, rating, description, created_by) VALUES
('The Dark Knight', 'Action', 2008, 9.0, 'Batman faces the Joker in Gotham City.', 1),
('Inception', 'Sci-Fi', 2010, 8.8, 'A thief who steals corporate secrets through dream-sharing technology.', 1),
('Interstellar', 'Sci-Fi', 2014, 8.6, 'A team travels through a wormhole in space to ensure humanity''s survival.', 2),
('Parasite', 'Thriller', 2019, 8.5, 'A poor family schemes to become employed by a wealthy household.', 3),
('The Godfather', 'Crime', 1972, 9.2, 'The aging patriarch of an organized crime dynasty transfers control to his son.', 1),
('Avengers: Endgame', 'Action', 2019, 8.4, 'The Avengers assemble once more to reverse Thanos'' actions.', 2),
('Titanic', 'Romance', 1997, 7.9, 'A seventeen-year-old aristocrat falls in love with a kind but poor artist.', 3),
('Joker', 'Drama', 2019, 8.3, 'A mentally troubled comedian descends into madness.', 1),
('The Matrix', 'Sci-Fi', 1999, 8.7, 'A hacker discovers the truth about his reality.', 2),
('Gladiator', 'Historical Drama', 2000, 8.5, 'A former Roman General seeks revenge.', 3)");
    echo "Created and seeded: $secDb\n";
} else {
    echo "Database exists (kept existing data): $secDb\n";
}
echo "Setup complete. Run: php -S localhost:8000\n";
echo "Then open: http://localhost:8000\n";
echo "Registered users are saved; you can log in with them next time.\n";
