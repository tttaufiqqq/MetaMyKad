<div class="login-wrap">
    <section class="card login-card">
        <div class="login-header">
            <h2>Student Login</h2>
            <p class="muted">Sign in with your matric number and password to edit your profile.</p>
        </div>

        <form action="<?= e(url('/login')) ?>" method="post" novalidate>
            <?php require src_path('Views/partials/csrf.php'); ?>
            <?php if ($embed ?? false): ?>
            <input type="hidden" name="_embed" value="1">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <input
                        type="text"
                        id="matric_number"
                        name="matric_number"
                        value="<?= e(old('matric_number', $prefillMatric ?? '')) ?>"
                        autocomplete="username"
                        required
                    >
                    <label for="matric_number">Matric Number</label>
                </div>

                <div class="form-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                    <label for="password">Password</label>
                </div>
            </div>

            <div class="form-actions">
                <button class="button button--full" type="submit">Sign In</button>
            </div>
        </form>

        <p class="login-footer-note">
            No account yet?
            <a href="<?= e(url('/register')) ?>">Register here</a>
        </p>
    </section>
</div>
