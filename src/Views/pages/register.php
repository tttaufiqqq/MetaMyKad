<?php if (!empty($prefill)): ?>
<div class="confirm-dialog hidden" id="welcome-modal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="confirm-dialog__panel">
        <h3>Complete Your Profile</h3>
        <p>
            Welcome, <strong><?= e($prefill['full_name']) ?></strong>!
            Your student identity has been verified.
        </p>
        <p style="margin-top:.75rem;">
            To use MetaMyKad, please fill in the fields below &mdash;
            your <strong>IC number</strong>, <strong>email</strong>, and
            <strong>multimedia file uploads</strong> are required to activate your profile.
        </p>
        <div class="confirm-dialog__actions">
            <button type="button" class="button" id="welcome-modal-close">Got it, let's go</button>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="register-card">
    <h1 class="register-title">Student Registration</h1>
    <p class="muted" style="margin-bottom:1.5rem; text-align:center;">
        Create a new student profile. Fill in your personal details and upload your multimedia files —
        photo, audio, PDF, and video. IC number is used to derive your birthdate, gender, and state automatically.
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
        </div>

        <div class="feedback-box" style="margin-bottom:0.6rem; display:flex; align-items:flex-start; gap:0.55rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;opacity:0.7;" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span>Your IC number is private — only you can see it after registration. It is used solely to derive your birthdate, gender, and state of birth.</span>
        </div>
        <div class="feedback-box" style="margin-bottom:1rem;">
            DOB, gender, state of birth, and age are derived from the IC number automatically.
            Email is classified as personal, student, or work.
        </div>

        <hr class="register-divider">

        <div class="upload-panel">
            <div class="upload-grid">
                <label class="upload-box" for="photo">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></span>
                    <span>Photo</span>
                    <small>JPEG / PNG</small>
                </label>
                <label class="upload-box" for="audio">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></span>
                    <span>Audio</span>
                    <small>MP3 / WAV</small>
                </label>
                <label class="upload-box" for="pdf">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
                    <span>Document</span>
                    <small>Searchable PDF</small>
                </label>
                <label class="upload-box" for="video">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg></span>
                    <span>Video</span>
                    <small>MP4 / MOV / AVI</small>
                </label>
            </div>
            <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png" class="hidden">
            <input id="audio" name="audio" type="file" accept=".mp3,.wav"       class="hidden">
            <input id="pdf"   name="pdf"   type="file" accept=".pdf"            class="hidden">
            <input id="video" name="video" type="file" accept=".mp4,.mov,.avi"  class="hidden">
        </div>

        <div class="feedback-box" style="margin-top:1rem; display:flex; align-items:flex-start; gap:0.55rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;opacity:0.7;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Upload your multimedia files to enable metadata extraction — photo properties, audio duration, document text content, and video resolution will be automatically analysed and stored for search and retrieval.</span>
        </div>

        <button class="register-submit-btn" type="submit">Register Student</button>
    </form>

    <a class="register-cancel" href="<?= e(url('/')) ?>">&#8592; Cancel and Back to Home</a>
</div>
<script>
(function () {
    ['photo', 'audio', 'pdf', 'video'].forEach(function (type) {
        var input = document.getElementById(type);
        if (!input) return;
        input.addEventListener('change', function () {
            var name = 'original_date_' + type;
            var hidden = input.form.querySelector('[name="' + name + '"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                input.form.appendChild(hidden);
            }
            if (this.files && this.files[0]) {
                var d = new Date(this.files[0].lastModified);
                hidden.value = d.getFullYear() + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    String(d.getDate()).padStart(2, '0');
            } else {
                hidden.value = '';
            }
        });
    });

    ['audio', 'video'].forEach(function (type) {
        var input = document.getElementById(type);
        if (!input) return;
        input.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            var url = URL.createObjectURL(this.files[0]);
            var el = document.createElement(type === 'video' ? 'video' : 'audio');
            el.preload = 'metadata';
            el.onloadedmetadata = function () {
                URL.revokeObjectURL(url);
                var sec = Math.round(el.duration);
                var name = 'duration_sec_' + type;
                var hidden = input.form.querySelector('[name="' + name + '"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = name;
                    input.form.appendChild(hidden);
                }
                hidden.value = sec;
            };
            el.src = url;
        });
    });
}());

(function () {
    var modal = document.getElementById('welcome-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.getElementById('welcome-modal-close').addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    });
}());
</script>
