<?php
// ─── Users ───
function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function createUser($pdo, $name, $email, $password) {
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt   = $pdo->prepare("INSERT INTO users (name, email, password, is_verified) VALUES (?, ?, ?, 0)");
    $stmt->execute([$name, $email, $hashed]);
    return $pdo->lastInsertId();
}

function verifyUser($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
    $stmt->execute([$userId]);
}

// ─── Verification ───
function saveVerificationCode($pdo, $userId, $code) {
    $stmt = $pdo->prepare("INSERT INTO email_verifications (user_id, code) VALUES (?, ?)");
    $stmt->execute([$userId, $code]);
}

function getVerificationCode($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
