# CONTRAT.md — K-Way × Quézac « Du ciel à la source »

Ce fichier est la **source de vérité** entre le back (Mathéo) et le front (Clément).
Toute IA ou personne qui code sur ce projet **lit ce fichier avant de modifier le code** et respecte exactement ses noms, ses formats et ses règles.
Si un nom ou un format doit changer : on modifie ce fichier **d'abord**, on commit, on prévient l'autre.

Projet étudiant fictif, sans lien avec les marques K-Way et Quézac.

---

## 1. Conventions de nommage

| Élément | Convention | Exemple |
|---|---|---|
| Langue du code | Anglais | `GameSession`, `fillPercent` |
| Langue des textes affichés | Français | « Tourne la roue ! » |
| Classes PHP | PascalCase, PSR-12 | `GameEngine`, `FinishGameRequest` |
| Méthodes et variables PHP | camelCase | `simulate()`, `$fillPercent` |
| Modèles Eloquent | Singulier | `PromoCode` |
| Tables | Pluriel, snake_case | `promo_codes` |
| Colonnes | snake_case | `started_at` |
| Clés JSON (requêtes et réponses) | snake_case | `fill_percent` |
| Noms de routes | Notation pointée | `game-sessions.finish` |
| URL | kebab-case, au pluriel | `/api/game-sessions` |
| Variables JS | camelCase | `const fillPercent = data.fill_percent` |
| Fichiers JS | kebab-case | `game-renderer.js` |
| `id` et classes CSS | kebab-case | `#game-canvas`, `.value-card` |
| Variables CSS | kebab-case, préfixe `--color-`, `--font-` | `--color-sky` |

Le JS lit les clés JSON en snake_case telles quelles, et les convertit en camelCase seulement dans ses propres variables.

---

## 2. Arborescence

```
app/
  Http/Controllers/
    LandingController.php        # Page d'accueil (Blade)
    OrderController.php          # Précommande (formulaire Blade)
    Api/GameSessionController.php
  Http/Requests/
    StoreOrderRequest.php
    StartGameRequest.php
    FinishGameRequest.php
  Http/Resources/
    GameSessionResource.php
  Models/
    PromoCode.php  GameSession.php  Product.php  Order.php
  Services/Game/
    CourseGenerator.php          # Génère le parcours d'une partie
    GameEngine.php               # Moteur : rejoue les entrées (autorité)
    WheelSpinner.php             # Tirage de la roue
config/game.php                  # Toutes les règles du jeu (section 5)
resources/views/
  layouts/app.blade.php
  landing.blade.php
  partials/  (hero, story, values, product, game, footer)
  legal/     (rules, notice, privacy)
resources/js/
  app.js                         # Point d'entrée Vite
  landing/  hero-rain.js  drop-story.js  strata.js  reveals.js
  game/     api.js  game-renderer.js  game-loop.js  wheel.js
resources/css/app.css            # Variables de la DA (section 9)
routes/web.php                   # Pages Blade et précommande
routes/api.php                   # API du jeu (php artisan install:api)
```

**Propriétaires :** Mathéo possède `app/`, `config/`, `database/`, `routes/` et les tests. Clément possède `resources/js/`, `resources/css/` et `resources/views/`. Chacun peut lire tout le projet, mais modifie seulement ses fichiers.

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

La vue `landing` reçoit `$products` (collection de `Product`).

### API du jeu (`routes/api.php`, préfixe `/api` automatique)

| Méthode | URL | Nom | Middleware |
|---|---|---|---|
| POST | `/api/game-sessions` | `game-sessions.store` | `throttle:10,1` |
| GET | `/api/game-sessions/{gameSession:token}/course` | `game-sessions.course` | `throttle:30,1` |
| POST | `/api/game-sessions/{gameSession:token}/finish` | `game-sessions.finish` | `throttle:10,1` |
| POST | `/api/game-sessions/{gameSession:token}/spin` | `game-sessions.spin` | `throttle:10,1` |

Le token (ULID) dans l'URL sert d'identifiant de partie. Toutes les requêtes envoient `Accept: application/json`.

---

## 4. Formats JSON de l'API

Toutes les réponses réussies sont enveloppées dans `data`, comme une API Resource Laravel.

### 4.1 Démarrer une partie — `POST /api/game-sessions`

Requête :
```json
{ "code": "QZK-7F3A-92XC" }
```

Réponse `201` :
```json
{ "data": { "token": "01JC8Z3K4V8Q6R2M5T7W9Y1A3B" } }
```

Erreurs : `422` code absent ou invalide, `409` code déjà utilisé, `429` trop d'essais.

### 4.2 Récupérer le parcours — `GET /api/game-sessions/{token}/course`

Le serveur enregistre `started_at` au **premier** appel. Le front lance la partie dès la réception.

Réponse `200` :
```json
{
  "data": {
    "phases": [
      { "type": "dry",  "start_tick": 0,   "end_tick": 200 },
      { "type": "rain", "start_tick": 200, "end_tick": 380 },
      { "type": "dry",  "start_tick": 380, "end_tick": 560 }
    ],
    "trees": [
      { "start": 900,  "end": 1050 },
      { "start": 2400, "end": 2550 }
    ],
    "rules": {
      "ticks_per_second": 20,
      "course_length": 6000,
      "speed": 3,
      "speed_hand_wet": 2,
      "bottle_capacity": 1000,
      "rain_per_tick": 2,
      "max_fill_percent_for_wheel": 9,
      "max_ticks": 4000
    }
  }
}
```

Erreurs : `404` token inconnu, `409` partie déjà terminée.

### 4.3 Terminer une partie — `POST /api/game-sessions/{token}/finish`

Le front envoie **uniquement les entrées du joueur**, jamais un score.

Requête :
```json
{
  "inputs": [
    { "tick": 205, "action": "cover" },
    { "tick": 310, "action": "uncover" }
  ]
}
```

Réponse `200` (valeurs **calculées par le serveur**) :
```json
{
  "data": {
    "fill_percent": 4,
    "duration_ticks": 2140,
    "wheel_allowed": true
  }
}
```

Erreurs : `422` entrées invalides (section 6), `409` partie déjà terminée, `422` partie terminée trop vite par rapport au temps réel.

### 4.4 Tourner la roue — `POST /api/game-sessions/{token}/spin`

Requête : corps vide.

Réponse `200` :
```json
{
  "data": {
    "segment": 3,
    "won": true,
    "label": "Une participation au tirage"
  }
}
```

Erreurs : `403` roue non autorisée (remplissage ≥ 10 %), `409` roue déjà tournée.

### 4.5 Format des erreurs

Format Laravel standard :
```json
{
  "message": "Ce code a déjà été utilisé.",
  "errors": { "code": ["Ce code a déjà été utilisé."] }
}
```
Le front affiche `message` tel quel. Les messages sont en français.

---

## 5. Règles du jeu (`config/game.php`)

Les valeurs vivent **uniquement** dans `config/game.php`. Le serveur les envoie dans `rules` (section 4.2). Le JS ne code **aucune** valeur en dur.

```php
return [
    'ticks_per_second' => 20,
    'course_length' => 6000,
    'speed' => 3,
    'speed_hand_wet' => 2,
    'bottle_capacity' => 1000,
    'rain_per_tick' => 2,
    'max_fill_percent_for_wheel' => 9,
    'max_ticks' => 4000,
    'max_inputs' => 600,
    'real_time_tolerance' => 0.9,
];
```

Ces valeurs sont un point de départ, à ajuster après les premiers tests.

### Simulation, tick par tick (identique en PHP et en JS)

Tous les calculs se font en **nombres entiers**. Au départ : `tick = 0`, `position = 0`, `fill = 0`, `covered = false`.

Pour chaque tick, **dans cet ordre** :
1. Appliquer l'entrée dont le `tick` est égal au tick courant : `cover` → `covered = true`, `uncover` → `covered = false`.
2. `raining` = une phase `rain` vérifie `start_tick <= tick < end_tick`. Après la dernière phase, il fait sec.
3. `sheltered` = un arbre vérifie `start <= position < end`.
4. `wet = raining && !sheltered`.
5. Vitesse : si `wet && covered`, `speed_hand_wet`, sinon `speed`. (La main est la seule partie mouillée : la K-Way protège tout le reste.)
6. Remplissage : si `wet && !covered`, `fill = min(fill + rain_per_tick, bottle_capacity)`.
7. `position = position + vitesse`.
8. `tick = tick + 1`.

Arrêt quand `position >= course_length` ou `tick >= max_ticks`.

Résultats :
- `duration_ticks = tick`
- `fill_percent = intdiv(fill * 100, bottle_capacity)`
- `wheel_allowed = fill_percent <= max_fill_percent_for_wheel` (soit **moins de 10 %**)

Le résultat du serveur fait foi. Si l'affichage JS diffère, le front affiche la valeur du serveur.

---

## 6. Validation des entrées (`FinishGameRequest`)

- `inputs` : tableau, au plus `max_inputs` éléments (peut être vide).
- `inputs.*.tick` : entier, `0 <= tick < max_ticks`, **strictement croissant**.
- `inputs.*.action` : `cover` ou `uncover`, **en alternance**, en commençant par `cover`.
- Temps réel : `now() - started_at` doit être au moins `duration_ticks / ticks_per_second × real_time_tolerance` secondes. Sinon : `422`.

---

## 7. La roue

8 segments, numérotés de `0` à `7` dans le sens des aiguilles d'une montre, en partant du haut.

| Segment | Libellé | Gagnant |
|---|---|---|
| 0 | Pas cette fois | Non |
| 1 | Une participation au tirage | Oui |
| 2 | Pas cette fois | Non |
| 3 | Une participation au tirage | Oui |
| 4 | Pas cette fois | Non |
| 5 | Une participation au tirage | Oui |
| 6 | Pas cette fois | Non |
| 7 | Une participation au tirage | Oui |

Le tirage se fait **en PHP** (`random_int`) dans `WheelSpinner`. Le front anime la roue avec GSAP pour qu'elle s'arrête au centre du segment reçu.

---

## 8. Base de données

| Table | Colonnes |
|---|---|
| `promo_codes` | `id`, `code` (string, unique), `used_at` (timestamp, nullable), timestamps |
| `game_sessions` | `id`, `token` (ulid, unique), `promo_code_id` (foreign, unique), `course` (json), `started_at`, `finished_at`, `inputs` (json, nullable), `fill_percent` (tinyint, nullable), `duration_ticks` (int, nullable), `wheel_allowed` (bool, défaut false), `spun_at`, `segment` (tinyint, nullable), `won` (bool, nullable), timestamps |
| `products` | `id`, `name`, `slug` (unique), `description`, `price_cents` (int), `image_path` (nullable), timestamps |
| `orders` | `id`, `product_id` (foreign), `first_name`, `email`, `size` (nullable), timestamps |

- Un code est consommé dans une **transaction** avec `lockForUpdate()` : un seul code = une seule partie.
- Les prix sont stockés en **centimes**. La précommande est fictive : **aucun paiement**.

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
| `#game` | `partials/game` | Saisie du code, jeu, roue |
| `#site-footer` | `partials/footer` | Liens légaux, mention « projet fictif » |

### Éléments animés (`#story`)

`#story-cloud`, `#story-drop`, `#story-kway`, `#story-drops-split` (contient `.story-drop--blue`, `.story-drop--yellow`, `.story-drop--orange`), `#story-strata` (contient `.stratum--calcium`, `.stratum--magnesium`, `.stratum--bicarbonates`), `#story-bottle`.

Texte de la traversée : « l'eau se charge en minéraux ». Ne pas écrire que les roches rendent l'eau pétillante.

### Éléments du jeu (`#game`)

| Élément | Rôle |
|---|---|
| `#game-code-form` | Formulaire de saisie du code (`input[name="code"]`) |
| `#game-error` | Message d'erreur (`role="alert"`) |
| `#game-canvas` | Canvas du jeu |
| `#game-fill-gauge` | Jauge de remplissage |
| `#game-cover-button` | Bouton maintenu pour couvrir la bouteille (souris, toucher, barre espace) |
| `#game-result` | Écran de fin |
| `#wheel` | La roue |
| `#wheel-spin-button` | Bouton pour tourner la roue |

### Attributs `data-*`

- `data-animate="reveal"` : élément qui apparaît au défilement.
- `data-api-base="{{ url('/api') }}"` sur `#game` : base des appels API.

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
- Les données de démonstration passent par des seeders (`PromoCodeSeeder`, `ProductSeeder`).
- Les textes ne dénigrent aucune des deux marques : la K-Way protège toujours, la bouteille ne fuit jamais.
- Toute modification de ce contrat est commitée seule, avec un message qui commence par `contrat:`.
