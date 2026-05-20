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
                    <input id="ic_number" name="ic_number" type="text" maxlength="12" autocomplete="off">
                    <label for="ic_number">Existing IC Number</label>
                </div>
                <div class="id-or-divider">or</div>
                <div class="form-group" style="flex:1;">
                    <input id="passport_number" name="passport_number" type="text" autocomplete="off">
                    <label for="passport_number">Passport Number</label>
                </div>
            </div>
            <div class="feedback-box">
                Re-registration updates the existing student row. It must not create a second record
                for the same IC number.
            </div>
            <div class="form-group">
                <input id="full_name" name="full_name" type="text">
                <label for="full_name">Full Name</label>
            </div>
            <div class="form-group">
                <input id="phone" name="phone" type="tel" required
                       pattern="(\+?60|0)[0-9\-\s]{7,11}"
                       maxlength="16"
                       title="Malaysian phone number, e.g. 012-3456789">
                <label for="phone">Phone</label>
            </div>
            <div class="form-group">
                <input id="email" name="email" type="email">
                <label for="email">Email</label>
            </div>
        </div>
        <div class="upload-panel">
            <p class="mb-2"><strong>Replace Selected Files</strong></p>
            <p class="muted">Leave a file type empty if the current stored file should remain unchanged.</p>
            <div class="upload-grid">
                <label class="upload-box" for="photo">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-image.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Replace Photo</span>
                    <small>JPEG / PNG</small>
                </label>
                <label class="upload-box" for="audio">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-audio.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Replace Audio</span>
                    <small>MP3 / WAV</small>
                </label>
                <label class="upload-box" for="pdf">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-pdf.png')) ?>" alt="" aria-hidden="true">
                    </span>
                    <span>Replace PDF</span>
                    <small>Searchable Text</small>
                </label>
                <label class="upload-box" for="video">
                    <span class="upload-icon">
                        <img src="<?= e(asset('images/nav/replace-video.png')) ?>" alt="" aria-hidden="true">
                    </span>
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
