<?php if ($isOwner): ?>
<script>
(function () {
    var FILE_TYPES = ['photo', 'audio', 'pdf', 'video'];
    var formEl = document.getElementById('student-update-form');

    function setHidden(form, name, value) {
        var h = form.querySelector('[name="' + name + '"]');
        if (!h) {
            h = document.createElement('input');
            h.type = 'hidden';
            h.name = name;
            form.appendChild(h);
        }
        h.value = value;
    }

    document.addEventListener('change', function (e) {
        var input = e.target;
        if (input.type !== 'file') return;
        var type = input.name;
        if (FILE_TYPES.indexOf(type) === -1) return;

        var f = formEl || input.form;
        if (!f) return;

        if (input.files && input.files[0]) {
            var d = new Date(input.files[0].lastModified);
            setHidden(f, 'original_date_' + type,
                d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0'));
        }

        if ((type === 'audio' || type === 'video') && input.files && input.files[0]) {
            var url = URL.createObjectURL(input.files[0]);
            var el = document.createElement(type === 'video' ? 'video' : 'audio');
            el.preload = 'metadata';
            el.onloadedmetadata = function () {
                URL.revokeObjectURL(url);
                setHidden(f, 'duration_sec_' + type, String(Math.round(el.duration)));
            };
            el.src = url;
        }
    });
}());
</script>
<?php endif; ?>
