// Animation de 10 secondes (regle.md §7), construite uniquement avec les 6 assets SVG du jeu :
//   fond-depart / fond-route / fond-arrivee  → le décor, mis bout à bout (1600 × 600 chacun), nuages en parallaxe
//   personnage-neutre + veste-gr70-profil + bouteille-dos → le randonneur (même repère 400 × 560, superposés)
// Le randonneur en K-Way traverse la pluie, la bouteille ouverte dans le dos, la main posée sur le goulot :
// seule la main se mouille (les gouttes y rebondissent), la K-Way protège tout le reste.
// La fin révèle le résultat déjà tiré par le serveur — rien de ce que fait le joueur ne le change :
//   gagné : arrivée à la source, la main se lève, les bulles jaillissent du goulot
//   perdu : la main lâche le goulot avant l'arrivée, la pluie entre dans la bouteille ouverte et l'eau monte
//           (jamais de K-Way qui prend l'eau, jamais de bouteille qui fuit)
// 3 scénarios par résultat = 3 météos : pluie régulière, averse soudaine, orage.
// Renvoie { finished: Promise, skip() } ; « Passer » (ou prefers-reduced-motion) affiche directement la fin.
import { gsap } from 'gsap';

const NS = 'http://www.w3.org/2000/svg';
const VIEW_W = 1000; // fenêtre de la caméra (viewBox du <svg data-g-scene>)
const PANEL_W = 1600; // largeur d'un fond
const CAM_END = 2 * PANEL_W + 500; // la caméra s'arrête sur l'arche « Arrivée » (entière) et la source
const SCALE = 0.58; // taille du randonneur dans le décor
const FEET_Y = 515; // sol : sur le chemin (bande 492 → 550 des fonds)
const HIKER_START = 190; // position à l'écran pendant la traversée
const HIKER_END = 470; // juste devant la fontaine de fond-arrivee (x = 1140–1260 dans le fond)
const CAM_TIME = 7; // traversée de la caméra (s)
const ARRIVE = 8.2; // le randonneur atteint la source
const DURATION = 10;
const STEP = 0.56; // un cycle de marche (s)

// Pivots documentés dans les assets (data-pivot) ; rotation positive = vers l'arrière
const PIVOTS = {
    legBack: '193 400', shinBack: '193 468', footBack: '193 532',
    legFront: '209 400', shinFront: '209 468', footFront: '209 532',
    arm: '205 150', forearm: '203 226',
};
const HAND_ON_MOUTH = { arm: -157, fore: -118 }; // pose « main sur le goulot » (veste-gr70-profil.svg)
const HAND_RAISED = { arm: -178, fore: -150 }; // la main se lève au-dessus du goulot
const HAND_DOWN = { arm: -20, fore: -10 }; // la main lâche le goulot
const MOUTH = { x: 128, y: 118 }; // centre de l'ouverture du goulot (bouteille-dos.svg)

// Marche : 4 quarts de cycle (jambe avant f / arrière b : cuisse T, mollet S, pied F ; rebond du buste)
const GAIT = [
    { fT: -24, fS: 4, fF: -6, bT: 22, bS: 8, bF: 14, bob: 0 },
    { fT: -1, fS: 6, fF: 4, bT: 0, bS: 48, bF: 0, bob: -6 },
    { fT: 22, fS: 8, fF: 14, bT: -24, bS: 4, bF: -6, bob: 0 },
    { fT: 0, fS: 48, fF: 0, bT: -1, bS: 6, bF: 4, bob: -6 },
];
const STAND = { fT: 0, fS: 0, fF: 0, bT: 0, bS: 0, bF: 0, bob: 0 };

const WEATHER = [
    { drops: 70, speed: 0.75 }, // 0 : pluie régulière
    { drops: 70, speed: 0.75, burst: { at: 2.6, length: 2.4, factor: 2.2 } }, // 1 : averse soudaine
    { drops: 90, speed: 0.6, burst: { at: 4, length: 1.8, factor: 1.8 }, flashes: [2.2, 5.3] }, // 2 : orage
];

// ---------- Chargement des assets (une fois, en cache) ----------
let assetsPromise = null;
export function preloadAssets(urls) {
    if (!assetsPromise) {
        const parse = (text) => new DOMParser().parseFromString(text, 'image/svg+xml').documentElement;
        assetsPromise = Promise.all(Object.entries(urls).map(([key, url]) => fetch(url).then((r) => r.text()).then((t) => [key, parse(t)]))).then(Object.fromEntries);
        assetsPromise.catch(() => (assetsPromise = null)); // échec réseau : on réessaiera à la prochaine partie
    }
    return assetsPromise;
}

const el = (tag, attrs = {}, parent = null) => {
    const node = document.createElementNS(NS, tag);
    Object.entries(attrs).forEach(([k, v]) => node.setAttribute(k, v));
    parent?.append(node);
    return node;
};
const clone = (doc, selector) => document.importNode(doc.querySelector(selector), true);

// ---------- Construction de la scène ----------
function buildScene(svg, a) {
    svg.replaceChildren();
    const defs = el('defs', {}, svg);
    Object.values(a).forEach((doc) => doc.querySelectorAll(':scope > defs > *').forEach((d) => defs.append(document.importNode(d, true))));

    // Ciel fixe (celui du départ), qui se réchauffe en ciel d'arrivée
    const skyGroup = el('g', {}, svg);
    skyGroup.append(clone(a.depart, '#bg-sky'));
    const skyEnd = clone(a.arrivee, '#bg-sky');
    skyEnd.setAttribute('opacity', '0');
    skyGroup.append(skyEnd);
    skyGroup.querySelectorAll('[id]').forEach((n) => n.removeAttribute('id'));

    // Décor : départ | route | arrivée, chaque calque dans son propre plan de parallaxe
    const world = el('g', {}, svg);
    const planes = ['bg-clouds', 'bg-hills-far', 'bg-hills-near', 'bg-ground', 'bg-props'];
    const parallax = Object.fromEntries(planes.map((id) => [id, el('g', {}, world)]));
    ['depart', 'route', 'arrivee'].forEach((key, i) => {
        planes.forEach((id) => {
            const layer = clone(a[key], `#${id}`);
            layer.removeAttribute('id'); // les 3 fonds partagent les mêmes id
            el('g', { transform: `translate(${i * PANEL_W} 0)` }, parallax[id]).append(layer);
        });
    });

    // Le randonneur, dans l'ordre des calques indiqué par les assets :
    // jambes → bouteille (derrière la veste) → corps, zip, capuche → tête → sangle → bras avant (avec la main)
    const hikerPos = el('g', {}, svg);
    const hiker = el('g', { transform: `scale(${SCALE})` }, hikerPos);
    const part = (doc, id) => hiker.appendChild(clone(doc, `#${id}`));
    const legBack = part(a.hiker, 'hiker-leg-back');
    const legFront = part(a.hiker, 'hiker-leg-front');
    const bottle = part(a.bottle, 'bottle');
    ['kway-body', 'kway-zip', 'kway-hood'].forEach((id) => part(a.kway, id));
    part(a.hiker, 'hiker-head');
    part(a.bottle, 'bottle-strap');
    const arm = part(a.kway, 'kway-arm-front');
    const forearm = arm.querySelector('#kway-forearm');
    forearm.append(clone(a.hiker, '#hiker-hand')); // la main suit l'avant-bras
    const fx = el('g', {}, hiker); // gouttes et bulles autour du goulot (repère du randonneur)

    // La pluie (repère de l'écran) et le flash de l'orage, devant tout
    const rain = el('g', { stroke: '#D7E7F3', 'stroke-width': 2, 'stroke-linecap': 'round', opacity: 0.7 }, svg);
    const flash = el('rect', { width: VIEW_W, height: 600, fill: '#fff', opacity: 0 }, svg);

    const q = (root, id) => root.querySelector(`#${id}`);
    const water = q(bottle, 'bottle-water');
    return {
        skyEnd, world, parallax, hikerPos, fx, rain, flash, bottle, water,
        waterLine: water.nextElementSibling, // ligne de surface (M100,212 H156)
        bubbles: [...q(bottle, 'bottle-bubbles').children],
        joints: {
            legBack, shinBack: q(legBack, 'hiker-shin-back'), footBack: q(legBack, 'hiker-foot-back'),
            legFront, shinFront: q(legFront, 'hiker-shin-front'), footFront: q(legFront, 'hiker-foot-front'),
            arm, forearm,
        },
    };
}

// ---------- L'animation ----------
export function playAnimation(stage, { won, scenario }) {
    let resolve;
    const finished = new Promise((r) => (resolve = r));
    let tl = null;
    let render = null;
    let skipped = false;
    const loops = [];
    const done = () => {
        loops.forEach((l) => l.kill());
        if (render) gsap.ticker.remove(render);
        resolve();
    };

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        resolve();
        return { finished, skip() {} };
    }

    const svg = stage.querySelector('[data-g-scene]');
    preloadAssets(JSON.parse(stage.dataset.gAssets))
        .then((assets) => {
            if (skipped) return;
            ({ tl, render } = animate(buildScene(svg, assets), Boolean(won), Number(scenario) || 0, loops));
            gsap.ticker.add(render);
            render();
            tl.eventCallback('onComplete', done);
        })
        .catch(done); // assets introuvables : on affiche directement la fin

    return {
        finished,
        skip() {
            skipped = true;
            if (tl) tl.progress(1); // → onComplete → done
            else done();
        },
    };
}

function animate(s, won, scenario, loops) {
    const weather = WEATHER[scenario] ?? WEATHER[0];
    const st = { cam: 0, hx: HIKER_START, ...GAIT[0], ...HAND_ON_MOUTH, water: 212 };
    const rotate = (node, angle, pivot) => node.setAttribute('transform', `rotate(${angle.toFixed(2)} ${pivot})`);
    // parallaxe sur les nuages seulement : les collines des 3 fonds ne se raccordent pas si elles glissent entre elles
    const speeds = { 'bg-clouds': 0.5 };

    const render = () => {
        s.world.setAttribute('transform', `translate(${-st.cam} 0)`);
        Object.entries(speeds).forEach(([id, k]) => s.parallax[id].setAttribute('transform', `translate(${st.cam * k} 0)`));
        s.hikerPos.setAttribute('transform', `translate(${st.hx} ${FEET_Y - 547 * SCALE + st.bob})`);
        const j = s.joints;
        rotate(j.legBack, st.bT, PIVOTS.legBack);
        rotate(j.shinBack, st.bS, PIVOTS.shinBack);
        rotate(j.footBack, st.bF, PIVOTS.footBack);
        rotate(j.legFront, st.fT, PIVOTS.legFront);
        rotate(j.shinFront, st.fS, PIVOTS.shinFront);
        rotate(j.footFront, st.fF, PIVOTS.footFront);
        rotate(j.arm, st.arm, PIVOTS.arm);
        rotate(j.forearm, st.fore, PIVOTS.forearm);
        s.water.setAttribute('y', st.water);
        s.water.setAttribute('height', 292 - st.water); // le fond de l'eau reste au fond de la bouteille (y = 292)
        s.waterLine.setAttribute('d', `M100,${st.water} H156`);
    };

    const tl = gsap.timeline({ defaults: { ease: 'none' } });

    // ---------- Traversée : la caméra suit le randonneur jusqu'à l'arche d'arrivée, puis il rejoint la source ----------
    tl.to(st, { cam: CAM_END, duration: CAM_TIME, ease: 'power1.inOut' }, 0);
    tl.to(st, { hx: HIKER_END, duration: ARRIVE - CAM_TIME + 0.4, ease: 'power1.out' }, CAM_TIME - 0.4);
    tl.to(s.skyEnd, { attr: { opacity: 1 }, duration: 2.5, ease: 'sine.inOut' }, CAM_TIME - 2.2); // ciel d'arrivée, plus chaud

    // Marche (boucle), puis pose debout à l'arrivée
    const walk = gsap.timeline({ repeat: -1 });
    GAIT.slice(1).concat(GAIT[0]).forEach((pose) => walk.to(st, { ...pose, duration: STEP / 4, ease: 'sine.inOut' }));
    loops.push(walk);
    tl.call(() => walk.kill(), null, ARRIVE).to(st, { ...STAND, duration: 0.35, ease: 'power1.out' }, ARRIVE);

    // La main suit le pas, sans quitter le goulot
    const sway = gsap.to(st, { arm: HAND_ON_MOUTH.arm - 2, duration: STEP / 2, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    loops.push(sway);

    // ---------- La pluie ----------
    const rainLoops = [];
    for (let i = 0; i < weather.drops; i++) {
        const x = gsap.utils.random(-60, VIEW_W + 160);
        const len = gsap.utils.random(18, 30);
        const drop = el('line', { x1: x, y1: 0, x2: x - len * 0.25, y2: len }, s.rain);
        const d = weather.speed * gsap.utils.random(0.8, 1.2);
        rainLoops.push(gsap.fromTo(drop, { y: -40, x: 0 }, { y: 640, x: -160, duration: d, repeat: -1, ease: 'none', delay: -gsap.utils.random(0, d) }));
    }
    loops.push(...rainLoops);
    if (weather.burst) {
        const { at, length, factor } = weather.burst;
        tl.call(() => rainLoops.forEach((l) => l.timeScale(factor)), null, at)
            .to(s.rain, { attr: { opacity: 0.95 }, duration: 0.3 }, at)
            .call(() => rainLoops.forEach((l) => l.timeScale(1)), null, at + length)
            .to(s.rain, { attr: { opacity: 0.7 }, duration: 0.5 }, at + length);
    }
    (weather.flashes ?? []).forEach((t) => tl.to(s.flash, { attr: { opacity: 0.6 }, duration: 0.05 }, t).to(s.flash, { attr: { opacity: 0 }, duration: 0.4 }, t + 0.05));
    tl.to(s.rain, { attr: { opacity: 0 }, duration: 1.2 }, ARRIVE - 0.6); // l'averse cesse à la source

    // ---------- Seule la main se mouille : les gouttes y rebondissent ----------
    const hit = el('circle', { cx: MOUTH.x, cy: -60, r: 6, fill: '#BFE6FA' }, s.fx);
    const sides = [-1, 1].map(() => el('circle', { cx: MOUTH.x, cy: MOUTH.y - 18, r: 4, fill: '#BFE6FA', opacity: 0 }, s.fx));
    const splash = gsap.timeline({ repeat: -1, repeatDelay: 0.15 });
    splash
        .fromTo(hit, { attr: { cy: -60 }, opacity: 1 }, { attr: { cy: MOUTH.y - 22 }, duration: 0.3, ease: 'power2.in' })
        .set(hit, { opacity: 0 })
        .fromTo(sides, { attr: { cx: MOUTH.x, cy: MOUTH.y - 22 }, opacity: 1 }, { attr: { cx: (i) => MOUTH.x + (i ? 34 : -34), cy: MOUTH.y - 40 }, duration: 0.16, ease: 'power1.out' })
        .to(sides, { attr: { cy: MOUTH.y + 10 }, opacity: 0, duration: 0.22, ease: 'power1.in' });
    loops.push(splash);
    const stopSplash = () => {
        sway.kill();
        splash.kill();
        hit.remove();
        sides.forEach((d) => d.remove());
    };

    // ---------- Les fins ----------
    if (won) {
        // la main est restée sur le goulot jusqu'à la source : elle se lève, les bulles jaillissent
        tl.call(stopSplash, null, ARRIVE + 0.1)
            .to(st, { ...HAND_RAISED, duration: 0.5, ease: 'back.out(1.6)' }, ARRIVE + 0.1)
            .to(s.bottle, { filter: 'drop-shadow(0 0 14px rgba(191,230,250,0.95))', duration: 0.4 }, ARRIVE + 0.4);
        s.bubbles.forEach((b, i) => tl.to(b, { attr: { cy: 140 }, duration: 0.9, ease: 'power1.in' }, ARRIVE + 0.3 + i * 0.06));
        for (let i = 0; i < 18; i++) {
            const r = gsap.utils.random(3, 7);
            const bubble = el('circle', { cx: MOUTH.x, cy: MOUTH.y, r, fill: 'rgba(228,244,253,0.25)', stroke: '#E4F4FD', 'stroke-width': 2, opacity: 0 }, s.fx);
            tl.fromTo(bubble, { attr: { cx: MOUTH.x, cy: MOUTH.y }, opacity: 1 }, { attr: { cx: MOUTH.x + gsap.utils.random(-70, 70), cy: MOUTH.y - gsap.utils.random(140, 260) }, opacity: 0, duration: 1.1, ease: 'power1.out' }, ARRIVE + 0.45 + i * 0.07);
        }
    } else {
        // la main lâche le goulot avant la source : la pluie entre dans la bouteille ouverte et l'eau monte
        const LET_GO = ARRIVE - 1.8;
        tl.call(stopSplash, null, LET_GO).to(st, { ...HAND_DOWN, duration: 0.45, ease: 'power2.out' }, LET_GO);
        for (let i = 0; i < 5; i++) {
            const drop = el('circle', { cx: MOUTH.x, cy: -60, r: 5, fill: '#BFE6FA', opacity: 0 }, s.fx);
            const at = LET_GO + 0.3 + i * 0.32;
            tl.fromTo(drop, { attr: { cy: -60 }, opacity: 1 }, { attr: { cy: MOUTH.y + 6 }, duration: 0.32, ease: 'power2.in' }, at).set(drop, { opacity: 0 }, at + 0.32);
        }
        tl.to(st, { water: 150, duration: 1.8, ease: 'power1.inOut' }, LET_GO + 0.5); // l'eau de pluie monte dans la bouteille
    }
    tl.to({}, { duration: 0.01 }, DURATION - 0.01); // durée totale : 10 s

    return { tl, render };
}
