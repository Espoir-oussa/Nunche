<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;

require __DIR__.'/auth.php';
require __DIR__.'/front.php';
require __DIR__.'/admin.php';

Route::middleware('auth')->group(function () {
    Route::post('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile-photo.update');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile-photo.destroy');
});
