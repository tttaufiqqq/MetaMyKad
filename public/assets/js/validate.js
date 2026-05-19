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
        return 'Please match the requested format.';
    }

    return field.validationMessage || 'Please review this field.';
}

function clearFieldError(field) {
    var group = field.closest('.form-group');
    if (!group) {
        return;
    }

    group.classList.remove('is-invalid');

    var message = group.querySelector('.field-error');
    if (message) {
        message.remove();
    }
}

function showFieldError(field, message) {
    var group = field.closest('.form-group');
    if (!group) {
        return;
    }

    clearFieldError(field);
    group.classList.add('is-invalid');

    var bubble = document.createElement('div');
    bubble.className = 'field-error';
    bubble.setAttribute('role', 'alert');
    bubble.innerHTML =
        '<span class="field-error__icon" aria-hidden="true">!</span>' +
        '<span class="field-error__text"></span>';
    bubble.querySelector('.field-error__text').textContent = message;
    group.appendChild(bubble);
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

document.addEventListener(
    'invalid',
    function (event) {
        var field = event.target;
        if (!field.closest('[data-validate]')) {
            return;
        }

        event.preventDefault();
        validateField(field);
    },
    true
);

document.addEventListener('input', function (event) {
    var field = event.target;
    if (!field.closest('[data-validate]')) {
        return;
    }

    if (field.closest('.form-group.is-invalid')) {
        validateField(field);
    }
});

document.addEventListener('change', function (event) {
    var field = event.target;
    if (!field.closest('[data-validate]')) {
        return;
    }

    if (field.matches('select, input[type="file"], input[type="email"]') || field.closest('.form-group.is-invalid')) {
        validateField(field);
    }
});

document.addEventListener('submit', function (event) {
    var form = event.target.closest('[data-validate]');
    var fields;
    var i;
    var firstInvalid = null;

    if (!form) {
        return;
    }

    fields = form.querySelectorAll('input, select, textarea');
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
