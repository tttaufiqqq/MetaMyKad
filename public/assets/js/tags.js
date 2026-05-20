(function () {
    'use strict';

    var csrfMeta  = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function post(url, data) {
        var params = new URLSearchParams(data);
        params.set('_csrf', csrfToken);
        return fetch(url, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    params.toString(),
        });
    }

    function escHtml(str) {
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function escAttr(str) {
        return escHtml(str).replace(/"/g, '&quot;');
    }

    // ── Remove tag ──────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.tag-remove-btn');
        if (!btn) return;

        var pill    = btn.closest('.tag-pill');
        var manager = btn.closest('.fc-tag-manager');
        if (!pill || !manager) return;

        var fileId    = manager.dataset.fileId;
        var tagName   = btn.dataset.tag;
        var removeUrl = manager.dataset.removeUrl;

        btn.disabled = true;

        post(removeUrl, { file_id: fileId, tag_name: tagName })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.ok) {
                    pill.remove();
                } else {
                    btn.disabled = false;
                    alert(data.error || 'Could not remove tag.');
                }
            })
            .catch(function () {
                btn.disabled = false;
                alert('Request failed. Please try again.');
            });
    });

    // ── Add tag (button click) ──────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.tag-add-btn');
        if (!btn) return;
        var manager = btn.closest('.fc-tag-manager');
        if (manager) submitAdd(manager);
    });

    // ── Add tag (Enter key) ─────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        var input = e.target;
        if (!input || !input.classList.contains('tag-add-input')) return;
        e.preventDefault();
        var manager = input.closest('.fc-tag-manager');
        if (manager) submitAdd(manager);
    });

    function submitAdd(manager) {
        var input   = manager.querySelector('.tag-add-input');
        var addUrl  = manager.dataset.addUrl;
        var fileId  = manager.dataset.fileId;
        var tagName = input.value.trim().toLowerCase();

        if (!tagName) return;

        var addBtn = manager.querySelector('.tag-add-btn');
        if (addBtn) addBtn.disabled = true;
        input.disabled = true;

        post(addUrl, { file_id: fileId, tag_name: tagName })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (addBtn) addBtn.disabled = false;
                input.disabled = false;

                if (data.ok) {
                    var tagsDiv = manager.querySelector('.fc-tags');
                    if (!tagsDiv) {
                        tagsDiv = document.createElement('div');
                        tagsDiv.className = 'fc-tags';
                        var addRow = manager.querySelector('.tag-add-row');
                        manager.insertBefore(tagsDiv, addRow);
                    }

                    var pill = document.createElement('span');
                    pill.className = 'tag-pill';
                    pill.innerHTML = escHtml(data.tag)
                        + ' <button type="button" class="tag-remove-btn"'
                        + ' data-tag="' + escAttr(data.tag) + '"'
                        + ' aria-label="Remove tag ' + escAttr(data.tag) + '">&times;</button>';
                    tagsDiv.appendChild(pill);

                    input.value = '';
                    input.focus();
                } else {
                    alert(data.error || 'Could not add tag.');
                }
            })
            .catch(function () {
                if (addBtn) addBtn.disabled = false;
                input.disabled = false;
                alert('Request failed. Please try again.');
            });
    }
}());
