// Outils communs aux formulaires envoyés en fetch (précommande, newsletter) : messages d'erreur sous les champs
// ([data-field-error="nom"]) et envoi JSON vers Laravel.

/** Affiche les erreurs ({ champ: message }) sous chaque champ ; place le focus sur le premier champ en erreur. */
export function showFieldErrors(form, errors) {
    let first = null;
    form.querySelectorAll('[data-field-error]').forEach((p) => {
        const name = p.dataset.fieldError;
        const message = errors[name] ?? '';
        p.textContent = message;
        form.querySelectorAll(`[name="${name}"]`).forEach((input) => {
            if (message) input.setAttribute('aria-invalid', 'true');
            else input.removeAttribute('aria-invalid');
        });
        if (message && !first) first = form.querySelector(`[name="${name}"]`);
    });
    first?.focus({ preventScroll: true });
    return first;
}

/** Erreurs de validité native ({ champ: { valueMissing: '…', typeMismatch: '…' } }) avant l'envoi. */
export function clientErrors(form, messages) {
    const errors = {};
    Object.entries(messages).forEach(([name, byKey]) => {
        const input = form.elements[name];
        if (input.type !== 'checkbox') input.value = input.value.trim();
        const key = Object.keys(byKey).find((k) => input.validity[k]);
        if (key) errors[name] = byKey[key];
    });
    return errors;
}

/**
 * POST du formulaire en JSON. Renvoie { ok: true, message } ou { ok: false, errors } (422).
 * Lève une erreur si le serveur ne répond pas ou répond autre chose.
 */
export async function postForm(form) {
    const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(form), // contient le jeton CSRF (_token)
    });
    const json = await response.json().catch(() => ({}));

    if (response.status === 422) {
        return { ok: false, errors: Object.fromEntries(Object.entries(json.errors ?? {}).map(([k, v]) => [k, v[0]])) };
    }
    if (!response.ok) throw new Error(response.status);

    return { ok: true, message: json.data?.message ?? '' };
}

/** Message de statut (succès ou erreur) sous le formulaire ; le focus y est placé pour les lecteurs d'écran. */
export function setStatus(el, text, state = 'success') {
    el.textContent = text;
    el.dataset.state = state;
    if (text) el.focus({ preventScroll: true });
}
