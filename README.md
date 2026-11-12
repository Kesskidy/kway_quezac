# RE·SOURCE — K-Way × Quézac « Du ciel à la source »

> **Projet étudiant fictif**, sans lien avec les marques K-Way et Quézac. Aucun lot n'est réellement attribué et aucune vente n'est réalisée.

Site vitrine d'une collaboration imaginaire entre K-Way et Quézac, avec un jeu-concours :

1. Le joueur saisit le code trouvé sur sa bouteille (**1 code = 1 tirage**).
2. Le serveur tire le résultat, puis une animation GSAP de 10 secondes le révèle.
3. S'il a gagné, le joueur laisse son prénom et son e-mail : il obtient une participation au **tirage final des lots**.
4. La commande `php artisan lottery:draw` désigne les gagnants des 3 lots (un seul lot par adresse e-mail).

Le site présente aussi la veste RE·SOURCE, avec une précommande fictive (sans paiement).

- **Back-end (Laravel)** : Mathéo
- **Front-end (Blade, Tailwind, GSAP)** : Clément

Le contrat entre le back et le front (routes, formats JSON, tables, règles du jeu) est dans [`regle.md`](regle.md). Le suivi des tickets est dans [`ticket.md`](ticket.md).

---

## Prérequis

- PHP 8.3 ou plus, avec l'extension SQLite
- Composer 2
- Node.js 22 et npm

La base de données est un fichier SQLite (`database/database.sqlite`) : il n'y a pas de serveur de base à installer.

## Installation

```bash
composer setup
```

Cette commande installe les dépendances PHP et JS, crée le fichier `.env`, génère la clé de l'application, crée la base et construit le front.

Ouvre ensuite `.env` et renseigne le compte administrateur (il n'a **aucune valeur par défaut**, et `.env` ne doit jamais être commité) :

```dotenv
APP_NAME="K-Way × Quézac"
ADMIN_EMAIL=ton-adresse@example.com
ADMIN_PASSWORD=un-mot-de-passe-solide   # 8 caractères minimum
```

Puis remplis la base avec les données de démonstration :

```bash
php artisan db:seed
```

La commande affiche **20 codes de démonstration** à saisir dans le jeu. En environnement `local`, elle ajoute aussi 8 participations fictives pour montrer l'administration et le tirage final.

## Lancer le site

```bash
composer dev
```

Le site est alors sur http://127.0.0.1:8000. Le front est recompilé à chaque modification.

---

## Scénario de démonstration

| Étape | Où | Ce qu'on montre |
|---|---|---|
| 1. Jouer | Section « Le jeu » de la page d'accueil | Saisir un des codes affichés par `db:seed`. Le résultat est tiré par le serveur et l'animation le révèle. |
| 2. Rejouer le même code | Même section | Le code est refusé : **1 code = 1 tirage**. |
| 3. Participer | Formulaire affiché après une partie gagnée | Prénom, e-mail et consentement. Un e-mail de confirmation est envoyé. |
| 4. Administrer | http://127.0.0.1:8000/admin/participations | Identifiants `ADMIN_EMAIL` / `ADMIN_PASSWORD`. Liste des participations et export CSV. |
| 5. Tirer les lots | Terminal : `php artisan lottery:draw` | 3 gagnants, un seul lot par adresse e-mail. Les gagnants reçoivent un e-mail. Un second tirage est refusé. |
| 6. Lire les e-mails | `storage/logs/laravel.log` | En local (`MAIL_MAILER=log`), les e-mails ne partent pas : ils sont écrits dans ce fichier. |
| 7. Pages légales | `/reglement`, `/mentions-legales`, `/confidentialite` | Dates du jeu, lots, règle des codes et usage des données. |

**Astuces pour la démo :**

- Pour gagner à coup sûr, mets temporairement `'win_probability' => 100` dans [`config/game.php`](config/game.php) (30 par défaut). Mets `0` pour montrer une partie perdue.
- Pour tout remettre à zéro (nouveaux codes, tirage final non effectué) :
  ```bash
  php artisan migrate:fresh --seed
  ```
- Les dates du jeu, le contact et les auteurs affichés sur les pages légales sont dans [`config/legal.php`](config/legal.php).

---

## API du jeu

Détail complet dans [`regle.md`](regle.md), sections 3 et 4. Toutes les requêtes envoient `Accept: application/json`.

| Méthode | URL | Rôle | Erreurs |
|---|---|---|---|
| POST | `/api/game-sessions` | Consomme le code et tire le résultat : `{ "data": { "token", "won", "scenario" } }` | `422` code invalide, `409` déjà utilisé, `429` trop d'essais |
| POST | `/api/game-sessions/{token}/participation` | Enregistre une participation gagnante | `403` partie perdue, `409` déjà enregistrée, `422` champs invalides |

## Tests

```bash
composer test
```

Les tests couvrent notamment le code utilisé deux fois, le tirage avec une probabilité de 0 % et de 100 %, la participation refusée si la partie est perdue ou en double, `lottery:draw` lancé deux fois, l'administration, la précommande, les e-mails et un parcours complet, du code au lot.

## Organisation du code

```
app/Services/Game/GameDraw.php          Tirage du résultat à la saisie du code
app/Http/Controllers/Api/               API du jeu (code, participation)
app/Http/Controllers/Admin/             Administration des participations
app/Console/Commands/DrawLottery.php    php artisan lottery:draw
app/Mail/                               E-mails ParticipationConfirmed et LotteryWon
config/game.php                         Probabilité de gain, scénarios d'animation, lots
database/seeders/                       Données de démonstration
resources/views/                        Pages Blade (front), pages légales, administration, e-mails
resources/js/                           Animations GSAP
```

## Sécurité et données

- `.env` reste hors de Git : il contient la clé de l'application et le mot de passe administrateur.
- Les codes de démonstration sont générés au hasard à chaque `db:seed` : aucun vrai code n'est stocké dans le dépôt.
- `auth.basic` transmet le mot de passe à chaque requête : en ligne, le site doit être servi en HTTPS.
