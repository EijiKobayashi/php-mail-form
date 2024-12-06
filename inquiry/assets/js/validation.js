document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('application');

    form.addEventListener('submit', (event) => {
        let valid = true;
        const requiredFields = ['subject', 'name', 'email', 'content'];
        const emailField = document.getElementById('email');
        const errors = [];

        requiredFields.forEach((fieldId) => {
            const field = document.getElementById(fieldId);
            if (!field || field.value.trim() === '') {
                valid = false;
                errors.push(`${fieldId} は必須です。`);
            }
        });

        // Email validation
        if (emailField) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailField.value)) {
                valid = false;
                errors.push('メールアドレスの形式が正しくありません。');
            }
        }

        if (!valid) {
            event.preventDefault();
            alert(errors.join('\n'));
        }
    });
});
