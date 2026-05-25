<div class="auth-wrap">
    <div class="auth-card">
        <h2>Create Account</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?>
                    <div>• <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" data-validate>
            <label>Full Name</label>
            <input type="text" name="name" 
                   value="<?= htmlspecialchars($name) ?>">

            <label>Email Address</label>
            <input type="email" name="email" 
                   value="<?= htmlspecialchars($email) ?>">

            <label>Password</label>
            <input type="password" name="password">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password">

            <button type="submit">Register</button>
        </form>

        <div class="bottom-link">
            Already have an account? 
            <a href="login.php">Login</a>
        </div>
    </div>
</div>