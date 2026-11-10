<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Mon Application' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav><!-- barre de navigation --></nav>

    <main>
        {{ $slot }} <!-- Le contenu de la page -->
    </main>

    <footer><!--  pied de page --></footer>
</body>
</html>