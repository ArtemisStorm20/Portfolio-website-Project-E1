<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projecten beheren | Amy Software</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page">
    <div class="page-shell">
        <div class="utility-bar"><span>AMY SOFTWARE STUDIO</span><span>Gemaakt met liefde</span><span class="utility-social">f&nbsp;&nbsp;in&nbsp;&nbsp;p</span></div>
        <header class="admin-header">
            <a class="brand" href="{{ route('home') }}"><span class="brand-script">Amy</span><span class="brand-subtitle">software<br>development</span></a>
            <span class="admin-label">Studio dashboard</span>
        </header>
        <main class="admin-shell">
            <a class="admin-back" href="{{ route('home') }}">&larr; Naar de welcome pagina</a>
            <div class="admin-heading"><div><p class="eyebrow">02 / Portfolio</p><h1>Projecten beheren</h1></div><a class="button" href="{{ route('admin.projects.create') }}">Nieuw project <span>&rarr;</span></a></div>
            @if (session('success')) <p class="form-success">{{ session('success') }}</p> @endif
            <div class="admin-project-list">
                @forelse ($projects as $project)
                    <article>
                        <div>
                            <p class="project-number">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <h2>{{ $project->title }}
                                </h2>
                                @if ($project->tags->isNotEmpty())
                                    <p class="project-tags">@foreach ($project->tags as $tag)<span>{{ $tag->name }}</span>@endforeach</p>
                                @endif
                                <p>{{ $project->description }}
                                    </p>
                                </div>
                                <div class="project-admin-actions">
                                    <small>{{ $project->images->count() }} afbeelding(en)</small>
                                    <a class="text-link" href="{{ route('admin.projects.edit', $project) }}">Bewerken</a>
                                    <button class="delete-link" type="button" onclick="document.getElementById('delete-project-{{ $project->id }}').showModal()">Verwijderen</button><dialog id="delete-project-{{ $project->id }}">
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}">
                                            @csrf @method('DELETE')
                                            <h2>Project verwijderen</h2>
                                            <p>Typ de naam van het project om te bevestigen: <strong>{{ $project->title }}</strong></p>
                                            <input type="text" name="project_name_confirmation" required autocomplete="off">
                                            <button type="submit">Definitief verwijderen</button>
                                            <button type="button" onclick="this.closest('dialog').close()">Annuleren</button>
                                        </form>
                                    </dialog>
                                </div>
                            </article>
                @empty
                    <div class="admin-empty"><span class="empty-mark">*</span><p>Nog geen projecten toegevoegd.</p><span>Jouw volgende mooie idee krijgt hier een plek.</span></div>
                @endforelse
            </div>
        </main>
        <footer class="site-footer"><span>AMY SOFTWARE STUDIO</span><span>Gebouwd met code en liefde</span><span>&copy; {{ date('Y') }}</span></footer>
    </div>
</body>
</html>
