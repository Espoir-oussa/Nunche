<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route d'accueil
use App\Http\Controllers\RegionController;
// Page contenus par région
Route::get('/region/{id}/contenus', [RegionController::class, 'showContenus'])->name('region.contenus');
use App\Models\Contenu;

Route::get('/', function () {
    $contenus = Contenu::with(['region', 'langue', 'auteur', 'typeContenu', 'media'])
        ->orderBy('date_creation', 'desc')
        ->take(6)
        ->get();
    $user = \Illuminate\Support\Facades\Auth::user();
    $contenus->transform(function ($contenu) use ($user) {
        $contenu->user_is_subscribed = $user && $user->is_subscribed;
        return $contenu;
    });
    return Inertia::render('Home', [
        'contenus' => $contenus,
        'types' => \App\Models\TypeContenu::orderBy('nom_contenu')->get(),
        'regions' => \App\Models\Region::orderBy('nom_region')->get()
    ]);
})->name('home');

// Dashboard
// Abonnement Feedapay
use App\Http\Controllers\AbonnementController;

Route::get('/abonnement', [AbonnementController::class, 'index'])->name('abonnement.index');
Route::post('/abonnement/process', [AbonnementController::class, 'process'])->name('abonnement.process');
Route::get('/abonnement/callback', [AbonnementController::class, 'callback'])->name('abonnement.callback');

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\ContenuController;

Route::get('/discover', [ContenuController::class, 'index'])->name('discover');

// Route de test FedaPay (à supprimer en production)
Route::get('/test-fedapay', [AbonnementController::class, 'testConnection'])
    ->middleware('auth')
    ->name('abonnement.test');
