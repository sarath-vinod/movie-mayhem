<?php
/**
 * Router for PHP built-in server so "/" and .php routes are served correctly.
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;
// Normalize path separators on Windows
$root = str_replace('\\', '/', $root);

if ($uri === '' || $uri === '/') {
    chdir($root);
    require $root . '/index.php';
    return true;
}

$file = $root . $uri;
if (file_exists($file) && is_file($file)) {
    return false;
}

if (preg_match('/\.php$/', $uri) && is_file($root . $uri)) {
    chdir($root);
    require $root . $uri;
    return true;
}

chdir($root);
require $root . '/index.php';
return true;
