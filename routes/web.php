<?php

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', function (string $locale) {
    // Alleen Nederlands en Engels zijn toegestane talen.
    abort_unless(in_array($locale, ['nl', 'en'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language.switch');

Route::get('/', function () {
    app()->setLocale(session('locale', config('app.locale')));

    return app(PortfolioController::class)->index();
})->name('home');

Route::get('/portfolio', function () {
    app()->setLocale(session('locale', config('app.locale')));

    return app(PortfolioController::class)->index();
})->name('portfolio');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', function (Request $request) {
        // Valideer de invoer voordat Laravel probeert in te loggen.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'De inloggegevens zijn niet juist.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.projects.index'));
    })->name('login.store');
});

Route::post('/logout', function (Request $request) {
    // Beveilig de logout tegen hergebruik van de oude sessie en CSRF-token.
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

// Alleen ingelogde beheerders mogen projecten beheren.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});
