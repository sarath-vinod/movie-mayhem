# Movie Mayhem

Web Security project: a PHP + SQLite movie app in two versions—**vulnerable** (for learning attacks) and **secured** (with fixes). 

## Structure

```
movie-mayhem/
├── index.php          # Landing page – choose Vulnerable or Secured
├── landing.css
├── setup.php          # One-time setup: creates db/ and SQLite databases
├── db/                # Created by setup (vulnerable.sqlite, secured.sqlite)
├── vulnerable/        # Intentionally vulnerable app (SQLite)
│   ├── config.php, login.php, register.php, logout.php
│   ├── index.php, movie.php, add.php, edit.php, delete.php, search.php
│   ├── functions.php, header.php, style.css
│   └── database.sql   # Reference schema (actual DB created by setup.php)
└── secured/           # Hardened app (SQLite, prepared statements, auth, audit)
    ├── config.php, login.php, register.php, logout.php
    ├── index.php, movie.php, add.php, edit.php, delete.php, search.php
    ├── functions.php, header.php, style.css
    ├── encryption.php, BACKUP_INSTRUCTIONS.md
    └── database.sql   # Reference schema
```

## Run (PHP only)

1. **One-time setup** – create the SQLite databases (and seed demo users):
   ```bash
   php setup.php
   ```
   If you run `setup.php` again later, existing databases are **not** overwritten: registered users and data are kept so you can log in next time.

2. **Start the server:**
   ```bash
   php -S localhost:8000
   ```

3. Open **http://localhost:8000** and use the red (Vulnerable) or green (Secured) button.

No MySQL or XAMPP needed.
