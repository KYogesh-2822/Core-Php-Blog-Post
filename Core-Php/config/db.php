<?php

$hostname     = "db";
$username     = "blog_user";
$password     = "blog_password";
$databaseName = "core_php_blog";
$charset      = "utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

    try {
        $pdo = new PDO(
            "mysql:host=$hostname;dbname=$databaseName;charset=$charset",
            $username,
            $password,
            $options
        );
        logInfo("Database connected successfully");

        return $pdo;

    } catch (PDOException $e) {
        logError("Database connection failed", [
            'error' => $e->getMessage()
        ]);
        die("Database connection failed.");
    }