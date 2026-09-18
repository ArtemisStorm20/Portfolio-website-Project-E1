<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $tag = request('tag');

        return view('welcome', [
            'projects' => Project::with(['images', 'tags'])
                ->when($tag, fn ($query) => $query->whereHas('tags', fn ($tags) => $tags->where('slug', $tag)))
                ->latest()
                ->get(),
            'tags' => \App\Models\Tag::withCount('projects')->orderBy('name')->get(),
            'activeTag' => $tag,
        ]);
    }
}
