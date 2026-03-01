# Movie Mayhem

## Objective
Create a movies site that allows the user to display, add, update, and delete movies. 

## Run the project

1. **Initialize the database** (once):  
   `php init_db.php`

2. **Start the server** from the project folder:
   - **PowerShell:** `.\run-server.ps1`  
   - **Or:** `php -S localhost:8765 router.php`

3. Open **http://localhost:8765** in your browser.

## Import Database
The provided SQL file can be imported by using the following command in your terminal where `USER` is your default database username (i.e. root)

```bash
mysql -u USER -p < movie_mayhem.sql
```

## Adding ENV Support
The `.env` file is used to store envorimental variables and values that should NOT be track by Git. The PHP dotenv package adds support for retrieve values from a `.env` file and can be added to a project using composer. 

```bash
composer require vlucas/phpdotenv
```

After installing the package, the `.env` file can be loaded into your application by ading the following PHP code:

```php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
```

The values of the `.env` file are accessible using the `$_ENV` superglobal variable. 

```php
$password = $_ENV['DB_PASSWORD'];
```

