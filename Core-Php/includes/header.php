<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'PHP Blog' ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    
<nav>
    <a class="logo" href="/">PHP Blog</a>
    <ul>
        <li><a href="/">Home</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/create_post">New Post</a></li>
            <li><a href="/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>