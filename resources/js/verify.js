document.addEventListener('DOMContentLoaded', () => {
    const resendLink = document.getElementById('resend-link');
    const resendForm = document.getElementById('resend-form');

    if (resendLink && resendForm) {
        resendLink.addEventListener('click', (e) => {
            e.preventDefault();
            resendForm.submit();
        });
    }
});