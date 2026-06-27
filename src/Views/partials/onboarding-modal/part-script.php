<script>
(function () {
    var modal   = document.getElementById('onboard-modal');
    if (!modal) return;

    var steps   = modal.querySelectorAll('.onboard-step');
    var dots    = modal.querySelectorAll('.onboard-dot');
    var prevBtn = document.getElementById('onboard-prev');
    var nextBtn = document.getElementById('onboard-next');
    var closeBtn = document.getElementById('onboard-close');
    var total   = steps.length;
    var current = 0;

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

    // Opens the search guide modal directly via DOM — no dependency on window.openSearchGuide
    function openSgModal() {
        var sg = document.getElementById('srchguide-modal');
        if (!sg) return;
        sg.querySelectorAll('[data-sg-step]').forEach(function (s) {
            s.classList.remove('is-active');
        });
        var first = sg.querySelector('[data-sg-step="0"]');
        if (first) first.classList.add('is-active');
        ['sg-dot-0', 'sg-dot-1', 'sg-dot-2'].forEach(function (id, i) {
            var d = document.getElementById(id);
            if (d) { d.classList.toggle('is-active', i === 0); d.classList.remove('is-done'); }
        });
        var pb = document.getElementById('srchguide-prev');
        if (pb) pb.disabled = true;
        var nb = document.getElementById('srchguide-next');
        if (nb) nb.textContent = 'Next';
        sg.classList.remove('hidden');
        sg.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function open() {
        goTo(0);
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function dismiss() {
        if (modal.contains(document.activeElement)) {
            document.activeElement.blur();
        }
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        try { localStorage.setItem('onboard_seen', '1'); } catch (e) {}
    }

    function close() {
        dismiss();
        var guideSeen = false;
        try { guideSeen = localStorage.getItem('search_guide_seen') === '1'; } catch (e) {}
        if (!guideSeen) {
            setTimeout(openSgModal, 80);
        }
    }

    nextBtn.addEventListener('click', function () {
        if (current < total - 1) { goTo(current + 1); } else { close(); }
    });
    prevBtn.addEventListener('click', function () {
        if (current > 0) goTo(current - 1);
    });
    closeBtn.addEventListener('click', function () { close(); });
    dots.forEach(function (d, idx) {
        d.addEventListener('click', function () { goTo(idx); });
    });

    // Re-open trigger button on home page
    var trigger = document.getElementById('onboard-trigger');
    if (trigger) {
        trigger.addEventListener('click', open);
    }

    // Search guide button — always opens regardless of localStorage
    var sgLink = document.getElementById('onboard-open-search-guide');
    if (sgLink) {
        sgLink.addEventListener('click', function () {
            dismiss();
            setTimeout(openSgModal, 80);
        });
    }

    var autoShow = <?= (isset($currentPath) && $currentPath === '/') ? 'true' : 'false' ?>;
    var alreadySeen = false;
    try { alreadySeen = localStorage.getItem('onboard_seen') === '1'; } catch (e) {}
    if (autoShow && !alreadySeen) open();
}());
</script>
