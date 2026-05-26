<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/bootstrap.php';

logInfo("index.php loaded", ['url' => $_SERVER['REQUEST_URI']]);
logInfo("Session data", ['session' => $_SESSION]);
logInfo("Request method", ['method' => $_SERVER['REQUEST_METHOD']]);
logInfo("Current page details", ['page' => $current = $_GET['page'] ?? '']);
runRouter($pdo, $routes);