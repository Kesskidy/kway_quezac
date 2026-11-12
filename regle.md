# CONTRAT.md — K-Way × Quézac « Du ciel à la source »

Ce fichier est la **source de vérité** entre le back (Mathéo) et le front (Clément).
Toute IA ou personne qui code sur ce projet **lit ce fichier avant de modifier le code** et respecte exactement ses noms, ses formats et ses règles.
Si un nom ou un format doit changer : on modifie ce fichier **d'abord**, on commit, on prévient l'autre.

Projet étudiant fictif, sans lien avec les marques K-Way et Quézac.

---

## 0. Principe du jeu (version 2)

Le jeu n'est **plus un jeu d'adresse**. C'est une **animation de 10 secondes** qui joue le rôle d'une roue :

1. Le joueur saisit le code trouvé sur sa bouteille.
2. Dans la même requête, le serveur consomme le code, **tire le résultat en PHP** et l'enregistre. **1 code = 1 tirage.**
3. Le front joue l'animation : le randonneur traverse la pluie, sa bouteille ouverte sur le dos.
4. À la fin, l'animation révèle le résultat déjà tiré. Rien de ce que fait le joueur ne le modifie.
5. S'il a gagné, le joueur laisse son prénom et son e-mail : il obtient **une participation au tirage final des lots**.
6. Le tirage final désigne 3 gagnants parmi toutes les participations (commande `lottery:draw`).

Il n'y a plus de moteur de jeu, de parcours, de ticks ni de roue côté serveur.

---

## 1. Conventions de nommage

| Élément | Convention | Exemple |
|---|---|---|
| Langue du code | Anglais | `GameSession`, `firstName` |
| Langue des textes affichés | Français | « Presque ! » |
| Classes PHP | PascalCase, PSR-12 | `GameDraw`, `StoreParticipationRequest` |
| Méthodes et variables PHP | camelCase | `draw()`, `$winProbability` |
| Modèles Eloquent | Singulier | `Participation` |
| Tables | Pluriel, snake_case | `participations` |
| Colonnes | snake_case | `consented_at` |
| Clés JSON (requêtes et réponses) | snake_case | `first_name` |
| Noms de routes | Notation pointée | `game-sessions.participation` |
| URL | kebab-case, au pluriel | `/api/game-sessions` |
| Commandes Artisan | `groupe:action` | `lottery:draw` |
| Variables JS | camelCase | `const firstName = data.first_name` |
| Fichiers JS | kebab-case | `game-animation.js` |
| `id` et classes CSS | kebab-case | `#game-stage`, `.value-card` |
| Variables CSS | kebab-case, préfixe `--color-`, `--font-` | `--color-sky` |

Le JS lit les clés JSON en snake_case telles quelles, et les convertit en camelCase seulement dans ses propres variables.

---

## 2. Arborescence

```
app/
  Console/Commands/
    DrawLottery.php              # php artisan lottery:draw
  Http/Controllers/
    LandingController.php        # Page d'accueil (Blade)
    OrderController.php          # Précommande (formulaire Blade)
    Admin/ParticipationController.php
    Api/GameSessionController.php
  Http/Requests/
    StoreOrderRequest.php
    StartGameRequest.php
    StoreParticipationRequest.php
  Http/Resources/
    GameSessionResource.php
  Mail/
    ParticipationConfirmed.php
    LotteryWon.php
  Models/
    PromoCode.php  GameSession.php  Participation.php  Product.php  Order.php
  Services/Game/
    GameDraw.php                 # Tirage du résultat à la saisie du code
config/game.php                  # Probabilité, scénarios, lots (section 5)
resources/views/
  layouts/app.blade.php
  landing.blade.php
  partials/  (hero, story, values, product, game, footer)
  legal/     (rules, notice, privacy)
  admin/     (participations)
  mail/      (participation-confirmed, lottery-won)
resources/js/
  app.js                         # Point d'entrée Vite
  landing/  hero-rain.js  drop-story.js  strata.js  reveals.js
  game/     api.js  game-animation.js  game-endings.js
resources/css/app.css            # Variables de la DA (section 9)
routes/web.php                   # Pages Blade, précommande, administration
routes/api.php                   # API du jeu
routes/console.php               # Planification éventuelle
```

**À supprimer (version 1) :** `CourseGenerator.php`, `GameEngine.php`, `GameResult.php`, `FinishGameRequest.php`, les routes `course` et `finish`, et leurs tests (`CourseGeneratorTest`, `GameEngineTest`, `FinishGameTest`).

**Propriétaires :** Mathéo possède `app/`, `config/`, `database/`, `routes/`, les vues `legal/`, `admin/`, `mail/` et les tests. Clément possède `resources/js/`, `resources/css/` et les autres vues de `resources/views/`. Chacun peut lire tout le projet, mais modifie seulement ses fichiers.

---

## 3. Routes

### Pages web (`routes/web.php`)

| Méthode | URL | Nom | Contrôleur | Réponse |
|---|---|---|---|---|
| GET | `/` | `landing` | `LandingController@index` | Vue `landing` |
| POST | `/orders` | `orders.store` | `OrderController@store` | Redirection vers `/#product` avec `session('status')` |
| GET | `/reglement` | `legal.rules` | Vue | Vue `legal.rules` |
| GET | `/mentions-legales` | `legal.notice` | Vue | Vue `legal.notice` |
| GET | `/confidentialite` | `legal.privacy` | Vue | Vue `legal.privacy` |
| GET | `/admin/participations` | `admin.participations.index` | `Admin\ParticipationController@index` | Vue `admin.participations` |
| GET | `/admin/participations/export` | `admin.participations.export` | `Admin\ParticipationController@export` | Fichier CSV |

La vue `landing` reçoit `$products` (collection de `Product`).
Les routes `/admin/*` sont protégées par le middleware `auth.basic` (compte administrateur créé par un seeder, mot de passe lu dans `.env` : `ADMIN_EMAIL`, `ADMIN_PASSWORD`).

### API du jeu (`routes/api.php`, préfixe `/api` automatique)

| Méthode | URL | Nom | Middleware |
|---|---|---|---|
| POST | `/api/game-sessions` | `game-sessions.store` | `throttle:10,1` |
| POST | `/api/game-sessions/{gameSession:token}/participation` | `game-sessions.participation` | `throttle:10,1` |

Le token (ULID) dans l'URL sert d'identifiant de partie. Toutes les requêtes envoient `Accept: application/json`.

---

## 4. Formats JSON de l'API

Toutes les réponses réussies sont enveloppées dans `data`, comme une API Resource Laravel.

### 4.1 Valider un code et tirer le résultat — `POST /api/game-sessions`

Requête :
```json
{ "code": "QZK-7F3A-92XC" }
```

Réponse `201` :
```json
{
  "data": {
    "token": "01JC8Z3K4V8Q6R2M5T7W9Y1A3B",
    "won": true,
    "scenario": 2
  }
}
```

- `won` : résultat du tirage, **définitif**.
- `scenario` : numéro de la variante d'animation à jouer, de `0` à `scenarios.won - 1` si `won` vaut `true`, de `0` à `scenarios.lost - 1` sinon (section 5).

Le résultat est connu du front dès la réponse : c'est voulu. Il est déjà enregistré et ne peut plus changer, donc le lire en avance ne permet pas de tricher.

Erreurs : `422` code absent ou invalide, `409` code déjà utilisé, `429` trop d'essais.

### 4.2 Enregistrer une participation — `POST /api/game-sessions/{token}/participation`

Appelée seulement si `won` vaut `true`.

Requête :
```json
{
  "first_name": "Camille",
  "email": "camille@example.com",
  "consent": true
}
```

Réponse `201` :
```json
{
  "data": {
    "message": "C'est noté ! Ta participation au tirage des lots est enregistrée."
  }
}
```

Erreurs : `403` partie perdue, `409` participation déjà enregistrée pour cette partie, `422` champs invalides.

### 4.3 Format des erreurs

Format Laravel standard :
```json
{
  "message": "Ce code a déjà été utilisé.",
  "errors": { "code": ["Ce code a déjà été utilisé."] }
}
```
Le front affiche `message` tel quel. Les messages sont en français.

---

## 5. Configuration (`config/game.php`)

```php
return [
    // Chance de gagner une participation, en pourcentage (0 à 100).
    'win_probability' => 30,

    // Nombre de variantes d'animation pour chaque résultat.
    'scenarios' => [
        'won' => 3,
        'lost' => 3,
    ],

    // Lots du tirage final, dans l'ordre (rang 1 = premier lot).
    'prizes' => [
        1 => 'Une K-Way Le Vrai, un an de Quézac et un séjour en Lozère',
        2 => 'Une K-Way Le Vrai et 3 mois de Quézac',
        3 => 'Un accessoire K-Way et 1 mois de Quézac',
    ],
];
```

### Le tirage (`App\Services\Game\GameDraw`)

- `won = random_int(1, 100) <= win_probability`
- `scenario = random_int(0, scenarios[won ? 'won' : 'lost'] - 1)`
- Le tirage a lieu **dans la même transaction** que la consommation du code (`lockForUpdate()`), avant l'enregistrement de la `GameSession`.
- Le front ne connaît ni `win_probability` ni les lots : ils ne sont jamais envoyés par l'API.

### Le tirage final des lots (`php artisan lottery:draw`)

- Tire au hasard, avec `random_int`, autant de gagnants qu'il y a de lots dans `prizes`, parmi les participations.
- Enregistre `prize_rank` (1, 2, 3) et `drawn_at` sur les participations tirées.
- Refuse de s'exécuter si un tirage a déjà eu lieu (une participation a déjà un `drawn_at`).
- Envoie l'e-mail `LotteryWon` à chaque gagnant.
- Une même personne peut avoir plusieurs participations (une par bouteille gagnante), mais ne peut gagner **qu'un seul lot** : les participations de la même adresse e-mail sont écartées après son tirage.

---

## 6. Validation

### `StartGameRequest`

| Champ | Règles |
|---|---|
| `code` | `required`, `string`, `max:32` |

### `StoreParticipationRequest`

| Champ | Règles |
|---|---|
| `first_name` | `required`, `string`, `max:50` |
| `email` | `required`, `email`, `max:255` |
| `consent` | `accepted` |

Le texte de consentement côté front : « J'accepte le règlement du jeu et l'utilisation de mon e-mail pour être contacté(e) si je gagne. » avec un lien vers `/reglement`.

---

## 7. L'animation (front)

- **Durée :** 10 secondes, en une timeline GSAP.
- **Contenu commun :** le randonneur en K-Way avance sous la pluie, la bouteille ouverte sur le dos, et pose sa main au-dessus pour la protéger. Seule sa main se mouille : la K-Way protège tout le reste.
- **Fins gagnantes** (`won: true`, scénarios `0` à `2`) : la bouteille arrive protégée, les bulles jaillissent, puis le formulaire de participation s'affiche.
- **Fins perdantes** (`won: false`, scénarios `0` à `2`) : le randonneur arrive à la source et le message « Presque ! » s'affiche. La pluie peut entrer dans la bouteille **ouverte**, mais on ne montre **jamais** une K-Way qui prend l'eau ni une bouteille qui fuit.
- **Pas de fausse interactivité :** aucun bouton ne donne l'impression que l'adresse du joueur change le résultat.
- **Bouton « Passer » :** affiche directement la fin. Si `prefers-reduced-motion` est activé, la fin s'affiche sans animation.

---

## 8. Base de données

| Table | Colonnes |
|---|---|
| `promo_codes` | `id`, `code` (string, unique), `used_at` (timestamp, nullable), timestamps |
| `game_sessions` | `id`, `token` (ulid, unique), `promo_code_id` (foreign, unique), `won` (bool), `scenario` (tinyint), timestamps |
| `participations` | `id`, `game_session_id` (foreign, unique), `first_name`, `email`, `consented_at` (timestamp), `prize_rank` (tinyint, nullable), `drawn_at` (timestamp, nullable), timestamps |
| `products` | `id`, `name`, `slug` (unique), `description`, `price_cents` (int), `image_path` (nullable), timestamps |
| `orders` | `id`, `product_id` (foreign), `first_name`, `email`, `size` (nullable), timestamps |

- **Passage à la version 2 :** les migrations déjà fusionnées ne sont pas modifiées. Une nouvelle migration retire de `game_sessions` les colonnes `course`, `started_at`, `finished_at`, `inputs`, `fill_percent`, `duration_ticks`, `wheel_allowed`, `spun_at` et `segment`, rend `won` obligatoire et ajoute `scenario`.
- Un code est consommé dans une **transaction** avec `lockForUpdate()` : un seul code = une seule partie = un seul tirage.
- Les prix sont stockés en **centimes**. La précommande est fictive : **aucun paiement**.
- Les e-mails partent avec `MAIL_MAILER=log` en local : ils s'écrivent dans `storage/logs/laravel.log`.

### Formulaire de précommande (`StoreOrderRequest`)

| Champ | Règles |
|---|---|
| `product_id` | `required`, `exists:products,id` |
| `first_name` | `required`, `string`, `max:50` |
| `email` | `required`, `email`, `max:255` |
| `size` | `nullable`, `in:XS,S,M,L,XL,XXL` |

Formulaire Blade classique avec `@csrf`. Les erreurs s'affichent avec `@error`.

---

## 9. Front : identifiants et styles

### Sections de la landing (dans cet ordre)

| `id` | Partial Blade | Contenu |
|---|---|---|
| `#hero` | `partials/hero` | Nuage, pluie, titre « Du ciel à la source » |
| `#story` | `partials/story` | La goutte, la K-Way, les strates, la bouteille |
| `#values` | `partials/values` | Les 4 valeurs |
| `#product` | `partials/product` | Produit de la collaboration et précommande |
| `#game` | `partials/game` | Saisie du code, animation, résultat, participation |
| `#site-footer` | `partials/footer` | Liens légaux, mention « projet fictif » |

### Éléments animés (`#story`)

`#story-cloud`, `#story-drop`, `#story-kway`, `#story-drops-split` (contient `.story-drop--blue`, `.story-drop--yellow`, `.story-drop--orange`), `#story-strata` (contient `.stratum--calcium`, `.stratum--magnesium`, `.stratum--bicarbonates`), `#story-bottle`.

Texte de la traversée : « l'eau se charge en minéraux ». Ne pas écrire que les roches rendent l'eau pétillante.

### Éléments du jeu (`#game`)

| Élément | Rôle |
|---|---|
| `#game-code-form` | Formulaire de saisie du code (`input[name="code"]`) |
| `#game-error` | Message d'erreur (`role="alert"`) |
| `#game-stage` | Scène de l'animation de 10 secondes |
| `#game-skip-button` | Bouton « Passer » |
| `#game-result` | Message de fin (gagné ou « Presque ! ») |
| `#game-participation-form` | Formulaire de participation (`first_name`, `email`, `consent`), affiché seulement si `won` |
| `#game-participation-status` | Confirmation ou erreur de la participation (`role="status"`) |

### Attributs `data-*`

- `data-animate="reveal"` : élément qui apparaît au défilement.
- `data-api-base="{{ url('/api') }}"` sur `#game` : base des appels API.
- `data-scenario` et `data-outcome` (`won` ou `lost`) sur `#game-stage` : posés par le JS avant de lancer l'animation.

### Variables CSS (`resources/css/app.css`)

```css
:root {
  --color-sky: #5E6B73;
  --color-schist: #2B2D2F;
  --color-limestone: #F3EEE4;
  --color-spring-blue: #1F4E8C;
  --color-zip-blue: #1F5FA8;
  --color-zip-yellow: #F2B705;
  --color-zip-orange: #E8692C;
  --font-heading-quezac: 'Fraunces', serif;
  --font-heading-kway: 'Archivo', sans-serif;
  --font-body: 'Inter', sans-serif;
}
```

---

## 10. Règles communes

- Pas de clé, de mot de passe ni de vrai code dans le dépôt : `.env` reste hors de Git.
- Les données de démonstration passent par des seeders (`PromoCodeSeeder`, `ProductSeeder`, `AdminUserSeeder`).
- Le règlement (`/reglement`) indique les dates du jeu, la règle « 1 code = 1 tirage », les lots, la date du tirage final et l'usage des données personnelles.
- Les textes ne dénigrent aucune des deux marques : la K-Way protège toujours, la bouteille ne fuit jamais.
- Toute modification de ce contrat est commitée seule, avec un message qui commence par `contrat:`.
