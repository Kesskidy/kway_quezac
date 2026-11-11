<x-app-layout>
    <x-slot:title>RE·SOURCE — La collection K-Way × Quézac</x-slot:title>

    {{-- ============ BARRE DE PROGRESSION : tracé vertical à droite, une étape par titre (01 → 05), comme les repères d'une carte de randonnée ============ --}}
    {{-- Masquée en haut de page, elle apparaît dès qu'on commence à scroller dans le hero --}}
    <nav data-progress aria-label="Progression de l’histoire" class="group/bar pointer-events-none fixed top-1/2 right-5 z-30 -translate-y-1/2 opacity-0 transition-opacity duration-500 data-visible:opacity-100 md:right-8">
        <ol class="relative flex flex-col gap-14">
            {{-- Sentier : pointillés à parcourir, trait plein déjà parcouru (aligné sur le centre des repères) --}}
            <span data-progress-track class="absolute top-2 right-[7px] bottom-2 w-0 border-r-2 border-dashed border-white/30 transition-colors group-data-light/bar:border-nuit/25" aria-hidden="true"></span>
            <span data-progress-line class="absolute top-2 right-[6px] bottom-2 w-1 origin-top rounded-full bg-source" style="transform: scaleY(0)" aria-hidden="true"></span>
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
                    <a href="#collection" class="inline-flex min-h-12 items-center rounded-full bg-zip px-6 font-semibold text-nuit hover:opacity-90">Découvrir la collection</a>

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
        <div data-runner-cam class="pointer-events-none absolute inset-0">
            <img data-runner data-impact-x="0.53" data-impact-y="0.18" data-shoe-x="0.86" data-shoe-y="0.963" src="{{ asset('images/coureur.png') }}" alt="Veste RE·SOURCE portée en pleine course" width="1024" height="1536" class="absolute inset-x-0 -bottom-[8%] mx-auto h-full w-auto max-w-none opacity-40 lg:opacity-100">
        </div>

        {{-- Sol : monte quand la caméra descend ; même couleur que la section suivante, qui le prolonge --}}
        <div data-ground class="pointer-events-none absolute inset-x-0 top-full h-full bg-linear-to-b from-transparent to-nuit to-[1rem]" aria-hidden="true">
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
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
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
    <section id="terre" data-terre aria-labelledby="titre-terre" class="relative overflow-x-clip bg-nuit pt-30 pb-48">
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
                    <p class="text-xs font-semibold tracking-[.16em] text-sauge uppercase">Couche 1 · Mg</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Magnésium</h3>
                    <p class="mt-2 leading-normal text-white/85">La goutte entre dans la roche. Premier passage, premiers minéraux.</p>
                </article>
                <article data-terre-card class="w-full max-w-85 self-end rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5 transition-[border-color,background-color,box-shadow] duration-500 data-wet:border-source/80 data-wet:bg-source/10 data-wet:shadow-[0_0_40px_rgb(85_136_184/0.35)]">
                    <p class="text-xs font-semibold tracking-[.16em] text-white/75 uppercase">Couche 2 · Ca</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Calcium</h3>
                    <p class="mt-2 leading-normal text-white/85">Elle ralentit et se charge. La pierre calcaire lui donne sa structure.</p>
                </article>
                <article data-terre-card class="w-full max-w-85 rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5 transition-[border-color,background-color,box-shadow] duration-500 data-wet:border-source/80 data-wet:bg-source/10 data-wet:shadow-[0_0_40px_rgb(85_136_184/0.35)]">
                    <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">Couche 3 · HCO₃⁻</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Bicarbonates</h3>
                    <p class="mt-2 leading-normal text-white/85">Dernier passage : l’eau devient pétillante. Les premières bulles apparaissent.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ 04 — SOURCE / QUÉZAC ============ --}}
    <section id="source" data-source aria-labelledby="titre-source" class="relative flex min-h-svh items-center overflow-x-clip bg-nuit pt-24 pb-30">
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

        <div class="relative mx-auto grid w-full max-w-7xl items-center gap-10 px-8 lg:grid-cols-[1fr_auto_1fr]">
            {{-- Bouteille au centre, sous le point commun. data-mouth-x / data-mouth-y = ouverture du goulot (fractions de l'image) --}}
            <img data-bottle data-mouth-x="0.5" data-mouth-y="0.02" src="{{ asset('images/bouteille-quezac.png') }}" alt="Bouteille d'eau minérale pétillante Quézac" width="1024" height="1536" class="order-first mx-auto h-[70svh] max-h-[720px] w-auto drop-shadow-[0_0_45px_rgb(85_136_184/0.65)] lg:order-none lg:col-start-2 lg:row-start-1">
            <div class="max-w-100 lg:col-start-1 lg:row-start-1">
                <img src="{{ asset('images/logo-quezac.png') }}" alt="Quézac" width="395" height="161" class="h-14 w-auto">
                <h2 id="titre-source" class="mt-6 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Une source,<br>une bouteille.</h2>
                <p class="mt-4.5 text-lg leading-normal text-white/85">Captée à la source, l’eau gazeuse de Lozère est mise en bouteille depuis 1901. Une fois bue, la bouteille a encore une vie à vivre.</p>
            </div>
            <div class="max-w-90 lg:col-start-3 lg:row-start-1 lg:justify-self-end">
                <blockquote>
                    <p class="font-serif text-3xl leading-tight font-bold tracking-[-0.015em]">« Un patrimoine à boire, un avenir à bâtir. »</p>
                </blockquote>
                <p class="mt-4 leading-normal text-white/80">Vide, la bouteille ne finit pas sa route : elle part au recyclage.</p>
                <p class="mt-6.5"><a href="#recyclage" class="inline-flex min-h-13 items-center rounded-full bg-zip px-6.5 font-semibold text-nuit hover:opacity-90">Suivre la bouteille ↓</a></p>
            </div>
        </div>
    </section>

    {{-- ============ 05 — RECYCLAGE ============ --}}
    <section id="recyclage" aria-labelledby="titre-recyclage" class="relative overflow-hidden bg-white py-30 text-nuit">
        <div class="mx-auto max-w-7xl px-8">
            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(420px,100%),1fr))] items-end gap-14">
                <div>
                    <h2 id="titre-recyclage" class="text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">De la bouteille au fil.</h2>
                    <p class="mt-5 max-w-140 text-xl leading-normal text-nuit/80">Les bouteilles Quézac vides sont collectées, puis transformées en fil de polyester recyclé. Ce fil devient le tissu de la veste RE·SOURCE.</p>
                </div>

                {{-- Chiffre clé --}}
                <div class="rounded-[28px] bg-nuit p-9 text-white">
                    <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">Il faut environ</p>
                    <p class="mt-3 flex items-baseline gap-4 font-serif font-bold"><span class="text-[96px] leading-none text-zip">12</span><span class="text-3xl leading-tight">bouteilles<br>pour 1 veste</span></p>
                    <p class="mt-5 text-sm leading-relaxed text-white/75">Estimation : environ 180 g de tissu polyester par veste, environ 18 g de PET par bouteille de 50 cl, et environ 15 % de matière perdue au recyclage. À confirmer avec le fabricant.</p>
                </div>
            </div>

            <ol class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(200px,100%),1fr))] gap-6 border-t border-nuit/15 pt-10">
                <li class="flex flex-col gap-3">
                    <p class="font-serif text-4xl font-bold text-source" aria-hidden="true">1</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Collecte</h3>
                    <p class="leading-normal text-nuit/80">Les bouteilles vides sont collectées et triées.</p>
                </li>
                <li class="flex flex-col gap-3">
                    <p class="font-serif text-4xl font-bold text-source" aria-hidden="true">2</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Broyage</h3>
                    <p class="leading-normal text-nuit/80">Lavées puis broyées, elles deviennent des paillettes de PET.</p>
                </li>
                <li class="flex flex-col gap-3">
                    <p class="font-serif text-4xl font-bold text-source" aria-hidden="true">3</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Filage</h3>
                    <p class="leading-normal text-nuit/80">Fondues et étirées, les paillettes deviennent un fil de polyester recyclé.</p>
                </li>
                <li class="flex flex-col gap-3">
                    <p class="font-serif text-4xl font-bold text-zip" aria-hidden="true">4</p>
                    <h3 class="text-2xl font-bold tracking-[-0.02em]">Tissage</h3>
                    <p class="leading-normal text-nuit/80">Le fil est tissé, rendu déperlant, et devient une veste K-Way.</p>
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

    {{-- ============ PRODUIT ============ --}}
    <section id="collection" aria-labelledby="titre-collection" class="bg-white py-30 text-nuit">
        <div class="mx-auto max-w-7xl px-8">
            <div class="max-w-190">
                <p class="text-xs font-semibold tracking-[.16em] text-cevenol uppercase">La collection</p>
                <h2 id="titre-collection" class="mt-3.5 text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">La veste qui vient de la source.</h2>
                <p class="mt-5 text-xl leading-normal text-nuit/80">RE·SOURCE, une veste K-Way tissée à partir de bouteilles Quézac recyclées.</p>
            </div>

            <div class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(480px,100%),1fr))] items-start gap-14">
                {{-- Visuel : image principale + miniatures (une seule photo pour l'instant : changer data-src / data-alt sur chaque miniature) --}}
                <div data-gallery class="flex flex-col gap-4">
                    <figure class="relative aspect-[3/2] overflow-hidden rounded-[28px] bg-nuit">
                        <img data-gallery-main src="{{ asset('images/veste-resource.jpg') }}" alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" width="1264" height="843" class="size-full object-cover transition-opacity duration-300">
                        <p class="absolute top-6 left-6 inline-flex min-h-9 items-center rounded-full bg-nuit px-4 text-[13px] font-semibold text-white">Édition collaboration</p>
                    </figure>
                    <div class="grid grid-cols-3 gap-4">
                        <button type="button" data-gallery-thumb data-src="{{ asset('images/veste-resource.jpg') }}" data-alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" aria-pressed="true" aria-label="Voir la photo 1" class="aspect-[2/1] cursor-pointer overflow-hidden rounded-2xl border-2 border-transparent bg-nuit opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-resource.jpg') }}" alt="" width="1264" height="843" loading="lazy" class="size-full object-cover">
                        </button>
                        <button type="button" data-gallery-thumb data-src="{{ asset('images/veste-resource.jpg') }}" data-alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" aria-pressed="false" aria-label="Voir la photo 2" class="aspect-[2/1] cursor-pointer overflow-hidden rounded-2xl border-2 border-transparent bg-nuit opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-resource.jpg') }}" alt="" width="1264" height="843" loading="lazy" class="size-full object-cover">
                        </button>
                        <button type="button" data-gallery-thumb data-src="{{ asset('images/veste-resource.jpg') }}" data-alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" aria-pressed="false" aria-label="Voir la photo 3" class="aspect-[2/1] cursor-pointer overflow-hidden rounded-2xl border-2 border-transparent bg-nuit opacity-70 transition hover:opacity-100 aria-pressed:border-nuit aria-pressed:opacity-100">
                            <img src="{{ asset('images/veste-resource.jpg') }}" alt="" width="1264" height="843" loading="lazy" class="size-full object-cover">
                        </button>
                    </div>
                </div>

                {{-- Infos --}}
                <div class="flex flex-col gap-7.5">
                    <div>
                        <img src="{{ asset('images/logo-resource-nuit.png') }}" alt="RE·SOURCE by K-Way × Quézac" width="3640" height="684" class="mb-6 h-14 w-auto">
                        <h3 class="text-[34px] leading-[1.05] font-bold tracking-[-0.02em]">Veste RE·SOURCE<br>K-Way × Quézac</h3>
                        <div class="mt-4.5 flex flex-wrap items-baseline gap-4">
                            <p class="font-serif text-[40px] font-bold">300 €</p>
                            <p class="text-[15px] text-nuit/80">Édition limitée · 50 000 exemplaires</p>
                        </div>
                    </div>

                    <fieldset>
                        <legend class="text-sm font-semibold tracking-[.04em]">Taille : <span data-size-label>M</span></legend>
                        <div data-size-group class="mt-3 flex flex-wrap gap-2.5">
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium transition-colors hover:border-nuit aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">S</button>
                            <button type="button" aria-pressed="true" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium transition-colors hover:border-nuit aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">M</button>
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium transition-colors hover:border-nuit aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">L</button>
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium transition-colors hover:border-nuit aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">XL</button>
                        </div>
                    </fieldset>

                    <button type="button" class="inline-flex min-h-15 cursor-pointer items-center self-start rounded-full bg-zip px-8 text-[17px] font-bold text-nuit hover:opacity-90"><span>Ajouter au panier · Taille <span data-size-label>M</span> · 300 €</span></button>
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

            {{-- Carte du tracé --}}
            <aside class="mt-18 grid grid-cols-[repeat(auto-fit,minmax(min(420px,100%),1fr))] items-center gap-10 rounded-[28px] bg-nuit p-10 text-white">
                <div>
                    <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">Le tracé</p>
                    <h3 class="mt-3 text-[38px] leading-[1.03] font-bold tracking-[-0.025em]">Le GR70, imprimé sur le bras.</h3>
                    <p class="mt-4 text-[17px] leading-relaxed text-white/85">Chaque veste porte la trace GPX du chemin de Stevenson, du Puy-en-Velay à Saint-Jean-du-Gard, à travers la Lozère où naît l’eau de Quézac.</p>
                    <p class="mt-3.5 text-[13px] leading-normal text-white/70">Tracé indicatif dans cette maquette — [importer le fichier GPX officiel].</p>
                </div>
                {{-- carte : Le Puy-en-Velay · Langogne · Le Bleymard · Florac · Saint-Jean-du-Gard · Source Quézac --}}
                <div class="aspect-square w-full rounded-2xl border border-dashed border-source/40" aria-hidden="true"></div>
            </aside>
        </div>
    </section>

    {{-- ============ LE JEU ============ --}}
    <section id="jeu" aria-labelledby="titre-jeu" class="relative overflow-hidden bg-source py-30 text-nuit">
        <div class="pointer-events-none absolute -top-30 -right-30 size-155 rounded-full bg-white/25" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-55 -left-40 size-140 rounded-full bg-nuit/10" aria-hidden="true"></div>
        <div class="relative mx-auto grid max-w-7xl grid-cols-[repeat(auto-fit,minmax(min(460px,100%),1fr))] items-center gap-14 px-8">
            <div>
                <p class="inline-flex min-h-9 items-center rounded-full bg-nuit px-4 text-[13px] font-semibold tracking-[.08em] text-white uppercase">Jeu concours · du [DATE] au [DATE]</p>
                <h2 id="titre-jeu" class="mt-5.5 text-[clamp(44px,6vw,86px)] leading-[0.94] font-bold tracking-[-0.035em]">Remonte à<br>la source.</h2>
                <p class="mt-6 max-w-130 text-xl leading-normal">Tente de gagner ta veste RE·SOURCE K-Way × Quézac. [N] vestes à gagner, [LOT SUPPLÉMENTAIRE À DÉFINIR].</p>
                <ol class="mt-8 flex max-w-130 flex-col gap-3.5 text-[17px] font-semibold">
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">1</span>Renseigne ton e-mail</li>
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">2</span>Valide le règlement</li>
                    <li class="flex items-center gap-4"><span class="flex size-11 flex-none items-center justify-center rounded-full bg-nuit font-serif text-lg font-bold text-white" aria-hidden="true">3</span>Attends le tirage au sort</li>
                </ol>
            </div>

            <div class="rounded-[28px] bg-nuit p-9 text-white shadow-[0_30px_60px_rgb(4_9_40/0.35)]">
                <h3 class="max-w-75 text-[32px] leading-[1.05] font-bold tracking-[-0.02em]">Je tente ma chance</h3>
                <form class="mt-6.5 flex flex-col gap-4.5">
                    <div class="flex flex-col gap-2">
                        <label for="jeu-email" class="text-sm font-semibold">Adresse e-mail</label>
                        <input id="jeu-email" type="email" placeholder="prenom@exemple.fr" class="min-h-13 w-full rounded-[14px] border-[1.5px] border-white/35 bg-white/10 px-4.5 font-medium placeholder:text-white/70 focus:border-source focus:outline-none">
                    </div>
                    <div class="flex items-start gap-3">
                        <input id="jeu-reglement" type="checkbox" class="mt-0.5 size-5.5 flex-none accent-zip">
                        <label for="jeu-reglement" class="text-sm leading-normal text-white/85">J’ai lu et j’accepte le règlement du jeu [LIEN VERS LE RÈGLEMENT].</label>
                    </div>
                    <button type="submit" class="inline-flex min-h-14.5 cursor-pointer items-center justify-center rounded-full bg-zip text-[17px] font-bold text-nuit hover:opacity-90">Participer au jeu</button>
                </form>
            </div>
        </div>
    </section>

</x-app-layout>
