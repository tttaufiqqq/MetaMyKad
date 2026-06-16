<section class="card">
    <h2>Re-Registration</h2>
    <p class="muted">Update the existing record for an IC number and replace selected files.</p>
</section>

<form class="form-grid two-col" action="<?= e(url('/register')) ?>" method="post" enctype="multipart/form-data" data-validate>
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="mode" value="update">
    <div class="form-card-layout full-span">
        <div class="form-grid">
            <div class="reg-id-row full-span">
                <div class="form-group" style="flex:1;">
                    <input id="ic_number" name="ic_number" type="text" maxlength="12" autocomplete="off"
                               value="<?= e((string) old('ic_number')) ?>">
                    <label for="ic_number">Existing IC Number</label>
                </div>
                <div class="id-or-divider">or</div>
                <div class="form-group" style="flex:1;">
                    <input id="passport_number" name="passport_number" type="text" autocomplete="off"
                           pattern="[A-Za-z0-9]{6,20}"
                           minlength="6" maxlength="20"
                           title="6–20 alphanumeric characters (letters and digits only)"
                           value="<?= e((string) old('passport_number')) ?>">
                    <label for="passport_number">Passport Number</label>
                </div>
            </div>
            <div class="feedback-box">
                Re-registration updates the existing student row. It must not create a second record
                for the same IC number.
            </div>
            <div class="form-group">
                <input id="full_name" name="full_name" type="text" required
                       value="<?= e((string) old('full_name')) ?>">
                <label for="full_name">Full Name</label>
            </div>
            <div class="form-group">
                <input id="phone" name="phone" type="tel" required
                       pattern="(\+?60|0)[0-9\-\s]{7,11}"
                       maxlength="16"
                       title="Malaysian phone number, e.g. 012-3456789"
                       value="<?= e((string) old('phone')) ?>">
                <label for="phone">Phone</label>
            </div>
            <div class="form-group">
                <input id="email" name="email" type="email" required
                       value="<?= e((string) old('email')) ?>">
                <label for="email">Email</label>
            </div>
        </div>
        <div class="upload-panel">
            <p class="mb-2"><strong>Replace Selected Files</strong></p>
            <p class="muted">Leave a file type empty if the current stored file should remain unchanged.</p>
            <div class="upload-grid">
                <label class="upload-box" for="photo">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></span>
                    <span>Replace Photo</span>
                    <small>JPEG / PNG</small>
                </label>
                <label class="upload-box" for="audio">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></span>
                    <span>Replace Audio</span>
                    <small>MP3 / WAV</small>
                </label>
                <label class="upload-box" for="pdf">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
                    <span>Replace PDF</span>
                    <small>Searchable Text</small>
                </label>
                <label class="upload-box" for="video">
                    <span class="upload-icon" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg></span>
                    <span>Replace Video</span>
                    <small>MP4 / MOV / AVI</small>
                </label>
            </div>
            <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png" class="hidden">
            <input id="audio" name="audio" type="file" accept=".mp3,.wav" class="hidden">
            <input id="pdf" name="pdf" type="file" accept=".pdf" class="hidden">
            <input id="video" name="video" type="file" accept=".mp4,.mov,.avi" class="hidden">
            <button class="button" type="submit">Update Registration</button>
        </div>
    </div>
</form>
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
</script>
