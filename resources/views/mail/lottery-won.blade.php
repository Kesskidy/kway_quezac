<x-mail::message>
# Bravo {{ $firstName }}, tu as gagné !

Tu as été tiré au sort lors du tirage final K-Way × Quézac. Tu remportes le **lot n° {{ $prizeRank }}** :

<x-mail::panel>
{{ $prize }}
</x-mail::panel>

Nous te contacterons à cette adresse pour organiser la remise de ton lot.

<x-mail::button :url="$rulesUrl">
Lire le règlement
</x-mail::button>

Du ciel à la source,<br>
L'équipe K-Way × Quézac

<x-mail::subcopy>
Projet étudiant fictif, sans lien avec les marques K-Way et Quézac : aucun lot n'est réellement attribué.
</x-mail::subcopy>
</x-mail::message>
