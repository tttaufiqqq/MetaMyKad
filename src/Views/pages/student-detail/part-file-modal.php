<!-- ── File detail modal ───────────────────────────────── -->
<div id="fc-modal" class="fm-overlay" hidden aria-modal="true" role="dialog" aria-label="File detail">
    <div class="fm">
        <button id="fc-modal-close" class="fm-close" type="button" aria-label="Close">&times;</button>
        <div class="fm-media" id="fm-media"></div>
        <div class="fm-body">
            <div class="fm-header">
                <span class="fm-name" id="fm-name"></span>
                <span class="fc-type-badge" id="fm-badge"></span>
            </div>
            <dl class="fc-meta fm-meta" id="fm-meta"></dl>
            <div class="fm-tags fc-tags" id="fm-tags"></div>
        </div>
    </div>
</div>

<script>
(function () {
    var overlay = document.getElementById('fc-modal');
    var mediaEl = document.getElementById('fm-media');
    var nameEl  = document.getElementById('fm-name');
    var badgeEl = document.getElementById('fm-badge');
    var metaEl  = document.getElementById('fm-meta');
    var tagsEl  = document.getElementById('fm-tags');

    function esc(str) {
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function addRow(label, valueHtml) {
        var div = document.createElement('div');
        div.className = 'fc-meta__row';
        div.innerHTML = '<dt>' + esc(label) + '</dt><dd>' + valueHtml + '</dd>';
        metaEl.appendChild(div);
    }

    function openModal(card) {
        var raw = card.getAttribute('data-fc');
        if (!raw) return;
        var f;
        try { f = JSON.parse(raw); } catch (e) { return; }

        mediaEl.innerHTML = '';
        if (f.type === 'photo') {
            var img = document.createElement('img');
            img.src = f.url; img.alt = f.filename;
            mediaEl.appendChild(img);
        } else if (f.type === 'audio') {
            var audio = document.createElement('audio');
            audio.controls = true; audio.src = f.url;
            mediaEl.appendChild(audio);
        } else if (f.type === 'video') {
            var video = document.createElement('video');
            video.controls = true; video.src = f.url;
            mediaEl.appendChild(video);
        } else if (f.type === 'pdf') {
            var iframe = document.createElement('iframe');
            iframe.src = f.url; iframe.title = f.filename;
            mediaEl.appendChild(iframe);
        }

        nameEl.textContent  = f.filename;
        badgeEl.textContent = f.type.toUpperCase();

        metaEl.innerHTML = '';
        addRow('SIZE', esc(f.size));
        addRow('UPLOADED', esc(f.uploaded));
        if (f.original_date) addRow('CREATED', esc(f.original_date));
        if (f.mime) addRow('MIME', esc(f.mime));

        var c = f.cbr || {};
        if (f.type === 'audio') {
            if (c.duration)    addRow('DURATION', esc(c.duration) + (c.duration_tier ? ' <span class="fc-tier">' + esc(c.duration_tier) + '</span>' : ''));
            if (c.bitrate)     addRow('BITRATE', esc(String(c.bitrate)) + ' kbps');
            if (c.sample_rate) addRow('SAMPLE RATE', esc(String(c.sample_rate)) + ' Hz');
            if (c.channels)    addRow('CHANNELS', esc(String(c.channels)));
        } else if (f.type === 'video') {
            if (c.resolution) addRow('RESOLUTION', esc(c.resolution) + (c.resolution_tier ? ' <span class="fc-tier">' + esc(c.resolution_tier) + '</span>' : ''));
            if (c.duration)   addRow('DURATION', esc(c.duration));
            if (c.framerate)  addRow('FRAME RATE', esc(c.framerate));
            if (c.codec)      addRow('CODEC', esc(c.codec));
        } else if (f.type === 'photo') {
            if (c.category)   addRow('CATEGORY', esc(c.category));
            if (c.expression) addRow('EXPRESSION', esc(c.expression) + (c.expression_confidence ? ' <span class="fc-tier">' + c.expression_confidence + '%</span>' : ''));
            if (c.bg_color)   addRow('BG COLOR', '<span style="display:inline-flex;align-items:center;gap:0.35rem;"><span style="width:10px;height:10px;border-radius:50%;background:' + esc(c.bg_color) + ';display:inline-block;flex-shrink:0;"></span>' + esc(c.bg_color) + '</span>');
            if (c.width && c.height) addRow('DIMENSIONS', esc(c.width) + ' × ' + esc(c.height) + ' px');
        } else if (f.type === 'pdf' && f.text) {
            addRow('EXTRACTED TEXT', '<span style="white-space:pre-wrap;word-break:break-word;">' + esc(f.text.slice(0, 400)) + (f.text.length > 400 ? '…' : '') + '</span>');
        }

        tagsEl.innerHTML = '';
        (f.tags || []).forEach(function (tag) {
            var span = document.createElement('span');
            span.className = 'tag-pill';
            span.textContent = tag;
            tagsEl.appendChild(span);
        });

        overlay.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.hidden = true;
        document.body.style.overflow = '';
        mediaEl.innerHTML = '';
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.fc-actions') && !e.target.closest('.fc-tag-manager')) {
            var card = e.target.closest('.fc[data-fc]');
            if (card) openModal(card);
        }
    });

    document.getElementById('fc-modal-close').addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !overlay.hidden) closeModal();
    });
}());
</script>
