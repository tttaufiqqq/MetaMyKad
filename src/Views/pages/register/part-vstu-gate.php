<div class="register-card">
    <h1 class="register-title"><?= $_isProfileCompletion ? 'Profile Completion' : 'Student Registration' ?></h1>
    <p class="muted" style="margin-bottom:1.5rem; text-align:center;">
        <?php if ($_isProfileCompletion): ?>
            Your MetaMyKad profile is incomplete. Sign in with your <strong>student credentials in Madam Hidayah's System</strong>
            to continue completing your profile.
        <?php else: ?>
            To register, please verify your identity using your <strong>VSTU student credentials</strong>
            (matric number and password from Madam Hidayah's system).
        <?php endif; ?>
    </p>

    <div class="feedback-box" style="margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.55rem;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;opacity:0.7;" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        <span>Your credentials are verified against the central student database (<code>mmdb2026.vstu</code>). MetaMyKad never stores your VSTU password.</span>
    </div>

    <form action="<?= e(url('/login')) ?>" method="post" novalidate>
        <?php require src_path('Views/partials/csrf.php'); ?>
        <input type="hidden" name="redirect" value="/register">

        <div class="form-grid two-col">
            <div class="form-group">
                <input
                    type="text"
                    id="matric_number"
                    name="matric_number"
                    value="<?= e($_vstuMatric !== '' ? $_vstuMatric : old('matric_number', '')) ?>"
                    autocomplete="username"
                    <?= $_isProfileCompletion ? 'readonly' : '' ?>
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

        <div class="form-actions" style="margin-top:1.25rem;">
            <button class="button button--full" type="submit">Verify &amp; Continue</button>
        </div>
    </form>

    <hr class="register-divider">

    <p style="text-align:center; margin-top:1rem; font-size:0.875rem; color:var(--text-muted,#888);">
        Not enrolled in the Madam Hidayah system?
        <a href="<?= e(url('/register?open=1')) ?>">Register independently &rarr;</a>
    </p>
</div>
