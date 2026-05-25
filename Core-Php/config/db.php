<?php

$hostname = "db";
$username= "blog_user";
$password= "blog_password";
$databaseName = "core_php_blog";
$charset  = "utf8mb4";

$relatedValues = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];



try{
    $pdo = new PDO("mysql:host=$hostname;dbname=$databaseName;charset=$charset", $username, $password, $relatedValues);
    $connection = $pdo;
    logInfo("Database connected successfully");

}catch(PDOException $e){
     logError("Database connection failed", [
        'error' => $e->getMessage()
    ]);
    echo "Connection failed: " . $e->getMessage();
}