// Règlement du jeu dans une modale (<dialog>) au lieu de la page /reglement.
// Le contenu vient de la page légale elle-même (source unique) : chargé une fois, au premier affichage.
// Sans JS ou sans la modale dans la page (pages légales), les liens [data-rules-modal] restent des liens normaux.

export function initRulesModal() {
    const links = document.querySelectorAll('a[data-rules-modal]');
    const dialog = document.querySelector('[data-rules-dialog]');
    if (!dialog || typeof dialog.showModal !== 'function') {
        links.forEach((a) => a.removeAttribute('aria-haspopup'));
        return;
    }

    const body = dialog.querySelector('[data-rules-body]');
    let loaded = null;

    // Contenu de l'article de /reglement, sans son titre (déjà dans la modale) ni ses liens de navigation
    const load = (url) => {
        loaded ??= fetch(url, { headers: { Accept: 'text/html' } })
            .then((r) => (r.ok ? r.text() : Promise.reject(new Error(r.status))))
            .then((html) => {
                const article = new DOMParser().parseFromString(html, 'text/html').querySelector('article');
                if (!article) throw new Error('article introuvable');
                article.querySelectorAll(':scope > h1, :scope > nav').forEach((el) => el.remove());
                body.replaceChildren(...[...article.children].map((el) => document.importNode(el, true)));
            })
            .catch(() => {
                loaded = null; // nouvel essai à la prochaine ouverture
                body.innerHTML = '';
                const p = document.createElement('p');
                p.append('Le règlement n’a pas pu être chargé. ');
                const a = document.createElement('a');
                a.href = url;
                a.textContent = 'Ouvrir la page du règlement';
                p.append(a);
                body.append(p);
            });
        return loaded;
    };

    const open = (url) => {
        load(url);
        dialog.showModal();
        document.documentElement.style.overflow = 'hidden'; // la page ne défile pas derrière la modale
        body.scrollTop = 0;
        body.focus({ preventScroll: true });
    };

    links.forEach((a) =>
        a.addEventListener('click', (e) => {
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) return; // ouvrir dans un onglet reste possible
            e.preventDefault();
            open(a.href);
        }),
    );

    dialog.querySelector('[data-rules-close]').addEventListener('click', () => dialog.close());
    // clic sur le fond (hors du contenu) : fermeture
    dialog.addEventListener('click', (e) => e.target === dialog && dialog.close());
    // Échap (natif) ou bouton : la page redevient défilable ; le focus revient au lien d'origine (natif)
    dialog.addEventListener('close', () => (document.documentElement.style.overflow = ''));
}
