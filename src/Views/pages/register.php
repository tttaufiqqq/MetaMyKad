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

        <div class="feedback-box" style="margin-bottom:1rem;">
            DOB, gender, state of birth, and age are derived from the IC number automatically.
            Email is classified as personal, student, or work.
        </div>

        <hr class="register-divider">

        <div class="upload-panel">
            <div class="upload-grid">
                <label class="upload-box" for="photo">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-image.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Photo</span>
                    <small>JPEG / PNG</small>
                </label>
                <label class="upload-box" for="audio">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-audio.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Audio</span>
                    <small>MP3 / WAV</small>
                </label>
                <label class="upload-box" for="pdf">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-pdf.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Document</span>
                    <small>Searchable PDF</small>
                </label>
                <label class="upload-box" for="video">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-video.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Video</span>
                    <small>MP4 / MOV / AVI</small>
                </label>
            </div>
            <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png" class="hidden">
            <input id="audio" name="audio" type="file" accept=".mp3,.wav"       class="hidden">
            <input id="pdf"   name="pdf"   type="file" accept=".pdf"            class="hidden">
            <input id="video" name="video" type="file" accept=".mp4,.mov,.avi"  class="hidden">
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
