// Précommande de la veste, sans rechargement de page :
//   1. « S'inscrire à la précommande » → vérification des champs ; les erreurs s'affichent sous les champs
//   2. champs valides → modale de confirmation (taille, prénom, e-mail)
//   3. « Confirmer l'inscription » → POST /orders en JSON ; erreurs serveur (422) affichées sous les champs,
//      succès → message de confirmation à la place, la page ne bouge pas.
// Sans JS ou sans <dialog>, le formulaire s'envoie normalement (retour sur #product).

import { clientErrors, postForm, setStatus, showFieldErrors } from './form-errors';

const MESSAGES = {
    first_name: { valueMissing: 'Indique ton prénom.', tooLong: 'Ton prénom doit faire 50 caractères maximum.' },
    email: {
        valueMissing: 'Indique ton adresse e-mail.',
        typeMismatch: 'Cette adresse e-mail est invalide.',
        tooLong: 'Cette adresse e-mail est trop longue.',
    },
};

export function initOrderModal() {
    const form = document.getElementById('order-form');
    const dialog = document.querySelector('[data-order-dialog]');
    if (!form || !dialog || typeof dialog.showModal !== 'function') return;

    const status = document.querySelector('[data-order-status]');
    const confirmButton = dialog.querySelector('[data-order-confirm-submit]');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        setStatus(status, '');
        // Mêmes règles que StoreOrderRequest, vérifiées avant d'ouvrir la modale
        const errors = clientErrors(form, MESSAGES);
        if (!form.querySelector('[name="size"]:checked')) errors.size = 'Choisis une taille.';
        if (showFieldErrors(form, errors)) return;

        const data = new FormData(form);
        dialog.querySelectorAll('[data-order-confirm]').forEach((el) => {
            el.textContent = data.get(el.dataset.orderConfirm) ?? '';
        });
        dialog.showModal();
        document.documentElement.style.overflow = 'hidden'; // la page ne défile pas derrière la modale
    });

    confirmButton.addEventListener('click', async () => {
        confirmButton.disabled = true;
        try {
            const result = await postForm(form);
            dialog.close();
            if (!result.ok) {
                showFieldErrors(form, result.errors);
                return;
            }
            form.elements.first_name.value = '';
            form.elements.email.value = '';
            showFieldErrors(form, {});
            setStatus(status, result.message || 'Ton inscription à la précommande est enregistrée.');
        } catch {
            dialog.close();
            setStatus(status, 'L’inscription n’a pas pu être envoyée. Vérifie ta connexion et réessaie.', 'error');
        } finally {
            confirmButton.disabled = false;
        }
    });

    dialog.querySelector('[data-order-cancel]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (e) => e.target === dialog && dialog.close());
    dialog.addEventListener('close', () => (document.documentElement.style.overflow = ''));
}
