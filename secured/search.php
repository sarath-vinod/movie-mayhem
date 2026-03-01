<?php
/**
 * Search redirect - SECURE CODE: actual search uses prepared statements in index.php
 */
require_once 'config.php';
if (!empty($_GET['search'])) {
    header('Location: index.php?search=' . urlencode($_GET['search']));
    exit;
}
header('Location: index.php');
exit;
