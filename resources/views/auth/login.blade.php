<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inloggen | Amy Software</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="admin-shell">
        <p class="eyebrow">Amy Software Studio</p>
        <h1>Inloggen</h1>
        <a class="text-link" href="{{ route('home') }}">&larr; Terug naar welcome pagina</a>
        @if ($errors->any()) <div class="form-errors">{{ $errors->first() }}</div> @endif
        <form class="project-form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">E-mailadres</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            <label for="password">Wachtwoord</label>
            <input id="password" type="password" name="password" required>
            <button class="button" type="submit">Inloggen &rarr;</button>
        </form>
    </main>
</body>
</html>
