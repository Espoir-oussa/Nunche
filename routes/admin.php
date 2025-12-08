<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Routes protégées (admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('langues', App\Http\Controllers\LangueController::class);
    Route::resource('media', App\Http\Controllers\MediaController::class);
    Route::resource('commentaires', App\Http\Controllers\CommentaireController::class);
    Route::resource('regions', App\Http\Controllers\RegionController::class);
    Route::get('contenus', [App\Http\Controllers\ContenuController::class, 'adminIndex'])->name('contenus.index');
    Route::get('contenus/create', [App\Http\Controllers\ContenuController::class, 'create'])->name('contenus.create');
    Route::post('contenus', [App\Http\Controllers\ContenuController::class, 'store'])->name('contenus.store');
    Route::get('contenus/{id}', [App\Http\Controllers\ContenuController::class, 'show'])->name('contenus.show');
    Route::get('contenus/{id}/edit', [App\Http\Controllers\ContenuController::class, 'edit'])->name('contenus.edit');
    Route::put('contenus/{id}', [App\Http\Controllers\ContenuController::class, 'update'])->name('contenus.update');
    Route::delete('contenus/{id}', [App\Http\Controllers\ContenuController::class, 'destroy'])->name('contenus.destroy');
    Route::resource('parlers', App\Http\Controllers\ParlerController::class);
});
