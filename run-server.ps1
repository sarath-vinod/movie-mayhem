# Start Movie Mayhem PHP development server
# Must run from project directory so the router and document root work correctly.
Set-Location $PSScriptRoot
php -S localhost:8765 router.php
