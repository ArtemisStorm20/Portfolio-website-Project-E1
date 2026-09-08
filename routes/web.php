<?php

use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['nl', 'en'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language.switch');

Route::get('/', function () {
    app()->setLocale(session('locale', config('app.locale')));

    return view('welcome');
})->name('home');

Route::get('/portfolio', function () {
    app()->setLocale(session('locale', config('app.locale')));

    return view('welcome');
})->name('portfolio');
