<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        try {
            // 1. Supprimer l'ancienne photo si elle existe
            if ($user->photo) {
                $this->deleteOldPhoto($user->photo);
            }

            // 2. Upload sur Cloudinary
            // Le premier paramètre est le fichier, le second est le dossier
            $path = $request->file('photo')->store('profile-photos', 'cloudinary');

            // 3. Obtenir l'URL complète
            $url = Storage::disk('cloudinary')->url($path);

            // 4. Stocker l'URL complète dans la colonne 'photo'
            $user->photo = $url;
            $user->save();

            return redirect()->back()->with('success', 'Photo de profil mise à jour avec succès!');

        } catch (\Exception $e) {
            \Log::error('Erreur upload photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!$user->photo) {
            return redirect()->back()->with('error', 'Aucune photo à supprimer.');
        }

        try {
            // Supprimer de Cloudinary
            $this->deleteOldPhoto($user->photo);

            // Effacer de la base
            $user->photo = null;
            $user->save();

            return redirect()->back()->with('success', 'Photo supprimée avec succès!');

        } catch (\Exception $e) {
            \Log::error('Erreur suppression photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Supprime une photo de Cloudinary
     */
    private function deleteOldPhoto($photoUrl): bool
    {
        if (!$photoUrl) {
            return false;
        }

        try {
            // Si c'est une URL Cloudinary, extraire le chemin
            if (str_contains($photoUrl, 'cloudinary.com')) {
                $path = $this->extractPathFromCloudinaryUrl($photoUrl);

                if ($path) {
                    Storage::disk('cloudinary')->delete($path);
                    \Log::info('Photo supprimée de Cloudinary: ' . $path);
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::warning('Impossible de supprimer l\'ancienne photo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrait le chemin Cloudinary d'une URL
     * Ex: https://res.cloudinary.com/dteo1ix6m/image/upload/v1234567890/profile-photos/filename.jpg
     * -> profile-photos/filename.jpg
     */
    private function extractPathFromCloudinaryUrl(string $url): ?string
    {
        // Pattern pour extraire tout après /upload/
        $pattern = '/\/upload\/(?:v\d+\/)?(.+)$/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
