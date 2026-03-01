-- Run this file as MySQL root to create a limited user for the Movie Mayhem app.
-- SECURE CODE: Principle of least privilege - app user has only needed permissions.

-- Create user (change 'your_strong_password_here' to a strong password)
CREATE USER IF NOT EXISTS 'movie_app'@'localhost' IDENTIFIED BY 'your_strong_password_here';

-- Grant only SELECT, INSERT, UPDATE, DELETE on movie_mayhem database (no DROP, no GRANT, no CREATE USER)
GRANT SELECT, INSERT, UPDATE, DELETE ON movie_mayhem.* TO 'movie_app'@'localhost';

FLUSH PRIVILEGES;

-- Then update config.php to use:
-- define('DB_USER', 'movie_app');
-- define('DB_PASS', 'your_strong_password_here');
