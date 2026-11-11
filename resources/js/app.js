import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Expose GSAP globally (handy in Blade inline scripts / the console)
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

document.addEventListener('DOMContentLoaded', () => {
    const mm = gsap.matchMedia();
    // Partagé entre la scène accrochée et la section Terre : hauteur des flaques par rapport au bas de la scène
    const shared = { navigating: false, finishSeq: null, replaySeq: null, poolOffset: null, handoff: false, renderTerre: null, stageST: null, detachAt: null, terreST: null, sourceST: null, sourcePinST: null };

    // ============ RETOUR EN HAUT ============
    const toTop = document.querySelector('[data-to-top]');
    if (toTop) {
        const showToTop = () => toTop.toggleAttribute('data-visible', window.scrollY > 10);
        window.addEventListener('scroll', showToTop, { passive: true });
        showToTop();
        toTop.addEventListener('click', () => scrollToSky());
    }
    // « Remonter au ciel » (fin du Recyclage) : même remontée douce que le bouton
    document.querySelectorAll('[data-to-sky]').forEach((link) =>
        link.addEventListener('click', (e) => {
            e.preventDefault();
            scrollToSky();
        }),
    );

    function scrollToSky() {
        shared.navigating = true; // pas de blocage du scroll pendant le trajet
        let finished = false;
        const done = () => {
            if (finished) return;
            finished = true;
            shared.navigating = false;
            window.removeEventListener('scrollend', done);
        };
        window.addEventListener('scrollend', done);
        setTimeout(done, 3000);
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    }

    // ============ COLLECTION : choix de la taille ============
    document.querySelectorAll('[data-size-group]').forEach((group) => {
        const buttons = group.querySelectorAll('button');
        const labels = document.querySelectorAll('[data-size-label]');
        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                buttons.forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
                labels.forEach((l) => (l.textContent = btn.textContent.trim()));
            });
        });
    });

    // ============ COLLECTION : galerie (miniatures → image principale) ============
    document.querySelectorAll('[data-gallery]').forEach((gallery) => {
        const main = gallery.querySelector('[data-gallery-main]');
        const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');
        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                thumbs.forEach((t) => t.setAttribute('aria-pressed', String(t === thumb)));
                if (main.getAttribute('src') === thumb.dataset.src) return;
                // fondu rapide pendant le changement d'image
                main.style.opacity = '0';
                setTimeout(() => {
                    main.src = thumb.dataset.src;
                    main.alt = thumb.dataset.alt;
                    main.style.opacity = '1';
                }, 200);
            });
        });
    });

    // Les animations ne se jouent pas si l'utilisateur a demandé moins de mouvement
    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const stage = document.querySelector('[data-stage]');
        if (!stage) return;
        const q = (s) => stage.querySelector(s);

        // 01 — Ciel
        const text = q('[data-hero-text]');
        const sky = q('[data-sky]');
        const cloud = q('[data-hero-cloud]');
        const cloudSmall = q('[data-hero-cloud-small]');
        // 02 — Pluie (coureur)
        const scene2 = q('[data-scene-runner]');
        const runner = q('[data-runner]');
        const runnerText = q('[data-runner-text]');
        const runnerAside = q('[data-runner-aside]');
        const runnerUi = q('[data-runner-ui]');
        const cam = q('[data-runner-cam]');
        const ground = q('[data-ground]');
        const pools = gsap.utils.toArray(stage.querySelectorAll('[data-ground-pool]')); // les gouttes posées sur le sol
        // La goutte
        const root = q('[data-drop-root]');
        const maskGroup = q('[data-drop-mask]');
        const base = q('[data-drop-base]'); // eau qui s'accumule sous le nuage
        const neck = q('[data-drop-neck]'); // filet qui relie la goutte au nuage
        const drop = q('[data-drop-body]');
        const shine = q('[data-drop-shine]');
        const puddle = q('[data-drop-puddle]');
        const droplets = gsap.utils.toArray(stage.querySelectorAll('[data-drop-droplet]'));
        const falls = gsap.utils.toArray(stage.querySelectorAll('[data-drop-fall]')); // 3 gouttes qui tombent de l'épaule

        // Réglages
        const CLOUD_SCALE = 1.3; // taille finale du nuage
        const R = 13; // rayon de la goutte
        const STRETCH_Y = 64; // longueur de l'étirement avant rupture
        const FOLLOW = 1; // chute « caméra qui suit la goutte » sur fond bleu (s)
        const APPROACH = 0.8; // transition bleu → vert + arrivée sur le coureur (s)
        // Distances de scroll, en hauteurs d'écran
        const HERO = 1.2; // texte qui part + nuage qui se centre
        const STRETCH = 0.6; // étirement de la goutte
        const HOLD = 1.0; // la scène reste accrochée pendant la chute et l'impact
        const DESCENT = 1.0; // la caméra descend vers le sol
        const SEEP = 0.6; // les 3 gouttes s'infiltrent dans le sol
        const CAM_SCALE = 1.25; // léger zoom pendant la descente
        const CAM_LIFT = 0.45; // la scène remonte de 45 % d'écran (= la caméra descend)
        const FALL_DX = [-26, -4, 18]; // glissement de chaque goutte sur l'épaule avant de tomber

        // Positions calculées (recalculées au resize)
        const pos = { followY: 0, impactX: 0, impactY: 0, fallY: 0, groundY: 0 };

        const layout = () => {
            const W = stage.offsetWidth;
            const H = stage.offsetHeight;
            const w = cloud.offsetWidth * CLOUD_SCALE;
            const h = cloud.offsetHeight * CLOUD_SCALE;
            // Accroche : sous le nuage centré, un peu vers la droite (côté coureur)
            const ax = W / 2 + w * 0.15;
            const ay = H / 2 + h * 0.34;
            root.setAttribute('transform', `translate(${ax} ${ay})`);
            // Impact : haut de l'épaule du coureur (position finale de l'image)
            const ix = runner.offsetLeft + runner.offsetWidth * parseFloat(runner.dataset.impactX);
            const iy = runner.offsetTop + runner.offsetHeight * parseFloat(runner.dataset.impactY);
            pos.impactX = ix - ax;
            pos.impactY = iy - ay;
            pos.followY = H * 0.14 - ay; // la goutte se recentre en haut de l'écran pendant la chute
            puddle.setAttribute('cx', pos.impactX);
            puddle.setAttribute('cy', pos.impactY - 2);
            droplets.forEach((d) => {
                d.setAttribute('cx', pos.impactX);
                d.setAttribute('cy', pos.impactY - 4);
            });
            // Les 3 gouttes partent de l'éclaboussure et tombent jusque sous le bas de l'écran
            falls.forEach((f) => {
                f.setAttribute('cx', pos.impactX);
                f.setAttribute('cy', pos.impactY - 2);
            });
            pos.fallY = H + 60 - ay - pos.impactY;
            // Niveau du sol = semelle du coureur une fois la caméra descendue (zoom autour du bas de la scène)
            const soleY = runner.offsetTop + runner.offsetHeight * parseFloat(runner.dataset.shoeY);
            pos.groundY = H + (soleY - H) * CAM_SCALE - H * CAM_LIFT - 6;
            // Les flaques sont là où les gouttes sont tombées
            pools.forEach((p, i) => {
                p.style.left = `${ax + pos.impactX + FALL_DX[i]}px`;
            });
            // Après le décrochage, la section Terre commence juste sous la scène : les tracés partiront de là
            shared.poolOffset = pos.groundY + 14 - H;
        };

        // États de départ
        gsap.set(scene2, { autoAlpha: 0 });
        gsap.set(runnerText, { autoAlpha: 0, x: -60 });
        gsap.set(runnerAside, { autoAlpha: 0, x: 60 });
        gsap.set([drop, shine], { svgOrigin: '0 0' }); // déformations autour du centre de la goutte

        // ============ PARTIE LIÉE AU SCROLL (scène accrochée) ============
        const tl = gsap.timeline({
            defaults: { ease: 'none' },
            scrollTrigger: {
                trigger: stage,
                start: 'top top',
                end: `+=${(HERO + STRETCH + HOLD + DESCENT + SEEP) * 100}%`,
                pin: true,
                scrub: 0.4,
                anticipatePin: 1,
                invalidateOnRefresh: true,
            },
        });

        // 01 — Titre + texte : fade out vers la gauche ; petit nuage : fade out vers la droite
        tl.to(text, { x: -160, opacity: 0, duration: HERO * 0.6 }, 0);
        if (cloudSmall) tl.to(cloudSmall, { x: 200, opacity: 0, duration: HERO * 0.6 }, 0);

        // Nuage principal : grandit et rejoint le centre
        tl.to(
            cloud,
            {
                scale: CLOUD_SCALE,
                x: () => stage.offsetWidth / 2 - (cloud.offsetLeft + cloud.offsetWidth / 2),
                y: () => stage.offsetHeight / 2 - (cloud.offsetTop + cloud.offsetHeight / 2),
                ease: 'power1.inOut',
                duration: HERO,
            },
            0,
        );

        // Phase 1 — étirement : l'eau s'accumule, la goutte s'allonge, le filet s'amincit
        const s1 = STRETCH * 0.35;
        const s2 = STRETCH * 0.65;
        tl.fromTo(base, { attr: { rx: 0, ry: 0 } }, { attr: { rx: 20, ry: 10 }, duration: s1 }, HERO)
            .fromTo(neck, { attr: { rx: 0, ry: 0, cy: 0 } }, { attr: { rx: 10, ry: 4, cy: 4 }, duration: s1 }, HERO)
            .fromTo(drop, { attr: { r: 0 }, y: 0 }, { attr: { r: R }, y: 8, duration: s1 }, HERO)
            .fromTo(shine, { opacity: 0, y: 0 }, { opacity: 0.8, y: 8, duration: s1 }, HERO)
            .to(neck, { attr: { rx: 2.6, ry: STRETCH_Y / 2, cy: STRETCH_Y / 2 }, duration: s2 }, HERO + s1)
            .to([drop, shine], { y: STRETCH_Y, scaleY: 1.25, scaleX: 0.9, ease: 'power1.in', duration: s2 }, HERO + s1)
            // la scène reste accrochée pendant la chute
            .to({}, { duration: HOLD }, HERO + STRETCH);

        // 02 → 03 — la caméra descend vers le sol : la scène remonte et grossit, le sol apparaît sous les pieds
        const Z = HERO + STRETCH + HOLD;
        tl.to(runnerUi, { opacity: 0, x: -30, duration: DESCENT * 0.4 }, Z)
            .to(cam, { scale: CAM_SCALE, y: () => -stage.offsetHeight * CAM_LIFT, transformOrigin: '50% 100%', ease: 'power2.inOut', duration: DESCENT }, Z)
            .fromTo(ground, { y: 0 }, { y: () => pos.groundY - stage.offsetHeight, ease: 'power2.inOut', duration: DESCENT }, Z);

        // les 3 gouttes tombées sur le sol s'infiltrent, l'une après l'autre
        const S = Z + DESCENT;
        tl.to(pools, { scaleX: 0.3, y: 18, opacity: 0, ease: 'power1.in', duration: SEEP * 0.6, stagger: SEEP * 0.2 }, S);

        // la scène se décroche : le sol (bleu nuit) se prolonge dans la section Terre, où les tracés partent des flaques

        // ============ PARTIE EN TEMPS RÉEL : chute → transition → impact ============
        const seq = gsap.timeline({ paused: true });
        const IMPACT = FOLLOW + APPROACH;

        seq
            // rupture du filet, le reste de l'eau remonte dans le nuage
            // (opacité plutôt que taille : la partie liée au scroll anime la taille, on évite le conflit)
            .set(neck, { opacity: 0 }, 0)
            .to(base, { opacity: 0, duration: 0.15 }, 0)
            .set(maskGroup, { attr: { mask: 'none' } }, 0.15)
            // Phase 2 — chute libre : la caméra suit la goutte, le nuage part par le haut en accélérant (gravité)
            .to(sky, { y: () => -stage.offsetHeight * 2.2, duration: FOLLOW, ease: 'power1.in' }, 0)
            .to([drop, shine], { y: () => pos.followY, x: () => pos.impactX * 0.55, scaleY: 1.35, scaleX: 0.85, duration: FOLLOW, ease: 'sine.inOut' }, 0)
            // Transition douce : fond bleu → fond vert, le coureur entre dans le cadre
            .to(scene2, { autoAlpha: 1, duration: 0.7, ease: 'power1.inOut' }, FOLLOW - 0.1)
            .fromTo(runner, { y: () => stage.offsetHeight * 0.35 }, { y: 0, duration: APPROACH, ease: 'power2.out', immediateRender: false }, FOLLOW)
            .to([drop, shine], { y: () => pos.impactY - R * 1.3, x: () => pos.impactX, duration: APPROACH, ease: 'power2.in' }, FOLLOW)
            // Phase 3 — écrasement sur l'épaule
            .to(shine, { opacity: 0, duration: 0.05 }, IMPACT)
            .to(drop, { y: () => pos.impactY - R * 0.4, scaleY: 0.35, scaleX: 1.9, duration: 0.1, ease: 'power2.out' }, IMPACT)
            .to(drop, { scaleX: 0, scaleY: 0, duration: 0.25, ease: 'power2.in' }, IMPACT + 0.1)
            // (visibility : jamais animée par la partie liée au scroll → la goutte ne peut pas réapparaître figée)
            .set([drop, shine], { visibility: 'hidden' }, IMPACT + 0.36)
            .fromTo(puddle, { attr: { rx: 0, ry: 0 }, opacity: 1 }, { attr: { rx: 28, ry: 5 }, duration: 0.2, ease: 'power3.out', immediateRender: false }, IMPACT)
            .to(puddle, { attr: { rx: 14, ry: 2 }, opacity: 0, duration: 0.6, ease: 'power1.in' }, IMPACT + 0.3)
            // le texte arrive depuis la gauche, l'encart « Déperlant » depuis la droite
            .to(runnerText, { autoAlpha: 1, x: 0, duration: 0.7, ease: 'power2.out' }, IMPACT + 0.3)
            .to(runnerAside, { autoAlpha: 1, x: 0, duration: 0.7, ease: 'power2.out' }, IMPACT + 0.45);

        // couronne de gouttelettes projetées en arc (rayon ≥ 6 px : en dessous, le filtre gooey efface la forme)
        droplets.forEach((d, i) => {
            const side = i % 2 ? 1 : -1;
            const k = Math.floor(i / 2); // 0, 1, 2 : de plus en plus loin, de moins en moins haut
            const at = IMPACT + 0.02;
            seq.fromTo(d, { attr: { r: 0 }, x: 0, y: 0 }, { attr: { r: 8 - k }, duration: 0.06, immediateRender: false }, at)
                .to(d, { x: side * (16 + k * 16), duration: 0.55, ease: 'power1.out' }, at)
                .to(d, { keyframes: [{ y: -(46 - k * 10), duration: 0.24, ease: 'power2.out' }, { y: 10, duration: 0.3, ease: 'power2.in' }] }, at)
                .to(d, { attr: { r: 0 }, duration: 0.15 }, at + 0.45);
        });

        // 3 gouttes naissent de l'éclaboussure, glissent sur l'épaule puis tombent jusqu'au sol (hors cadre)
        falls.forEach((f, i) => {
            const at = IMPACT + 0.12 + i * 0.12;
            seq.fromTo(f, { attr: { r: 0 }, x: 0, y: 0 }, { attr: { r: 7 }, duration: 0.1, immediateRender: false }, at)
                .to(f, { x: FALL_DX[i], y: 10, duration: 0.2, ease: 'sine.out' }, at + 0.05)
                .to(f, { y: () => pos.fallY, duration: 0.6, ease: 'power2.in' }, at + 0.25)
                .set(f, { attr: { r: 0 } }, at + 0.85); // arrivées au sol (hors cadre) : on les retire, elles ne doivent jamais rester visibles
        });

        // La séquence et la partie liée au scroll animent les mêmes éléments (goutte, reflet).
        // Quand la séquence a fini de se rejouer à l'envers, la partie liée au scroll reprend la main
        // (sinon le reflet de la goutte resterait affiché sous le nuage).
        const resyncScroll = () => {
            const p = tl.progress();
            // aller-retour forcé : si la timeline est déjà à sa position, GSAP ne redessine rien
            tl.progress(p < 0.5 ? 1 : 0).progress(p);
        };
        seq.eventCallback('onReverseComplete', resyncScroll);

        // Détachement de la goutte → la séquence se joue ; on remonte → elle se rejoue à l'envers (plus vite)
        ScrollTrigger.create({
            start: () => tl.scrollTrigger.start + window.innerHeight * (HERO + STRETCH),
            onEnter: () => seq.timeScale(1).play(),
            onLeaveBack: () => seq.timeScale(2.5).reverse(),
        });

        // Tant que la goutte n'a pas éclaté sur le coureur, on reste cadré sur lui : arrivé au bout de la phase accrochée
        // (juste avant la descente vers le sol), le scroll vers le bas est ignoré — sans à-coup ni accélération de la chute.
        // Le scroll vers le haut reste libre.
        const SETTLED = IMPACT + 0.5; // impact + éclaboussure (secondes de séquence)
        const holdLimit = () => tl.scrollTrigger.start + window.innerHeight * (HERO + STRETCH + HOLD) - 2;
        const needsHold = () => !shared.navigating && !seq.reversed() && seq.progress() > 0 && seq.time() < SETTLED;

        let touchY = 0;
        const blockWheel = (e) => e.deltaY > 0 && e.preventDefault();
        const onTouchStart = (e) => (touchY = e.touches[0].clientY);
        const blockTouch = (e) => e.touches[0].clientY < touchY && e.preventDefault();
        const blockKeys = (e) => ['ArrowDown', 'PageDown', 'End', ' '].includes(e.key) && e.preventDefault();
        let locked = false;
        const lock = () => {
            if (locked) return;
            locked = true;
            window.addEventListener('wheel', blockWheel, { passive: false });
            window.addEventListener('touchstart', onTouchStart, { passive: true });
            window.addEventListener('touchmove', blockTouch, { passive: false });
            window.addEventListener('keydown', blockKeys);
        };
        const unlock = () => {
            if (!locked) return;
            locked = false;
            window.removeEventListener('wheel', blockWheel);
            window.removeEventListener('touchstart', onTouchStart);
            window.removeEventListener('touchmove', blockTouch);
            window.removeEventListener('keydown', blockKeys);
        };
        const onScrollHold = () => {
            if (!needsHold()) return unlock();
            const limit = holdLimit();
            const y = window.scrollY;
            // Saut lointain (lien du menu, barre de défilement tirée…) : on ne ramène pas la page en arrière,
            // la chute est terminée d'office
            if (y > limit + window.innerHeight * 0.5) {
                unlock();
                seq.timeScale(1).progress(1);
                return;
            }
            if (y >= limit - 40) lock();
            if (y > limit) window.scrollTo(0, limit); // petit dépassement d'un scroll normal : retenu
        };
        window.addEventListener('scroll', onScrollHold, { passive: true });
        seq.eventCallback('onUpdate', () => locked && !needsHold() && unlock()); // libéré dès l'éclaboussure passée

        // Navigation depuis la barre de progression : on termine la séquence pour ne pas la rejouer en accéléré
        shared.finishSeq = () => {
            unlock();
            seq.timeScale(1).progress(1);
        };
        shared.replaySeq = () => {
            if (seq.progress() === 1) seq.timeScale(1).progress(0).play();
        };

        shared.stageST = tl.scrollTrigger;
        shared.detachAt = () => tl.scrollTrigger.start + window.innerHeight * (HERO + STRETCH);

        const onRefresh = () => {
            layout();
            // Si la séquence a déjà été jouée avec d'anciennes mesures (images pas encore chargées, fenêtre redimensionnée…),
            // on la rejoue depuis le début avec les nouvelles positions, jusqu'à l'endroit où elle en était
            const progress = seq.progress();
            seq.progress(0).invalidate().progress(progress);
            if (progress === 0) resyncScroll();
        };
        ScrollTrigger.addEventListener('refresh', onRefresh);
        layout();

        return () => {
            ScrollTrigger.removeEventListener('refresh', onRefresh);
            window.removeEventListener('scroll', onScrollHold);
            unlock();
        };
    });

    // ============ 03 — TERRE : 3 tracés d'eau qui zigzaguent dans la roche et se rejoignent ============
    const terre = document.querySelector('[data-terre]');
    if (!terre) return;

    const paths = gsap.utils.toArray(terre.querySelectorAll('[data-terre-path]'));
    const heads = gsap.utils.toArray(terre.querySelectorAll('[data-terre-head]'));
    const endDot = terre.querySelector('[data-terre-end]');
    const cards = gsap.utils.toArray(terre.querySelectorAll('[data-terre-card]')); // goutte i → couche i
    const terreData = { lengths: [], cardAt: [] };

    // Aléatoire « fixe » : chaque tracé garde la même forme à chaque chargement
    const seeded = (seed) => () => {
        seed = (seed + 0x6d2b79f5) | 0;
        let t = Math.imul(seed ^ (seed >>> 15), 1 | seed);
        t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };

    // Zigzag entre deux points : un crochet tous les ~80 px, d'amplitude variable
    const zigzag = (rand, from, to, side) => {
        const pts = [];
        const n = Math.max(2, Math.round(Math.hypot(to.x - from.x, to.y - from.y) / 80));
        for (let k = 1; k < n; k++) {
            const t = k / n;
            side = -side;
            pts.push({
                x: from.x + (to.x - from.x) * t + side * (18 + rand() * 42),
                y: from.y + (to.y - from.y) * t + (rand() - 0.5) * 24,
            });
        }
        pts.push(to);
        return { pts, side };
    };

    const buildTerre = () => {
        const box = terre.getBoundingClientRect();
        const W = box.width;
        const end = { x: W / 2, y: box.height - 80 }; // point commun, en bas de la section
        // Départs : là où les 3 gouttes se sont infiltrées dans le sol (scène précédente)
        const pools = gsap.utils.toArray(document.querySelectorAll('[data-ground-pool]'));
        // Les tracés descendent d'abord sous le titre de la section pour ne pas le traverser
        const intro = terre.querySelector('[data-terre-intro]');
        const introBottom = intro ? intro.getBoundingClientRect().bottom - box.top + 50 : 0;

        paths.forEach((path, i) => {
            const rand = seeded(i * 97 + 13);
            const startX = parseFloat(pools[i]?.style.left) || W / 2 + (i - 1) * 40;
            const start = { x: startX, y: shared.poolOffset ?? 0 }; // = l'endroit où la goutte s'est infiltrée (au-dessus de la section)
            const under = { x: startX + (i - 1) * 30, y: introBottom };
            const r = cards[i].getBoundingClientRect();
            const card = { x: r.left - box.left + r.width / 2, y: r.top - box.top + r.height / 2 };

            const a0 = zigzag(rand, start, under, i % 2 ? 1 : -1);
            const a1 = zigzag(rand, under, card, a0.side);
            const a = { pts: [...a0.pts, ...a1.pts], side: a1.side }; // départ → couche
            const b = zigzag(rand, card, end, a.side); // couche → point commun
            const pts = [start, ...a.pts, ...b.pts];
            // Virages adoucis : chaque angle devient une courbe (le tracé passe par le milieu des segments)
            const f = (p) => `${p.x.toFixed(1)} ${p.y.toFixed(1)}`;
            let d = `M ${f(pts[0])}`;
            for (let k = 1; k < pts.length - 1; k++) {
                const mid = { x: (pts[k].x + pts[k + 1].x) / 2, y: (pts[k].y + pts[k + 1].y) / 2 };
                d += ` Q ${f(pts[k])} ${f(mid)}`;
            }
            d += ` L ${f(pts[pts.length - 1])}`;
            path.setAttribute('d', d);

            // Longueur totale + position de la couche sur le tracé (pour l'allumer au passage)
            let len = 0;
            let cardLen = 0;
            pts.forEach((p, k) => {
                if (k) len += Math.hypot(p.x - pts[k - 1].x, p.y - pts[k - 1].y);
                if (k === a.pts.length) cardLen = len;
            });
            terreData.lengths[i] = path.getTotalLength();
            terreData.cardAt[i] = cardLen / len;
        });
        endDot.setAttribute('cx', end.x);
        endDot.setAttribute('cy', end.y);
    };

    buildTerre();
    ScrollTrigger.addEventListener('refresh', buildTerre);

    // Animation liée au scroll (sans mouvement réduit, les tracés restent affichés en entier)
    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const state = { p: 0 };
        const render = () => {
            paths.forEach((path, i) => {
                const L = terreData.lengths[i];
                path.style.strokeDasharray = L;
                path.style.strokeDashoffset = L * (1 - state.p);
                const pt = path.getPointAtLength(L * state.p);
                heads[i].setAttribute('cx', pt.x);
                heads[i].setAttribute('cy', pt.y);
                // visibles pendant le parcours, masquées quand la goutte de la bouteille a pris le relais
                heads[i].setAttribute('opacity', state.p > 0.001 && !shared.handoff ? 1 : 0);
                cards[i].toggleAttribute('data-wet', state.p >= terreData.cardAt[i]); // la couche s'allume au passage de sa goutte
            });
            // les 3 gouttes se rejoignent : le point commun grossit à la toute fin
            endDot.setAttribute('r', shared.handoff ? 0 : 12 * gsap.utils.clamp(0, 1, (state.p - 0.95) / 0.05));
        };

        shared.renderTerre = render;
        const terreTween = gsap.to(state, {
            p: 1,
            ease: 'none',
            onUpdate: render,
            scrollTrigger: { trigger: terre, start: 'top bottom', end: 'bottom 80%', scrub: 0.5 }, // démarre dès le décrochage de la scène
        });

        shared.terreST = terreTween.scrollTrigger;
        render();
        ScrollTrigger.addEventListener('refresh', render);

        return () => {
            ScrollTrigger.removeEventListener('refresh', render);
            paths.forEach((p) => {
                p.style.removeProperty('stroke-dasharray');
                p.style.removeProperty('stroke-dashoffset');
            });
            cards.forEach((c) => c.removeAttribute('data-wet'));
        };
    });

    // ============ 04 — SOURCE : la goutte réunie tombe dans la bouteille Quézac ============
    const source = document.querySelector('[data-source]');
    if (!source) return;

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const sRoot = source.querySelector('[data-source-root]');
        const sBase = source.querySelector('[data-source-base]');
        const sNeck = source.querySelector('[data-source-neck]');
        const sDrop = source.querySelector('[data-source-drop]');
        const sShine = source.querySelector('[data-source-shine]');
        const SR = 13; // même rayon que la goutte du ciel
        const S_STRETCH = 64;
        const bottle = source.querySelector('[data-bottle]');
        const sp = { x: 0, y0: 0, mx: 0, my: 0 };
        const GLOW = 'drop-shadow(0 0 45px rgba(85,136,184,0.65)) brightness(1)';

        // Départ = point commun de la section Terre ; arrivée = ouverture du goulot
        const layoutSource = () => {
            const sb = source.getBoundingClientRect();
            const tb = terre.getBoundingClientRect();
            const br = bottle.getBoundingClientRect();
            sp.x = parseFloat(endDot.getAttribute('cx')) + tb.left - sb.left;
            sp.y0 = parseFloat(endDot.getAttribute('cy')) + tb.top - sb.top; // au-dessus de la section (valeur négative)
            sp.mx = br.left - sb.left + br.width * parseFloat(bottle.dataset.mouthX);
            sp.my = br.top - sb.top + br.height * parseFloat(bottle.dataset.mouthY);
            sRoot.setAttribute('transform', `translate(${sp.x} ${sp.y0})`);
        };
        layoutSource();

        gsap.set(bottle, { filter: GLOW });
        gsap.set([sDrop, sShine], { svgOrigin: '0 0' }); // déformations autour du centre de la goutte

        const sourceTl = gsap.timeline({
            defaults: { ease: 'none' },
            // dès que la goutte de la bouteille existe, la section Terre masque ses 3 gouttes et son point commun
            onUpdate() {
                const on = this.progress() > 0.001;
                if (on !== shared.handoff) {
                    shared.handoff = on;
                    shared.renderTerre?.();
                }
            },
            scrollTrigger: {
                trigger: source,
                start: 'top 80%', // = fin de la section Terre (les 3 gouttes viennent de se rejoindre)
                end: () => `top+=${sp.my} 30%`, // le goulot arrive dans le tiers haut de l'écran
                scrub: 0.5,
                invalidateOnRefresh: true,
                onRefresh: layoutSource,
            },
        })
            // l'eau réunie prend le relais du point commun de la section Terre
            .set(sBase, { attr: { r: 12 } }, 0)
            // Phase 1 — étirement : la goutte gonfle, s'allonge, le filet s'amincit jusqu'à se pincer (comme sous le nuage)
            .fromTo(sNeck, { attr: { rx: 0, ry: 0, cy: 0 }, opacity: 1 }, { attr: { rx: 9, ry: 4, cy: 4 }, duration: 0.1 }, 0)
            .fromTo(sDrop, { attr: { r: 0 }, x: 0, y: 0, scaleX: 1, scaleY: 1, opacity: 1 }, { attr: { r: SR }, y: 8, duration: 0.1 }, 0)
            .fromTo(sShine, { opacity: 0, x: 0, y: 0, scaleX: 1, scaleY: 1 }, { opacity: 0.8, y: 8, duration: 0.1 }, 0)
            .to(sNeck, { attr: { rx: 2.6, ry: S_STRETCH / 2, cy: S_STRETCH / 2 }, duration: 0.2 }, 0.1)
            .to([sDrop, sShine], { y: S_STRETCH, scaleY: 1.25, scaleX: 0.9, ease: 'power1.in', duration: 0.2 }, 0.1)
            // rupture : le filet disparaît, le reste de l'eau se résorbe
            .to(sNeck, { opacity: 0, duration: 0.02 }, 0.3)
            .to(sBase, { attr: { r: 0 }, duration: 0.1 }, 0.3)
            // Phase 2 — chute libre accélérée (gravité) jusqu'au goulot, la goutte s'allonge avec la vitesse
            .to([sDrop, sShine], { x: () => sp.mx - sp.x, y: () => sp.my - sp.y0 + 8, scaleY: 1.4, scaleX: 0.82, ease: 'power2.in', duration: 0.6 }, 0.3)
            // …et entre dans le goulot : la bouteille s'illumine
            .to([sDrop, sShine], { opacity: 0, duration: 0.05 }, 0.9)
            .to(bottle, { filter: 'drop-shadow(0 0 80px rgba(85,136,184,0.95)) brightness(1.25)', duration: 0.05 }, 0.9)
            .to(bottle, { filter: GLOW, duration: 0.1 }, 0.95);
        shared.sourceST = sourceTl.scrollTrigger;

        // La section reste accrochée un moment une fois en haut de l'écran : on profite de la bouteille illuminée
        shared.sourcePinST = ScrollTrigger.create({
            trigger: source,
            start: 'top top',
            end: '+=60%',
            pin: true,
            anticipatePin: 1,
        });

        return () => {
            shared.handoff = false;
            gsap.set(bottle, { clearProps: 'filter' });
        };
    });

    // ============ BARRE DE PROGRESSION : 5 étapes (01 Ciel → 02 Pluie → 03 Terre → 04 Source → 05 Recyclage) ============
    const bar = document.querySelector('[data-progress]');
    if (!bar) return;

    const stepEls = gsap.utils.toArray(bar.querySelectorAll('[data-progress-step]'));
    const line = bar.querySelector('[data-progress-line]');
    const pageTop = (el) => el.getBoundingClientRect().top + window.scrollY;

    // Positions de scroll qui bornent chaque étape : [début 01, début 02, début 03, début 04, fin 04]
    // Étape 05 : se termine quand le bas de la section Recyclage passe le milieu de l'écran
    const recycle = document.querySelector('#recyclage');
    const recycleEnd = () => pageTop(recycle) + recycle.offsetHeight - window.innerHeight / 2;

    const anchors = () => {
        if (shared.stageST && shared.terreST && shared.sourceST) {
            return [
                shared.stageST.start, // haut de page
                shared.detachAt(), // la goutte se détache du nuage
                shared.stageST.end, // la scène se décroche, les tracés partent
                shared.terreST.end, // les 3 gouttes se rejoignent
                shared.sourcePinST ? shared.sourcePinST.end : shared.sourceST.end, // la section Source se décroche : le recyclage commence
                recycleEnd(), // fin du recyclage
            ];
        }
        // Sans animation (mouvement réduit) : une étape par section, au passage du milieu de l'écran
        const half = window.innerHeight / 2;
        return [
            pageTop(document.querySelector('[data-hero]')),
            pageTop(document.querySelector('[data-scene-runner]')) - half,
            pageTop(terre) - half,
            pageTop(source) - half,
            pageTop(source) + source.offsetHeight - half,
            recycleEnd(),
        ];
    };

    let marks = anchors();
    const updateBar = () => {
        const y = window.scrollY;
        const last = stepEls.length - 1;
        let active = 0;
        stepEls.forEach((_, i) => {
            if (y >= marks[i]) active = i;
        });
        // Le trait plein atteint le repère d'une étape au moment où elle commence ; entre deux repères, il avance avec l'étape en cours
        const local = gsap.utils.clamp(0, 1, (y - marks[active]) / (marks[active + 1] - marks[active] || 1));
        const fill = active >= last ? 1 : (active + local) / last;
        line.style.transform = `scaleY(${fill})`;
        stepEls.forEach((step, i) => {
            step.toggleAttribute('data-done', i < active);
            step.toggleAttribute('data-active', i === active);
            step.querySelector('[data-progress-go]')?.toggleAttribute('aria-current', i === active);
        });
        // Masquée en haut de page : apparaît dès qu'on commence à scroller, s'efface une fois l'histoire terminée
        bar.toggleAttribute('data-visible', y > 10 && y < marks[marks.length - 1] + window.innerHeight * 0.5);
        // Sur une section claire (Recyclage), la barre passe en version sombre
        const r = recycle.getBoundingClientRect();
        const mid = window.innerHeight / 2;
        bar.toggleAttribute('data-light', r.top < mid && r.bottom > mid);
    };

    // Étapes cliquables : défilement doux jusqu'au début de l'étape
    stepEls.forEach((step, i) => {
        step.querySelector('[data-progress-go]')?.addEventListener('click', () => {
            const target = Math.round(marks[i]) + (i ? 2 : 0);
            shared.navigating = true;
            if (i >= 2) shared.finishSeq?.(); // on va au-delà de la chute : séquence terminée d'office
            let finished = false;
            const done = () => {
                if (finished) return;
                finished = true;
                shared.navigating = false;
                window.removeEventListener('scrollend', done);
                if (i === 1) shared.replaySeq?.(); // « 02 — La pluie » depuis plus bas : la goutte retombe du nuage
            };
            window.addEventListener('scrollend', done);
            setTimeout(done, 2500); // navigateurs sans « scrollend »
            window.scrollTo({ top: target, behavior: 'smooth' });
        });
    });

    ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate: updateBar,
        onRefresh: () => {
            marks = anchors();
            updateBar();
        },
    });
    updateBar();
});
