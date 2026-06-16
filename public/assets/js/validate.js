function isBlank(field) {
    if (field.type === 'file') {
        return !field.files || field.files.length === 0;
    }

    return !field.value || !field.value.trim();
}

function getValidationMessage(field) {
    if (field.required && isBlank(field)) {
        return 'Please fill out this field.';
    }

    if (field.validity.valueMissing) {
        return 'Please fill out this field.';
    }

    if (field.validity.typeMismatch && field.type === 'email') {
        return 'Please enter a valid email address.';
    }

    if (field.validity.tooShort) {
        return 'Please use at least ' + field.minLength + ' characters.';
    }

    if (field.validity.patternMismatch) {
        if (field.name === 'phone') {
            return 'Please enter a valid Malaysian phone number (e.g., 012-3456789).';
        }
        return 'Please match the requested format.';
    }

    return field.validationMessage || 'Please review this field.';
}

function clearFieldError(field) {
    var group = field.closest('.form-group');
    if (group) {
        group.classList.remove('is-invalid');
        var msg = group.querySelector('.field-error');
        if (msg) { msg.remove(); }
        return;
    }
    var row = field.closest('.edit-field-row');
    if (row) {
        row.classList.remove('is-invalid');
        var rowMsg = row.querySelector('.field-error');
        if (rowMsg) { rowMsg.remove(); }
    }
}

function showFieldError(field, message) {
    clearFieldError(field);

    var group = field.closest('.form-group');
    if (group) {
        group.classList.add('is-invalid');
        var bubble = document.createElement('div');
        bubble.className = 'field-error';
        bubble.setAttribute('role', 'alert');
        bubble.innerHTML =
            '<span class="field-error__icon" aria-hidden="true">!</span>' +
            '<span class="field-error__text"></span>';
        bubble.querySelector('.field-error__text').textContent = message;
        group.appendChild(bubble);
        return;
    }

    var row = field.closest('.edit-field-row');
    if (row) {
        row.classList.add('is-invalid');
        var rowBubble = document.createElement('div');
        rowBubble.className = 'field-error field-error--inline';
        rowBubble.setAttribute('role', 'alert');
        rowBubble.innerHTML =
            '<span class="field-error__icon" aria-hidden="true">!</span>' +
            '<span class="field-error__text"></span>';
        rowBubble.querySelector('.field-error__text').textContent = message;
        row.appendChild(rowBubble);
    }
}

function validateField(field) {
    var message = '';

    if (field.required && isBlank(field)) {
        message = getValidationMessage(field);
    } else if (!field.checkValidity()) {
        message = getValidationMessage(field);
    }

    if (message) {
        showFieldError(field, message);
        return false;
    }

    clearFieldError(field);
    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('[data-validate]');
    for (var i = 0; i < forms.length; i += 1) {
        forms[i].setAttribute('novalidate', 'novalidate');
    }
});

function inValidateForm(field) {
    var form = field.form || field.closest('form');
    return !!(form && form.hasAttribute('data-validate'));
}

document.addEventListener(
    'invalid',
    function (event) {
        var field = event.target;
        if (!inValidateForm(field)) {
            return;
        }
        event.preventDefault();
        validateField(field);
    },
    true
);

document.addEventListener('input', function (event) {
    var field = event.target;
    if (!inValidateForm(field)) {
        return;
    }
    if (field.closest('.form-group.is-invalid') || field.closest('.edit-field-row.is-invalid')) {
        validateField(field);
    }
});

document.addEventListener('change', function (event) {
    var field = event.target;
    if (!inValidateForm(field)) {
        return;
    }
    if (field.matches('select, input[type="file"], input[type="email"]') ||
        field.closest('.form-group.is-invalid') ||
        field.closest('.edit-field-row.is-invalid')) {
        validateField(field);
    }
});

document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form.hasAttribute || !form.hasAttribute('data-validate')) {
        return;
    }

    // Collect fields inside the form AND fields associated via form="id"
    var fields = Array.from(form.querySelectorAll('input, select, textarea'));
    if (form.id) {
        var associated = Array.from(document.querySelectorAll(
            'input[form="' + form.id + '"], select[form="' + form.id + '"], textarea[form="' + form.id + '"]'
        ));
        associated.forEach(function (el) {
            if (fields.indexOf(el) === -1) { fields.push(el); }
        });
    }

    var i;
    var firstInvalid = null;
    for (i = 0; i < fields.length; i += 1) {
        if (fields[i].disabled) {
            continue;
        }
        clearFieldError(fields[i]);
        if (!validateField(fields[i]) && !firstInvalid) {
            firstInvalid = fields[i];
        }
    }

    if (firstInvalid) {
        event.preventDefault();
        firstInvalid.focus();
    }
});

// --- IC / Passport mutual exclusivity ---

function lockField(field) {
    var group = field.closest('.form-group');
    if (group) {
        group.classList.add('is-locked');
    }
    field.disabled = true;
    field.value = '';
    clearFieldError(field);
}

function unlockField(field) {
    var group = field.closest('.form-group');
    if (group) {
        group.classList.remove('is-locked');
    }
    field.disabled = false;
}

function syncIdMutex(icField, passportField) {
    var icFilled       = icField.value.trim() !== '';
    var passportFilled = passportField.value.trim() !== '';

    if (icFilled) {
        unlockField(icField);
        lockField(passportField);
    } else if (passportFilled) {
        lockField(icField);
        unlockField(passportField);
    } else {
        unlockField(icField);
        unlockField(passportField);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('[data-validate]');
    for (var f = 0; f < forms.length; f += 1) {
        (function (form) {
            var icField       = form.querySelector('#ic_number');
            var passportField = form.querySelector('#passport_number');

            if (!icField || !passportField) {
                return;
            }

            icField.addEventListener('input', function () {
                syncIdMutex(icField, passportField);
            });

            passportField.addEventListener('input', function () {
                syncIdMutex(icField, passportField);
            });

            syncIdMutex(icField, passportField);
        }(forms[f]));
    }
});
