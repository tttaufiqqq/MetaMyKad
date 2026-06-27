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
