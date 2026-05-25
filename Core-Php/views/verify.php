<?php
// Variables available: $errors, $email
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h2>Verify Your Email</h2>

        <p style="text-align:center; color:#555; margin-bottom:20px; font-size:14px;">
            We sent a 6-digit code to<br>
            <strong><?= htmlspecialchars($email) ?></strong>
        </p>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?>
                    <div>• <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['resend_success'])): ?>
            <div class="alert-success">
                <?= htmlspecialchars($_SESSION['resend_success']) ?>
            </div>
            <?php unset($_SESSION['resend_success']); ?>
        <?php endif; ?>

        <form method="POST" data-validate>
            <label>Enter Verification Code</label>
            <input
                type="text"
                name="code"
                maxlength="6"
                placeholder="e.g. 847291"
                autocomplete="off"
                style="letter-spacing: 8px; font-size: 22px; text-align: center;"
            >

            <button type="submit">Verify Email</button>
        </form>

        <form method="POST" action="resend_code.php" 
              style="margin-top:15px;">
            <button type="submit" 
                    style="background:#fff; color:#222; border: 2px solid #222;">
                Resend Code
            </button>
        </form>

        <div class="bottom-link">
            Wrong email? <a href="register.php">Register again</a>
        </div>
    </div>
</div>