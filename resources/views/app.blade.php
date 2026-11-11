<x-app-layout>
    <x-slot:title>RE·SOURCE — La collection K-Way × Quézac</x-slot:title>

    {{-- ============ 01 — CIEL (hero) ============ --}}
    <section id="histoire" aria-labelledby="titre-hero" class="relative flex min-h-svh items-center overflow-hidden bg-nuit pt-28 pb-16">
        <div class="mx-auto w-full max-w-7xl px-8">
            <div class="max-w-160">
                <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">La collection RE·SOURCE</p>
                <h1 id="titre-hero" class="mt-4 text-[clamp(48px,6vw,88px)] leading-[0.95] font-bold tracking-[-0.03em]">Du ciel<br>à la source.</h1>
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

    {{-- ============ 02 — FORÊT / K-WAY ============ --}}
    <section id="foret" aria-labelledby="titre-foret" class="relative overflow-hidden bg-cevenol py-36">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-36 bg-linear-to-b from-nuit to-transparent" aria-hidden="true"></div>
        <div class="relative mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-10 px-8">
            <div class="max-w-110">
                <span class="inline-flex items-center rounded-lg bg-white px-3 py-2">
                    <img src="{{ asset('images/logo-kway.png') }}" alt="K-Way" width="405" height="79" class="h-6 w-auto">
                </span>
                <p class="mt-6 text-xs font-semibold tracking-[.16em] text-white/80 uppercase">02 — La pluie</p>
                <h2 id="titre-foret" class="mt-3.5 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Qu’il pleuve,<br>on avance.</h2>
                <p class="mt-4.5 text-lg leading-normal">Sur les hauts plateaux de Lozère, l’averse ne ralentit personne.</p>
                <p class="mt-4 text-lg leading-normal text-white/85">Depuis 1965, K-Way garde la pluie dehors, pour que rien n’arrête la marche.</p>
            </div>
            <aside class="max-w-85 rounded-2xl border border-white/15 bg-nuit/75 px-5 py-4">
                <p class="text-xs font-semibold tracking-[.16em] text-zip uppercase">Déperlant</p>
                <p class="mt-2 text-[17px] leading-snug">L’eau glisse, la marche continue.</p>
            </aside>
        </div>
    </section>

    {{-- ============ 03 — TERRE ============ --}}
    <section id="terre" aria-labelledby="titre-terre" class="relative overflow-hidden bg-nuit py-30">
        <div class="mx-auto max-w-7xl px-8">
            <div class="max-w-130">
                <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">03 — La terre de Lozère</p>
                <h2 id="titre-terre" class="mt-3.5 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Trois couches, une eau qui prend vie.</h2>
                <p class="mt-4.5 text-lg leading-normal text-white/85">La goutte s’infiltre dans les roches des Cévennes. À chaque couche, elle se charge de minéraux.</p>
            </div>

            <div class="mt-16 flex flex-col gap-10 lg:gap-16">
                <article class="w-full max-w-85 rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5">
                    <p class="text-xs font-semibold tracking-[.16em] text-sauge uppercase">Couche 1 · Mg</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Magnésium</h3>
                    <p class="mt-2 leading-normal text-white/85">La goutte entre dans la roche. Premier passage, premiers minéraux.</p>
                </article>
                <article class="w-full max-w-85 self-end rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5">
                    <p class="text-xs font-semibold tracking-[.16em] text-white/75 uppercase">Couche 2 · Ca</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Calcium</h3>
                    <p class="mt-2 leading-normal text-white/85">Elle ralentit et se charge. La pierre calcaire lui donne sa structure.</p>
                </article>
                <article class="w-full max-w-85 rounded-2xl border border-white/15 bg-nuit/80 px-5.5 py-5">
                    <p class="text-xs font-semibold tracking-[.16em] text-source uppercase">Couche 3 · HCO₃⁻</p>
                    <h3 class="mt-1.5 text-3xl font-bold tracking-[-0.02em]">Bicarbonates</h3>
                    <p class="mt-2 leading-normal text-white/85">Dernier passage : l’eau devient pétillante. Les premières bulles apparaissent.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ 04 — SOURCE / QUÉZAC ============ --}}
    <section id="source" aria-labelledby="titre-source" class="relative overflow-hidden bg-nuit py-30">
        <div class="pointer-events-none absolute top-0 left-1/2 size-200 -translate-x-1/2 rounded-full bg-radial from-source/30 to-transparent to-60%" aria-hidden="true"></div>
        <div class="relative mx-auto flex w-full max-w-7xl flex-wrap items-end justify-between gap-10 px-8">
            <div class="max-w-100">
                <img src="{{ asset('images/logo-quezac.png') }}" alt="Quézac" width="395" height="161" class="h-14 w-auto">
                <p class="mt-6 text-xs font-semibold tracking-[.16em] text-source uppercase">04 — La source</p>
                <h2 id="titre-source" class="mt-3.5 text-[44px] leading-[1.02] font-bold tracking-[-0.025em]">Une source,<br>une bouteille.</h2>
                <p class="mt-4.5 text-lg leading-normal text-white/85">Captée à la source, l’eau gazeuse de Lozère est mise en bouteille depuis 1901. Une fois bue, la bouteille a encore une vie à vivre.</p>
            </div>
            <div class="max-w-90">
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
                    <p class="text-xs font-semibold tracking-[.16em] text-cevenol uppercase">05 — Le recyclage</p>
                    <h2 id="titre-recyclage" class="mt-3.5 text-[clamp(40px,5vw,68px)] leading-[0.98] font-bold tracking-[-0.03em]">De la bouteille au fil.</h2>
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
        </div>
    </section>

    {{-- ============ LE CYCLE ============ --}}
    <section id="cycle" aria-labelledby="titre-cycle" class="relative overflow-hidden bg-cevenol py-30">
        <div class="mx-auto max-w-7xl px-8">
            <div class="max-w-190">
                <p class="text-xs font-semibold tracking-[.16em] text-white/80 uppercase">Le cycle</p>
                <h2 id="titre-cycle" class="mt-3.5 text-[clamp(38px,4.6vw,62px)] leading-[0.98] font-bold tracking-[-0.03em]">L’eau ne s’arrête jamais.</h2>
            </div>
            <ol class="mt-14 grid grid-cols-[repeat(auto-fit,minmax(min(200px,100%),1fr))] gap-7">
                <li class="flex flex-col gap-4">
                    <div class="flex items-center gap-3.5" aria-hidden="true"><span class="size-16 flex-none rounded-full border-[1.5px] border-source bg-source/15"></span><span class="flex-1 border-t-[1.5px] border-dashed border-white/40"></span></div>
                    <h3 class="text-[28px] font-bold tracking-[-0.02em]">Pluie</h3>
                    <p class="leading-normal text-white/85">L’eau tombe du ciel et glisse sur la veste de ceux qui marchent.</p>
                </li>
                <li class="flex flex-col gap-4">
                    <div class="flex items-center gap-3.5" aria-hidden="true"><span class="size-16 flex-none rounded-full border-[1.5px] border-source bg-source/15"></span><span class="flex-1 border-t-[1.5px] border-dashed border-white/40"></span></div>
                    <h3 class="text-[28px] font-bold tracking-[-0.02em]">Eau pétillante</h3>
                    <p class="leading-normal text-white/85">Elle traverse les roches des Cévennes et devient l’eau gazeuse de Quézac.</p>
                </li>
                <li class="flex flex-col gap-4">
                    <div class="flex items-center gap-3.5" aria-hidden="true"><span class="size-16 flex-none rounded-full border-[1.5px] border-source bg-source/15"></span><span class="flex-1 border-t-[1.5px] border-dashed border-white/40"></span></div>
                    <h3 class="text-[28px] font-bold tracking-[-0.02em]">Bouteille</h3>
                    <p class="leading-normal text-white/85">Captée et mise en bouteille à la source, elle se boit partout où l’on marche.</p>
                </li>
                <li class="flex flex-col gap-4">
                    <div class="flex items-center gap-3.5" aria-hidden="true"><span class="size-16 flex-none rounded-full border-[1.5px] border-source bg-source/15"></span><span class="flex-1 border-t-[1.5px] border-dashed border-white/40"></span></div>
                    <h3 class="text-[28px] font-bold tracking-[-0.02em]">Recyclage</h3>
                    <p class="leading-normal text-white/85">Une fois vide, la bouteille est collectée puis filée. Environ 12 bouteilles font une veste.</p>
                </li>
                <li class="flex flex-col gap-4">
                    <div class="flex items-center gap-3.5" aria-hidden="true"><span class="size-16 flex-none rounded-full bg-zip"></span><span class="flex-1 border-t-[1.5px] border-dashed border-white/40"></span></div>
                    <h3 class="text-[28px] font-bold tracking-[-0.02em]">K-Way</h3>
                    <p class="leading-normal text-white/85">Le fil redevient la veste qui, sur les hauts plateaux, a vu glisser la première goutte.</p>
                </li>
            </ol>
            <div class="relative mt-7 ml-8 hidden h-16 rounded-b-[32px] border-[1.5px] border-t-0 border-dashed border-white/50 xl:block">
                <p class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-cevenol px-4.5 text-[13px] font-semibold tracking-[.14em] whitespace-nowrap text-white uppercase">… et la pluie retombe</p>
            </div>
            <p class="mt-10 text-[13px] font-semibold tracking-[.14em] text-white uppercase xl:hidden">↺ … et la pluie retombe</p>
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
                <p class="mt-5 text-xl leading-normal text-nuit/80">RE·SOURCE, une veste K-Way tissée à partir de bouteilles Quézac recyclées, avec le tracé du GR70 imprimé sur le bras.</p>
            </div>

            <div class="mt-16 grid grid-cols-[repeat(auto-fit,minmax(min(480px,100%),1fr))] items-start gap-14">
                {{-- Visuel --}}
                <figure class="relative aspect-[3/2] overflow-hidden rounded-[28px] bg-nuit">
                    <img src="{{ asset('images/veste-resource.jpg') }}" alt="Veste RE·SOURCE K-Way × Quézac posée sur des roches mouillées, avec le tracé du GR70 brodé sur la manche" width="1264" height="843" class="size-full object-cover">
                    <p class="absolute top-6 left-6 inline-flex min-h-9 items-center rounded-full bg-nuit px-4 text-[13px] font-semibold text-white">Édition collaboration</p>
                    <figcaption class="absolute bottom-6 left-6 inline-flex min-h-9 items-center rounded-full bg-white/90 px-4 text-[13px] font-semibold">Tracé GR70 sur le bras</figcaption>
                </figure>

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
                        <legend class="text-sm font-semibold tracking-[.04em]">Taille</legend>
                        <div class="mt-3 flex flex-wrap gap-2.5">
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">S</button>
                            <button type="button" aria-pressed="true" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">M</button>
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">L</button>
                            <button type="button" aria-pressed="false" class="min-h-12 min-w-14 cursor-pointer rounded-xl border-[1.5px] border-nuit/30 bg-white text-[15px] font-medium aria-pressed:border-2 aria-pressed:border-nuit aria-pressed:bg-nuit aria-pressed:font-semibold aria-pressed:text-white">XL</button>
                        </div>
                    </fieldset>

                    <button type="button" class="inline-flex min-h-15 cursor-pointer items-center self-start rounded-full bg-zip px-8 text-[17px] font-bold text-nuit hover:opacity-90">Ajouter au panier · 300 €</button>

                    <ul class="flex flex-col gap-4.5 border-t border-nuit/15 pt-6.5">
                        <li class="flex items-start gap-4">
                            <span class="size-11 flex-none rounded-full bg-source/20" aria-hidden="true"></span>
                            <div>
                                <h4 class="font-sans text-[17px] font-semibold">Environ 12 bouteilles Quézac recyclées</h4>
                                <p class="mt-1 text-[15px] leading-normal text-nuit/80">La bouteille qui a contenu l’eau devient le tissu de la veste. <a href="#recyclage" class="underline underline-offset-2">Voir le recyclage</a></p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="size-11 flex-none rounded-full bg-source/20" aria-hidden="true"></span>
                            <div>
                                <h4 class="font-sans text-[17px] font-semibold">Trace GPX du GR70 imprimée sur le bras</h4>
                                <p class="mt-1 text-[15px] leading-normal text-nuit/80">Du Puy-en-Velay à Saint-Jean-du-Gard, le chemin de Stevenson porté sur soi.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="size-11 flex-none rounded-full bg-source/20" aria-hidden="true"></span>
                            <div>
                                <h4 class="font-sans text-[17px] font-semibold">Étanche, pliable, prête pour la pluie</h4>
                                <p class="mt-1 text-[15px] leading-normal text-nuit/80">Le K-Way avec lequel on brave la pluie, dans une version qui boucle la boucle.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

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
