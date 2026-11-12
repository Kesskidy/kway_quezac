<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'RE·SOURCE — La collection K-Way × Quézac' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative bg-nuit text-white antialiased">
    <x-nav />

    <main>
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
