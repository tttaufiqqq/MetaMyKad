/**
 * MetaMyKad — Custom Select Dropdown
 * Auto-enhances every .form-group select with a themed panel.
 * The native <select> stays hidden in the DOM so form submission works as-is.
 */
(function () {
    'use strict';

    function buildCustomSelect(select) {
        var wrap = document.createElement('div');
        wrap.className = 'cs-wrap';

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'cs-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        var valueSpan = document.createElement('span');
        valueSpan.className = 'cs-value';
        trigger.appendChild(valueSpan);

        var panel = document.createElement('div');
        panel.className = 'cs-panel';
        panel.setAttribute('role', 'listbox');
        panel.hidden = true;

        var opts = select.querySelectorAll('option');
        opts.forEach(function (opt) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cs-option';
            btn.setAttribute('role', 'option');
            btn.dataset.value = opt.value;
            btn.textContent = opt.textContent.trim();
            if (opt.selected) {
                btn.classList.add('is-selected');
                valueSpan.textContent = btn.textContent;
            }
            panel.appendChild(btn);
        });

        // Fallback: show first option text if none selected
        if (!valueSpan.textContent && opts[0]) {
            valueSpan.textContent = opts[0].textContent.trim();
        }

        wrap.appendChild(trigger);
        wrap.appendChild(panel);
        select.parentNode.insertBefore(wrap, select);

        // Hide native select but keep in DOM for form submission
        select.style.cssText = 'display:none;position:absolute;';

        // Open / close
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = !panel.hidden;
            closeAll();
            if (!isOpen) {
                panel.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
                wrap.classList.add('is-open');
                scrollSelectedIntoView(panel);
            }
        });

        // Option selection via click
        panel.addEventListener('click', function (e) {
            var opt = e.target.closest('.cs-option');
            if (!opt) return;
            pickOption(wrap, select, valueSpan, opt);
        });

        // Keyboard: open on Enter/Space from trigger
        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                trigger.click();
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                trigger.click();
            }
        });

        // Keyboard: navigate options
        panel.addEventListener('keydown', function (e) {
            var focused = panel.querySelector('.cs-option:focus');
            var allOpts = Array.from(panel.querySelectorAll('.cs-option'));
            var idx = focused ? allOpts.indexOf(focused) : -1;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                var next = allOpts[idx + 1] || allOpts[0];
                if (next) {
                    next.focus();
                    next.scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                var prev = allOpts[idx - 1] || allOpts[allOpts.length - 1];
                if (prev) {
                    prev.focus();
                    prev.scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (focused) pickOption(wrap, select, valueSpan, focused);
            } else if (e.key === 'Escape') {
                closeAll();
                trigger.focus();
            }
        });
    }

    function pickOption(wrap, select, valueSpan, opt) {
        var panel = wrap.querySelector('.cs-panel');
        var trigger = wrap.querySelector('.cs-trigger');

        panel.querySelectorAll('.cs-option').forEach(function (o) {
            o.classList.remove('is-selected');
        });
        opt.classList.add('is-selected');
        valueSpan.textContent = opt.textContent;
        select.value = opt.dataset.value;

        // Dispatch change so validate.js stays in sync
        select.dispatchEvent(new Event('change', { bubbles: true }));

        panel.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
        wrap.classList.remove('is-open');
        trigger.focus();
    }

    function closeAll() {
        document.querySelectorAll('.cs-wrap.is-open').forEach(function (wrap) {
            wrap.querySelector('.cs-panel').hidden = true;
            wrap.querySelector('.cs-trigger').setAttribute('aria-expanded', 'false');
            wrap.classList.remove('is-open');
        });
    }

    function scrollSelectedIntoView(panel) {
        var selected = panel.querySelector('.cs-option.is-selected');
        if (selected) {
            selected.scrollIntoView({ block: 'nearest' });
        }
    }

    // Close on outside click
    document.addEventListener('click', closeAll);

    // Close on Escape from anywhere
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll();
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-group select').forEach(buildCustomSelect);
    });
}());
