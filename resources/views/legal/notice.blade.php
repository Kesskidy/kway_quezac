@extends('legal.layout')

@section('title', 'Mentions légales')

@section('content')
    <h2>Éditeur du site</h2>
    <p>
        Ce site est un projet étudiant fictif réalisé par {{ config('legal.authors') }}, dans un cadre pédagogique.
        Il n’a pas de but commercial.
    </p>
    <p>Contact : <a href="mailto:{{ config('legal.contact_email') }}">{{ config('legal.contact_email') }}</a></p>

    <h2>Hébergement</h2>
    <p>Le site est présenté en démonstration et n’est pas hébergé publiquement.</p>

    <h2>Marques</h2>
    <p>
        K-Way et Quézac sont des marques appartenant à leurs titulaires respectifs. Elles sont citées dans le cadre
        d’un exercice de collaboration imaginaire. Ce projet n’est ni réalisé, ni approuvé, ni soutenu par ces marques.
    </p>

    <h2>Contenus</h2>
    <p>
        La veste RE·SOURCE, son prix, la précommande, le jeu et ses lots sont fictifs.
        Aucune vente n’est conclue et aucun paiement n’est demandé.
    </p>

    <h2>Données personnelles</h2>
    <p>Voir la <a href="{{ route('legal.privacy') }}">politique de confidentialité</a>.</p>
@endsection
