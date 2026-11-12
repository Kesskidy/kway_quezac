<footer class="bg-nuit pt-20 pb-12 text-white">
    <div class="mx-auto max-w-7xl px-8">
        <div class="flex flex-wrap items-start justify-between gap-12">
            <div data-reveal class="max-w-md">
                <a href="{{ route('landing') }}#histoire" class="inline-block">
                    <img src="{{ asset('images/logo-resource-source.png') }}" alt="RE·SOURCE by K-Way × Quézac — accueil" width="3330" height="469" class="h-10 w-auto">
                </a>
                <p class="mt-5 font-serif text-2xl leading-tight font-bold">Eau, bouteille, plastique recyclé, K-Way : un seul cycle.</p>
                <div class="mt-6 flex flex-wrap items-center gap-6">
                    <a href="{{ route('landing') }}#histoire" class="inline-flex items-center rounded-xl bg-white px-4 py-2.5">
                        <img src="{{ asset('images/logo-kway.png') }}" alt="K-Way" width="405" height="79" class="h-8 w-auto">
                    </a>
                    <span class="text-xl text-white/60" aria-hidden="true">×</span>
                    <a href="{{ route('landing') }}#histoire" class="inline-flex items-center">
                        <img src="{{ asset('images/logo-quezac.png') }}" alt="Quézac depuis 1901" width="395" height="161" class="h-14 w-auto">
                    </a>
                </div>
            </div>
            <nav data-reveal aria-label="Pied de page" class="flex flex-wrap gap-16">
                <div>
                    <h2 class="font-sans text-xs font-semibold tracking-[.16em] text-source uppercase">Explorer</h2>
                    <ul class="mt-3.5 flex flex-col text-[15px]">
                        <li><a href="#histoire" class="inline-flex min-h-11 items-center hover:text-source">L’histoire</a></li>
                        <li><a href="#recyclage" class="inline-flex min-h-11 items-center hover:text-source">Le recyclage</a></li>
                        <li><a href="#product" class="inline-flex min-h-11 items-center hover:text-source">La collection</a></li>
                        <li><a href="#valeurs" class="inline-flex min-h-11 items-center hover:text-source">Les valeurs</a></li>
                        <li><a href="#expedition" class="inline-flex min-h-11 items-center hover:text-source">L’expédition</a></li>
                        <li><a href="#game" class="inline-flex min-h-11 items-center hover:text-source">Le jeu</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="font-sans text-xs font-semibold tracking-[.16em] text-source uppercase">Infos</h2>
                    <ul class="mt-3.5 flex flex-col text-[15px]">
                        <li><a href="{{ route('legal.rules') }}" data-rules-modal aria-haspopup="dialog" class="inline-flex min-h-11 items-center hover:text-source">Règlement du jeu</a></li>
                        <li><a href="{{ route('legal.notice') }}" class="inline-flex min-h-11 items-center hover:text-source">Mentions légales</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="inline-flex min-h-11 items-center hover:text-source">Confidentialité</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="mt-14 flex flex-wrap justify-between gap-4 border-t border-white/15 pt-6 text-sm text-white/70">
            <p>Du ciel à la source · Lozère</p>
        </div>
    </div>
</footer>
