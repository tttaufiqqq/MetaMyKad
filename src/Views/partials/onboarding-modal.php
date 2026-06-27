<div class="onboard-modal hidden" id="onboard-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="onboard-title">
    <div class="onboard-modal__panel">

        <button class="student-modal__close" id="onboard-close" aria-label="Close">&times;</button>

        <p class="student-modal__eyebrow">Getting Started</p>
        <h3 id="onboard-title" class="onboard-modal__title">How to use MetaMyKad</h3>

        <div class="onboard-dots" aria-hidden="true">
            <span class="onboard-dot is-active"></span>
            <span class="onboard-dot"></span>
            <span class="onboard-dot"></span>
            <span class="onboard-dot"></span>
        </div>

        <div class="onboard-steps">

            <!-- Step 1: Find your profile -->
            <div class="onboard-step is-active" data-step="0">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--cyan">
                    <!-- Users search: person + magnifier -->
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#39c5ff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M3 21v-1a6 6 0 0 1 9-5.2"/>
                        <circle cx="19" cy="19" r="3"/>
                        <line x1="21.5" y1="21.5" x2="23" y2="23"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 1 of 4</span>
                    <h4 class="onboard-step__title">Find your profile</h4>
                </div>
                <p class="onboard-step__desc">
                    You're already in Madam Hidayah's system, so your basic info is here.
                    Head to the <strong>Student List</strong>, search for your name,
                    and click your profile card.
                </p>
                <a href="<?= e(url('/students')) ?>" class="onboard-step__hint">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Go to Student List
                </a>
            </div>

            <!-- Step 2: Authenticate -->
            <div class="onboard-step" data-step="1">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--green">
                    <!-- Shield with checkmark -->
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#71efc0" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 2 of 4</span>
                    <h4 class="onboard-step__title">Prove it's you</h4>
                </div>
                <p class="onboard-step__desc">
                    Click <strong>Login</strong> on your profile card and enter the
                    <strong>password you used in Madam Hidayah's system</strong>.
                    This verifies your identity before you can edit anything.
                </p>
                <p class="onboard-step__note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Not a new account &mdash; use your existing password.
                </p>
            </div>

            <!-- Step 3: Complete profile -->
            <div class="onboard-step" data-step="2">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--orange">
                    <!-- Folder upload -->
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#ffb35c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        <polyline points="12 11 12 17"/>
                        <polyline points="9 14 12 11 15 14"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 3 of 4</span>
                    <h4 class="onboard-step__title">Complete your profile</h4>
                </div>
                <p class="onboard-step__desc">
                    Fill in your <strong>IC number</strong> and re-upload your multimedia files.
                    You need all four types &mdash; sorry for the inconvenience.
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

            <!-- Step 4: View metadata -->
            <div class="onboard-step" data-step="3">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--purple">
                    <!-- Sparkles: extracted insight -->
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3l1.8 5.4L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.6L12 3z"/>
                        <path d="M19 14l.9 2.6L22.5 17.5l-2.6.9L19 21l-.9-2.6L15.5 17.5l2.6-.9L19 14z"/>
                        <path d="M5 18l.6 1.7L7.3 20.3l-1.7.6L5 22.6l-.6-1.7L2.7 20.3l1.7-.6L5 18z"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">Step 4 of 4</span>
                    <h4 class="onboard-step__title">Explore your metadata</h4>
                </div>
                <p class="onboard-step__desc">
                    Visit your <strong>Student Profile</strong> to see everything extracted
                    automatically &mdash; birthdate, state &amp; gender from your IC,
                    plus dimensions, duration, page count &amp; more from your files.
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

    </div><!-- /.onboard-modal__panel -->
</div>

<style>
.onboard-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.65);
    padding: var(--space-2);
    z-index: 70;
}
.onboard-modal.hidden { display: none; }

.onboard-modal__panel {
    width: min(560px, 100%);
    max-height: 90vh;
    overflow-y: auto;
    background: linear-gradient(160deg, rgba(15, 24, 50, 0.99), rgba(8, 14, 33, 0.98));
    border-radius: var(--radius-md);
    padding: var(--space-4) var(--space-3) var(--space-3);
    border: 1px solid var(--color-line);
    box-shadow: var(--shadow-panel);
    position: relative;
}

.onboard-modal__title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 1.25rem;
    letter-spacing: -0.01em;
}

/* Progress dots */
.onboard-dots {
    display: flex;
    gap: 5px;
    justify-content: center;
    margin-bottom: 1.75rem;
}
.onboard-dot {
    height: 6px;
    width: 6px;
    border-radius: var(--radius-pill);
    background: rgba(121, 166, 255, 0.22);
    transition: width 0.25s ease, background 0.25s ease;
    cursor: pointer;
}
.onboard-dot.is-active {
    width: 22px;
    background: var(--color-brand);
}
.onboard-dot.is-done {
    background: rgba(57, 197, 255, 0.45);
}

/* Step panels */
.onboard-steps { min-height: 260px; }
.onboard-step { display: none; }
.onboard-step.is-active {
    display: block;
    animation: onboard-in 0.28s ease;
}
@keyframes onboard-in {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Icon wrap */
.onboard-step__icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.onboard-step__icon-wrap--cyan   { background: rgba(57, 197, 255, 0.1);  border: 1px solid rgba(57, 197, 255, 0.22); }
.onboard-step__icon-wrap--green  { background: rgba(113, 239, 192, 0.1); border: 1px solid rgba(113, 239, 192, 0.22); }
.onboard-step__icon-wrap--orange { background: rgba(255, 179, 92, 0.1);  border: 1px solid rgba(255, 179, 92, 0.22); }
.onboard-step__icon-wrap--purple { background: rgba(192, 132, 252, 0.1); border: 1px solid rgba(192, 132, 252, 0.22); }

/* Step number + title */
.onboard-step__meta { text-align: center; margin-bottom: 0.85rem; }
.onboard-step__num {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--color-dim);
    margin-bottom: 0.3rem;
}
.onboard-step__title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
}

/* Description */
.onboard-step__desc {
    font-size: 0.875rem;
    color: var(--color-muted);
    line-height: 1.65;
    text-align: center;
    margin-bottom: 1rem;
}
.onboard-step__desc strong { color: var(--color-text); }

/* Step 1: hint link */
.onboard-step__hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--color-brand);
    text-decoration: none;
    border: 1px solid rgba(57, 197, 255, 0.28);
    background: rgba(57, 197, 255, 0.07);
    padding: 0.35rem 0.75rem;
    border-radius: var(--radius-pill);
    width: fit-content;
    margin: 0 auto;
    transition: background 0.18s, border-color 0.18s;
}
.onboard-step__hint:hover {
    background: rgba(57, 197, 255, 0.14);
    border-color: rgba(57, 197, 255, 0.5);
}

/* Step 2: info note — red warning */
.onboard-step__note {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--color-danger-text);
    background: rgba(255, 97, 119, 0.08);
    border: 1px solid rgba(255, 97, 119, 0.25);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    margin: 0;
}
.onboard-step__note svg { flex-shrink: 0; opacity: 0.8; }

/* Step 3: file type chips */
.onboard-step__file-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}
.onboard-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.65rem;
    border-radius: var(--radius-pill);
}
.onboard-chip--photo  { background: rgba(57, 197, 255, 0.1);  color: #39c5ff;  border: 1px solid rgba(57, 197, 255, 0.22); }
.onboard-chip--audio  { background: rgba(113, 239, 192, 0.1); color: #71efc0;  border: 1px solid rgba(113, 239, 192, 0.22); }
.onboard-chip--pdf    { background: rgba(255, 179, 92, 0.1);  color: #ffb35c;  border: 1px solid rgba(255, 179, 92, 0.22); }
.onboard-chip--video  { background: rgba(192, 132, 252, 0.1); color: #c084fc;  border: 1px solid rgba(192, 132, 252, 0.22); }

/* Step 4: metadata tag pills */
.onboard-step__meta-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}
.onboard-step__meta-tags span {
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--color-muted);
    background: rgba(121, 166, 255, 0.07);
    border: 1px solid rgba(121, 166, 255, 0.14);
    border-radius: var(--radius-pill);
    padding: 0.25rem 0.6rem;
}

/* Nav */
.onboard-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-line);
    gap: 0.75rem;
}
.onboard-nav .button { min-width: 90px; }
.onboard-nav .button:disabled,
.onboard-nav .button[disabled] {
    opacity: 0.35;
    pointer-events: none;
}

/* Mobile: bottom-sheet style */
@media (max-width: 540px) {
    .onboard-modal {
        padding: 0;
        align-items: flex-end;
    }
    .onboard-modal__panel {
        width: 100%;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        max-height: 88vh;
        padding: 1.5rem 1.25rem 1.25rem;
    }
    .onboard-step__icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        margin-bottom: 1rem;
    }
    .onboard-modal__title {
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }
    .onboard-steps { min-height: 210px; }
    .onboard-step__title { font-size: 1rem; }
    .onboard-step__desc { font-size: 0.82rem; }
    .onboard-nav { margin-top: 1.25rem; padding-top: 0.85rem; }
}

@media (prefers-reduced-motion: reduce) {
    .onboard-step.is-active { animation: none; }
    .onboard-dot { transition: none; }
}
</style>

<script>
(function () {
    var modal    = document.getElementById('onboard-modal');
    if (!modal) return;

    var steps    = modal.querySelectorAll('.onboard-step');
    var dots     = modal.querySelectorAll('.onboard-dot');
    var prevBtn  = document.getElementById('onboard-prev');
    var nextBtn  = document.getElementById('onboard-next');
    var closeBtn = document.getElementById('onboard-close');
    var total    = steps.length;
    var current  = 0;

    function goTo(i) {
        steps[current].classList.remove('is-active');
        current = i;
        steps[current].classList.add('is-active');
        dots.forEach(function (d, idx) {
            d.classList.toggle('is-active', idx === current);
            d.classList.toggle('is-done', idx < current);
        });
        prevBtn.disabled = current === 0;
        nextBtn.textContent = current === total - 1 ? 'Got it, let\'s start!' : 'Next';
    }

    function open() {
        goTo(0);
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    nextBtn.addEventListener('click', function () {
        if (current < total - 1) { goTo(current + 1); } else { close(); }
    });
    prevBtn.addEventListener('click', function () {
        if (current > 0) goTo(current - 1);
    });
    closeBtn.addEventListener('click', close);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) close();
    });
    dots.forEach(function (d, idx) {
        d.addEventListener('click', function () { goTo(idx); });
    });

    // Re-open trigger button on home page
    var trigger = document.getElementById('onboard-trigger');
    if (trigger) {
        trigger.addEventListener('click', open);
    }

    // Always show on homepage — no localStorage gate
    var autoShow = <?= (isset($currentPath) && $currentPath === '/') ? 'true' : 'false' ?>;
    if (autoShow) open();
}());
</script>
