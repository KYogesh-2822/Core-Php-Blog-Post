<?php

function handleRegister($pdo) {
    $data = [
        'errors' => [],
        'name'   => '',
        'email'  => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $data;
    }

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    $data['name']  = $name;
    $data['email'] = $email;

    // ─── Validate ───
    if (empty($name))
        $data['errors'][] = "Name is required.";

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $data['errors'][] = "Valid email is required.";

    if (strlen($password) < 6)
        $data['errors'][] = "Password must be at least 6 characters.";

    if ($password !== $confirm)
        $data['errors'][] = "Passwords do not match.";

    // ─── Check existing email ───
    if (empty($data['errors'])) {
        $existingUser = getUserByEmail($pdo, $email);

        if ($existingUser) {

            // ─── Already verified ───
            if ($existingUser['is_verified'] == 1) {
                $data['errors'][] = "Email already registered. Please login.";

            // ─── Exists but not verified ───
            } else {
                deleteVerificationCode($pdo, $existingUser['id']);

                $code = rand(100000, 999999);
                saveVerificationCode($pdo, $existingUser['id'], $code);

                // ─── Log to file so you can read the code ───
                logVerificationCode($email, $code, 'RESEND');

                logAuth("Resent code to unverified existing user", [
                    'user_id' => $existingUser['id'],
                    'email'   => $email,
                    'code'    => $code
                ]);

                $_SESSION['verify_user_id'] = $existingUser['id'];
                $_SESSION['verify_email']   = $existingUser['email'];
                $_SESSION['success']        = "Account exists but not verified. New code sent — check verification_log.txt";

                header("Location: /verify", true, 303);
                exit;
            }
        }
    }

    // ─── Create new user ───
    if (empty($data['errors'])) {
        $userId = createUser($pdo, $name, $email, $password);
        $code   = rand(100000, 999999);
        saveVerificationCode($pdo, $userId, $code);

         // ─── Send verification email ───
        $emailSent = sendVerificationEmail($email, $name, $code);

        // ─── Log to file so you can read the code ───
        logVerificationCode($email, $code, 'NEW');

        if (!$emailSent) {
            logWarning("Email failed — check log file for code", [
                'email' => $email
            ]);
        }
        logAuth("New user registered", [
            'user_id' => $userId,
            'email'   => $email,
            'email_sent' => $emailSent,
            'code'    => $code
        ]);

        $_SESSION['verify_user_id'] = $userId;
        $_SESSION['verify_email']   = $email;

        header("Location: /verify", true, 303);
        exit;
    }

    return $data;
}