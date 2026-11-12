<header data-site-header class="absolute inset-x-0 top-0 z-20 text-white data-open:z-60">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-8 py-6">
        <a href="#histoire" class="relative z-10 block">
            <img src="{{ asset('images/logo-resource-source.png') }}" alt="RE·SOURCE by K-Way × Quézac — accueil" width="3330" height="469" class="h-6 w-auto">
        </a>
        <nav aria-label="Navigation principale" class="hidden md:block">
            <ul class="flex items-center gap-8 text-[15px] font-medium">
                <li><a href="#histoire" class="hover:text-source">L’histoire</a></li>
                <li><a href="#recyclage" class="hover:text-source">Le recyclage</a></li>
                <li><a href="#valeurs" class="hover:text-source">Les valeurs</a></li>
                <li>
                    <a href="#jeu" class="inline-flex min-h-10 items-center gap-2 rounded-full border-[1.5px] border-source px-4 hover:bg-source/20">
                        <span class="size-2 rounded-full bg-zip" aria-hidden="true"></span>Le jeu
                    </a>
                </li>
            </ul>
        </nav>
        <a href="#collection" class="hidden min-h-11 items-center rounded-full border-[1.5px] border-white/60 px-5 text-[15px] font-semibold hover:bg-white/10 md:inline-flex">Acheter la veste</a>

        {{-- Mobile : bouton burger (devient une croix quand le menu est ouvert) --}}
        <button type="button" data-menu-toggle aria-expanded="false" aria-controls="menu-mobile" aria-label="Ouvrir le menu" class="group relative z-10 -mr-2 grid size-11 cursor-pointer place-items-center rounded-full focus-visible:outline-2 focus-visible:outline-source md:hidden">
            <span class="relative block h-3.5 w-6" aria-hidden="true">
                <span class="absolute inset-x-0 top-0 h-0.5 rounded-full bg-current transition duration-300 group-aria-expanded:top-1.5 group-aria-expanded:rotate-45"></span>
                <span class="absolute inset-x-0 top-1.5 h-0.5 rounded-full bg-current transition duration-300 group-aria-expanded:opacity-0"></span>
                <span class="absolute inset-x-0 top-3 h-0.5 rounded-full bg-current transition duration-300 group-aria-expanded:top-1.5 group-aria-expanded:-rotate-45"></span>
            </span>
        </button>
    </div>

    {{-- Mobile : menu plein écran --}}
    <div id="menu-mobile" data-menu inert class="invisible fixed inset-0 flex flex-col overflow-y-auto bg-nuit px-8 pt-28 pb-10 opacity-0 transition-[opacity,visibility] duration-300 data-open:visible data-open:opacity-100 md:hidden">
        <nav aria-label="Navigation mobile">
            <ul class="flex flex-col gap-2 font-serif text-[clamp(32px,9vw,44px)] leading-tight font-bold">
                <li><a href="#histoire" class="flex min-h-14 items-center hover:text-source">L’histoire</a></li>
                <li><a href="#recyclage" class="flex min-h-14 items-center hover:text-source">Le recyclage</a></li>
                <li><a href="#valeurs" class="flex min-h-14 items-center hover:text-source">Les valeurs</a></li>
                <li>
                    <a href="#jeu" class="flex min-h-14 items-center gap-3 hover:text-source">
                        <span class="size-3 rounded-full bg-zip" aria-hidden="true"></span>Le jeu
                    </a>
                </li>
            </ul>
        </nav>
        <a href="#collection" class="mt-auto inline-flex min-h-14 items-center justify-center rounded-full bg-zip px-6 text-[17px] font-bold text-nuit hover:opacity-90">Acheter la veste</a>
    </div>
</header>
