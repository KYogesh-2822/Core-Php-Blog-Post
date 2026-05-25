<?php
session_start();

define('ROOT', __DIR__ . '/..');

// ─── Debug: show all paths ───
// echo "ROOT = " . ROOT . "<br>";
// echo "db.php exists: " . (file_exists(ROOT . '/config/db.php') ? 'YES' : 'NO') . "<br>";
// echo "logger.php exists: " . (file_exists(ROOT . '/config/logger.php') ? 'YES' : 'NO') . "<br>";
// echo "queries.php exists: " . (file_exists(ROOT . '/config/queries.php') ? 'YES' : 'NO') . "<br>";
// echo "layout.php exists: " . (file_exists(ROOT . '/includes/layout.php') ? 'YES' : 'NO') . "<br>";
// die();

require_once ROOT . '/config/logger.php';
require_once ROOT . '/config/db.php';
require_once ROOT . '/config/queries.php';
require_once ROOT . '/includes/layout.php';
require_once ROOT . '/config/router.php';

// ─── Auto-load all controllers ───
foreach (glob(ROOT . '/controllers/*.php') as $controller) {
    require_once $controller;
}