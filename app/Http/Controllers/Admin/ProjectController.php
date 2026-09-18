<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::with('images')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Een project moet minimaal één afbeelding hebben en mag maximaal vijf afbeeldingen bevatten.
        // In de database wordt alleen het pad naar de afbeelding opgeslagen. De afbeelding zelf wordt beheerd en opgeslagen via de storage-disk.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        foreach ($request->file('images') as $sortOrder => $image) {
            // Alleen het bestandspad wordt opgeslagen in de database. De bestanden zelf worden opgeslagen op de public disk.
            // De sorteerpositie wordt automatisch bepaald door de volgorde van de geüploade bestanden.
            $project->images()->create([
                'path' => $image->store('projects', 'public'),
                'sort_order' => $sortOrder,
            ]);
        }

        $project->tags()->sync($this->tagsFromInput($validated['tags'] ?? ''));

        return redirect()->route('admin.projects.index')->with('success', 'Project toegevoegd.');
    }

    public function edit(Project $project): View
    {
        $project->load(['images', 'tags']);

        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $removeIds = $request->input('remove_images', []);
        // Bij het toevoegen van nieuwe afbeeldingen mag het totaal niet boven de vijf afbeeldingen uitkomen.
        // Na het verwijderen van afbeeldingen wordt opnieuw berekend hoeveel afbeeldingen er nog toegevoegd kunnen worden. Hierdoor blijft de limiet van vijf afbeeldingen ook bij het aanpassen van een bestaand project hetzelfde.
        $remainingImageCount = $project->images()->whereNotIn('id', $removeIds)->count();
        $maxNewImages = max(0, 5 - $remainingImageCount);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', "max:{$maxNewImages}"],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]);

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);
        $project->tags()->sync($this->tagsFromInput($validated['tags'] ?? ''));

        $imagesToRemove = $project->images()->whereIn('id', $removeIds)->get();
        foreach ($imagesToRemove as $image) {
            //Bij het verwijderen van een afbeelding wordt zowel het bestand zelf als de bijbehorende databasevermelding verwijderd.
            //Op deze manier blijven er geen ongebruikte bestanden of verwijzingen naar bestanden die niet meer bestaan achter.
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $nextSortOrder = ((int) $project->images()->max('sort_order')) + 1;
        foreach ($request->file('images', []) as $image) {
            $project->images()->create([
                'path' => $image->store('projects', 'public'),
                'sort_order' => $nextSortOrder++,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project bijgewerkt.');
    }

    private function tagsFromInput(string $input): array
    {
        // Bij het vergelijken van tags wordt geen verschil gemaakt tussen hoofdletters en kleine letters. Hierdoor worden bijvoorbeeld "Laravel" en "laravel" niet als twee verschillende tags gezien.
        return collect(explode(',', $input))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique(fn (string $tag) => Str::lower($tag))
            ->mapWithKeys(function (string $name): array {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );

                return [$tag->id => []];
            })
            ->all();
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'project_name_confirmation' => [
                'required',
                'string',
                Rule::in([$project->title]),
            ],
        ], [
            'project_name_confirmation.in' =>
                'De ingevoerde projectnaam komt niet overeen.',
        ]);

        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project verwijderd.');
    }
}
