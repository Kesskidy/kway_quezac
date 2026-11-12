# RE·SOURCE — K-Way × Quézac « Du ciel à la source »

> Projet étudiant, sans lien avec les marques K-Way et Quézac.

Site vitrine avec un jeu-concours : le joueur saisit le code de sa bouteille (**1 code = 1 tirage**), le serveur tire le résultat et une animation de 10 secondes le révèle. S'il gagne, il obtient une participation au tirage final des 3 lots (`php artisan lottery:draw`).

- Back-end (Laravel) : Mathéo · Front-end (Blade, GSAP) : Clément
- Contrat back/front : [`regle.md`](regle.md) · Tickets : [`ticket.md`](ticket.md)

## Installation

Prérequis : PHP 8.3, Composer, Node.js 22.

```bash
composer setup
```

Dans `.env`, renseigne le compte administrateur, puis remplis la base :

```dotenv
ADMIN_EMAIL=ton-adresse@example.com
ADMIN_PASSWORD=un-mot-de-passe-solide
```

```bash
php artisan db:seed     # affiche 20 codes de démonstration
composer dev            # http://127.0.0.1:8000
```

## Démonstration

1. Saisir un code dans la section du jeu, puis le ressaisir : il est refusé.
2. Après une partie gagnée, remplir le formulaire de participation.
3. Ouvrir `/admin/participations` (identifiants du `.env`) et exporter en CSV.
4. Lancer `php artisan lottery:draw` : 3 gagnants, un seul lot par e-mail.
5. Les e-mails sont écrits dans `storage/logs/laravel.log`.

Pour gagner à coup sûr, mettre `'win_probability' => 100` dans `config/game.php`. Pour tout remettre à zéro : `php artisan migrate:fresh --seed`.

## Tests

```bash
composer test
```
