<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('mantenedor', 'mantenedor')->name('mantenedor');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('instituicao', 'instituicao')->name('instituicao');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('professor', 'professor')->name('professor');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('curso', 'curso')->name('curso');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('instrumento', 'instrumento')->name('instrumento');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('avaliacao', 'avaliacao')->name('avaliacao');
});



require __DIR__.'/settings.php';
