<?php
// Pure logic — no HTML at all

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

    // Keep values to refill form
    $data['name']  = $name;
    $data['email'] = $email;

    // Validate
    if (empty($name))   
        $data['errors'][] = "Name is required.";
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $data['errors'][] = "Valid email is required.";
    
    if (strlen($password) < 6)
        $data['errors'][] = "Password must be at least 6 characters.";
    
    if ($password !== $confirm)
        $data['errors'][] = "Passwords do not match.";

    // Check duplicate email
    if (empty($data['errors'])) {
        if (getUserByEmail($pdo, $email)) {
            $data['errors'][] = "Email already registered.";
        }
    }

    // Save user
    if (empty($data['errors'])) {
        $userId = createUser($pdo, $name, $email, $password);
        $code   = rand(100000, 999999);
        saveVerificationCode($pdo, $userId, $code);

        // Log verification code
        file_put_contents(
            ROOT . '/verification_log.txt',
            date('Y-m-d H:i:s') . " | $email | Code: $code\n",
            FILE_APPEND
        );

        // ─── Log verification code ───
        logAuth("Verification code generated", [
            'user_id' => $userId,
            'email'   => $email,
            'code'    => $code        // remove this in production
        ]);
        $_SESSION['verify_user_id'] = $userId;
        $_SESSION['verify_email']   = $email;

        header("Location: verify");
        exit;
    }

    return $data;
}