{{--
    Shared frame of the legal pages (regle.md, sections 3 and 10): title, fictitious-project
    notice, content and links. Uses the site layout <x-app-layout> without changing it.
--}}
<x-app-layout>
    <x-slot:title>@yield('title') — RE·SOURCE K-Way × Quézac</x-slot:title>

    <article class="mx-auto max-w-3xl px-8 pt-36 pb-24 leading-relaxed text-white/90
                    [&_h2]:mt-12 [&_h2]:mb-4 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-white
                    [&_p]:mb-4 [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-6 [&_li]:mb-1.5
                    [&_a]:text-source [&_a]:underline [&_a]:underline-offset-2 [&_strong]:text-white">
        <h1 class="font-serif text-4xl leading-tight font-bold text-white md:text-5xl">@yield('title')</h1>

        <p role="note" class="mt-8 rounded-2xl border border-zip/60 bg-white/5 p-5 text-white">
            <strong>Projet fictif.</strong> Ce site est un projet étudiant, sans lien avec les marques K-Way et Quézac.
            Aucun lot n’est réellement attribué et aucune vente n’est réalisée.
        </p>

        @yield('content')

        <nav aria-label="Pages légales" class="mt-16 flex flex-wrap gap-6 border-t border-white/15 pt-6 text-sm">
            <a href="{{ route('landing') }}">Retour au site</a>
            <a href="{{ route('legal.rules') }}">Règlement du jeu</a>
            <a href="{{ route('legal.notice') }}">Mentions légales</a>
            <a href="{{ route('legal.privacy') }}">Confidentialité</a>
        </nav>
    </article>
</x-app-layout>
