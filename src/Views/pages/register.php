<div class="register-card">
    <h1 class="register-title">Student Registration</h1>

    <form action="<?= e(url('/register')) ?>" method="post" enctype="multipart/form-data" data-validate>
        <?php require src_path('Views/partials/csrf.php'); ?>

        <div class="form-grid two-col">
            <div class="form-group">
                <label for="full_name">Full Name (as per IC)</label>
                <input id="full_name" name="full_name" type="text" required value="<?= e((string) old('full_name')) ?>">
            </div>
            <div class="form-group">
                <label for="matric_number">Matric Number</label>
                <input id="matric_number" name="matric_number" type="text" required value="<?= e((string) old('matric_number')) ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="tel" required
                       pattern="(\+?60|0)[0-9\-\s]{7,11}"
                       maxlength="16"
                       title="Malaysian phone number, e.g. 012-3456789"
                       value="<?= e((string) old('phone')) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= e((string) old('email')) ?>">
            </div>
            <div class="form-group">
                <label for="ic_number">Malaysian IC Number (12 Digits)</label>
                <input id="ic_number" name="ic_number" type="text" maxlength="12" value="<?= e((string) old('ic_number')) ?>">
            </div>
            <div class="id-or-divider">or</div>
            <div class="form-group">
                <label for="passport_number">Passport Number (International Student)</label>
                <input id="passport_number" name="passport_number" type="text" value="<?= e((string) old('passport_number')) ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required minlength="8">
            </div>
            <div class="feedback-box">
                DOB, gender, state of birth, and age are derived from the IC number automatically.
                Email is classified as personal, student, or work.
            </div>
        </div>

        <hr class="register-divider">

        <div class="upload-card-grid">
            <div class="upload-card">
                <span class="upload-card-label">Photo (Image):</span>
                <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png">
            </div>
            <div class="upload-card">
                <span class="upload-card-label">Document (PDF):</span>
                <input id="pdf" name="pdf" type="file" accept=".pdf">
            </div>
            <div class="upload-card">
                <span class="upload-card-label">Audio (MP3):</span>
                <input id="audio" name="audio" type="file" accept=".mp3,.wav">
            </div>
            <div class="upload-card">
                <span class="upload-card-label">Video (MP4):</span>
                <input id="video" name="video" type="file" accept=".mp4,.mov,.avi">
            </div>
        </div>

        <button class="register-submit-btn" type="submit">Register Student</button>
    </form>

    <a class="register-cancel" href="<?= e(url('/')) ?>">&#8592; Cancel and Back to Home</a>
</div>
