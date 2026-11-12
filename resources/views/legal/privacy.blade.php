@extends('legal.layout')

@section('title', 'Politique de confidentialité')

@section('content')
    <h2>Données collectées</h2>
    <ul>
        <li><strong>Participation au tirage des lots</strong> (seulement après un tirage gagnant) : prénom, adresse e-mail, date du consentement et code utilisé.</li>
        <li><strong>Précommande</strong> : prénom, adresse e-mail, produit et taille choisis.</li>
        <li><strong>Saisie d’un code</strong> : le code et le résultat du tirage. Aucune donnée personnelle n’est demandée à cette étape.</li>
    </ul>

    <h2>À quoi elles servent</h2>
    <ul>
        <li>Organiser le tirage final des lots et contacter les gagnants par e-mail.</li>
        <li>Confirmer une participation par e-mail.</li>
        <li>Enregistrer une précommande fictive.</li>
    </ul>
    <p>
        Les données de participation sont utilisées avec ton <strong>consentement</strong>, donné en cochant la case
        « J’accepte le règlement du jeu et l’utilisation de mon e-mail pour être contacté(e) si je gagne. »
    </p>

    <h2>Qui y a accès</h2>
    <p>Seule l’équipe du projet y a accès, depuis une page d’administration protégée par mot de passe. Les données ne sont ni vendues ni transmises.</p>

    <h2>Combien de temps elles sont gardées</h2>
    <p>Les données sont supprimées au plus tard {{ config('legal.data_retention_months') }} mois après le tirage final des lots.</p>

    <h2>Cookies</h2>
    <p>Le site utilise uniquement les cookies techniques nécessaires à son fonctionnement (session et protection des formulaires). Il n’utilise aucun cookie publicitaire ni de mesure d’audience.</p>

    <h2>Tes droits</h2>
    <p>
        Tu peux demander à consulter, corriger ou supprimer tes données, ou retirer ton consentement, en écrivant à
        <a href="mailto:{{ config('legal.contact_email') }}">{{ config('legal.contact_email') }}</a>.
    </p>
@endsection
