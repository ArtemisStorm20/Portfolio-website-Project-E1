<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        foreach ($request->file('images') as $sortOrder => $image) {
            $project->images()->create([
                'path' => $image->store('projects', 'public'),
                'sort_order' => $sortOrder,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project toegevoegd.');
    }

    public function edit(Project $project): View
    {
        $project->load('images');

        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]);

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $removeIds = $validated['remove_images'] ?? [];
        $imagesToRemove = $project->images()->whereIn('id', $removeIds)->get();
        foreach ($imagesToRemove as $image) {
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

    public function destroy(Project $project): RedirectResponse
    {
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project verwijderd.');
    }
}
