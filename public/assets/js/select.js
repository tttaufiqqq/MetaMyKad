/**
 * MetaMyKad — Custom Select Dropdown
 * Auto-enhances every .form-group select with a themed panel.
 * The native <select> stays hidden in the DOM so form submission works as-is.
 *
 * Panel is appended to document.body (portal pattern) so it is never clipped
 * by overflow:hidden + backdrop-filter stacking contexts on ancestor containers.
 */
(function () {
    'use strict';

    var panelCounter = 0;

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
        var panelId = 'cs-panel-' + (++panelCounter);
        panel.id = panelId;
        panel.className = 'cs-panel';
        panel.setAttribute('role', 'listbox');
        panel.hidden = true;

        // Link wrap <-> panel via data attribute
        wrap.dataset.panelId = panelId;

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
        select.parentNode.insertBefore(wrap, select);

        // Panel lives on body so overflow:hidden ancestors never clip it
        document.body.appendChild(panel);

        // Hide native select but keep in DOM for form submission
        select.style.cssText = 'display:none;position:absolute;';

        // Open / close
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = !panel.hidden;
            closeAll();
            if (!isOpen) {
                positionPanel(panel, trigger);
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
            pickOption(wrap, select, valueSpan, panel, trigger);
            // Find which option was picked and apply it
            panel.querySelectorAll('.cs-option').forEach(function (o) {
                o.classList.remove('is-selected');
            });
            opt.classList.add('is-selected');
            valueSpan.textContent = opt.textContent;
            select.value = opt.dataset.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            panel.hidden = true;
            trigger.setAttribute('aria-expanded', 'false');
            wrap.classList.remove('is-open');
            trigger.focus();
        });

        // Keyboard: open on Enter/Space/Arrow from trigger
        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                trigger.click();
            }
        });

        // Keyboard: navigate options inside panel
        panel.addEventListener('keydown', function (e) {
            var focused = panel.querySelector('.cs-option:focus');
            var allOpts = Array.from(panel.querySelectorAll('.cs-option'));
            var idx = focused ? allOpts.indexOf(focused) : -1;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                var next = allOpts[idx + 1] || allOpts[0];
                if (next) { next.focus(); next.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                var prev = allOpts[idx - 1] || allOpts[allOpts.length - 1];
                if (prev) { prev.focus(); prev.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (focused) focused.click();
            } else if (e.key === 'Escape') {
                closeAll();
                trigger.focus();
            }
        });
    }

    function positionPanel(panel, trigger) {
        var rect = trigger.getBoundingClientRect();
        var gap  = 12;
        panel.style.position   = 'fixed';
        panel.style.top        = (rect.bottom + gap) + 'px';
        panel.style.left       = (rect.left - 16) + 'px';
        panel.style.width      = (rect.width + 32) + 'px';
        panel.style.right      = 'auto';
        panel.style.zIndex     = '9999';
    }

    // pickOption is now handled inline in the click listener above;
    // kept as no-op stub so keyboard Enter path (focused.click()) reuses the same handler
    function pickOption() {}

    function closeAll() {
        document.querySelectorAll('.cs-wrap.is-open').forEach(function (wrap) {
            var panel = document.getElementById(wrap.dataset.panelId);
            if (panel) panel.hidden = true;
            wrap.querySelector('.cs-trigger').setAttribute('aria-expanded', 'false');
            wrap.classList.remove('is-open');
        });
    }

    function scrollSelectedIntoView(panel) {
        var selected = panel.querySelector('.cs-option.is-selected');
        if (selected) selected.scrollIntoView({ block: 'nearest' });
    }

    // Close on outside click (panel is on body, so stopPropagation in trigger still works)
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.cs-wrap') && !e.target.closest('.cs-panel')) {
            closeAll();
        }
    });

    // Close on Escape from anywhere
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll();
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-group select').forEach(buildCustomSelect);
    });
}());
