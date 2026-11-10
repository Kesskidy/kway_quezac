# Tickets

**Priorités**

- 🔴 **Haute** : indispensable, bloque d'autres tickets ou le cœur du jeu
- 🟠 **Moyenne** : important pour l'expérience, à faire une fois le cœur en place
- 🟢 **Basse** : finitions, à faire en dernier

## Mathéo : back-end Laravel

| # | Ticket | Contenu | Priorité |
|---|--------|---------|----------|
| M1 | Installer le projet | Créer le projet Laravel et le layout Blade, puis déclarer les polices et les couleurs de la direction artistique en variables CSS | 🔴 Haute |
| M2 | Les paramètres du jeu | Créer `config/jeu.php` et la route `/parcours`, qui génère les phases et les arbres de chaque partie | 🔴 Haute |
| M3 | Le moteur de jeu en PHP | Créer une classe `GameEngine` qui rejoue les entrées tick par tick : pluie, main mouillée qui ralentit, remplissage. Ajouter des tests unitaires | 🔴 Haute |
| M4 | Les codes et QR codes | Créer la table `promo_codes`, un seeder qui génère les codes et une page de démonstration des QR codes | 🟠 Moyenne |
| M5 | La saisie du code | Valider le code et l’utiliser une seule fois grâce à une transaction, limiter les essais avec `throttle`, créer une `GameSession` et noter l’heure de début | 🔴 Haute |
| M6 | La fin de partie | Rejouer les entrées avec le moteur, refuser les entrées incohérentes ou trop rapides, enregistrer, et autoriser la roue sous 10 % | 🔴 Haute |
| M7 | La roue | Faire le tirage en PHP, un seul par session, enregistrer la participation et renvoyer le numéro du segment | 🟠 Moyenne |
| M8 | Le produit et la précommande | Créer les modèles `Product` et `Order`, un seeder, et un formulaire de précommande fictif avec validation, sans paiement réel | 🟢 Basse |
| M9 | Les tests | Vérifier le code utilisé deux fois, la roue refusée au-dessus de 10 %, la roue lancée deux fois et une partie trop rapide | 🟠 Moyenne |
| M10 | La démonstration | Préparer les données de démonstration et un README pour lancer le projet | 🟢 Basse |

## Clément : front-end et GSAP

| # | Ticket | Contenu | Priorité |
|---|--------|---------|----------|
| C1 | Installer GSAP | Ajouter GSAP et ScrollTrigger avec Vite, avec un fichier JS par module | 🔴 Haute |
| C2 | La vue de la landing page | Écrire le HTML et le CSS de toutes les sections (hero, histoire, valeurs, produit, jeu, bas de page) avec les id du ticket 0 | 🔴 Haute |
| C3 | Hero : nuage et pluie | Animer la pluie, avec un effet de profondeur au défilement | 🟠 Moyenne |
| C4 | La goutte et la K-Way | Épingler la section pendant que la goutte tombe sur la K-Way et se divise en 3 gouttes aux couleurs du zip | 🟠 Moyenne |
| C5 | La traversée des strates | Faire descendre les 3 gouttes à travers les couches, liées au défilement, jusqu’à la bouteille | 🟠 Moyenne |
| C6 | Le rendu du jeu | Dessiner le décor, le personnage, la pluie et les arbres à partir du parcours reçu, et noter le tick de chaque appui et relâchement | 🔴 Haute |
| C7 | La main et la jauge | Afficher la main sur la bouteille, le ralentissement et la jauge en direct, avec les mêmes règles que le moteur | 🔴 Haute |
| C8 | L’écran de fin | Envoyer les entrées, afficher le résultat calculé par le serveur, puis le bouton de la roue ou « Réessaie » | 🔴 Haute |
| C9 | La roue | Animer la roue avec GSAP jusqu’au segment renvoyé par le serveur | 🟠 Moyenne |
| C10 | Les apparitions | Faire apparaître les valeurs, le produit et le zip séparateur au défilement | 🟢 Basse |
| C11 | Mobile et accessibilité | Ajouter le contrôle tactile, adapter au téléphone et alléger les animations si `prefers-reduced-motion` est activé | 🟠 Moyenne |
| C12 | Pages légales | Écrire les vues Blade du règlement, des mentions légales et de la confidentialité, avec la mention « projet fictif » | 🟢 Basse |
