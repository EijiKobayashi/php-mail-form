document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('application');
  if (!form) {
    return;
  }

  const inputElements = form.querySelectorAll('input, select, textarea');

  form.addEventListener(
    'submit',
    (e) => {
      // If submitted via "back" button, bypass client validation
      if (e.submitter && e.submitter.name === 'back') {
        return;
      }

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

        // Custom validation for required select
        if (element.tagName === 'SELECT' && element.hasAttribute('required') && element.value === '') {
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
      if (!isValid || hasError) {
        e.preventDefault();
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

    if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
      element.addEventListener('input', (e) => {
        const $this = e.currentTarget;
        if ($this.checkValidity()) {
          const div = $this.closest('div.control');
          if (div) {
            div.classList.remove('is-error');
          }
          const errorMessage = $this.nextElementSibling;
          if (errorMessage && errorMessage.classList.contains('error-message')) {
            errorMessage.textContent = '';
          }
        }
      });
    }
  });
});
