<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Participations — Administration K-Way × Quézac</title>
    {{-- Self-contained page: it does not depend on the site's front-end assets. --}}
    <style>
        :root { --ink: #2B2D2F; --muted: #5E6B73; --line: #e2ddd3; --bg: #F3EEE4; --accent: #1F4E8C; --win: #F2B705; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; color: var(--ink); background: var(--bg); }
        main { max-width: 1100px; margin: 0 auto; padding: 32px 16px; }
        h1 { margin: 0 0 4px; font-size: 1.6rem; }
        .muted { color: var(--muted); }
        .stats { display: flex; flex-wrap: wrap; gap: 12px; margin: 24px 0; }
        .stat { background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 12px 16px; min-width: 160px; }
        .stat strong { display: block; font-size: 1.4rem; }
        .actions { margin-bottom: 16px; }
        .button { display: inline-block; background: var(--accent); color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; }
        .table-wrap { overflow-x: auto; background: #fff; border: 1px solid var(--line); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid var(--line); white-space: nowrap; }
        th { background: #faf8f4; font-weight: 600; }
        tr.winner td { background: #fff8de; }
        .badge { background: var(--win); border-radius: 999px; padding: 2px 8px; font-weight: 600; }
        .empty { padding: 32px; text-align: center; }
        .pagination { display: flex; gap: 16px; align-items: center; margin-top: 16px; }
        .pagination a { color: var(--accent); font-weight: 600; }
        footer { margin-top: 32px; font-size: .85rem; }
    </style>
</head>
<body>
<main>
    <h1>Participations au tirage des lots</h1>
    <p class="muted">Administration K-Way × Quézac « Du ciel à la source »</p>

    <section class="stats" aria-label="Résumé">
        <div class="stat"><strong>{{ $total }}</strong> participation(s)</div>
        <div class="stat"><strong>{{ $people }}</strong> adresse(s) e-mail différente(s)</div>
        <div class="stat">
            <strong>{{ $drawnAt ? \Illuminate\Support\Carbon::parse($drawnAt)->format('d/m/Y H:i') : 'Pas encore' }}</strong>
            tirage final des lots
        </div>
    </section>

    <p class="actions">
        <a class="button" href="{{ route('admin.participations.export') }}">Exporter en CSV</a>
    </p>

    @if ($participations->isEmpty())
        <p class="table-wrap empty muted">Aucune participation pour le moment.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Code</th>
                        <th scope="col">Participation le</th>
                        <th scope="col">Lot gagné</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($participations as $participation)
                        <tr @class(['winner' => $participation->prize_rank])>
                            <td>{{ $participation->id }}</td>
                            <td>{{ $participation->first_name }}</td>
                            <td>{{ $participation->email }}</td>
                            <td>{{ $participation->gameSession?->promoCode?->code }}</td>
                            <td>{{ $participation->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($participation->prize_rank)
                                    <span class="badge">Lot {{ $participation->prize_rank }}</span>
                                    {{ $prizes[$participation->prize_rank] ?? '' }}
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Plain pagination: this page does not load the site's CSS. --}}
        @if ($participations->hasPages())
            <nav class="pagination" aria-label="Pagination">
                @if ($participations->onFirstPage())
                    <span class="muted">← Précédent</span>
                @else
                    <a href="{{ $participations->previousPageUrl() }}" rel="prev">← Précédent</a>
                @endif

                <span>Page {{ $participations->currentPage() }} sur {{ $participations->lastPage() }}</span>

                @if ($participations->hasMorePages())
                    <a href="{{ $participations->nextPageUrl() }}" rel="next">Suivant →</a>
                @else
                    <span class="muted">Suivant →</span>
                @endif
            </nav>
        @endif
    @endif

    <footer class="muted">Projet étudiant fictif, sans lien avec les marques K-Way et Quézac.</footer>
</main>
</body>
</html>
