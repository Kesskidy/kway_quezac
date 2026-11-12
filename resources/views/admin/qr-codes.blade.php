<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>QR codes — Administration</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 24px; color: #0b1533; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 16px; padding: 0; list-style: none; }
        .card { border: 1px solid #ccd; border-radius: 12px; padding: 12px; text-align: center; break-inside: avoid; }
        .card img { width: 100%; height: auto; display: block; }
        .code { font-family: ui-monospace, monospace; font-weight: 700; letter-spacing: .05em; margin: 8px 0 4px; }
        .used { opacity: .45; }
        .badge { font-size: 12px; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <h1>QR codes des bouteilles</h1>

    <p class="no-print">
        Chaque QR code ouvre le jeu avec son code déjà saisi.
        {{ $codes->count() }} code(s), dont {{ $codes->where('used', true)->count() }} déjà utilisé(s).
        <a href="{{ route('admin.participations.index') }}">Participations</a> ·
        <button type="button" onclick="window.print()">Imprimer</button>
    </p>

    @if ($codes->isEmpty())
        <p>Aucun code pour le moment. Lance <code>php artisan db:seed --class=PromoCodeSeeder</code>.</p>
    @else
        <ul class="grid">
            @foreach ($codes as $item)
                <li class="card {{ $item['used'] ? 'used' : '' }}">
                    <a href="{{ $item['url'] }}"><img src="{{ $item['image'] }}" alt="QR code du code {{ $item['code'] }}" width="200" height="200"></a>
                    <p class="code">{{ $item['code'] }}</p>
                    <p class="badge">{{ $item['used'] ? 'Déjà utilisé' : 'Disponible' }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
