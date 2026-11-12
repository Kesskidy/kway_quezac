// API du jeu (regle.md §3 et §4). Toutes les réponses réussies sont enveloppées dans `data`.
// Les erreurs suivent le format Laravel : { message, errors: { champ: [messages] } }.

const FALLBACK_MESSAGES = {
    429: 'Trop d’essais. Patiente une minute avant de réessayer.',
    500: 'Une erreur est survenue. Réessaie dans un instant.',
};

async function postJson(url, body) {
    let response;
    try {
        response = await fetch(url, {
            method: 'POST',
            headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
    } catch {
        return { ok: false, status: 0, message: 'Connexion impossible. Vérifie ta connexion et réessaie.', errors: {} };
    }

    let json = {};
    try {
        json = await response.json();
    } catch {
        // réponse vide ou non JSON : on garde le message de secours
    }

    if (response.ok) return { ok: true, status: response.status, data: json.data ?? {} };

    return {
        ok: false,
        status: response.status,
        message: json.message || FALLBACK_MESSAGES[response.status] || FALLBACK_MESSAGES[500],
        errors: json.errors ?? {},
    };
}

/** POST /api/game-sessions — consomme le code et renvoie { token, won, scenario } (tirage déjà enregistré). */
export function startGame(apiBase, code) {
    return postJson(`${apiBase}/game-sessions`, { code });
}

/** POST /api/game-sessions/{token}/participation — seulement si `won` vaut true. */
export function sendParticipation(apiBase, token, { firstName, email, consent }) {
    return postJson(`${apiBase}/game-sessions/${encodeURIComponent(token)}/participation`, {
        first_name: firstName,
        email,
        consent,
    });
}
