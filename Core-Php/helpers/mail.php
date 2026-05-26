<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (file_exists(ROOT . '/vendor/autoload.php')) {
    require_once ROOT . '/vendor/autoload.php';
} else {
    require_once ROOT . '/vendor/phpmailer/phpmailer/src/Exception.php';
    require_once ROOT . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once ROOT . '/vendor/phpmailer/phpmailer/src/SMTP.php';
}

// ════════════════════════════════════════
//  CORE MAIL SENDER
//  This is the base function — all mail
//  functions call this one
// ════════════════════════════════════════
function sendMail(string $toEmail, string $toName, string $subject, string $htmlBody) {
    $mail = new PHPMailer(true);

    try {
        // ─── Server settings ───
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port       = MAIL_PORT;

        // ─── Recipients ───
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);

        // ─── Content ───
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;

        // ─── Plain text fallback ───
        $mail->AltBody = strip_tags($htmlBody);

        $mail->send();

        logInfo("Email sent successfully", [
            'to'      => $toEmail,
            'subject' => $subject
        ]);

        return true;

    } catch (Exception $e) {
        logError("Email failed to send", [
            'to'    => $toEmail,
            'error' => $mail->ErrorInfo
        ]);
        return false;
    }
}


// ════════════════════════════════════════
//  EMAIL TEMPLATES
//  Add new mail functions here as needed
// ════════════════════════════════════════

// ─── 1. Verification Code Email ───
function sendVerificationEmail(string $toEmail, string $toName, int $code) {
    $subject = "Verify Your Email — PHP Blog";

    $body = "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto;'>
        <div style='background: #222; padding: 20px; text-align: center;'>
            <h1 style='color: #fff; margin: 0;'>PHP Blog</h1>
        </div>

        <div style='padding: 30px; background: #f9f9f9;'>
            <h2 style='color: #222;'>Verify Your Email</h2>
            <p style='color: #555;'>Hi <strong>{$toName}</strong>,</p>
            <p style='color: #555;'>
                Thank you for registering. 
                Use the code below to verify your email address.
                This code expires in <strong>10 minutes</strong>.
            </p>

            <div style='text-align: center; margin: 30px 0;'>
                <span style='
                    font-size: 36px;
                    font-weight: bold;
                    letter-spacing: 10px;
                    background: #222;
                    color: #fff;
                    padding: 15px 30px;
                    border-radius: 8px;
                '>
                    {$code}
                </span>
            </div>

            <p style='color: #999; font-size: 13px;'>
                If you did not create an account, ignore this email.
            </p>
        </div>

        <div style='background: #eee; padding: 15px; text-align: center;'>
            <p style='color: #999; font-size: 12px; margin: 0;'>
                &copy; " . date('Y') . " PHP Blog. All rights reserved.
            </p>
        </div>
    </div>
    ";

    return sendMail($toEmail, $toName, $subject, $body);
}


// ─── 2. Welcome Email ───
function sendWelcomeEmail(string $toEmail, string $toName) {
    $subject = "Welcome to PHP Blog!";

    $body = "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto;'>
        <div style='background: #222; padding: 20px; text-align: center;'>
            <h1 style='color: #fff; margin: 0;'>PHP Blog</h1>
        </div>

        <div style='padding: 30px; background: #f9f9f9;'>
            <h2 style='color: #222;'>Welcome, {$toName}!</h2>
            <p style='color: #555;'>
                Your email has been verified successfully.
                You can now login and start creating blog posts.
            </p>

            <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost:9001/login'
                   style='
                       background: #222;
                       color: #fff;
                       padding: 12px 30px;
                       border-radius: 6px;
                       text-decoration: none;
                       font-size: 16px;
                   '>
                    Login Now
                </a>
            </div>
        </div>

        <div style='background: #eee; padding: 15px; text-align: center;'>
            <p style='color: #999; font-size: 12px; margin: 0;'>
                &copy; " . date('Y') . " PHP Blog. All rights reserved.
            </p>
        </div>
    </div>
    ";

    return sendMail($toEmail, $toName, $subject, $body);
}


// ─── 3. Password Reset Email (for later) ───
function sendPasswordResetEmail(string $toEmail, string $toName, string $resetLink) {
    $subject = "Reset Your Password — PHP Blog";

    $body = "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto;'>
        <div style='background: #222; padding: 20px; text-align: center;'>
            <h1 style='color: #fff; margin: 0;'>PHP Blog</h1>
        </div>

        <div style='padding: 30px; background: #f9f9f9;'>
            <h2 style='color: #222;'>Reset Your Password</h2>
            <p style='color: #555;'>Hi <strong>{$toName}</strong>,</p>
            <p style='color: #555;'>
                Click the button below to reset your password.
                This link expires in <strong>30 minutes</strong>.
            </p>

            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}'
                   style='
                       background: #222;
                       color: #fff;
                       padding: 12px 30px;
                       border-radius: 6px;
                       text-decoration: none;
                       font-size: 16px;
                   '>
                    Reset Password
                </a>
            </div>

            <p style='color: #999; font-size: 13px;'>
                If you did not request this, ignore this email.
            </p>
        </div>

        <div style='background: #eee; padding: 15px; text-align: center;'>
            <p style='color: #999; font-size: 12px; margin: 0;'>
                &copy; " . date('Y') . " PHP Blog. All rights reserved.
            </p>
        </div>
    </div>
    ";

    return sendMail($toEmail, $toName, $subject, $body);
}