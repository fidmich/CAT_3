document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('obituary-form');
    if (!form) return;

    var fields = {
        name: { el: document.getElementById('name'), label: 'Name' },
        date_of_birth: { el: document.getElementById('date_of_birth'), label: 'Date of Birth' },
        date_of_death: { el: document.getElementById('date_of_death'), label: 'Date of Death' },
        content: { el: document.getElementById('content'), label: 'Content' },
        author: { el: document.getElementById('author'), label: 'Author' }
    };

    function showError(field, message) {
        field.el.classList.add('invalid');
        var errorEl = document.getElementById(field.el.id + '-error');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.add('visible');
        }
    }

    function clearError(field) {
        field.el.classList.remove('invalid');
        var errorEl = document.getElementById(field.el.id + '-error');
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }
    }

    // Auto-formats a DD/MM/YYYY date field as the user types. Slashes are inserted
    // automatically, but the user can also type "/" themselves - it's simply ignored
    // and re-inserted in the right place. Cursor position is preserved by digit count
    // so editing/erasing in the middle of the date works instead of jumping to the end.
    function attachDateMask(field) {
        field.el.addEventListener('input', function () {
            var input = field.el;
            var cursorPos = input.selectionStart;
            var digitsBeforeCursor = input.value.slice(0, cursorPos).replace(/\D/g, '').length;

            var digits = input.value.replace(/\D/g, '').slice(0, 8);
            var formatted = digits;
            if (digits.length > 4) {
                formatted = digits.slice(0, 2) + '/' + digits.slice(2, 4) + '/' + digits.slice(4);
            } else if (digits.length > 2) {
                formatted = digits.slice(0, 2) + '/' + digits.slice(2);
            }
            input.value = formatted;

            var newPos = 0;
            var seenDigits = 0;
            while (newPos < formatted.length && seenDigits < digitsBeforeCursor) {
                if (/\d/.test(formatted[newPos])) seenDigits++;
                newPos++;
            }
            if (formatted[newPos] === '/') {
                newPos++;
            }
            input.setSelectionRange(newPos, newPos);
        });
    }

    attachDateMask(fields.date_of_birth);
    attachDateMask(fields.date_of_death);

    // Parses a DD/MM/YYYY string into a Date, returning null if it isn't a real calendar date.
    function parseDMY(value) {
        var match = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(value.trim());
        if (!match) return null;

        var day = parseInt(match[1], 10);
        var month = parseInt(match[2], 10);
        var year = parseInt(match[3], 10);
        var date = new Date(year, month - 1, day);

        if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
            return null;
        }
        return date;
    }

    function validate() {
        var valid = true;
        Object.keys(fields).forEach(function (key) {
            clearError(fields[key]);
        });

        if (!fields.name.el.value.trim()) {
            showError(fields.name, 'Name is required.');
            valid = false;
        } else if (fields.name.el.value.trim().length > 100) {
            showError(fields.name, 'Name must be 100 characters or fewer.');
            valid = false;
        }

        if (!fields.author.el.value.trim()) {
            showError(fields.author, 'Author is required.');
            valid = false;
        } else if (fields.author.el.value.trim().length > 100) {
            showError(fields.author, 'Author must be 100 characters or fewer.');
            valid = false;
        }

        var dob = null;
        var dod = null;

        if (!fields.date_of_birth.el.value.trim()) {
            showError(fields.date_of_birth, 'Date of birth is required.');
            valid = false;
        } else {
            dob = parseDMY(fields.date_of_birth.el.value);
            if (!dob) {
                showError(fields.date_of_birth, 'Enter a valid date as DD/MM/YYYY.');
                valid = false;
            }
        }

        if (!fields.date_of_death.el.value.trim()) {
            showError(fields.date_of_death, 'Date of death is required.');
            valid = false;
        } else {
            dod = parseDMY(fields.date_of_death.el.value);
            if (!dod) {
                showError(fields.date_of_death, 'Enter a valid date as DD/MM/YYYY.');
                valid = false;
            }
        }

        if (dob && dod) {
            var today = new Date();

            if (dod < dob) {
                showError(fields.date_of_death, 'Date of death cannot be before date of birth.');
                valid = false;
            }
            if (dob > today) {
                showError(fields.date_of_birth, 'Date of birth cannot be in the future.');
                valid = false;
            }
            if (dod > today) {
                showError(fields.date_of_death, 'Date of death cannot be in the future.');
                valid = false;
            }
        }

        if (!fields.content.el.value.trim()) {
            showError(fields.content, 'Content is required.');
            valid = false;
        } else if (fields.content.el.value.trim().length < 20) {
            showError(fields.content, 'Please write at least 20 characters.');
            valid = false;
        }

        return valid;
    }

    form.addEventListener('submit', function (event) {
        if (!validate()) {
            event.preventDefault();
        }
    });
});
