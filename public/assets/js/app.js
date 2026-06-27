(function () {
    var dialog = document.getElementById('confirm-dialog');
    var pendingForm = null;
    var pendingHref = null;

    document.addEventListener('click', function (event) {
        var closeButton = event.target.closest('[data-toast-close]');
        if (closeButton) {
            var toast = closeButton.closest('[data-toast]');
            if (toast) {
                toast.remove();
            }
            return;
        }

        var trigger = event.target.closest('[data-confirm]');
        if (!trigger) {
            return;
        }

        event.preventDefault();

        if (!dialog) {
            if (window.confirm(trigger.getAttribute('data-confirm') || 'Are you sure?')) {
                if (trigger.tagName === 'A') {
                    window.location.href = trigger.href;
                } else if (trigger.form) {
                    trigger.form.submit();
                }
            }
            return;
        }

        var message = trigger.getAttribute('data-confirm') || 'Are you sure you want to continue?';
        var messageNode = document.getElementById('confirm-dialog-message');
        if (messageNode) {
            messageNode.textContent = message;
        }

        pendingForm = trigger.form || null;
        pendingHref = trigger.tagName === 'A' ? trigger.href : null;
        dialog.classList.remove('hidden');
        dialog.setAttribute('aria-hidden', 'false');
    });

    if (dialog) {
        var cancel = dialog.querySelector('[data-confirm-cancel]');
        var accept = dialog.querySelector('[data-confirm-accept]');

        if (cancel) {
            cancel.addEventListener('click', function () {
                pendingForm = null;
                pendingHref = null;
                dialog.classList.add('hidden');
                dialog.setAttribute('aria-hidden', 'true');
            });
        }

        if (accept) {
            accept.addEventListener('click', function () {
                if (pendingForm) {
                    pendingForm.submit();
                } else if (pendingHref) {
                    window.location.href = pendingHref;
                }
            });
        }
    }

    // ── Page spinner ──────────────────────────────────────
    var spinner = document.getElementById('page-spinner');

    function showSpinner() {
        if (spinner) {
            spinner.classList.add('is-active');
            spinner.setAttribute('aria-hidden', 'false');
        }
    }

    function hideSpinner() {
        if (spinner) {
            spinner.classList.remove('is-active');
            spinner.setAttribute('aria-hidden', 'true');
        }
    }

    document.addEventListener('submit', function (event) {
        if (!event.defaultPrevented) {
            showSpinner();
        }
    });

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link) { return; }
        var href = link.getAttribute('href') || '';
        if (href === '' || href.charAt(0) === '#' || link.target || event.defaultPrevented) { return; }
        showSpinner();
    });

    window.addEventListener('pageshow', hideSpinner);

    // ── Badge Guide Modal ─────────────────────────────
    var badgeModal  = document.getElementById('badge-guide-modal');

    function openBadgeGuide() {
        if (!badgeModal) return;
        badgeModal.classList.remove('hidden');
        badgeModal.setAttribute('aria-hidden', 'false');
    }

    function closeBadgeGuide() {
        if (!badgeModal) return;
        badgeModal.classList.add('hidden');
        badgeModal.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-badge-guide-open]')) { openBadgeGuide(); return; }
        if (e.target.closest('#badge-guide-close') || e.target.closest('#badge-guide-cancel')) { closeBadgeGuide(); }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeBadgeGuide();
    });

    // ── Global scroll lock via MutationObserver ───────────
    // Watches every modal element; locks/unlocks body scroll automatically.
    document.addEventListener('DOMContentLoaded', function () {
        var MODAL_SELECTOR = '.student-modal, .badge-guide-modal, .confirm-dialog';

        function syncScrollLock() {
            var anyOpen = document.querySelector(
                '.student-modal:not(.hidden),' +
                '.badge-guide-modal:not(.hidden),' +
                '.confirm-dialog:not(.hidden)'
            );
            document.body.classList.toggle('modal-open', !!anyOpen);
        }

        document.querySelectorAll(MODAL_SELECTOR).forEach(function (el) {
            new MutationObserver(syncScrollLock).observe(el, {
                attributes: true,
                attributeFilter: ['class'],
            });
        });

        // ── Auto-dismiss toasts after 5 s ────────────────────
        var toasts = document.querySelectorAll('[data-toast]');
        for (var i = 0; i < toasts.length; i += 1) {
            (function (toast) {
                setTimeout(function () {
                    toast.style.transition = 'opacity 0.4s ease';
                    toast.style.opacity = '0';
                    setTimeout(function () { toast.remove(); }, 400);
                }, 5000);
            }(toasts[i]));
        }
    });
}());
