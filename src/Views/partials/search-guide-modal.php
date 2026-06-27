<div class="srchguide-modal hidden" id="srchguide-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="srchguide-title">
    <div class="srchguide-modal__panel">

        <button class="student-modal__close" id="srchguide-close" aria-label="Close">&times;</button>

        <p class="student-modal__eyebrow">Quick Guide</p>
        <h3 id="srchguide-title" class="onboard-modal__title">3 Ways to Search</h3>

        <div class="onboard-dots" aria-hidden="true">
            <span class="onboard-dot is-active" id="sg-dot-0"></span>
            <span class="onboard-dot" id="sg-dot-1"></span>
            <span class="onboard-dot" id="sg-dot-2"></span>
        </div>

        <div class="onboard-steps">

            <!-- ABR -->
            <div class="onboard-step is-active" data-sg-step="0">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--cyan">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#39c5ff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">1 of 3</span>
                    <h4 class="onboard-step__title">Attribute Search (ABR)</h4>
                </div>
                <p class="onboard-step__desc">
                    Use this when you know something specific about the student. You can filter by
                    <strong>name</strong>, <strong>badge level</strong>, file type or a mix of those.
                    Good for when you have someone in mind and just want to pull up their profile.
                </p>
                <a href="<?= e(url('/search-attribute')) ?>" class="onboard-step__hint">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Try ABR Search
                </a>
            </div>

            <!-- TBR -->
            <div class="onboard-step" data-sg-step="1">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--green">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#71efc0" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        <line x1="8" y1="9" x2="14" y2="9"/><line x1="8" y1="13" x2="12" y2="13"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">2 of 3</span>
                    <h4 class="onboard-step__title">Text Search (TBR)</h4>
                </div>
                <p class="onboard-step__desc">
                    Use this to search through the <strong>actual words inside files</strong>. Type a keyword
                    and the system scans through text extracted from PDFs and other media. Handy when you
                    remember what a file was about but not who uploaded it.
                </p>
                <a href="<?= e(url('/search-text')) ?>" class="onboard-step__hint">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Try TBR Search
                </a>
            </div>

            <!-- CBR -->
            <div class="onboard-step" data-sg-step="2">
                <div class="onboard-step__icon-wrap onboard-step__icon-wrap--orange">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#ffb35c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        <rect x="7" y="8" width="3" height="3" rx="0.5"/><polyline points="11 14 13 12 15 14"/>
                    </svg>
                </div>
                <div class="onboard-step__meta">
                    <span class="onboard-step__num">3 of 3</span>
                    <h4 class="onboard-step__title">Content Search (CBR)</h4>
                </div>
                <p class="onboard-step__desc">
                    Use this to find files based on their <strong>technical properties</strong>. Things like
                    image dimensions, audio duration, video resolution or how many pages a PDF has.
                    Great when you need a file with specific characteristics rather than a specific person.
                </p>
                <a href="<?= e(url('/search-content')) ?>" class="onboard-step__hint">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Try CBR Search
                </a>
            </div>

        </div>

        <div class="onboard-nav">
            <button type="button" class="button secondary" id="srchguide-prev" disabled>Back</button>
            <button type="button" class="button" id="srchguide-next">Next</button>
        </div>

    </div>
</div>

<style>
.srchguide-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.65);
    padding: var(--space-2);
    z-index: 70;
}
.srchguide-modal.hidden { display: none; }
.srchguide-modal__panel {
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
@media (max-width: 540px) {
    .srchguide-modal { padding: 0; align-items: flex-end; }
    .srchguide-modal__panel {
        width: 100%;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        max-height: 88vh;
        padding: 1.5rem 1.25rem 1.25rem;
    }
}
</style>

<script>
(function () {
    var modal   = document.getElementById('srchguide-modal');
    if (!modal) return;

    var steps   = modal.querySelectorAll('[data-sg-step]');
    var dotIds  = ['sg-dot-0', 'sg-dot-1', 'sg-dot-2'];
    var prevBtn = document.getElementById('srchguide-prev');
    var nextBtn = document.getElementById('srchguide-next');
    var closeBtn = document.getElementById('srchguide-close');
    var total   = steps.length;
    var current = 0;

    function goTo(i) {
        steps[current].classList.remove('is-active');
        current = i;
        steps[current].classList.add('is-active');
        dotIds.forEach(function (id, idx) {
            var d = document.getElementById(id);
            if (!d) return;
            d.classList.toggle('is-active', idx === current);
            d.classList.toggle('is-done', idx < current);
        });
        prevBtn.disabled = current === 0;
        nextBtn.textContent = current === total - 1 ? 'Got it!' : 'Next';
    }

    function close() {
        if (modal.contains(document.activeElement)) {
            document.activeElement.blur();
        }
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        try { localStorage.setItem('search_guide_seen', '1'); } catch (e) {}
    }

    nextBtn.addEventListener('click', function () {
        if (current < total - 1) { goTo(current + 1); } else { close(); }
    });
    prevBtn.addEventListener('click', function () {
        if (current > 0) goTo(current - 1);
    });
    closeBtn.addEventListener('click', close);
    dotIds.forEach(function (id, idx) {
        var d = document.getElementById(id);
        if (d) d.addEventListener('click', function () { goTo(idx); });
    });

    window.openSearchGuide = function () {
        goTo(0);
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };
}());
</script>
