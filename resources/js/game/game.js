// Enchaînement du jeu (regle.md §0) : code → animation → résultat → participation (si gagné).
import { sendParticipation, startGame } from './api';
import { playAnimation, preloadAssets } from './game-animation';
import { getEnding } from './game-endings';

export function initGame() {
    const section = document.querySelector('#game');
    const codeForm = section?.querySelector('#game-code-form');
    if (!section || !codeForm) return;

    const apiBase = section.dataset.apiBase;
    const panels = Object.fromEntries([...section.querySelectorAll('[data-game-panel]')].map((p) => [p.dataset.gamePanel, p]));
    const codeInput = codeForm.querySelector('input[name="code"]');
    const codeError = section.querySelector('#game-error');
    const stage = section.querySelector('#game-stage');
    const skipButton = section.querySelector('#game-skip-button');
    const result = section.querySelector('#game-result');
    const resultTitle = section.querySelector('[data-game-result-title]');
    const resultText = section.querySelector('[data-game-result-text]');
    const participationForm = section.querySelector('#game-participation-form');
    const participationStatus = section.querySelector('#game-participation-status');
    const replayButton = section.querySelector('[data-game-replay]');

    let game = null; // { token, won, scenario }
    let animation = null;

    // Les 6 assets SVG de l'animation sont chargés dès que la section du jeu approche de l'écran
    const assets = JSON.parse(stage.dataset.gAssets || '{}');
    new IntersectionObserver((entries, observer) => {
        if (!entries.some((e) => e.isIntersecting)) return;
        observer.disconnect();
        preloadAssets(assets).catch(() => {}); // en cas d'échec, nouvel essai au lancement de l'animation
    }, { rootMargin: '600px 0px' }).observe(section);

    const showPanel = (name) => Object.entries(panels).forEach(([key, panel]) => (panel.hidden = key !== name));
    const setBusy = (form, busy) => {
        form.querySelector('[type="submit"]').disabled = busy;
        form.setAttribute('aria-busy', String(busy));
    };

    // ---------- 1. Le code ----------
    // Arrivée par le QR code de la bouteille (/?code=QZK-XXXX-XXXX#game) : le code est déjà saisi
    const codeFromUrl = new URLSearchParams(window.location.search).get('code');
    if (codeFromUrl) codeInput.value = codeFromUrl.trim().toUpperCase().slice(0, 32);

    const showCodeError = (message) => {
        codeError.textContent = message;
        if (message) codeInput.setAttribute('aria-invalid', 'true');
        else codeInput.removeAttribute('aria-invalid');
    };

    codeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const code = codeInput.value.trim();
        if (!code) {
            showCodeError('Saisis ton code pour jouer.');
            codeInput.focus();
            return;
        }

        showCodeError('');
        setBusy(codeForm, true);
        const response = await startGame(apiBase, code);
        setBusy(codeForm, false);

        if (!response.ok) {
            showCodeError(response.errors.code?.[0] ?? response.message);
            codeInput.focus();
            return;
        }

        game = { token: response.data.token, won: Boolean(response.data.won), scenario: Number(response.data.scenario) || 0 };
        playGame();
    });

    // ---------- 2. L'animation ----------
    const playGame = () => {
        stage.dataset.outcome = game.won ? 'won' : 'lost';
        stage.dataset.scenario = String(game.scenario);
        showPanel('stage');
        skipButton.focus({ preventScroll: true });

        animation = playAnimation(stage, game);
        animation.finished.then(showResult);
    };

    skipButton.addEventListener('click', () => animation?.skip());

    // ---------- 3. Le résultat ----------
    const showResult = () => {
        const ending = getEnding(game.won, game.scenario);
        resultTitle.textContent = ending.title;
        resultText.textContent = ending.text;
        participationForm.hidden = !game.won;
        participationForm.reset();
        clearFieldErrors();
        setStatus('');
        showPanel('result');
        result.focus({ preventScroll: true });
    };

    // ---------- 4. La participation (seulement si gagné) ----------
    const fieldErrors = Object.fromEntries([...participationForm.querySelectorAll('[data-field-error]')].map((el) => [el.dataset.fieldError, el]));
    const clearFieldErrors = () => {
        Object.entries(fieldErrors).forEach(([field, el]) => {
            el.textContent = '';
            participationForm.elements[field]?.removeAttribute('aria-invalid');
        });
    };
    const setStatus = (message, state = 'ok') => {
        participationStatus.textContent = message;
        participationStatus.dataset.state = state;
    };

    participationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFieldErrors();
        setStatus('');

        const fields = participationForm.elements;
        setBusy(participationForm, true);
        const response = await sendParticipation(apiBase, game.token, {
            firstName: fields.first_name.value.trim(),
            email: fields.email.value.trim(),
            consent: fields.consent.checked,
        });
        setBusy(participationForm, false);

        if (response.ok) {
            participationForm.hidden = true;
            setStatus(response.data.message);
            return;
        }

        // 422 : une erreur par champ ; 403 / 409 / autres : message général
        const entries = Object.entries(response.errors).filter(([field]) => fieldErrors[field]);
        if (response.status === 422 && entries.length) {
            entries.forEach(([field, messages]) => {
                fieldErrors[field].textContent = messages[0];
                fields[field]?.setAttribute('aria-invalid', 'true');
            });
            fields[entries[0][0]]?.focus();
            return;
        }
        if (response.status === 409) participationForm.hidden = true; // déjà enregistrée : rien à renvoyer
        setStatus(response.message, 'error');
    });

    // ---------- Rejouer ----------
    replayButton.addEventListener('click', () => {
        game = null;
        codeForm.reset();
        showCodeError('');
        showPanel('code');
        codeInput.focus();
    });
}
