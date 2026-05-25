<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/bootstrap.php';

logInfo("index.php loaded", ['url' => $_SERVER['REQUEST_URI']]);

runRouter($pdo, $routes);