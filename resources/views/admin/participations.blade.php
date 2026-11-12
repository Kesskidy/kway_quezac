<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>Participations — Administration</title>
</head>
<body>
    <h1>Participations au tirage des lots</h1>

    <p><a href="{{ route('admin.participations.export') }}">Exporter en CSV</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Code</th>
            <th>Date</th>
            <th>Lot</th>
        </tr>
        @forelse ($participations as $participation)
            <tr>
                <td>{{ $participation->first_name }}</td>
                <td>{{ $participation->email }}</td>
                <td>{{ $participation->gameSession->promoCode->code }}</td>
                <td>{{ $participation->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $participation->prize_rank ? config('game.prizes')[$participation->prize_rank] : '' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Aucune participation pour le moment.</td></tr>
        @endforelse
    </table>
</body>
</html>
