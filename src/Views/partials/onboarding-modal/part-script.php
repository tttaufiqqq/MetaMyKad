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
        try { localStorage.setItem('onboard_seen', '1'); } catch (e) {}
        var guideSeen = false;
        try { guideSeen = localStorage.getItem('search_guide_seen') === '1'; } catch (e) {}
        if (!guideSeen && typeof window.openSearchGuide === 'function') {
            window.openSearchGuide();
        }
    }

    nextBtn.addEventListener('click', function () {
        if (current < total - 1) { goTo(current + 1); } else { close(); }
    });
    prevBtn.addEventListener('click', function () {
        if (current > 0) goTo(current - 1);
    });
    closeBtn.addEventListener('click', close);
    dots.forEach(function (d, idx) {
        d.addEventListener('click', function () { goTo(idx); });
    });

    // Re-open trigger button on home page
    var trigger = document.getElementById('onboard-trigger');
    if (trigger) {
        trigger.addEventListener('click', open);
    }

    var autoShow = <?= (isset($currentPath) && $currentPath === '/') ? 'true' : 'false' ?>;
    var alreadySeen = false;
    try { alreadySeen = localStorage.getItem('onboard_seen') === '1'; } catch (e) {}
    if (autoShow && !alreadySeen) open();
}());
</script>
