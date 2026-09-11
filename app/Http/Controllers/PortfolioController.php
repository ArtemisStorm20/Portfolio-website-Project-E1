<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'projects' => Project::with('images')->latest()->get(),
        ]);
    }
}
