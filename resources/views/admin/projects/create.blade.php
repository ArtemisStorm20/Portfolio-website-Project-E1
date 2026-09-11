<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nieuw project | Amy Software</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page">
    <div class="page-shell">
        <div class="utility-bar"><span>AMY SOFTWARE STUDIO</span><span>Gemaakt met liefde</span><span class="utility-social">f&nbsp;&nbsp;in&nbsp;&nbsp;p</span></div>
        <header class="admin-header"><a class="brand" href="{{ route('home') }}"><span class="brand-script">Amy</span><span class="brand-subtitle">software<br>development</span></a><span class="admin-label">Studio dashboard</span></header>
        <main class="admin-shell">
            <a class="admin-back" href="{{ route('admin.projects.index') }}">&larr; Terug naar projecten</a>
            <p class="eyebrow">03 / Nieuw werk</p><h1>Nieuw project toevoegen</h1>
        @if ($errors->any())
            <div class="form-errors">{{ $errors->first() }}</div>
        @endif
        <form class="project-form" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
            @csrf
            <label for="title">Titel</label>
            <input id="title" name="title" value="{{ old('title') }}" required>
            <label for="description">Beschrijving</label>
            <textarea id="description" name="description" rows="7" required>{{ old('description') }}</textarea>
            <label for="images">Afbeeldingen</label>
            <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required>
            <button class="button" type="submit">Project opslaan &rarr;</button>
        </form>
        </main>
        <footer class="site-footer"><span>AMY SOFTWARE STUDIO</span><span>Gebouwd met code en liefde</span><span>&copy; {{ date('Y') }}</span></footer>
    </div>
</body>
</html>
