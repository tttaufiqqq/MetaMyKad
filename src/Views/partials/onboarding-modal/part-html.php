<div class="onboard-modal hidden" id="onboard-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="onboard-title">
    <div class="onboard-modal__panel">

        <button class="student-modal__close" id="onboard-close" aria-label="Close">&times;</button>

        <p class="student-modal__eyebrow">Getting Started</p>
        <h3 id="onboard-title" class="onboard-modal__title">How to use MetaMyKad</h3>
        <p class="onboard-modal__intro">Take a minute to go through this before jumping in — it'll save you a lot of guessing.</p>

        <div class="onboard-dots" aria-hidden="true">
            <span class="onboard-dot is-active"></span>
            <span class="onboard-dot"></span>
            <span class="onboard-dot"></span>
            <span class="onboard-dot"></span>
            <span class="onboard-dot"></span>
        </div>

        <div class="onboard-steps">

            <!-- Step 1: Find your profile -->
            <div class="onboard-step is-active" data-step="0">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--cyan">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#39c5ff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M3 21v-1a6 6 0 0 1 9-5.2"/>
                        <circle cx="19" cy="19" r="3"/>
                        <line x1="21.5" y1="21.5" x2="23" y2="23"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 1 of 5</span>
                    <h4 class="onboard-step__title">Find your profile</h4>
                </div>
                <p class="onboard-step__desc">
                    Your basic info is already in the system from Madam Hidayah's class,
                    so you won't be starting from scratch. Head over to the
                    <strong>Student List</strong>, find your name and click your card.
                </p>
                <a href="<?= e(url('/students')) ?>" class="onboard-step__hint">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Go to Student List
                </a>
            </div>

            <!-- Step 2: Authenticate -->
            <div class="onboard-step" data-step="1">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--green">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#71efc0" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 2 of 5</span>
                    <h4 class="onboard-step__title">Prove it's you</h4>
                </div>
                <p class="onboard-step__desc">
                    Find the card with your name and click it. A login screen will pop up
                    asking for the same <strong>matric number and password</strong> you
                    use in Madam Hidayah's system. Once that goes through, you're in.
                </p>
                <p class="onboard-step__note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Not a new account, just use the password you already have.
                </p>
            </div>

            <!-- Step 3: Complete profile -->
            <div class="onboard-step" data-step="2">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--orange">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#ffb35c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        <polyline points="12 11 12 17"/>
                        <polyline points="9 14 12 11 15 14"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 3 of 5</span>
                    <h4 class="onboard-step__title">Complete your profile</h4>
                </div>
                <p class="onboard-step__desc">
                    Fill in your <strong>IC number</strong> and <strong>email address</strong>,
                    then upload your multimedia files. We use your email to figure out what kind
                    of account it is — student, personal, or work. You'll need all four file types
                    ready as well, so sorry for the inconvenience of reuploading back what you
                    already uploaded in Madam's system!
                </p>
                <div class="onboard-step__file-chips">
                    <span class="onboard-chip onboard-chip--photo">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Photo
                    </span>
                    <span class="onboard-chip onboard-chip--audio">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                        Audio
                    </span>
                    <span class="onboard-chip onboard-chip--pdf">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>
                        PDF
                    </span>
                    <span class="onboard-chip onboard-chip--video">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                        Video
                    </span>
                </div>
            </div>

            <!-- Step 4: IC Privacy -->
            <div class="onboard-step" data-step="3">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--cyan">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#39c5ff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 4 of 5</span>
                    <h4 class="onboard-step__title">Your IC stays private</h4>
                </div>
                <p class="onboard-step__desc">
                    When you enter your IC number, we hash it before saving anything.
                    Nobody can read the real number from the database or anywhere on screen.
                    This is what it looks like stored in our system:
                </p>
                <div class="onboard-ic-preview" aria-label="Example of masked IC numbers in the database">
                    <div class="onboard-ic-preview__header">ic_number</div>
                    <div class="onboard-ic-preview__row">94c68884246b7f08bb54d98e1c175582c3b6f7f98a66ec1e8b...</div>
                    <div class="onboard-ic-preview__row">5873c4aca414efffd6602f42b252e3e367a72fa472766cb1be...</div>
                    <div class="onboard-ic-preview__row">f9ade36fd850</div>
                    <div class="onboard-ic-preview__row">81d674a57ed3</div>
                </div>
                <p class="onboard-step__note" style="margin-top:0.75rem;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Hashing is one-way. Your real IC can never be recovered from what's stored.
                </p>
            </div>

            <!-- Step 5: View metadata -->
            <div class="onboard-step" data-step="4">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--purple">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3l1.8 5.4L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.6L12 3z"/>
                        <path d="M19 14l.9 2.6L22.5 17.5l-2.6.9L19 21l-.9-2.6L15.5 17.5l2.6-.9L19 14z"/>
                        <path d="M5 18l.6 1.7L7.3 20.3l-1.7.6L5 22.6l-.6-1.7L2.7 20.3l1.7-.6L5 18z"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 5 of 5</span>
                    <h4 class="onboard-step__title">Explore your metadata</h4>
                </div>
                <p class="onboard-step__desc">
                    Head to your <strong>Student Profile</strong> to see what the system
                    pulled out automatically. Things like your birthdate, state and gender
                    from your IC, plus image dimensions, audio duration, page count and
                    video resolution from your uploaded files.
                </p>
                <div class="onboard-step__meta-tags">
                    <span>IC &rarr; Birthdate</span>
                    <span>IC &rarr; State</span>
                    <span>IC &rarr; Gender</span>
                    <span>Image size</span>
                    <span>Audio duration</span>
                    <span>PDF pages</span>
                    <span>Video resolution</span>
                </div>
            </div>

        </div><!-- /.onboard-steps -->

        <div class="onboard-nav">
            <button type="button" class="button secondary" id="onboard-prev" disabled>Back</button>
            <button type="button" class="button" id="onboard-next">Next</button>
        </div>

        <p class="onboard-footer-hint">
            Not sure how to use the search features?
            <button type="button" class="onboard-footer-link" id="onboard-open-search-guide">Open the Search Guide</button>
        </p>

    </div><!-- /.onboard-modal__panel -->
</div>
