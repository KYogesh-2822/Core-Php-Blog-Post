<?php

function handleVerify($pdo) {
    $data = [
        'errors'     => [],
        'success'    => '',
        'email'      => $_SESSION['verify_email'] ?? '',
        'show_email' => !isset($_SESSION['verify_user_id']),
    ];

    // ─── No session — let user enter email ───
    if (!isset($_SESSION['verify_user_id'])) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lookup_email'])) {
            $email = trim($_POST['email'] ?? '');
            $user  = getUserByEmail($pdo, $email);

            if (!$user) {
                $data['errors'][] = "No account found with that email.";
                return $data;
            }

            if ($user['is_verified']) {
                $data['errors'][] = "Account already verified. Please login.";
                return $data;
            }

            // ─── Restore session and resend code ───
            deleteVerificationCode($pdo, $user['id']);
            $code = rand(100000, 999999);
            saveVerificationCode($pdo, $user['id'], $code);

            logVerificationCode($email, $code, 'RESEND');

            logAuth("Code resent via email lookup", [
                'user_id' => $user['id'],
                'email'   => $email,
                'code'    => $code
            ]);

            $_SESSION['verify_user_id'] = $user['id'];
            $_SESSION['verify_email']   = $user['email'];

            $data['email']      = $user['email'];
            $data['show_email'] = false;
            $data['success']    = "New code sent — check verification_log.txt";
        }

        return $data;
    }

    // ─── Has session — verify the code ───
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_POST['lookup_email'])) {
        return $data;
    }

    $enteredCode  = trim($_POST['code'] ?? '');
    $userId       = $_SESSION['verify_user_id'];
    $verification = getVerificationCode($pdo, $userId);

    if (!$verification) {
        $data['errors'][] = "No code found. Please request a new one.";
        return $data;
    }

    // ─── Check expiry 10 mins ───
    $diffMins = (time() - strtotime($verification['created_at'])) / 60;
    if ($diffMins > 10) {
        $data['errors'][] = "Code expired. Please request a new one.";
        return $data;
    }

    // ─── Check code match ───
    if ($enteredCode !== $verification['code']) {
        logAuth("Invalid verification code entered", ['user_id' => $userId]);
        $data['errors'][] = "Invalid code. Please try again.";
        return $data;
    }

    // ─── Success ───
    verifyUser($pdo, $userId);
    deleteVerificationCode($pdo, $userId);

    // ─── Get user name for welcome email ───
    $user = getUserById($pdo, $userId);
    sendWelcomeEmail($user['email'], $user['name']);

    logAuth("Email verified successfully", [
        'user_id' => $userId,
        'email'   => $data['email']
    ]);

    unset($_SESSION['verify_user_id']);
    unset($_SESSION['verify_email']);

    $_SESSION['success'] = "Email verified! You can now login.";
    header("Location: /login", true, 303);
    exit;
}


function handleResendCode($pdo) {
    if (!isset($_SESSION['verify_user_id'])) {
        header("Location: /verify", true, 303);
        exit;
    }

    $userId = $_SESSION['verify_user_id'];
    $email  = $_SESSION['verify_email'];

    deleteVerificationCode($pdo, $userId);
    $code = rand(100000, 999999);
    saveVerificationCode($pdo, $userId, $code);

    logVerificationCode($email, $code, 'RESEND');

    logAuth("Verification code resent", [
        'user_id' => $userId,
        'email'   => $email,
        'code'    => $code
    ]);

    $_SESSION['resend_success'] = "New code sent — check verification_log.txt";
    header("Location: /verify", true, 303);
    exit;
}