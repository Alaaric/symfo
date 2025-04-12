document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('form button');

    if (button) {
        button.addEventListener('click', () => {
            // Change en rouge
            button.style.backgroundColor = '#dc3545';

            // Reviens à la couleur initiale après 1 seconde
            setTimeout(() => {
                button.style.backgroundColor = ''; // remet la couleur d’origine
            }, 1000);
        });
    }
});
