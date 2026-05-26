<div class="auth-wrap">
    <div class="auth-card">
        <h2>Verify Your Email</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?>
                    <div>• <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['resend_success'])): ?>
            <div class="alert-success">
                <?= htmlspecialchars($_SESSION['resend_success']) ?>
            </div>
            <?php unset($_SESSION['resend_success']); ?>
        <?php endif; ?>

        <?php if ($show_email): ?>

            <p style="text-align:center; color:#555; margin-bottom:20px; font-size:14px;">
                Session expired. Enter your email to get a new code.
            </p>

            <form method="POST" data-validate>
                <input type="hidden" name="lookup_email" value="1">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com">
                <button type="submit">Send New Code</button>
            </form>

        <?php else: ?>

            <p style="text-align:center; color:#555; margin-bottom:20px; font-size:14px;">
                We sent a 6-digit code to<br>
                <strong><?= htmlspecialchars($email) ?></strong>
            </p>

            <form method="POST" data-validate>
                <label>Enter Verification Code</label>
                <input
                    type="text"
                    name="code"
                    maxlength="6"
                    placeholder="e.g. 847291"
                    autocomplete="off"
                    style="letter-spacing:8px; font-size:22px; text-align:center;"
                >
                <button type="submit">Verify Email</button>
            </form>

            <form method="POST" action="/resend_code" style="margin-top:15px;">
                <button type="submit"
                        style="background:#fff; color:#222; border:2px solid #222;">
                    Resend Code
                </button>
            </form>

        <?php endif; ?>

        <div class="bottom-link">
            Wrong email? <a href="/register">Register again</a>
        </div>
    </div>
</div>