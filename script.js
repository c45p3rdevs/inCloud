// script.js
document.querySelectorAll('.btn-warning').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const form = e.target.closest('form');
        form.querySelector('.form-control-file').click();
    });
});
