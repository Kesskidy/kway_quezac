# Tickets

Version 2 : le jeu d'adresse est remplacé par un **tirage au moment de la saisie du code**, révélé par une **animation de 10 secondes**. Les détails sont dans `regle.md`.

**Priorités**

- 🔴 **Haute** : indispensable, bloque d'autres tickets ou le cœur du jeu
- 🟠 **Moyenne** : important pour l'expérience, à faire une fois le cœur en place
- 🟢 **Basse** : finitions, à faire en dernier

**Statuts**

- ✅ **Fait** : fusionné dans `main`
- 🔄 **En cours** : branche ouverte
- ⬜ **À faire**
- 🗑️ **Abandonné** : remplacé par la version 2, le code est à supprimer (ticket M6)

**Points** : 1 = environ 1 h, 2 = une demi-journée courte, 3 = une demi-journée, 5 = une journée.

## Mathéo : back-end Laravel

| # | Ticket | Contenu | Points | Priorité | Statut |
|---|--------|---------|--------|----------|--------|
| M1 | Installer le projet | Créer le projet Laravel et le layout Blade, puis déclarer les polices et les couleurs de la direction artistique en variables CSS | 2 | 🔴 Haute | ✅ Fait |
| M2 | Les paramètres du jeu | Paramètres du parcours et route `course` | — | — | 🗑️ Abandonné |
| M3 | Le moteur de jeu en PHP | Classe `GameEngine` qui rejoue les entrées tick par tick | — | — | 🗑️ Abandonné |
| M4 | Les codes et QR codes | Créer la table `promo_codes`, un seeder qui génère les codes et une page de démonstration des QR codes | 3 | 🟠 Moyenne | ✅ Fait |
| M5 | La saisie du code | Valider le code et l'utiliser une seule fois grâce à une transaction, limiter les essais avec `throttle`, créer une `GameSession` | 3 | 🔴 Haute | ✅ Fait |
| M6 | Le tirage et le nettoyage | Créer `GameDraw` et la nouvelle `config/game.php`. Tirer `won` et `scenario` dans la transaction de M5 et les renvoyer dans `POST /api/game-sessions`. Ajouter la migration qui simplifie `game_sessions`. Supprimer `CourseGenerator`, `GameEngine`, `GameResult`, `FinishGameRequest`, les routes `course` et `finish`, et leurs tests | 3 | 🔴 Haute | ⬜ À faire |
| M7 | La participation | Créer la table et le modèle `Participation`, la route `POST /api/game-sessions/{token}/participation` et `StoreParticipationRequest`. Refuser une partie perdue (403) et une participation en double (409) | 3 | 🔴 Haute | ⬜ À faire |
| M8 | Le tirage final des lots | Créer la commande `php artisan lottery:draw` : tirer un gagnant par lot parmi les participations, et refuser un second tirage. C'est le code qui est à usage unique (un QR code par bouteille) : une même adresse e-mail peut jouer et participer autant de fois qu'elle a de codes gagnants, mais ne peut remporter qu'un seul lot | 3 | 🟠 Moyenne | ⬜ À faire |
| M9 | Les e-mails | Créer les mails `ParticipationConfirmed` et `LotteryWon`, envoyés en local avec `MAIL_MAILER=log` | 2 | 🟢 Basse | ⬜ À faire |
| M10 | L'administration des participations | Créer la page `/admin/participations` protégée par `auth.basic`, avec la liste des participations et un export CSV. Ajouter un `AdminUserSeeder` qui lit le mot de passe dans `.env` | 2 | 🟢 Basse | ⬜ À faire |
| M11 | Le produit et la précommande | Créer les modèles `Product` et `Order`, un seeder, et un formulaire de précommande fictif avec validation, sans paiement réel | 3 | 🟢 Basse | ⬜ À faire |
| M12 | Les tests | Vérifier le code utilisé deux fois, le tirage avec une probabilité de 0 % et de 100 %, la participation refusée si perdu ou en double, et `lottery:draw` lancé deux fois | 2 | 🟠 Moyenne | ⬜ À faire |
| M13 | Les pages légales | Écrire les vues du règlement, des mentions légales et de la confidentialité, avec la mention « projet fictif » | 1 | 🟢 Basse | ⬜ À faire |
| M14 | La démonstration | Préparer les données de démonstration et un README pour lancer le projet | 1 | 🟢 Basse | ⬜ À faire |

**Total : 28 points, dont 20 restants.**

## Clément : front-end et GSAP

| # | Ticket | Contenu | Points | Priorité | Statut |
|---|--------|---------|--------|----------|--------|
| C1 | Installer GSAP | Ajouter GSAP et ScrollTrigger avec Vite | 1 | 🔴 Haute | ✅ Fait |
| C2 | La vue de la landing page | Écrire le HTML et le CSS de toutes les sections (hero, histoire, valeurs, produit, jeu, bas de page) avec les `id` de `regle.md` | 3 | 🔴 Haute | 🔄 En cours |
| C3 | Hero : nuage et pluie | Animer la pluie, avec un effet de profondeur au défilement | 2 | 🟠 Moyenne | ⬜ À faire |
| C4 | La goutte et la K-Way | Épingler la section pendant que la goutte tombe sur la K-Way et se divise en 3 gouttes aux couleurs du zip | 3 | 🟠 Moyenne | ⬜ À faire |
| C5 | La traversée des strates | Faire descendre les 3 gouttes à travers les couches, liées au défilement, jusqu'à la bouteille | 3 | 🟠 Moyenne | ⬜ À faire |
| C6 | L'animation de 10 secondes | Créer la timeline GSAP du randonneur en K-Way qui traverse la pluie en protégeant sa bouteille avec la main | 5 | 🔴 Haute | ⬜ À faire |
| C7 | Les fins selon le scénario | Créer les 3 fins gagnantes (bulles, puis formulaire de participation) et les 3 fins perdantes (« Presque ! »), choisies avec `won` et `scenario` | 3 | 🔴 Haute | ⬜ À faire |
| C8 | Les formulaires et les transitions | Brancher le formulaire de code et celui de participation sur l'API, afficher les erreurs, et enchaîner code, animation et résultat | 2 | 🔴 Haute | ⬜ À faire |
| C9 | Les apparitions | Faire apparaître les valeurs, le produit et le zip séparateur au défilement | 1 | 🟢 Basse | ⬜ À faire |
| C10 | Mobile et accessibilité | Adapter au téléphone, ajouter le bouton « Passer » et afficher la fin sans animation si `prefers-reduced-motion` est activé | 2 | 🟠 Moyenne | ⬜ À faire |

**Total : 25 points, dont 24 restants.**

## Changements par rapport à la version 1

- **Supprimés :** le jeu d'adresse, le moteur de jeu, le parcours, la fin de partie et la roue (anciens M2, M3, M6, M7, C6, C7, C8, C9).
- **Ajoutés :** le tirage à la saisie du code, la participation, le tirage final des lots, les e-mails et l'administration.
- **Déplacés :** les pages légales passent de Clément (ancien C12) à Mathéo (M13), pour équilibrer la charge restante.
- **Renumérotés :** M6 à M14 et C6 à C10 ont un nouveau sens. L'ancienne branche `M6` (fin de partie) est abandonnée.
