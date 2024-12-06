document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('application');
  const inputElements = form.querySelectorAll('input, select, textarea');

  form.addEventListener(
    'submit',
    (e) => {
      e.preventDefault();
      let hasError = false;

      inputElements.forEach((element) => {
        const div = element.closest('div.control');
        if (div) {
          div.classList.remove('is-error');
        }
        const errorMessage = element.nextElementSibling;
        if (errorMessage && errorMessage.classList.contains('error-message')) {
          errorMessage.textContent = '';
        }

        // Check for select with empty value
        if (element.tagName === 'SELECT' && element.value === '') {
          if (div) {
            div.classList.add('is-error');
          }
          if (errorMessage) {
            errorMessage.textContent = 'この項目は必須です。';
          }
          hasError = true;
        }
      });

      const isValid = form.checkValidity();
      if (isValid && !hasError) {
        alert('submit!');
      }
    },
    { passive: false }
  );

  inputElements.forEach((element) => {
    element.addEventListener('invalid', (e) => {
      const $this = e.currentTarget;
      const div = $this.closest('div.control');
      if (div) {
        div.classList.add('is-error');
      }
      const errorMessage = $this.nextElementSibling;
      if (errorMessage && errorMessage.classList.contains('error-message')) {
        errorMessage.textContent = $this.validationMessage;
      }
    });

    // For select, handle change event to clear errors
    if (element.tagName === 'SELECT') {
      element.addEventListener('change', (e) => {
        const $this = e.currentTarget;
        const div = $this.closest('div.control');
        if (div) {
          div.classList.remove('is-error');
        }
        const errorMessage = $this.nextElementSibling;
        if (errorMessage && errorMessage.classList.contains('error-message')) {
          errorMessage.textContent = '';
        }
      });
    }
  });
});
