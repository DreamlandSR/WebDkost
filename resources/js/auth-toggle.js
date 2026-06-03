document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.auth-toggle-password');
    console.log('Toggles found:', toggles.length);

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const inputId = this.dataset.target;
            const input   = document.getElementById(inputId);
            const eyeOn   = document.getElementById('eye-' + inputId);
            const eyeOff  = document.getElementById('eye-off-' + inputId);

            if (!input || !eyeOn || !eyeOff) {
                console.warn('Element tidak ditemukan untuk target:', inputId);
                return;
            }

            const isPassword   = input.type === 'password';
            input.type         = isPassword ? 'text'  : 'password';
            eyeOn.style.display  = isPassword ? 'none'  : 'block';
            eyeOff.style.display = isPassword ? 'block' : 'none';
        });
    });
});
