<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project bewerken | Amy Software</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page">
    <div class="page-shell">
        <div class="utility-bar"><span>AMY SOFTWARE STUDIO</span><span>Gemaakt met liefde</span><span class="utility-social">f&nbsp;&nbsp;in&nbsp;&nbsp;p</span></div>
        <header class="admin-header"><a class="brand" href="{{ route('home') }}"><span class="brand-script">Amy</span><span class="brand-subtitle">software<br>development</span></a><span class="admin-label">Studio dashboard</span></header>
        <main class="admin-shell">
            <a class="admin-back" href="{{ route('admin.projects.index') }}">&larr; Terug naar projecten</a>
            <p class="eyebrow">03 / Project aanpassen</p><h1>{{ $project->title }}</h1>
            @if ($errors->any()) <div class="form-errors">{{ $errors->first() }}</div> @endif
            <form class="project-form" method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="title">Titel</label>
                <input id="title" name="title" value="{{ old('title', $project->title) }}" required>
                <label for="description">Beschrijving</label>
                <textarea id="description" name="description" rows="7" required>{{ old('description', $project->description) }}</textarea>
                @if ($project->images->isNotEmpty())
                    <label>Bestaande afbeeldingen</label>
                    <div class="edit-gallery">
                        @foreach ($project->images as $image)
                            <label class="edit-image"><img src="{{ asset('storage/'.$image->path) }}" alt="{{ $project->title }}"><span><input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Verwijderen</span></label>
                        @endforeach
                    </div>
                @endif
                <label for="images">Nieuwe afbeeldingen toevoegen <span class="form-hint">(optioneel)</span></label>
                <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
                <button class="button" type="submit">Wijzigingen opslaan <span>&rarr;</span></button>
            </form>
        </main>
        <footer class="site-footer"><span>AMY SOFTWARE STUDIO</span><span>Gebouwd met code en liefde</span><span>&copy; {{ date('Y') }}</span></footer>
    </div>
</body>
</html>
