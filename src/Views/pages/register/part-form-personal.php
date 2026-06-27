<div class="register-card">
    <h1 class="register-title"><?= ($isStubCompletion ?? false) ? 'Complete Your Profile' : 'Student Registration' ?></h1>
    <p class="muted" style="margin-bottom:1.5rem; text-align:center;">
        <?php if ($isStubCompletion ?? false): ?>
            Your basic account was created when you signed in. Add your IC number, email, and multimedia files to fully activate your MetaMyKad profile.
        <?php else: ?>
            Create a new student profile. Fill in your personal details and upload your multimedia files —
            photo, audio, PDF, and video. IC number is used to derive your birthdate, gender, and state automatically.
        <?php endif; ?>
    </p>

    <form action="<?= e(url('/register')) ?>" method="post" enctype="multipart/form-data" data-validate>
        <?php require src_path('Views/partials/csrf.php'); ?>

        <div class="form-grid two-col">
            <div class="form-group">
                <input id="full_name" name="full_name" type="text" required
                       autocomplete="name"
                       value="<?= e((string) ($prefill['full_name'] ?? old('full_name'))) ?>">
                <label for="full_name">Full Name (as per IC)</label>
            </div>
            <div class="form-group">
                <input id="matric_number" name="matric_number" type="text" required
                       autocomplete="off"
                       <?php if (!empty($prefill['matric'])): ?>readonly<?php endif; ?>
                       value="<?= e((string) ($prefill['matric'] ?? old('matric_number'))) ?>">
                <label for="matric_number">Matric Number</label>
            </div>
            <div class="form-group">
                <input id="phone" name="phone" type="tel" required
                       pattern="(\+?60|0)[0-9\-\s]{7,11}"
                       maxlength="16"
                       autocomplete="tel"
                       title="Malaysian phone number, e.g. 012-3456789"
                       value="<?= e((string) ($prefill['phone'] ?? old('phone'))) ?>">
                <label for="phone">Phone</label>
            </div>
            <div class="form-group">
                <input id="email" name="email" type="email" required
                       autocomplete="email"
                       value="<?= e((string) old('email')) ?>">
                <label for="email">Email</label>
            </div>
        </div>

        <div class="reg-id-row">
            <div class="form-group" style="flex:1;">
                <input id="ic_number" name="ic_number" type="text" maxlength="12"
                       autocomplete="off"
                       value="<?= e((string) old('ic_number')) ?>">
                <label for="ic_number">Malaysian IC Number (12 Digits)</label>
            </div>
            <?php if (!($isStubCompletion ?? false)): ?>
            <div class="id-or-divider">or</div>
            <div class="form-group" style="flex:1;">
                <input id="passport_number" name="passport_number" type="text"
                       autocomplete="off"
                       pattern="[A-Za-z0-9]{6,20}"
                       minlength="6" maxlength="20"
                       title="6–20 alphanumeric characters (letters and digits only)"
                       value="<?= e((string) old('passport_number')) ?>">
                <label for="passport_number">Passport Number (International)</label>
            </div>
            <?php endif; ?>
        </div>

        <div class="feedback-box" style="margin-bottom:0.6rem; display:flex; align-items:flex-start; gap:0.55rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;opacity:0.7;" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span>Your IC number is private — only you can see it after registration. It is used solely to derive your birthdate, gender, and state of birth.</span>
        </div>
        <div class="feedback-box" style="margin-bottom:1rem;">
            DOB, gender, state of birth, and age are derived from the IC number automatically.
            Email is classified as personal, student, or work.
        </div>

        <?php if (!($isStubCompletion ?? false)): ?>
        <div class="feedback-box" style="margin-top:0.75rem; margin-bottom:0.25rem;">
            <strong>Enrolled students</strong> leave the password field blank — your system password will be used automatically.
            If you are registering independently (not in the student system), set a password below.
        </div>
        <div class="form-grid two-col" style="margin-bottom:0.5rem;">
            <div class="form-group">
                <input id="password" name="password" type="password" autocomplete="new-password"
                       value="">
                <label for="password">Password <span class="field-note">(independent accounts only)</span></label>
            </div>
        </div>
        <?php endif; ?>

        <hr class="register-divider">
