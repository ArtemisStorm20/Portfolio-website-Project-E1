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
            //De relaties worden vooraf geladen, omdat de portfolio-weergave per project de bijbehorende afbeeldingen en tags laat zien.
            'projects' => Project::with(['images', 'tags'])
                ->when($tag, fn ($query) => $query->whereHas('tags', fn ($tags) => $tags->where('slug', $tag)))
                ->latest()
                ->get(),
            // Het aantal projecten per tag wordt vooraf geladen, zodat de filterweergave kan werken zonder voor iedere tag een aparte query uit te voeren.
            'tags' => \App\Models\Tag::withCount('projects')->orderBy('name')->get(),
            'activeTag' => $tag,
        ]);
    }
}
