<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $user = Auth::user();

    // Supprimer l'ancienne
    if ($user->profile_photo_path) {
        Storage::disk('cloudinary')->delete($user->profile_photo_path);
    }

    // Uploader et obtenir l'URL
    $file = $request->file('photo');

    // Upload avec Cloudinary et obtenir l'URL publique
    $uploadedFile = Cloudinary::upload($file->getRealPath(), [
        'folder' => 'profile-photos',
        'transformation' => [
            'width' => 500,
            'height' => 500,
            'crop' => 'fill',
            'gravity' => 'auto'
        ]
    ]);

    // Stocker l'URL complète
    $user->profile_photo_path = $uploadedFile->getSecurePath();
    $user->save();

    return redirect()->back()->with('success', 'Photo mise à jour!');
}

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_photo_path) {
            Storage::disk('cloudinary')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }
        return redirect()->back();
    }
}
