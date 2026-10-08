// Маска телефона: +7 (999) 123-45-67
function formatPhone(value) {
    let digits = value.replace(/\D/g, '');

    if (digits === '') {
        return '';
    }

    // 8XXXXXXXXXX и 7XXXXXXXXXX -> +7, любая другая первая цифра — это начало номера без кода страны
    if (digits[0] === '7' || digits[0] === '8') {
        digits = digits.slice(1);
    }

    digits = digits.slice(0, 10);

    let result = '+7';

    if (digits.length > 0) {
        result += ' (' + digits.slice(0, 3);
    }
    if (digits.length >= 3) {
        result += ')';
    }
    if (digits.length > 3) {
        result += ' ' + digits.slice(3, 6);
    }
    if (digits.length > 6) {
        result += '-' + digits.slice(6, 8);
    }
    if (digits.length > 8) {
        result += '-' + digits.slice(8, 10);
    }

    return result;
}

function initPhoneMask() {
    document.querySelectorAll('input[type="tel"]').forEach((input) => {
        input.addEventListener('input', (event) => {
            // При удалении не переформатируем хвост, иначе нельзя стереть скобку или дефис
            if (event.inputType && event.inputType.startsWith('delete')) {
                if (input.value.replace(/\D/g, '').length <= 1) {
                    input.value = '';
                }
                return;
            }

            input.value = formatPhone(input.value);
        });

        input.addEventListener('focus', () => {
            if (input.value === '') {
                input.value = '+7';
            }
        });

        input.addEventListener('blur', () => {
            if (input.value === '+7') {
                input.value = '';
            }
        });

        if (input.value !== '') {
            input.value = formatPhone(input.value);
        }
    });
}

document.addEventListener('DOMContentLoaded', initPhoneMask);
