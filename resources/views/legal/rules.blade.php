@extends('legal.layout')

@php($date = fn ($key) => \Illuminate\Support\Carbon::parse(config("legal.$key"))->locale('fr')->isoFormat('D MMMM YYYY'))

@section('title', 'Règlement du jeu')

@section('content')
    <h2>1. Dates</h2>
    <p>Le jeu est ouvert du <strong>{{ $date('game_starts_on') }}</strong> au <strong>{{ $date('game_ends_on') }}</strong>. Le tirage final des lots a lieu le <strong>{{ $date('final_draw_on') }}</strong>.</p>

    <h2>2. Comment participer</h2>
    <ul>
        <li>Chaque bouteille porte un QR code et un code unique.</li>
        <li><strong>1 code = 1 tirage</strong> : un code ne peut être utilisé qu’une seule fois.</li>
        <li>Le résultat est tiré au moment de la saisie du code, puis révélé par une animation de 10 secondes.</li>
        <li>Un tirage gagnant permet de laisser son prénom et son e-mail pour obtenir une participation au tirage final.</li>
    </ul>

    <h2>3. Les lots</h2>
    <ul>
        @foreach (config('game.prizes') as $rank => $prize)
            <li><strong>Lot {{ $rank }} :</strong> {{ $prize }}</li>
        @endforeach
    </ul>
    <p>Une même personne peut avoir plusieurs participations, mais ne peut remporter qu’un seul lot. Les gagnants sont prévenus par e-mail.</p>

    <h2>4. Données personnelles</h2>
    <p>Le prénom et l’e-mail servent uniquement au tirage final et à contacter les gagnants. Voir la <a href="{{ route('legal.privacy') }}">politique de confidentialité</a>.</p>
@endsection
