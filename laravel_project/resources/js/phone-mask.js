/**
 * Phone mask functionality for Russian phone numbers
 */

export function initPhoneMask() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');

    phoneInputs.forEach((input) => {
        // Set initial format
        if (!input.value) {
            input.value = "+7 (";
        }

        input.addEventListener("input", function (e) {
            formatPhoneNumber(e.target);
        });

        input.addEventListener("keydown", function (e) {
            handlePhoneKeydown(e);
        });

        input.addEventListener("focus", function (e) {
            if (!e.target.value || e.target.value === "+7 (") {
                e.target.value = "+7 (";
                // Set cursor position after +7 (
                setTimeout(() => {
                    e.target.setSelectionRange(4, 4);
                }, 0);
            }
        });

        input.addEventListener("blur", function (e) {
            if (e.target.value === "+7 (" || e.target.value === "+7") {
                e.target.value = "";
            }
        });
    });
}

function formatPhoneNumber(input) {
    // Remove all non-digit characters except +
    let value = input.value.replace(/[^\d+]/g, "");

    // Ensure it starts with +7
    if (!value.startsWith("+7")) {
        if (value.startsWith("7")) {
            value = "+" + value;
        } else if (value.startsWith("8")) {
            value = "+7" + value.substring(1);
        } else {
            value = "+7" + value.replace(/^\+?/, "");
        }
    }

    // Remove extra digits after +7
    value = "+7" + value.substring(2).replace(/\D/g, "");

    // Limit to 11 digits total (1 for country code + 10 for number)
    const digits = value.substring(2);
    if (digits.length > 10) {
        return;
    }

    // Format the number
    let formatted = "+7";

    if (digits.length > 0) {
        formatted += " (" + digits.substring(0, 3);

        if (digits.length > 3) {
            formatted += ") " + digits.substring(3, 6);

            if (digits.length > 6) {
                formatted += "-" + digits.substring(6, 8);

                if (digits.length > 8) {
                    formatted += "-" + digits.substring(8, 10);
                }
            }
        }
    } else {
        formatted += " (";
    }

    // Store cursor position
    const cursorPosition = input.selectionStart;
    const oldLength = input.value.length;

    // Update value
    input.value = formatted;

    // Restore cursor position
    const newLength = formatted.length;
    const lengthDiff = newLength - oldLength;
    input.setSelectionRange(
        cursorPosition + lengthDiff,
        cursorPosition + lengthDiff
    );
}

function handlePhoneKeydown(e) {
    const input = e.target;
    const cursorPosition = input.selectionStart;

    // Allow: backspace, delete, tab, escape, enter
    if (
        [8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.keyCode === 65 && e.ctrlKey === true) ||
        (e.keyCode === 67 && e.ctrlKey === true) ||
        (e.keyCode === 86 && e.ctrlKey === true) ||
        (e.keyCode === 88 && e.ctrlKey === true) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)
    ) {
        // Handle backspace
        if (e.keyCode === 8) {
            // Don't allow deletion of +7 ( part
            if (cursorPosition <= 4) {
                e.preventDefault();
                return;
            }
        }

        return;
    }

    // Ensure that it is a number and stop the keypress
    if (
        (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
        (e.keyCode < 96 || e.keyCode > 105)
    ) {
        e.preventDefault();
    }
}
