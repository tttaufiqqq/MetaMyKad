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
