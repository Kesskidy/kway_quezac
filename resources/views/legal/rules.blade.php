@extends('legal.layout')

@php
    $date = fn (string $key) => \Illuminate\Support\Carbon::parse(config("legal.$key"))->locale('fr')->isoFormat('D MMMM YYYY');
@endphp

@section('title', 'Règlement du jeu')

@section('content')
    <h2>1. Organisateur</h2>
    <p>
        Le jeu « Du ciel à la source » est organisé dans le cadre d’un projet étudiant fictif
        ({{ config('legal.authors') }}). Il n’est pas organisé par les marques K-Way et Quézac.
    </p>

    <h2>2. Dates du jeu</h2>
    <p>
        Le jeu est ouvert du <strong>{{ $date('game_starts_on') }}</strong> au <strong>{{ $date('game_ends_on') }}</strong> inclus.
        Le <strong>tirage final des lots</strong> a lieu le <strong>{{ $date('final_draw_on') }}</strong>.
    </p>

    <h2>3. Comment participer</h2>
    <ul>
        <li>Chaque bouteille de l’opération porte un QR code et un <strong>code unique</strong>.</li>
        <li>Le joueur saisit son code dans la section « Le jeu » du site. Le résultat est tiré au sort par le serveur au moment de la saisie, puis révélé par une animation de 10 secondes. L’animation ne change pas le résultat.</li>
        <li><strong>1 code = 1 tirage.</strong> Un code ne peut être utilisé qu’une seule fois.</li>
        <li>Un tirage gagnant permet de laisser son prénom et son adresse e-mail pour obtenir <strong>une participation au tirage final des lots</strong>.</li>
        <li>Une même personne peut jouer avec autant de codes qu’elle possède, et obtenir une participation par code gagnant.</li>
    </ul>

    <h2>4. Les lots</h2>
    <p>Le tirage final désigne au hasard {{ count(config('game.prizes')) }} gagnants parmi toutes les participations :</p>
    <ul>
        @foreach (config('game.prizes') as $rank => $prize)
            <li><strong>Lot {{ $rank }} :</strong> {{ $prize }}</li>
        @endforeach
    </ul>
    <p>
        Chaque participation compte une fois au tirage. Une même adresse e-mail ne peut remporter <strong>qu’un seul lot</strong> :
        dès qu’elle est tirée au sort, ses autres participations sont écartées pour les lots suivants.
    </p>
    <p>Les gagnants sont prévenus par e-mail à l’adresse donnée lors de leur participation.</p>

    <h2>5. Données personnelles</h2>
    <p>
        Le prénom et l’adresse e-mail servent uniquement à organiser le tirage final et à contacter les gagnants.
        Ils ne sont ni vendus ni transmis. Le détail figure dans la <a href="{{ route('legal.privacy') }}">politique de confidentialité</a>.
    </p>

    <h2>6. Projet fictif</h2>
    <p>
        Ce jeu est une maquette réalisée dans un cadre pédagogique. Aucun lot n’est réellement attribué
        et aucune somme n’est demandée pour participer.
    </p>
@endsection
