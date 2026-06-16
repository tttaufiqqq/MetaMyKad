(function () {
    'use strict';

    function formatFileLabel(input) {
        if (!input.files || input.files.length === 0) {
            return 'No file chosen';
        }

        if (input.files.length === 1) {
            return input.files[0].name;
        }

        return input.files.length + ' files selected';
    }

    function enhanceFileInput(input) {
        if (!input || input.dataset.fileEnhanced === 'true' || input.classList.contains('hidden')) {
            return;
        }

        var wrap = document.createElement('div');
        wrap.className = 'cf-wrap';

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'cf-trigger';
        trigger.textContent = 'Select File';

        var name = document.createElement('span');
        name.className = 'cf-name';
        name.textContent = formatFileLabel(input);

        input.dataset.fileEnhanced = 'true';
        input.classList.add('cf-native');

        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(trigger);
        wrap.appendChild(name);
        wrap.appendChild(input);

        if (input.files && input.files.length > 0) {
            wrap.classList.add('has-file');
        }

        trigger.addEventListener('click', function () {
            input.click();
        });

        input.addEventListener('change', function () {
            name.textContent = formatFileLabel(input);
            wrap.classList.toggle('has-file', !!(input.files && input.files.length));
        });

        input.addEventListener('focus', function () {
            wrap.classList.add('is-focus');
        });

        input.addEventListener('blur', function () {
            wrap.classList.remove('is-focus');
        });
    }

    function enhanceUploadBox(input) {
        if (!input.id) { return; }
        var label = document.querySelector('label.upload-box[for="' + input.id + '"]');
        if (!label) { return; }

        var nameEl = document.createElement('span');
        nameEl.className = 'upload-box__filename';
        label.appendChild(nameEl);

        input.addEventListener('change', function () {
            if (input.files && input.files.length > 0) {
                nameEl.textContent = input.files[0].name;
                label.classList.add('is-selected');
            } else {
                nameEl.textContent = '';
                label.classList.remove('is-selected');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var inputs = document.querySelectorAll('input[type="file"]');
        for (var i = 0; i < inputs.length; i += 1) {
            if (inputs[i].classList.contains('hidden')) {
                enhanceUploadBox(inputs[i]);
            } else {
                enhanceFileInput(inputs[i]);
            }
        }
    });
}());
