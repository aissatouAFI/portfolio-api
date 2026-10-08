<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CvController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\RealisationController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VisiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes PUBLIQUES (consommées par le site public React)
|--------------------------------------------------------------------------
|
| L'espace admin est désormais géré par Laravel Filament, directement
| sur /admin (voir app/Providers/Filament/AdminPanelProvider.php).
| Les anciennes routes admin (JWT) ne sont plus nécessaires : Filament
| travaille directement sur les modèles Eloquent, sans passer par l'API.
|
*/
Route::get('/profil', [ProfilController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/realisations', [RealisationController::class, 'index']);
Route::get('/realisations/{slug}', [RealisationController::class, 'showPublic']);
Route::get('/cv', [CvController::class, 'index']);
// Limité à 5 envois par minute et par adresse IP (anti-spam).
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1');
// Compteur de visites (une par session de navigation), limité pour éviter le gonflage artificiel.
Route::post('/visites', [VisiteController::class, 'store'])->middleware('throttle:10,1');
