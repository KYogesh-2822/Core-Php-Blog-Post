<?php
// Include this on pages that require login
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: config/index.php");
    exit;
}