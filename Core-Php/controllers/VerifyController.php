<?php

function handleVerify($pdo) {
    $data = [
        'errors'  => [],
        'success' => '',
        'email'   => $_SESSION['verify_email'] ?? ''
    ];

    // ─── Guard: if no session, redirect to register ───
    if (!isset($_SESSION['verify_user_id'])) {
        header("Location: register");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $data;
    }

    $enteredCode = trim($_POST['code'] ?? '');
    $userId      = $_SESSION['verify_user_id'];

    // ─── Get code from database ───
    $verification = getVerificationCode($pdo, $userId);

    if (!$verification) {
        $data['errors'][] = "Verification code not found. Please register again.";
        return $data;
    }

    // ─── Check if code is expired (10 minutes) ───
    $createdAt = strtotime($verification['created_at']);
    $now       = time();
    $diffMins  = ($now - $createdAt) / 60;

    if ($diffMins > 10) {
        $data['errors'][] = "Code has expired. Please request a new one.";
        return $data;
    }

    // ─── Check if code matches ───
    if ($enteredCode !== $verification['code']) {
        $data['errors'][] = "Invalid code. Please try again.";
        return $data;
    }

    // ─── Mark user as verified ───
    verifyUser($pdo, $userId);

    // ─── Clean up verification code ───
    deleteVerificationCode($pdo, $userId);

    // ─── Clear session ───
    unset($_SESSION['verify_user_id']);
    unset($_SESSION['verify_email']);

    // ─── Redirect to login with success message ───
    $_SESSION['success'] = "Email verified! You can now login.";
    header("Location: login");
    exit;
}


function handleResendCode($pdo) {
    if (!isset($_SESSION['verify_user_id'])) {
        header("Location: register");
        exit;
    }

    $userId = $_SESSION['verify_user_id'];
    $email  = $_SESSION['verify_email'];

    // ─── Delete old code ───
    deleteVerificationCode($pdo, $userId);

    // ─── Generate new code ───
    $code = rand(100000, 999999);
    saveVerificationCode($pdo, $userId, $code);

    // ─── Log new code ───
    file_put_contents(
        ROOT . '/verification_log.txt',
        date('Y-m-d H:i:s') . " | RESEND | $email | Code: $code\n",
        FILE_APPEND
    );

    $_SESSION['resend_success'] = "New code sent to $email";
    header("Location: verify");
    exit;
}