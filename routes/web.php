<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';
require __DIR__.'/front.php';
require __DIR__.'/admin.php';

Route::middleware('auth')->group(function () {
    Route::post('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile-photo.update');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile-photo.destroy');
});

Route::get('/debug-photo', function() {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Non connecté']);
    }

    $debug = [
        'user_id' => $user->id,
        'photo_column' => $user->photo,
        'profile_photo_path' => $user->profile_photo_path,
        'profile_photo_url' => $user->profile_photo_url,
        'cloudinary_config' => config('filesystems.disks.cloudinary'),
        'default_disk' => config('filesystems.default'),
    ];

    // Test Storage
    try {
        $testPath = 'test-file.jpg';
        $debug['storage_test_url'] = Storage::disk('cloudinary')->url($testPath);
        $debug['storage_test'] = 'OK';
    } catch (\Exception $e) {
        $debug['storage_test'] = 'ERREUR: ' . $e->getMessage();
    }

    return response()->json($debug);
});
