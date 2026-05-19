document.addEventListener('submit', function (event) {
    var form = event.target.closest('[data-validate]');
    if (!form) {
        return;
    }

    var required = form.querySelectorAll('[required]');
    for (var i = 0; i < required.length; i += 1) {
        if (!required[i].value.trim()) {
            event.preventDefault();
            required[i].focus();
            alert('Please complete all required fields.');
            return;
        }
    }
});
