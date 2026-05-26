<?php

require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
    logError("Database connection failed: PDO object not available.");
    die("Database connection failed: PDO object not available.\n");
}

$connection = $pdo;

$migrationFiles = glob(__DIR__ . '/migrations/*.php');

sort($migrationFiles);

foreach ($migrationFiles as $file) {
    $sql = require $file;

    try {
        $connection->exec($sql);
        logInfo("Migration successful", ['file' => basename($file)]);
        echo "Migration successful: " . basename($file) . PHP_EOL;
    } catch (PDOException $e) {
        logError("Migration failed", [
            'file' => basename($file),
            'error' => $e->getMessage()
        ]);
        echo "Migration failed: " . basename($file) . PHP_EOL;
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}


echo "All migrations completed successfully." . PHP_EOL;