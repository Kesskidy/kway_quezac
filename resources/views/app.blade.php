<x-app-layout>
    <x-slot:title>RE·SOURCE — La collection K-Way × Quézac</x-slot:title>

    {{-- ============ BARRE DE PROGRESSION : tracé vertical à droite, une étape par titre (01 → 05), comme les repères d'une carte de randonnée ============ --}}
    {{-- Masquée en haut de page, elle apparaît dès qu'on commence à scroller dans le hero --}}
    <nav data-progress aria-label="Progression de l’histoire" class="group/bar pointer-events-none fixed top-1/2 right-5 z-30 -translate-y-1/2 opacity-0 transition-opacity duration-500 data-visible:opacity-100 md:right-8">
        {{-- Sentier : pointillés à parcourir, trait plein déjà parcouru (aligné sur le centre des repères).
             Hors de la liste (une <ol> ne contient que des <li>) : le <nav> fixe, de la taille de la liste, sert de repère --}}
        <span data-progress-track class="absolute top-2 right-[7px] bottom-2 w-0 border-r-2 border-dashed border-white/30 transition-colors group-data-light/bar:border-nuit/25" aria-hidden="true"></span>
        <span data-progress-line class="absolute top-2 right-[6px] bottom-2 w-1 origin-top rounded-full bg-source" style="transform: scaleY(0)" aria-hidden="true"></span>
        <ol class="relative flex flex-col gap-14">
            <li data-progress-step class="group relative flex justify-end">
                <button type="button" data-progress-go aria-label="Aller à l’étape 01 — Le ciel" class="pointer-events-auto -m-2 flex cursor-pointer items-center gap-3 rounded-full p-2 focus-visible:outline-2 focus-visible:outline-source">
                    <span data-progress-label class="text-[11px] font-semibold tracking-[.14em] whitespace-nowrap text-white/45 uppercase [text-shadow:0_1px_10px_rgb(4_9_40/0.95)] transition-colors duration-300 group-hover:text-white group-data-done:text-white/70 group-data-active:text-white max-md:sr-only group-data-light/bar:text-nuit/55 group-data-light/bar:[text-shadow:none] group-data-light/bar:group-data-done:text-nuit/75 group-data-light/bar:group-data-active:text-nuit group-data-light/bar:group-hover:text-nuit">01 — Le ciel</span>
                    {{-- Repère : anneau à venir, bleu une fois passé, rouge « point GPX » pour l'étape en cours --}}
                    <span class="relative grid size-4 place-items-center rounded-full border-2 border-white/45 bg-nuit transition duration-300 group-hover:scale-125 group-data-done:border-source group-data-done:bg-source group-data-active:border-zip group-data-active:bg-zip" aria-hidden="true">
                        <span class="absolute inset-0 hidden animate-ping rounded-full bg-zip/60 group-data-active:block"></span>
                    </span>
                </button>
            </li>
            <li data-progress-step class="group relative flex justify-end">
                <button type="button" data-progress-go aria-label="Aller à l’étape 02 — La pluie" class="pointer-events-auto -m-2 flex cursor-pointer items-center gap-3 rounded-full p-2 focus-visible:outline-2 focus-visible:outline-source">
                    <span data-progress-label class="text-[11px] font-semibold tracking-[.14em] whitespace-nowrap text-white/45 uppercase [text-shadow:0_1px_10px_rgb(4_9_40/0.95)] transition-colors duration-300 group-hover:text-white group-data-done:text-white/70 group-data-active:text-white max-md:sr-only group-data-light/bar:text-nuit/55 group-data-light/bar:[text-shadow:none] group-data-light/bar:group-data-done:text-nuit/75 group-data-light/bar:group-data-active:text-nuit group-data-light/bar:group-hover:text-nuit">02 — La pluie</span>
                    {{-- Repère : anneau à venir, bleu une fois passé, rouge « point GPX » pour l'étape en cours --}}
                    <span class="relative grid size-4 place-items-center rounded-full border-2 border-white/45 bg-nuit transition duration-300 group-hover:scale-125 group-data-done:border-source group-data-done:bg-source group-data-active:border-zip group-data-active:bg-zip" aria-hidden="true">
                        <span class="absolute inset-0 hidden animate-ping rounded-full bg-zip/60 group-data-active:block"></span>
                    </span>
                </button>
            </li>
            <li data-progress-step class="group relative flex justify-end">
                <button type="button" data-progress-go aria-label="Aller à l’étape 03 — La terre de Lozère" class="pointer-events-auto -m-2 flex cursor-pointer items-center gap-3 rounded-full p-2 focus-visible:outline-2 focus-visible:outline-source">
                    <span data-progress-label class="text-[11px] font-semibold tracking-[.14em] whitespace-nowrap text-white/45 uppercase [text-shadow:0_1px_10px_rgb(4_9_40/0.95)] transition-colors duration-300 group-hover:text-white group-data-done:text-white/70 group-data-active:text-white max-md:sr-only group-data-light/bar:text-nuit/55 group-data-light/bar:[text-shadow:none] group-data-light/bar:group-data-done:text-nuit/75 group-data-light/bar:group-data-active:text-nuit group-data-light/bar:group-hover:text-nuit">03 — La terre de Lozère</span>
                    {{-- Repère : anneau à venir, bleu une fois passé, rouge « point GPX » pour l'étape en cours --}}
                    <span class="relative grid size-4 place-items-center rounded-full border-2 border-white/45 bg-nuit transition duration-300 group-hover:scale-125 group-data-done:border-source group-data-done:bg-source group-data-active:border-zip group-data-active:bg-zip" aria-hidden="true">
                        <span class="absolute inset-0 hidden animate-ping rounded-full bg-zip/60 group-data-active:block"></span>
                    </span>
                </button>
            </li>
            <li data-progress-step class="group relative flex justify-end">
                <button type="button" data-progress-go aria-label="Aller à l’étape 04 — La source" class="pointer-events-auto -m-2 flex cursor-pointer items-center gap-3 rounded-full p-2 focus-visible:outline-2 focus-visible:outline-source">
                    <span data-progress-label class="text-[11px] font-semibold tracking-[.14em] whitespace-nowrap text-white/45 uppercase [text-shadow:0_1px_10px_rgb(4_9_40/0.95)] transition-colors duration-300 group-hover:text-white group-data-done:text-white/70 group-data-active:text-white max-md:sr-only group-data-light/bar:text-nuit/55 group-data-light/bar:[text-shadow:none] group-data-light/bar:group-data-done:text-nuit/75 group-data-light/bar:group-data-active:text-nuit group-data-light/bar:group-hover:text-nuit">04 — La source</span>
                    {{-- Repère : anneau à venir, bleu une fois passé, rouge « point GPX » pour l'étape en cours --}}
                    <span class="relative grid size-4 place-items-center rounded-full border-2 border-white/45 bg-nuit transition duration-300 group-hover:scale-125 group-data-done:border-source group-data-done:bg-source group-data-active:border-zip group-data-active:bg-zip" aria-hidden="true">
                        <span class="absolute inset-0 hidden animate-ping rounded-full bg-zip/60 group-data-active:block"></span>
                    </span>
                </button>
            </li>
            <li data-progress-step class="group relative flex justify-end">
                <button type="button" data-progress-go aria-label="Aller à l’étape 05 — Le recyclage" class="pointer-events-auto -m-2 flex cursor-pointer items-center gap-3 rounded-full p-2 focus-visible:outline-2 focus-visible:outline-source">
                    <span data-progress-label class="text-[11px] font-semibold tracking-[.14em] whitespace-nowrap text-white/45 uppercase [text-shadow:0_1px_10px_rgb(4_9_40/0.95)] transition-colors duration-300 group-hover:text-white group-data-done:text-white/70 group-data-active:text-white max-md:sr-only group-data-light/bar:text-nuit/55 group-data-light/bar:[text-shadow:none] group-data-light/bar:group-data-done:text-nuit/75 group-data-light/bar:group-data-active:text-nuit group-data-light/bar:group-hover:text-nuit">05 — Le recyclage</span>
                    {{-- Repère : anneau à venir, bleu une fois passé, rouge « point GPX » pour l'étape en cours --}}
                    <span class="relative grid size-4 place-items-center rounded-full border-2 border-white/45 bg-nuit transition duration-300 group-hover:scale-125 group-data-done:border-source group-data-done:bg-source group-data-active:border-zip group-data-active:bg-zip" aria-hidden="true">
                        <span class="absolute inset-0 hidden animate-ping rounded-full bg-zip/60 group-data-active:block"></span>
                    </span>
                </button>
            </li>
        </ol>
    </nav>

    {{-- ============ RETOUR EN HAUT : masqué en haut de page, apparaît dès qu'on commence à scroller ============ --}}
    <button type="button" data-to-top aria-label="Revenir en haut de la page" class="invisible fixed right-5 bottom-5 z-30 grid size-12 translate-y-3 cursor-pointer place-items-center rounded-full border border-white/20 bg-nuit text-white opacity-0 shadow-[0_8px_24px_rgb(4_9_40/0.35)] transition duration-300 hover:bg-source focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-source data-visible:visible data-visible:translate-y-0 data-visible:opacity-100 md:right-8 md:bottom-8">
        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
            <path d="M12 19V5" />
            <path d="m5 12 7-7 7 7" />
        </svg>
    </button>

    {{-- ============ SCÈNE ACCROCHÉE : 01 Ciel → la goutte → 02 Pluie (coureur) ============ --}}
    {{-- Sans animation (mouvement réduit), les deux scènes s'affichent l'une sous l'autre --}}
    <div data-stage class="relative overflow-hidden bg-nuit">

    {{-- ============ 01 — CIEL (hero) ============ --}}
    <section id="histoire" data-hero aria-labelledby="titre-hero" class="relative flex min-h-svh items-center pt-28 pb-16">
        {{-- Nuages (décor) --}}
        <div data-sky class="pointer-events-none absolute inset-0 select-none" aria-hidden="true">
            <img data-hero-cloud-small src="{{ asset('images/nuage-1.png') }}" alt="" class="absolute right-[24%] bottom-[8%] hidden w-[23.5%] opacity-70 blur-[2px] lg:block">
            <img data-hero-cloud src="{{ asset('images/nuage-1.png') }}" alt="" class="absolute top-[14%] -right-[12%] w-[66%] max-w-[960px] opacity-30 lg:opacity-90">
        </div>

        <div class="relative mx-auto w-full max-w-7xl px-8">
            <div data-hero-text class="max-w-160">
                <h1 id="titre-hero" class="text-[clamp(48px,6vw,88px)] leading-[0.95] font-bold tracking-[-0.03em]">Du ciel<br>à la source.</h1>
                <p class="mt-6 max-w-130 text-lg leading-normal text-white/85">La pluie que l’on brave avec K-Way devient l’eau pétillante que l’on boit avec Quézac. Puis la bouteille redevient veste. Un seul cycle, une seule eau.</p>

                <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-6">
                    <a href="#product" class="inline-flex min-h-12 items-center rounded-full bg-zip px-6 font-semibold text-nuit hover:opacity-90">Découvrir la collection</a>

                    {{-- Co-signature des deux marques --}}
                    <p class="flex items-center gap-3">
                        <span class="inline-flex h-10 items-center rounded-lg bg-white px-3">
                            <img src="{{ asset('images/logo-kway.png') }}" alt="K-Way" width="405" height="79" class="h-5 w-auto">
                        </span>
                        <span class="text-white/60" aria-hidden="true">×</span>
                        <img src="{{ asset('images/logo-quezac.png') }}" alt="Quézac" width="395" height="161" class="h-10 w-auto">
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ 02 — LA PLUIE / K-WAY (coureur) ============ --}}
    <section id="foret" data-scene-runner aria-labelledby="titre-foret" class="relative min-h-svh overflow-hidden bg-cevenol py-28 motion-safe:absolute motion-safe:inset-0">
        {{-- Coureur centré. Point d'impact de la goutte : data-impact-x / data-impact-y (fractions de l'image) = haut de l'épaule --}}
        {{-- « Caméra » : descend ensuite vers le sol (data-shoe-y = bas de la semelle d'appui = niveau du sol) --}}
        {{-- Fond forêt (test) derrière le coureur, avec une vignette sur les bords --}}
        <div data-runner-bg class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img src="{{ asset('images/foret-fond.jpg') }}" alt="" width="798" height="532" class="size-full object-cover">
            {{-- vignette : apparaît en même temps que le titre et l'encart (JS), visible d'office sans animation --}}
            <div data-runner-vignette class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgb(4_9_40/0.15)_15%,rgb(4_9_40/0.3)_30%,rgb(4_9_40/0.58)_45%,rgb(4_9_40/0.86)_62%,rgb(4_9_40/0.97)_80%,rgb(4_9_40/1)_100%)]"></div>
            {{-- Assombrit la zone du texte blanc pour la lisibilité : en haut sur mobile (texte au-dessus du coureur), à gauche en desktop --}}
            <div data-runner-vignette class="absolute inset-0 bg-[linear-gradient(to_bottom,rgb(4_9_40/0.85)_0%,rgb(4_9_40/0.6)_40%,transparent_70%)] lg:bg-[linear-gradient(to_right,rgb(4_9_40/0.85)_0%,rgb(4_9_40/0.6)_30%,transparent_50%)]"></div>
        </div>
        <div data-runner-cam class="pointer-events-none absolute inset-0">
            <img data-runner data-impact-x="0.53" data-impact-y="0.18" data-shoe-x="0.86" data-shoe-y="0.963" src="{{ asset('images/coureur.png') }}" alt="Veste RE·SOURCE portée en pleine course" width="1024" height="1536" class="absolute inset-x-0 -bottom-[8%] mx-auto h-full w-auto max-w-none opacity-40 lg:opacity-100">
        </div>

        {{-- Sol : monte quand la caméra descend ; même couleur que la section suivante, qui le prolonge --}}
        <div data-ground class="pointer-events-none absolute inset-x-0 top-full h-full bg-linear-to-b from-transparent to-nuit to-[1rem]" aria-hidden="true">
            {{-- Fondu de la forêt vers le sol, au-dessus de la ligne du sol (apparaît pendant la descente de la caméra) --}}
            <span data-ground-fade class="absolute inset-x-0 bottom-[calc(100%-1rem)] h-36 bg-linear-to-b from-transparent to-nuit opacity-0 motion-reduce:hidden"></span>
            {{-- Les 3 gouttes tombées de l'épaule, posées sur le sol (position horizontale donnée par le JS) --}}
            <span data-ground-pool class="absolute top-1.5 left-1/2 h-2.5 w-9 -translate-x-1/2 rounded-full bg-source"></span>
            <span data-ground-pool class="absolute top-1.5 left-1/2 h-2.5 w-9 -translate-x-1/2 rounded-full bg-source"></span>
            <span data-ground-pool class="absolute top-1.5 left-1/2 h-2.5 w-9 -translate-x-1/2 rounded-full bg-source"></span>
        </div>

        <div data-runner-ui class="lg:absolute lg:inset-0">
        {{-- À gauche du coureur, à 1/3 de la hauteur --}}
        <div data-runner-text class="relative mx-8 max-w-110 lg:absolute lg:top-1/3 lg:left-[max(2rem,calc(50%-38rem))] lg:mx-0 lg:w-[min(24rem,calc(50%-18rem))]">
            <span class="inline-flex items-center rounded-lg bg-white px-3 py-2">
                <img src="{{ asset('images/logo-kway.png') }}" alt="K-Way" width="405" height="79" class="h-6 w-auto">
            </span>
            <h2 id="titre-foret" class="mt-6 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Qu’il pleuve,<br>on avance.</h2>
            <p class="mt-4.5 text-lg leading-normal">Sur les hauts plateaux de Lozère, l’averse ne ralentit personne.</p>
            <p class="mt-4 text-lg leading-normal text-white/85">Depuis 1965, K-Way garde la pluie dehors, pour que rien n’arrête la marche.</p>
        </div>

        {{-- À droite du coureur, à 2/3 de la hauteur --}}
        <aside data-runner-aside class="relative mx-8 mt-8 max-w-85 rounded-2xl border border-white/15 bg-nuit/75 px-5 py-4 lg:absolute lg:top-2/3 lg:right-[max(2rem,calc(50%-38rem))] lg:mx-0 lg:mt-0 lg:w-[min(21rem,calc(50%-18rem))]">
            <p class="text-xs font-semibold tracking-[.16em] text-zip uppercase">Déperlant</p>
            <p class="mt-2 text-[17px] leading-snug">L’eau glisse, la marche continue.</p>
        </aside>
        </div>{{-- /data-runner-ui --}}

    </section>

    {{-- ============ LA GOUTTE (SVG + filtre gooey, animée par GSAP) — coordonnées de la scène ============ --}}
    <svg data-drop class="pointer-events-none absolute inset-0 z-20 size-full overflow-visible text-source motion-reduce:hidden" aria-hidden="true" focusable="false">
        <defs>
            {{-- Gooey : flou puis seuil d'opacité → les formes proches fusionnent comme un liquide --}}
            <filter id="goo" x="-100%" y="-100%" width="300%" height="300%" color-interpolation-filters="sRGB">
                <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />
                <feColorMatrix in="blur" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                <feComposite in="SourceGraphic" in2="goo" operator="atop" />
            </filter>
            {{-- Fondu du haut : la goutte semble sortir du nuage (retiré au détachement) --}}
            <linearGradient id="drop-fade-grad" gradientUnits="userSpaceOnUse" x1="0" y1="-6" x2="0" y2="14">
                <stop offset="0" stop-color="#fff" stop-opacity="0" />
                <stop offset="1" stop-color="#fff" stop-opacity="1" />
            </linearGradient>
            <mask id="drop-fade" maskUnits="userSpaceOnUse" x="-2000" y="-100" width="4000" height="4000">
                <rect x="-2000" y="-100" width="4000" height="4000" fill="url(#drop-fade-grad)" />
            </mask>
        </defs>
        <g data-drop-root>
            <g data-drop-mask mask="url(#drop-fade)">
                <g filter="url(#goo)" fill="currentColor">
                    <ellipse data-drop-base cx="0" cy="0" rx="0" ry="0" />
                    <ellipse data-drop-neck cx="0" cy="0" rx="0" ry="0" />
                    <circle data-drop-body cx="0" cy="0" r="0" />
                    <ellipse data-drop-puddle cx="0" cy="0" rx="0" ry="0" />
                    <circle data-drop-fall cx="0" cy="0" r="0" />
                    <circle data-drop-fall cx="0" cy="0" r="0" />
                    <circle data-drop-fall cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                    <circle data-drop-droplet cx="0" cy="0" r="0" />
                </g>
            </g>
            {{-- Reflet (hors filtre) pour l'effet goutte d'eau --}}
            <ellipse data-drop-shine cx="-4" cy="-5" rx="3" ry="4.5" fill="#fff" opacity="0" />
        </g>
    </svg>

    </div>{{-- /data-stage --}}

    {{-- ============ 03 — TERRE ============ --}}
    <section id="terre" data-terre aria-labelledby="titre-terre" class="relative overflow-x-clip bg-nuit pt-10 pb-48 lg:pt-30">
        {{-- Tracés d'eau : 3 gouttes qui zigzaguent dans la roche, chacune à travers une couche, et se rejoignent en bas (tracés calculés par le JS) --}}
        <svg data-terre-svg class="pointer-events-none absolute inset-0 size-full overflow-visible text-source" aria-hidden="true" focusable="false">
            <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.85">
                <path data-terre-path d="" />
                <path data-terre-path d="" />
                <path data-terre-path d="" />
            </g>
            {{-- Les gouttes (filtre gooey de la scène précédente : elles fusionnent en arrivant au point commun) --}}
            <g filter="url(#goo)" fill="currentColor" class="motion-reduce:hidden">
                <circle data-terre-head cx="0" cy="0" r="7" opacity="0" />
                <circle data-terre-head cx="0" cy="0" r="7" opacity="0" />
                <circle data-terre-head cx="0" cy="0" r="7" opacity="0" />
                <circle data-terre-end cx="0" cy="0" r="0" />
            </g>
        </svg>

        <div class="relative mx-auto max-w-7xl px-8">
            <div data-terre-intro class="max-w-130">
                <h2 id="titre-terre" class="text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Trois couches, une eau qui prend vie.</h2>
                <p class="mt-4.5 text-lg leading-normal text-white/85">La goutte s’infiltre dans les roches des Cévennes. À chaque couche, elle se charge de minéraux.</p>
            </div>

            <div class="mt-24 flex flex-col gap-24 lg:gap-32">
                <article data-terre-card class="w-full max-w-85 rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5 transition-[border-color,background-color,box-shadow] duration-500 data-wet:border-source/80 data-wet:bg-source/10 data-wet:shadow-[0_0_40px_rgb(85_136_184/0.35)]">
                    <p class="text-xs font-semibold tracking-[.16em] text-sauge uppercase">Mg</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Magnésium</h3>
                    <p class="mt-2 leading-normal text-white/85">La goutte entre dans la roche. Premier passage, premiers minéraux.</p>
                </article>
                <article data-terre-card class="w-full max-w-85 self-end rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5 transition-[border-color,background-color,box-shadow] duration-500 data-wet:border-source/80 data-wet:bg-source/10 data-wet:shadow-[0_0_40px_rgb(85_136_184/0.35)]">
                    <p class="text-xs font-semibold tracking-[.16em] text-white/75 uppercase">Ca</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Calcium</h3>
                    <p class="mt-2 leading-normal text-white/85">Elle ralentit et se charge. La pierre calcaire lui donne sa structure.</p>
                </article>
                <article data-terre-card class="w-full max-w-85 rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5 transition-[border-color,background-color,box-shadow] duration-500 data-wet:border-source/80 data-wet:bg-source/10 data-wet:shadow-[0_0_40px_rgb(85_136_184/0.35)]">
                    <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">HCO₃⁻</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Bicarbonates</h3>
                    <p class="mt-2 leading-normal text-white/85">Dernier passage : l’eau devient pétillante. Les premières bulles apparaissent.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ 04 — SOURCE / QUÉZAC ============ --}}
    <section id="source" data-source aria-labelledby="titre-source" class="relative z-10 flex min-h-svh items-center overflow-x-clip bg-nuit pt-24 pb-30">
        <div class="pointer-events-none absolute top-0 left-1/2 size-200 -translate-x-1/2 rounded-full bg-radial from-source/30 to-transparent to-60%" aria-hidden="true"></div>

        {{-- La goutte qui part du point commun (fin de la section Terre) et tombe dans le goulot --}}
        <svg data-source-svg class="pointer-events-none absolute inset-0 z-10 size-full overflow-visible text-source motion-reduce:hidden" aria-hidden="true" focusable="false">
            {{-- Même construction que la goutte du ciel : eau réunie → filet qui s'amincit → goutte qui se détache (filtre gooey) --}}
            <g data-source-root>
                <g filter="url(#goo)" fill="currentColor">
                    <circle data-source-base cx="0" cy="0" r="0" />
                    <ellipse data-source-neck cx="0" cy="0" rx="0" ry="0" />
                    <circle data-source-drop cx="0" cy="0" r="0" />
                </g>
                <ellipse data-source-shine cx="-4" cy="-5" rx="3" ry="4.5" fill="#fff" opacity="0" />
            </g>
        </svg>

        {{-- Desktop : 3 colonnes, bouteille au centre.
             Mobile (max-lg, comme la scène du coureur) : titre + texte, puis la bouteille estompée remontée sous le texte (marge négative),
             puis la citation et le bouton sous la bouteille. Texte en z-20, devant la goutte (svg z-10) qui passe sous le titre --}}
        <div class="relative mx-auto grid w-full max-w-7xl items-center gap-10 px-8 max-lg:grid-cols-[minmax(0,1fr)] lg:grid-cols-[1fr_auto_1fr]">
            {{-- Bouteille sous le point commun. data-mouth-x / data-mouth-y = ouverture du goulot (fractions de l'image) --}}
            <img data-bottle data-mouth-x="0.5" data-mouth-y="0.02" src="{{ asset('images/bouteille-quezac.png') }}" alt="Bouteille d'eau minérale pétillante Quézac" width="1024" height="1536" class="mx-auto h-[70svh] max-h-[720px] w-auto drop-shadow-[0_0_45px_rgb(85_136_184/0.65)] max-lg:relative max-lg:-left-8 max-lg:-mt-[6svh] max-lg:h-[55svh] max-lg:max-h-[calc((100vw-4rem)*1.5)] max-lg:opacity-50 lg:col-start-2 lg:row-start-1">
            <div class="max-w-100 max-lg:relative max-lg:z-20 max-lg:order-first lg:col-start-1 lg:row-start-1">
                <img src="{{ asset('images/logo-quezac.png') }}" alt="Quézac" width="395" height="161" class="h-14 w-auto">
                <h2 id="titre-source" class="mt-6 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Une source,<br>une bouteille.</h2>
                <p class="mt-4.5 text-lg leading-normal text-white/85">Captée à la source, l’eau gazeuse de Lozère est mise en bouteille depuis 1901. Une fois bue, la bouteille a encore une vie à vivre.</p>
            </div>
            <div class="max-w-90 max-lg:relative max-lg:z-20 max-lg:mt-6 lg:col-start-3 lg:row-start-1 lg:justify-self-end">
                <blockquote>
                    <p class="font-serif text-3xl leading-tight font-bold tracking-[-0.015em]">« Un patrimoine à boire, un avenir à bâtir. »</p>
                </blockquote>
                <p class="mt-4 leading-normal text-white/80">Vide, la bouteille ne finit pas sa route : elle part au recyclage.</p>
                <p class="mt-6.5 max-lg:hidden"><a href="#recyclage" class="inline-flex min-h-13 items-center rounded-full bg-zip px-6.5 font-semibold text-nuit hover:opacity-90">Suivre la bouteille ↓</a></p>
            </div>
        </div>
    </section>

    {{-- ============ 05 — RECYCLAGE ============ --}}
    <section id="recyclage" aria-labelledby="titre-recyclage" class="relative overflow-hidden bg-white py-30 text-nuit">
        <div class="mx-auto max-w-7xl px-8">
            <div>
                {{-- devant la bouteille qui vient se glisser derrière (section Source en z-10) --}}
                <h2 id="titre-recyclage" data-recycle-title class="relative z-30 text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">De la bouteille au fil.</h2>
                <p class="relative z-30 mt-5 max-w-140 text-xl leading-normal text-nuit/80">Les bouteilles Quézac vides sont collectées, puis transformées en fil de polyester recyclé. Ce fil devient le tissu de la veste RE·SOURCE.</p>
            </div>

            {{-- Mobile (max-lg) : le fil est vertical (bordure gauche) et traverse les étapes 1 → 4, une pastille sur chaque numéro.
                 Desktop — le fil (bordure du haut) : chaque visuel est à cheval dessus, centré sur la ligne, sans fond.
                 lg:pt-32 = 8rem sous le fil → bas du visuel à 100% + 8rem − (sa hauteur / 2). Bouteille h-54 → 100% + 1.25rem ; paillettes h-36 → 100% + 3.5rem. En dessous de lg (étapes empilées), il reste au-dessus de son étape --}}
            <ol class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(200px,100%),1fr))] gap-6 border-t border-nuit/15 pt-10 max-lg:grid-cols-1 max-lg:gap-14 max-lg:border-t-0 max-lg:border-l-2 max-lg:pt-0 max-lg:pl-5 lg:mt-36 lg:pt-32">
                <li class="relative flex flex-col gap-3 max-lg:grid max-lg:grid-cols-[auto_1fr] max-lg:items-center max-lg:gap-x-3">
                    {{-- une bouteille Quézac, droite. Avec animations : masquée, c'est la bouteille de la section Source qui vient se poser à sa place --}}
                    <img data-collect-bottle src="{{ asset('images/bouteille-quezac.png') }}" alt="" width="1024" height="1536" loading="lazy" aria-hidden="true" class="mb-2 h-54 w-auto self-center max-lg:col-span-2 max-lg:justify-self-center drop-shadow-[0_0_12px_rgb(85_136_184/0.55)] lg:absolute lg:bottom-[calc(100%+1.25rem)] lg:left-1/2 lg:-translate-x-1/2 lg:mb-0">
                    <p class="font-serif text-4xl font-bold text-source max-lg:relative max-lg:before:absolute max-lg:before:top-1/2 max-lg:before:-left-[calc(1.25rem+7px)] max-lg:before:size-3 max-lg:before:-translate-y-1/2 max-lg:before:rounded-full max-lg:before:bg-current" aria-hidden="true">1</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Collecte</h3>
                    <p class="leading-normal text-nuit/80 max-lg:col-span-2">Les bouteilles vides sont collectées et triées. Il en faudra 12 de 50 cl pour faire un K-Way.</p>
                </li>
                <li class="relative flex flex-col gap-3 max-lg:grid max-lg:grid-cols-[auto_1fr] max-lg:items-center max-lg:gap-x-3">
                    <img src="{{ asset('images/paillettes-pet.png') }}" alt="Tas de paillettes de plastique PET transparentes et bleutées, issues de bouteilles broyées" width="420" height="297" loading="lazy" class="mb-2 h-36 w-auto self-center max-lg:col-span-2 max-lg:justify-self-center lg:absolute lg:bottom-[calc(100%+3.5rem)] lg:left-1/2 lg:-translate-x-1/2 lg:mb-0">
                    <p class="font-serif text-4xl font-bold text-source max-lg:relative max-lg:before:absolute max-lg:before:top-1/2 max-lg:before:-left-[calc(1.25rem+7px)] max-lg:before:size-3 max-lg:before:-translate-y-1/2 max-lg:before:rounded-full max-lg:before:bg-current" aria-hidden="true">2</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Broyage</h3>
                    <p class="leading-normal text-nuit/80 max-lg:col-span-2">Lavées puis broyées, elles deviennent des paillettes de PET. Environ 15 % de la matière se perd en route.</p>
                </li>
                <li class="relative flex flex-col gap-3 max-lg:grid max-lg:grid-cols-[auto_1fr] max-lg:items-center max-lg:gap-x-3">
                    <img src="{{ asset('images/bobine-fil.png') }}" alt="Bobine de fil de polyester recyclé bleu" width="246" height="420" loading="lazy" class="mb-2 h-36 w-auto self-center max-lg:col-span-2 max-lg:justify-self-center lg:absolute lg:bottom-[calc(100%+3.5rem)] lg:left-1/2 lg:-translate-x-1/2 lg:mb-0">
                    <p class="font-serif text-4xl font-bold text-source max-lg:relative max-lg:before:absolute max-lg:before:top-1/2 max-lg:before:-left-[calc(1.25rem+7px)] max-lg:before:size-3 max-lg:before:-translate-y-1/2 max-lg:before:rounded-full max-lg:before:bg-current" aria-hidden="true">3</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Filage</h3>
                    <p class="leading-normal text-nuit/80 max-lg:col-span-2">Fondues et étirées, les paillettes deviennent un fil de polyester recyclé.</p>
                </li>
                <li class="relative flex flex-col gap-3 max-lg:grid max-lg:grid-cols-[auto_1fr] max-lg:items-center max-lg:gap-x-3">
                    <img src="{{ asset('images/veste-face-petite.png') }}" alt="Veste RE·SOURCE K-Way × Quézac de face" width="364" height="420" loading="lazy" class="mb-2 h-54 w-auto self-center max-lg:col-span-2 max-lg:justify-self-center lg:absolute lg:bottom-[calc(100%+1.25rem)] lg:left-1/2 lg:-translate-x-1/2 lg:mb-0">
                    <p class="font-serif text-4xl font-bold text-zip max-lg:relative max-lg:before:absolute max-lg:before:top-1/2 max-lg:before:-left-[calc(1.25rem+7px)] max-lg:before:size-3 max-lg:before:-translate-y-1/2 max-lg:before:rounded-full max-lg:before:bg-current" aria-hidden="true">4</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Tissage</h3>
                    <p class="leading-normal text-nuit/80 max-lg:col-span-2">Le fil est tissé, rendu déperlant, et devient une veste K-Way : environ 180 g de tissu par veste.</p>
                </li>
            </ol>

            {{-- Clôture : la boucle se referme, retour au ciel du début --}}
            <div class="mt-16 flex flex-wrap items-center justify-between gap-x-10 gap-y-6 rounded-[28px] bg-nuit px-8 py-8 text-white md:px-10">
                <div class="max-w-160">
                    <p class="font-serif text-[clamp(28px,3.4vw,42px)] leading-tight font-bold">… et la pluie retombe.</p>
                    <p class="mt-3 text-lg leading-normal text-white/80">La veste repart sous l’averse, sur les hauts plateaux où tout a commencé. L’eau ne s’arrête jamais : le cycle recommence.</p>
                </div>
                <a href="#histoire" data-to-sky class="inline-flex min-h-13 items-center gap-3 rounded-full border-[1.5px] border-source px-6 font-semibold transition hover:bg-source/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-source">
                    <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M3 12a9 9 0 1 0 3-6.7" />
                        <path d="M3 3v6h6" />
                    </svg>
                    Remonter au ciel
                </a>
            </div>
        </div>
    </section>

    {{-- ============ PRODUIT ============ --}}
    <section id="product" aria-labelledby="titre-collection" class="bg-white py-30 text-nuit">
        <div class="mx-auto max-w-7xl px-8">
            <div class="max-w-190">
                <p class="text-xs font-semibold tracking-[.16em] text-cevenol uppercase">La collection</p>
                <h2 id="titre-collection" class="mt-3.5 text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">La veste qui vient de la source.</h2>
                <p class="mt-5 text-xl leading-normal text-nuit/80">RE·SOURCE, une veste K-Way tissée à partir de bouteilles Quézac recyclées.</p>
            </div>

            <div class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(480px,100%),1fr))] items-start gap-14">
                {{-- Visuel : image principale + miniatures. data-fit="contain" = visuel détouré (veste entière sur fond clair), sinon photo plein cadre --}}
                <div data-gallery class="flex flex-col gap-4">
                    <figure class="relative aspect-[5/4] overflow-hidden rounded-[28px] bg-source/15">
                        <img data-gallery-main data-fit="contain" src="{{ asset('images/veste-face.png') }}" alt="Veste RE·SOURCE K-Way × Quézac de face : bleu nuit, griffes cuivrées sur l’épaule droite et tracé du GR70 sur la manche gauche" width="1222" height="1287" class="size-full object-cover object-top transition-opacity duration-300 data-[fit=contain]:object-contain data-[fit=contain]:p-6">
                    </figure>
                    <div class="flex flex-wrap justify-center gap-3">
                        <button type="button" data-gallery-thumb data-fit="contain" data-src="{{ asset('images/veste-face.png') }}" data-alt="Veste RE·SOURCE K-Way × Quézac de face : bleu nuit, griffes cuivrées sur l’épaule droite et tracé du GR70 sur la manche gauche" aria-pressed="true" aria-label="Voir la veste de face" class="size-20 cursor-pointer overflow-hidden rounded-xl border-2 border-transparent bg-source/15 opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-face.png') }}" alt="" width="1222" height="1287" loading="lazy" class="size-full object-contain p-1">
                        </button>
                        <button type="button" data-gallery-thumb data-fit="contain" data-src="{{ asset('images/veste-profil.png') }}" data-alt="Veste RE·SOURCE K-Way × Quézac de profil : capuche, griffes cuivrées et inscription GR70° le long du tracé sur la manche" aria-pressed="false" aria-label="Voir la veste de profil" class="size-20 cursor-pointer overflow-hidden rounded-xl border-2 border-transparent bg-source/15 opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-profil.png') }}" alt="" width="1222" height="1287" loading="lazy" class="size-full object-contain p-1">
                        </button>
                        <button type="button" data-gallery-thumb data-fit="cover" data-src="{{ asset('images/veste-foret.jpg') }}" data-alt="Randonneuse portant la veste RE·SOURCE dans une forêt des Cévennes, mains dans les poches" aria-pressed="false" aria-label="Voir la veste portée en forêt" class="size-20 cursor-pointer overflow-hidden rounded-xl border-2 border-transparent bg-nuit opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-foret.jpg') }}" alt="" width="1086" height="1448" loading="lazy" class="size-full object-cover object-top">
                        </button>
                        <button type="button" data-gallery-thumb data-fit="cover" data-src="{{ asset('images/veste-resource.jpg') }}" data-alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" aria-pressed="false" aria-label="Voir la veste sur les roches" class="size-20 cursor-pointer overflow-hidden rounded-xl border-2 border-transparent bg-nuit opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-resource.jpg') }}" alt="" width="1264" height="843" loading="lazy" class="size-full object-cover">
                        </button>
                    </div>
                </div>

                {{-- Infos + précommande fictive (POST /orders → retour sur #product avec session('status') ou les erreurs) --}}
                @php
                    $product = ($products ?? collect())->first();
                    $selectedSize = old('size', 'M');
                @endphp
                <div class="flex flex-col gap-7.5">
                    <div>
                        <img src="{{ asset('images/logo-resource-nuit.png') }}" alt="RE·SOURCE by K-Way × Quézac" width="3640" height="684" class="mb-6 h-14 w-auto">
                        <h3 class="text-[34px] leading-[1.05] font-bold tracking-[-0.02em]">Veste RE·SOURCE<br>K-Way × Quézac</h3>
                        <div class="mt-4.5 flex flex-wrap items-baseline gap-4">
                            <p class="font-serif text-[40px] font-bold">{{ $product?->formattedPrice() ?? '300 €' }}</p>
                            <p class="text-[15px] text-nuit/80">Édition limitée · 50 000 exemplaires</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <p id="order-status" role="status" class="rounded-2xl border-[1.5px] border-source bg-source/10 px-5 py-4 leading-normal font-medium">{{ session('status') }}</p>
                    @endif

                    @if ($product)
                        <form id="order-form" method="POST" action="{{ route('orders.store') }}" class="flex flex-col gap-6" novalidate>
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @error('product_id')<p class="text-sm font-semibold text-zip" role="alert">{{ $message }}</p>@enderror

                            <fieldset>
                                <legend class="text-sm font-semibold tracking-[.04em]">Taille : <span data-size-label>{{ $selectedSize }}</span></legend>
                                <div data-size-group class="mt-3 flex flex-wrap gap-2.5">
                                    @foreach (\App\Models\Order::SIZES as $size)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="size" value="{{ $size }}" class="peer sr-only" @checked($selectedSize === $size)>
                                            <span class="inline-flex min-h-12 min-w-14 items-center justify-center rounded-xl border-[1.5px] border-nuit/30 bg-white px-3 text-[15px] font-medium transition-colors peer-checked:border-2 peer-checked:border-nuit peer-checked:bg-nuit peer-checked:font-semibold peer-checked:text-white peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-source hover:border-nuit">{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('size')<p class="mt-2 text-sm font-semibold text-zip" role="alert">{{ $message }}</p>@enderror
                            </fieldset>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="flex flex-col gap-2">
                                    <label for="order-first-name" class="text-sm font-semibold">Prénom</label>
                                    <input id="order-first-name" name="first_name" type="text" autocomplete="given-name" maxlength="50" required value="{{ old('first_name') }}" @error('first_name') aria-invalid="true" aria-describedby="order-first-name-error" @enderror class="min-h-13 w-full rounded-[14px] border-[1.5px] border-nuit/30 px-4.5 font-medium focus:border-source focus:outline-none aria-invalid:border-zip">
                                    @error('first_name')<p id="order-first-name-error" class="text-sm font-semibold text-zip">{{ $message }}</p>@enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="order-email" class="text-sm font-semibold">Adresse e-mail</label>
                                    <input id="order-email" name="email" type="email" autocomplete="email" maxlength="255" required value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="order-email-error" @enderror class="min-h-13 w-full rounded-[14px] border-[1.5px] border-nuit/30 px-4.5 font-medium focus:border-source focus:outline-none aria-invalid:border-zip">
                                    @error('email')<p id="order-email-error" class="text-sm font-semibold text-zip">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex flex-col gap-2.5">
                                <button type="submit" class="inline-flex min-h-15 cursor-pointer items-center self-start rounded-full bg-zip px-8 text-[17px] font-bold text-nuit hover:opacity-90"><span>Précommander · Taille <span data-size-label>{{ $selectedSize }}</span> · {{ $product->formattedPrice() }}</span></button>                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Les 3 atouts, en ligne --}}
            <ul class="mt-14 grid gap-8 border-t border-nuit/15 pt-10 md:grid-cols-3">
                <li class="flex items-start gap-4">
                    <span class="relative size-11 flex-none rounded-full bg-source/20" aria-hidden="true">
                    <img src="{{ asset('images/bouteille-quezac.png') }}" alt="" width="1024" height="1536" loading="lazy" class="absolute bottom-1 left-1/2 h-14 w-auto max-w-none -translate-x-1/2 rotate-15 drop-shadow-[0_0_6px_rgb(85_136_184/0.6)]">
                </span>
                    <div>
                        <h4 class="font-sans text-[17px] font-semibold">Environ 12 bouteilles Quézac recyclées</h4>
                        <p class="mt-1 text-[15px] leading-normal text-nuit/80">La bouteille qui a contenu l’eau devient le tissu de la veste. <a href="#recyclage" class="underline underline-offset-2">Voir le recyclage</a></p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <img src="{{ asset('images/icone-gr70.png') }}" alt="" width="123" height="141" loading="lazy" class="size-11 flex-none object-contain" aria-hidden="true">
                    <div>
                        <h4 class="font-sans text-[17px] font-semibold">Trace GPX du GR70 imprimée sur le bras</h4>
                        <p class="mt-1 text-[15px] leading-normal text-nuit/80">Du Puy-en-Velay à Saint-Jean-du-Gard, le chemin de Stevenson porté sur soi.</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="relative grid size-11 flex-none place-items-center rounded-full bg-source/20" aria-hidden="true">
                    {{-- même goutte que celle du ciel : bleu source + reflet blanc --}}
                    <svg viewBox="0 0 24 32" class="h-7 w-auto text-source" focusable="false">
                        <path d="M12 1C12 1 3 13 3 21a9 9 0 0 0 18 0C21 13 12 1 12 1Z" fill="currentColor" />
                        <ellipse cx="8.5" cy="20" rx="1.8" ry="2.8" fill="#fff" opacity="0.8" />
                    </svg>
                </span>
                    <div>
                        <h4 class="font-sans text-[17px] font-semibold">Étanche, pliable, prête pour la pluie</h4>
                        <p class="mt-1 text-[15px] leading-normal text-nuit/80">Le K-Way avec lequel on brave la pluie, dans une version qui boucle la boucle.</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    {{-- ============ VALEURS ============ --}}
    <section id="valeurs" aria-labelledby="titre-valeurs" class="relative overflow-hidden bg-nuit py-30">
        <div class="pointer-events-none absolute -top-60 left-1/2 h-150 w-275 -translate-x-1/2 rounded-full bg-radial from-source/15 to-transparent to-65%" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-7xl px-8">
            <div class="max-w-205">
                <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">Valeurs communes</p>
                <h2 id="titre-valeurs" class="mt-3.5 text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">Quatre valeurs, une même eau.</h2>
                <p class="mt-5 text-xl leading-normal text-white/85">K-Way protège de l’eau qui tombe, Quézac offre l’eau qui désaltère. Deux marques françaises, un seul cycle : c’est pour ça qu’est née RE·SOURCE.</p>
            </div>

            <div class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(270px,100%),1fr))] gap-5">
                <article class="flex min-h-95 flex-col gap-4.5 rounded-3xl border border-white/15 bg-white/5 p-7.5">
                    <p class="text-[13px] font-semibold tracking-[.14em] text-source">01</p>
                    <h3 class="text-3xl leading-[1.05] font-bold tracking-[-0.02em]">L’élément<br>Eau</h3>
                    <p class="text-[17px] leading-relaxed text-white/85">Protection extérieure d’un côté, hydratation intérieure de l’autre. La même eau, vécue de deux façons.</p>
                    <p class="mt-auto flex items-center gap-2.5 text-sm font-semibold"><span class="text-zip">K-Way</span><span class="h-px flex-1 bg-white/25" aria-hidden="true"></span><span class="text-source">Quézac</span></p>
                </article>
                <article class="flex min-h-95 flex-col gap-4.5 rounded-3xl border border-white/15 bg-white/5 p-7.5">
                    <p class="text-[13px] font-semibold tracking-[.14em] text-source">02</p>
                    <h3 class="text-3xl leading-[1.05] font-bold tracking-[-0.02em]">Héritage &amp;<br>Authenticité</h3>
                    <p class="text-[17px] leading-relaxed text-white/85">Deux marques pionnières du patrimoine français, qui unissent tradition et innovation.</p>
                    <p class="mt-auto flex items-baseline gap-3.5 font-serif text-[44px] font-bold tracking-[-0.02em]"><span class="text-source">1901</span><span class="h-px flex-1 bg-white/25" aria-hidden="true"></span><span class="text-zip">1965</span></p>
                    <p class="flex justify-between text-[13px] text-white/75"><span>Quézac</span><span>K-Way</span></p>
                </article>
                <article class="flex min-h-95 flex-col gap-4.5 rounded-3xl border border-white/15 bg-white/5 p-7.5">
                    <p class="text-[13px] font-semibold tracking-[.14em] text-source">03</p>
                    <h3 class="text-3xl leading-[1.05] font-bold tracking-[-0.02em]">Nature &amp;<br>Mouvement</h3>
                    <p class="text-[17px] leading-relaxed text-white/85">L’esprit de découverte, l’outdoor nomade, le mouvement. Un sentier, une veste, une gourde.</p>
                    <p class="mt-auto text-sm text-sauge">GR70</p>
                </article>
                <article class="flex min-h-95 flex-col gap-4.5 rounded-3xl bg-zip p-7.5 text-nuit">
                    <p class="text-[13px] font-bold tracking-[.14em]">04</p>
                    <h3 class="text-3xl leading-[1.05] font-bold tracking-[-0.02em]">Engagement</h3>
                    <blockquote><p class="font-serif text-[22px] leading-tight font-bold">« Un patrimoine à boire, un avenir à bâtir. »</p></blockquote>
                    <p class="leading-normal">Transformer une ressource minérale et son emballage en un vêtement durable.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ LE JEU (regle.md §0, §4, §7, §9) ============
         1. code de la bouteille → POST /api/game-sessions (le serveur tire le résultat)
         2. animation de 10 s (#game-stage, data-outcome / data-scenario posés par le JS), bouton « Passer »
         3. fin gagnante → formulaire de participation (POST /api/game-sessions/{token}/participation) ; fin perdante → « Presque ! » --}}
    @php
        $gameDate = fn (string $key) => \Illuminate\Support\Carbon::parse(config("legal.$key"))->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp
    <section id="game" data-api-base="{{ url('/api') }}" aria-labelledby="titre-jeu" class="relative overflow-hidden bg-source py-30 text-nuit">
        <div class="pointer-events-none absolute -top-30 -right-30 size-155 rounded-full bg-white/25" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-55 -left-40 size-140 rounded-full bg-nuit/10" aria-hidden="true"></div>
        <div class="relative mx-auto grid max-w-7xl grid-cols-[repeat(auto-fit,minmax(min(460px,100%),1fr))] items-center gap-14 px-8">
            <div>
                <p class="inline-flex min-h-9 items-center rounded-full bg-nuit px-4 text-[13px] font-semibold tracking-[.08em] text-white uppercase">Jeu · du {{ $gameDate('game_starts_on') }} au {{ $gameDate('game_ends_on') }}</p>
                <h2 id="titre-jeu" class="mt-5.5 text-[clamp(44px,6vw,86px)] leading-[0.94] font-bold tracking-[-0.035em]">Remonte à<br>la source.</h2>
                <p class="mt-6 max-w-130 text-xl leading-normal">Chaque bouteille Quézac cache un code. Saisis-le et suis le randonneur sous la pluie : à l’arrivée, tu sauras tout de suite si tu as gagné ta participation au tirage des lots.</p>
                <ol class="mt-8 flex max-w-130 flex-col gap-3.5 text-[17px] font-semibold">
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">1</span>Trouve le code sur ta bouteille</li>
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">2</span>Saisis-le : 1 code = 1 tirage</li>
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">3</span>Gagné ? Laisse ton prénom et ton e-mail</li>
                </ol>
                <p class="mt-6 max-w-130 text-[15px] leading-normal">Tirage final des lots le {{ $gameDate('final_draw_on') }}. <a href="{{ route('legal.rules') }}" data-rules-modal aria-haspopup="dialog" class="font-semibold underline underline-offset-2">Lire le règlement</a></p>
            </div>

            <div class="rounded-[28px] bg-nuit p-6 text-white shadow-[0_30px_60px_rgb(4_9_40/0.35)] sm:p-9">
                {{-- Étape 1 : le code --}}
                <div data-game-panel="code">
                    <h3 class="text-[32px] leading-[1.05] font-bold tracking-[-0.02em]">Je tente ma chance</h3>
                    <form id="game-code-form" class="mt-6.5 flex flex-col gap-4.5" novalidate>
                        <div class="flex flex-col gap-2">
                            <label for="game-code" class="text-sm font-semibold">Code de ta bouteille</label>
                            <input id="game-code" name="code" type="text" required maxlength="32" autocomplete="off" autocapitalize="characters" spellcheck="false" placeholder="QZK-XXXX-XXXX" aria-describedby="game-error" class="min-h-13 w-full rounded-[14px] border-[1.5px] border-white/35 bg-white/10 px-4.5 font-medium tracking-[.08em] uppercase placeholder:tracking-normal placeholder:text-white/60 focus:border-source focus:outline-none aria-invalid:border-zip">
                        </div>
                        <p id="game-error" role="alert" class="text-sm font-semibold text-zip empty:hidden"></p>
                        <button type="submit" class="inline-flex min-h-14.5 cursor-pointer items-center justify-center rounded-full bg-zip text-[17px] font-bold text-nuit hover:opacity-90 disabled:cursor-wait disabled:opacity-60">Jouer</button>
                    </form>
                </div>

                {{-- Étape 2 : l'animation (10 s). Tout est décoratif : le résultat est déjà tiré par le serveur --}}
                <div data-game-panel="stage" hidden>
                    {{-- Scène construite par le JS avec les 6 assets SVG du jeu (même repère pour personnage / veste / bouteille) --}}
                    @php
                        $gameAssets = [
                            'depart' => asset('images/fond-depart.svg'),
                            'route' => asset('images/fond-route.svg'),
                            'arrivee' => asset('images/fond-arrivee.svg'),
                            'hiker' => asset('images/personnage-neutre.svg'),
                            'kway' => asset('images/veste-gr70-profil.svg'),
                            'bottle' => asset('images/bouteille-dos.svg'),
                        ];
                    @endphp
                    <div id="game-stage" class="relative aspect-[5/3] overflow-hidden rounded-2xl bg-[#56636B]" aria-hidden="true" data-g-assets='@json($gameAssets)'>
                        <svg data-g-scene xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 600" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 size-full"></svg>
                        <button type="button" id="game-skip-button" class="absolute right-3 bottom-3 inline-flex min-h-10 cursor-pointer items-center rounded-full bg-nuit/80 px-4 text-sm font-semibold text-white backdrop-blur-sm hover:bg-nuit">Passer</button>
                    </div>
                    <p class="mt-4 text-sm text-white/75" role="status">Le randonneur rejoint la source… sa main protège la bouteille ouverte.</p>
                </div>

                {{-- Étape 3 : le résultat, puis la participation si gagné --}}
                <div data-game-panel="result" hidden>
                    <div id="game-result" tabindex="-1" class="focus:outline-none">
                        <h3 data-game-result-title class="font-serif text-[40px] leading-[1.02] font-bold">Résultat</h3>
                        <p data-game-result-text class="mt-3 text-lg leading-normal text-white/85"></p>
                    </div>

                    <form id="game-participation-form" class="mt-6.5 flex flex-col gap-4.5" novalidate hidden>
                        <div class="flex flex-col gap-2">
                            <label for="game-first-name" class="text-sm font-semibold">Prénom</label>
                            <input id="game-first-name" name="first_name" type="text" required maxlength="50" autocomplete="given-name" aria-describedby="game-first-name-error" class="min-h-13 w-full rounded-[14px] border-[1.5px] border-white/35 bg-white/10 px-4.5 font-medium focus:border-source focus:outline-none aria-invalid:border-zip">
                            <p id="game-first-name-error" data-field-error="first_name" class="text-sm font-semibold text-zip empty:hidden"></p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="game-email" class="text-sm font-semibold">Adresse e-mail</label>
                            <input id="game-email" name="email" type="email" required maxlength="255" autocomplete="email" placeholder="prenom@exemple.fr" aria-describedby="game-email-error" class="min-h-13 w-full rounded-[14px] border-[1.5px] border-white/35 bg-white/10 px-4.5 font-medium placeholder:text-white/60 focus:border-source focus:outline-none aria-invalid:border-zip">
                            <p id="game-email-error" data-field-error="email" class="text-sm font-semibold text-zip empty:hidden"></p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex items-start gap-3">
                                <input id="game-consent" name="consent" type="checkbox" value="1" required aria-describedby="game-consent-error" class="mt-0.5 size-5.5 flex-none accent-zip">
                                <label for="game-consent" class="text-sm leading-normal text-white/85">J’accepte le <a href="{{ route('legal.rules') }}" data-rules-modal aria-haspopup="dialog" class="underline underline-offset-2">règlement du jeu</a> et l’utilisation de mon e-mail pour être contacté(e) si je gagne.</label>
                            </div>
                            <p id="game-consent-error" data-field-error="consent" class="text-sm font-semibold text-zip empty:hidden"></p>
                        </div>
                        <button type="submit" class="inline-flex min-h-14.5 cursor-pointer items-center justify-center rounded-full bg-zip text-[17px] font-bold text-nuit hover:opacity-90 disabled:cursor-wait disabled:opacity-60">Je participe au tirage</button>
                    </form>

                    <p id="game-participation-status" role="status" class="mt-5 rounded-2xl border-[1.5px] border-source bg-source/15 px-5 py-4 leading-normal font-medium empty:hidden data-[state=error]:border-zip data-[state=error]:bg-zip/15"></p>

                    <button type="button" data-game-replay class="mt-6 inline-flex min-h-12 cursor-pointer items-center rounded-full border-[1.5px] border-white/60 px-5 font-semibold hover:bg-white/10">Jouer avec un autre code</button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ RÈGLEMENT DU JEU (modale) ============
         Ouverte par les liens [data-rules-modal]. Le contenu est chargé depuis /reglement (page légale, source unique)
         au premier affichage. Sans JS, les liens mènent à la page /reglement. --}}
    <dialog id="rules-dialog" data-rules-dialog aria-labelledby="rules-dialog-title" class="m-auto max-h-[min(85svh,52rem)] w-[min(44rem,calc(100%-2rem))] overflow-hidden rounded-[28px] border border-white/15 bg-nuit p-0 text-white shadow-[0_30px_80px_rgb(0_0_0/0.5)] backdrop:bg-nuit/75 backdrop:backdrop-blur-sm">
        <div class="flex max-h-[inherit] flex-col">
            <div class="flex items-center justify-between gap-4 border-b border-white/15 px-6 py-4 sm:px-8">
                <h2 id="rules-dialog-title" class="font-serif text-2xl font-bold">Règlement du jeu</h2>
                <button type="button" data-rules-close aria-label="Fermer le règlement" class="-mr-2 grid size-11 flex-none cursor-pointer place-items-center rounded-full hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-source">
                    <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" /></svg>
                </button>
            </div>
            {{-- mêmes styles de texte que les pages légales --}}
            <div data-rules-body tabindex="0" class="min-h-0 flex-1 overflow-y-auto px-6 py-6 leading-relaxed text-white/90 focus:outline-none sm:px-8 [&_a]:text-source [&_a]:underline [&_a]:underline-offset-2 [&_h2]:mt-8 [&_h2]:mb-3 [&_h2]:font-serif [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-white [&_li]:mb-1.5 [&_p]:mb-4 [&_strong]:text-white [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-6 [&>*:first-child]:mt-0">
                <p>Chargement du règlement…</p>
            </div>
        </div>
    </dialog>

</x-app-layout>
