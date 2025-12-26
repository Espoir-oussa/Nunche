<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $region = $request->input('region');
        $sort = $request->input('sort', 'recents');
        $media = $request->input('media');
        $query = \App\Models\Contenu::with(['region', 'langue', 'moderateur', 'typeContenu', 'auteur', 'media.typeMedia']);
        // Recherche (titre ou texte)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('texte', 'like', "%$search%");
            });
        }
        // Filtre type
        if ($type) {
            $query->whereHas('typeContenu', function($q) use ($type) {
                $q->where('id', $type);
            });
        }
        // Filtre région
        if ($region) {
            $query->where('region_id', $region);
        }
        // Filtre média (audio, vidéo)
        if ($media) {
            $query->whereHas('media.typeMedia', function($q) use ($media) {
                $q->where('nom_media', $media);
            });
        }
        // Tri (uniquement par date_creation, car nb_vues n'existe pas)
        $query->orderByDesc('date_creation');
        $contenus = $query->paginate(12)->withQueryString();
        $user = \Illuminate\Support\Facades\Auth::user();
        $contenus->getCollection()->transform(function ($contenu) use ($user) {
            $contenu->user_is_subscribed = $user && $user->is_subscribed;
            return $contenu;
        });
        $types = \App\Models\TypeContenu::orderBy('nom_contenu')->get();
        return inertia('Discover', [
            'contents' => $contenus,
            'contentsCount' => $contenus->total(),
            'regions' => \App\Models\Region::all(),
            'types' => $types,
            'search' => $search,
            'filters' => [
                'type' => $type,
                'region' => $region,
                'sort' => $sort,
                'media' => $media,
            ],
        ]);
    }

    /**
     * Affichage admin : liste des contenus
     */
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $region = $request->input('region');
        $sort = $request->input('sort', 'recents');
        $media = $request->input('media');
        $query = \App\Models\Contenu::with(['region', 'langue', 'moderateur', 'typeContenu', 'auteur', 'media.typeMedia']);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('texte', 'like', "%$search%");
            });
        }
        if ($type) {
            $query->whereHas('typeContenu', function($q) use ($type) {
                $q->where('id', $type);
            });
        }
        if ($region) {
            $query->where('region_id', $region);
        }
        if ($media) {
            $query->whereHas('media.typeMedia', function($q) use ($media) {
                $q->where('nom_media', $media);
            });
        }
        $query->orderByDesc('date_creation');
        $contenus = $query->paginate(12)->withQueryString();
        $user = \Illuminate\Support\Facades\Auth::user();
        $contenus->getCollection()->transform(function ($contenu) use ($user) {
            $contenu->user_is_subscribed = $user && $user->is_subscribed;
            return $contenu;
        });
        $types = \App\Models\TypeContenu::orderBy('nom_contenu')->get();
        return inertia('Contenu/index', [
            'contenus' => $contenus,
            'contentsCount' => $contenus->total(),
            'regions' => \App\Models\Region::all(),
            'types' => $types,
            'search' => $search,
            'filters' => [
                'type' => $type,
                'region' => $region,
                'sort' => $sort,
                'media' => $media,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = \App\Models\Region::orderBy('nom_region')->get();
        $langues = \App\Models\Langue::orderBy('nom_langue')->get();
        $moderateurs = \App\Models\User::orderBy('nom')->get();
        $types = \App\Models\TypeContenu::orderBy('nom_contenu')->get();
        $auteurs = \App\Models\User::orderBy('nom')->get();
        $parents = \App\Models\Contenu::orderBy('titre')->get();
        return inertia('Contenu/create', [
            'regions' => $regions,
            'langues' => $langues,
            'moderateurs' => $moderateurs,
            'types' => $types,
            'auteurs' => $auteurs,
            'parents' => $parents
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'texte' => 'required|string',
            'date_creation' => 'required|date',
            'statut' => 'required|string',
            'date_validation' => 'nullable|date',
            'parent_id' => 'nullable|exists:contenus,id',
            'region_id' => 'required|exists:regions,id',
            'langue_id' => 'required|exists:langues,id',
            'moderateur_id' => 'nullable|exists:users,id',
            'type_contenu_id' => 'required|exists:type_contenus,id',
            'auteur_id' => 'required|exists:users,id',
            'media' => 'required|file|mimes:jpg,jpeg,png,gif|max:20480',
        ]);

        // Création du contenu
        $contenu = \App\Models\Contenu::create($validated);

        // Upload et association du média principal
        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('medias', 'cloudinary');
            \App\Models\Media::create([
                'chemin' => $path,
                'description' => 'Image principale',
                'contenu_id' => $contenu->id,
                'type_media_id' => 1, // À adapter selon vos types (ex: 1 = image)
                'type_contenu_id' => $contenu->type_contenu_id,
            ]);
        }

        return redirect()->route('contenus.index')->with('success', 'Contenu ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contenu = \App\Models\Contenu::with(['region', 'langue', 'moderateur', 'typeContenu', 'auteur'])->findOrFail($id);
        return inertia('Contenu/show', [
            'contenu' => $contenu
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contenu = \App\Models\Contenu::findOrFail($id);
        $regions = \App\Models\Region::orderBy('nom_region')->get();
        $langues = \App\Models\Langue::orderBy('nom_langue')->get();
        $moderateurs = \App\Models\User::orderBy('nom')->get();
        $types = \App\Models\TypeContenu::orderBy('nom_contenu')->get();
        $auteurs = \App\Models\User::orderBy('nom')->get();
        $parents = \App\Models\Contenu::orderBy('titre')->get();
        return inertia('Contenu/edit', [
            'contenu' => $contenu,
            'regions' => $regions,
            'langues' => $langues,
            'moderateurs' => $moderateurs,
            'types' => $types,
            'auteurs' => $auteurs,
            'parents' => $parents
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contenu = \App\Models\Contenu::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'texte' => 'required|string',
            'date_creation' => 'required|date',
            'statut' => 'required|string',
            'date_validation' => 'nullable|date',
            'parent_id' => 'nullable|exists:contenus,id',
            'region_id' => 'required|exists:regions,id',
            'langue_id' => 'required|exists:langues,id',
            'moderateur_id' => 'nullable|exists:users,id',
            'type_contenu_id' => 'required|exists:type_contenus,id',
            'auteur_id' => 'required|exists:users,id',
        ]);
        $contenu->update($validated);
        return redirect()->route('contenus.index')->with('success', 'Contenu modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contenu = \App\Models\Contenu::findOrFail($id);
        $contenu->delete();
        return redirect()->route('contenus.index')->with('success', 'Contenu supprimé avec succès.');
    }
}
