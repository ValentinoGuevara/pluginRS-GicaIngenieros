document.addEventListener('DOMContentLoaded', function () {

    const botones = document.querySelectorAll('.gsa-btn');

    botones.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const socialId = this.dataset.id;
            const pagina   = window.location.href;

            const formData = new FormData();
            formData.append('action',    'gsa_registrar_clic');
            formData.append('nonce',     gsaTracker.nonce);
            formData.append('social_id', socialId);
            formData.append('pagina',    pagina);

            fetch(gsaTracker.ajaxurl, {
                method: 'POST',
                body:   formData
            });
        });
    });

});