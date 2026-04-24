<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MantenedorController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::resource('/mantenedor', MantenedorController::class);

require __DIR__.'/settings.php';
