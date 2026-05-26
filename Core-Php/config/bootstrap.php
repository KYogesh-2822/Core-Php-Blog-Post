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

// ─── Environment ───
define('APP_ENV', $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? 'development');

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT . '/logs/php_errors.log');
}


// ─── Mail Config from environment ───
define('MAIL_HOST',       $_ENV['MAIL_HOST']       ?? getenv('MAIL_HOST')       ?? 'mailtrap');
define('MAIL_PORT',       $_ENV['MAIL_PORT']        ?? getenv('MAIL_PORT')       ?? 25);
define('MAIL_USERNAME',   $_ENV['MAIL_USERNAME']    ?? getenv('MAIL_USERNAME')   ?? '');
define('MAIL_PASSWORD',   $_ENV['MAIL_PASSWORD']    ?? getenv('MAIL_PASSWORD')   ?? '');
define('MAIL_FROM',       $_ENV['MAIL_FROM']        ?? getenv('MAIL_FROM')       ?? 'noreply@phpblog.com');
define('MAIL_FROM_NAME',  $_ENV['MAIL_FROM_NAME']   ?? getenv('MAIL_FROM_NAME')  ?? 'PHP Blog');
define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']  ?? getenv('MAIL_ENCRYPTION') ?? '');


require_once ROOT . '/config/logger.php';
require_once ROOT . '/config/db.php';
require_once ROOT . '/config/queries.php';
require_once ROOT . '/includes/layout.php';
require_once ROOT . '/config/router.php';
require_once ROOT . '/helpers/mail.php'; 

// ─── Auto-load all controllers ───
foreach (glob(ROOT . '/controllers/*.php') as $controller) {
    require_once $controller;
}
