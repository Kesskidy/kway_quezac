// Formulaires d'inscription simples (newsletter, Expédition RE·SOURCE), sans rechargement de page :
// vérification des champs → POST en JSON → message de confirmation sous le formulaire.
// Sans JS, le formulaire s'envoie normalement (retour sur l'ancre de sa section).

import { clientErrors, postForm, setStatus, showFieldErrors } from './form-errors';

const EMAIL = {
    valueMissing: 'Indique ton adresse e-mail.',
    typeMismatch: 'Cette adresse e-mail est invalide.',
    tooLong: 'Cette adresse e-mail est trop longue.',
};
const FIRST_NAME = { valueMissing: 'Indique ton prénom.', tooLong: 'Ton prénom doit faire 50 caractères maximum.' };

// Mêmes règles que StoreNewsletterSubscriberRequest et StoreExpeditionRegistrationRequest
const FORMS = [
    {
        form: '#newsletter-form',
        status: '[data-newsletter-status]',
        messages: {
            email: EMAIL,
            consent: { valueMissing: 'Coche la case pour accepter de recevoir la newsletter.' },
        },
        success: 'C’est noté ! Tu es inscrit(e) à la newsletter.',
    },
    {
        form: '#expedition-form',
        status: '[data-expedition-status]',
        messages: {
            first_name: FIRST_NAME,
            email: EMAIL,
            consent: { valueMissing: 'Coche la case pour que l’on puisse te contacter au sujet de l’expédition.' },
        },
        success: 'C’est noté ! Tu es inscrit(e) à l’expédition.',
    },
];

export function initSimpleForms() {
    FORMS.forEach(({ form: formSelector, status: statusSelector, messages, success }) => {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const status = document.querySelector(statusSelector);
        const button = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            setStatus(status, '');
            if (showFieldErrors(form, clientErrors(form, messages))) return;

            button.disabled = true;
            try {
                const result = await postForm(form);
                if (!result.ok) {
                    showFieldErrors(form, result.errors);
                    return;
                }
                form.reset();
                showFieldErrors(form, {});
                setStatus(status, result.message || success);
            } catch {
                setStatus(status, 'L’inscription n’a pas pu être envoyée. Vérifie ta connexion et réessaie.', 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}
