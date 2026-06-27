<script>
(function () {
    ['photo', 'audio', 'pdf', 'video'].forEach(function (type) {
        var input = document.getElementById(type);
        if (!input) return;
        input.addEventListener('change', function () {
            var name = 'original_date_' + type;
            var hidden = input.form.querySelector('[name="' + name + '"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                input.form.appendChild(hidden);
            }
            if (this.files && this.files[0]) {
                var d = new Date(this.files[0].lastModified);
                hidden.value = d.getFullYear() + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    String(d.getDate()).padStart(2, '0');
            } else {
                hidden.value = '';
            }
        });
    });

    ['audio', 'video'].forEach(function (type) {
        var input = document.getElementById(type);
        if (!input) return;
        input.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            var url = URL.createObjectURL(this.files[0]);
            var el = document.createElement(type === 'video' ? 'video' : 'audio');
            el.preload = 'metadata';
            el.onloadedmetadata = function () {
                URL.revokeObjectURL(url);
                var sec = Math.round(el.duration);
                var name = 'duration_sec_' + type;
                var hidden = input.form.querySelector('[name="' + name + '"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = name;
                    input.form.appendChild(hidden);
                }
                hidden.value = sec;
            };
            el.src = url;
        });
    });
}());

(function () {
    var modal = document.getElementById('welcome-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.getElementById('welcome-modal-close').addEventListener('click', function () {
        if (modal.contains(document.activeElement)) {
            document.activeElement.blur();
        }
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    });
}());
</script>
