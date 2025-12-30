
/**
 * 
 * @description
 * Les fonction décrites ici seront utilisés dans les différentes pages.
 * Elles sont chargé dans le fichier de script globale => app.js
 * Puis app.js est chargé dans le layout de la page ou l'on veut utiliser ces scripts.
 */



/**
 * Valide un nom/prénom
 * - Obligatoire
 * - Chaîne de caractères
 * - Longueur max : 255
 */
const nameRegex = /^.{1,255}$/;

window.validateText = function (input) {
    if (!nameRegex.test(input.value.trim())) {
        // Vide → rouge
        input.style.borderLeft = '4px solid #b20101';
    } else {
        // Rempli → orange warning
        input.style.borderLeft = '4px solid #32f50b';
    }
}

/**
 * Valide une adresse email
 * - Obligatoire
 * - Format email valide
 * - Longueur max : 255
 * ⚠️ L'unicité se vérifie côté serveur
 */
const emailRegex = /^[^\s@]{1,64}@[^\s@]{1,189}$/;
window.validateEmail = function (input) {

    if (!emailRegex.test(input.value.trim())) {
        // Vide → rouge
        input.style.borderLeft = '4px solid #b20101';
    } else {
        // Rempli → orange warning
        input.style.borderLeft = '4px solid #32f50b';
    }
};

/**
 * Valide un mot de passe
 * - Obligatoire
 * - Minimum 8 caractères
 * - Chaîne de caractères
 */
const passwordRegex = /^.{8,}$/;
window.validatePassword = function (input) {
    if (!passwordRegex.test(input.value.trim())) {
        // Vide → rouge
        input.style.borderLeft = '4px solid #b20101';
    } else {
        // Rempli → orange warning
        input.style.borderLeft = '4px solid #32f50b';
    }
};
