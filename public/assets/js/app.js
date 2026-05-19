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
})();
