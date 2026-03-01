<?php
/**
 * Search page - VULNERABLE CODE: uses same vulnerable getMoviesSearch() (SQL injection)
 * Redirect to index with search param so one search form is used
 */
require_once 'config.php';
if (!empty($_GET['search'])) {
    header('Location: index.php?search=' . urlencode($_GET['search']));
    exit;
}
header('Location: index.php');
exit;
