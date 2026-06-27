/**
 * MetaMyKad — Student Row Preview Modal
 * Clicking any [data-student-row] <tr> opens a brief student preview modal.
 */
(function () {
    'use strict';

    var modal   = document.getElementById('student-modal');
    if (!modal) return;

    var nameEl     = document.getElementById('sm-name');
    var viewLink   = document.getElementById('sm-view-link');
    var fields     = ['ic', 'badge', 'gender', 'state', 'files', 'file-type'];

    // Maps field key → dataset property name (camelCase)
    var datasetKey = {
        'ic'        : 'ic',
        'badge'     : 'badge',
        'gender'    : 'gender',
        'state'     : 'state',
        'files'     : 'files',
        'file-type' : 'fileType',
    };

    function openModal(data) {
        nameEl.textContent = data.name || '—';

        fields.forEach(function (key) {
            var rowEl = document.getElementById('sm-row-' + key);
            var valEl = document.getElementById('sm-' + key);
            var val   = data[key] || '';
            if (rowEl && valEl) {
                valEl.textContent = val;
                rowEl.hidden = !val;
            }
        });

        var href = data.href || '';
        viewLink.href = href || '#';
        viewLink.style.display = href ? '' : 'none';

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        if (modal.contains(document.activeElement)) {
            document.activeElement.blur();
        }
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function (e) {
        // Close via buttons
        if (e.target.closest('#student-modal-close') || e.target.closest('#student-modal-cancel')) {
            closeModal();
            return;
        }

        // Row click — ignore clicks on interactive children (buttons, links)
        var row = e.target.closest('[data-student-row]');
        if (!row) return;
        if (e.target.closest('a, button')) return;

        var ds   = row.dataset;
        var data = { name: ds.name || '', href: ds.href || '' };
        fields.forEach(function (key) {
            data[key] = ds[datasetKey[key]] || '';
        });

        openModal(data);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
}());
