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
        // Een project heeft minimaal een afbeelding en maximaal vijf afbeeldingen.
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
            // Bewaar alleen het bestandspad; de bestanden zelf komen op de public disk.
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
        // Nieuwe afbeeldingen mogen het totaal van vijf niet overschrijden.
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
            // Verwijder zowel het fysieke bestand als de databasevermelding.
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
