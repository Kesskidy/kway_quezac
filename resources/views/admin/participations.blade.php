<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>Participations, expédition et newsletter — Administration</title>
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

    <h2 id="expedition">Inscrits à l’Expédition RE·SOURCE ({{ $expeditionRegistrations->count() }})</h2>

    <p><a href="{{ route('admin.expedition.export') }}">Exporter en CSV</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Date d’inscription</th>
        </tr>
        @forelse ($expeditionRegistrations as $registration)
            <tr>
                <td>{{ $registration->first_name }}</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Aucun inscrit pour le moment.</td></tr>
        @endforelse
    </table>

    <h2 id="newsletter">Inscrits à la newsletter ({{ $subscribers->count() }})</h2>

    <p><a href="{{ route('admin.newsletter.export') }}">Exporter en CSV</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>E-mail</th>
            <th>Date d’inscription</th>
        </tr>
        @forelse ($subscribers as $subscriber)
            <tr>
                <td>{{ $subscriber->email }}</td>
                <td>{{ $subscriber->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="2">Aucun inscrit pour le moment.</td></tr>
        @endforelse
    </table>
</body>
</html>
