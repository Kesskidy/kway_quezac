<x-app-layout>
    <x-slot:title>@yield('title') — RE·SOURCE K-Way × Quézac</x-slot:title>

    <article class="mx-auto max-w-3xl px-8 pt-36 pb-24 leading-relaxed text-white/90
                    [&_h2]:mt-12 [&_h2]:mb-4 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-white
                    [&_p]:mb-4 [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:text-source [&_a]:underline">
        <h1 class="font-serif text-4xl font-bold text-white">@yield('title')</h1>

        <p class="mt-8 rounded-2xl border border-zip/60 p-5 text-white">
            Projet étudiant, sans lien avec les marques K-Way et Quézac.
            Aucun lot n’est réellement attribué et aucune vente n’est réalisée.
        </p>

        @yield('content')

        <nav class="mt-16 flex flex-wrap gap-6 text-sm">
            <a href="{{ route('landing') }}">Retour au site</a>
            <a href="{{ route('legal.rules') }}">Règlement du jeu</a>
            <a href="{{ route('legal.notice') }}">Mentions légales</a>
            <a href="{{ route('legal.privacy') }}">Confidentialité</a>
        </nav>
    </article>
</x-app-layout>
