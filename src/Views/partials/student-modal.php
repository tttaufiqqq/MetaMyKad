<div class="student-modal hidden" id="student-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="sm-name">
    <div class="student-modal__panel">
        <button class="student-modal__close" id="student-modal-close" aria-label="Close">&times;</button>
        <p class="student-modal__eyebrow">Student Preview</p>
        <h3 class="student-modal__name" id="sm-name"></h3>
        <dl class="student-modal__meta">
            <div class="sm-field" id="sm-row-ic" hidden>
                <dt>IC Number</dt>
                <dd id="sm-ic"></dd>
            </div>
            <div class="sm-field" id="sm-row-badge" hidden>
                <dt>Badge</dt>
                <dd id="sm-badge"></dd>
            </div>
            <div class="sm-field" id="sm-row-gender" hidden>
                <dt>Gender</dt>
                <dd id="sm-gender"></dd>
            </div>
            <div class="sm-field" id="sm-row-state" hidden>
                <dt>State of Birth</dt>
                <dd id="sm-state"></dd>
            </div>
            <div class="sm-field" id="sm-row-files" hidden>
                <dt>Files Uploaded</dt>
                <dd id="sm-files"></dd>
            </div>
            <div class="sm-field" id="sm-row-file-type" hidden>
                <dt>File Type</dt>
                <dd id="sm-file-type"></dd>
            </div>
        </dl>
        <div class="student-modal__actions">
            <button type="button" class="button secondary" id="student-modal-cancel">Close</button>
            <a class="button" id="sm-view-link" href="#">View Full Profile</a>
        </div>
    </div>
</div>
