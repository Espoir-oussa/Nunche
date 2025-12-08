<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\Media::with(['contenu', 'typeMedia']);
        if ($search) {
            $query->where('chemin', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        $media = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return Inertia::render('Media/index', [
            'media' => $media,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contenus = \App\Models\Contenu::orderBy('titre')->get();
        $types = \App\Models\TypeMedia::orderBy('nom_media')->get();
        $typesContenu = \App\Models\TypeContenu::orderBy('nom_contenu')->get();
        return inertia('Media/create', [
            'contenus' => $contenus,
            'types' => $types,
            'typesContenu' => $typesContenu,
            'debugProp' => ['test1', 'test2', 'test3']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Limite à 20 Mo (20480 Ko)
            'chemin' => 'required|file|mimes:jpg,jpeg,png,gif,mp3,mp4,wav,pdf,doc,docx|max:20480',
            'description' => 'nullable|string',
            'contenu_id' => 'required|exists:contenus,id',
            'type_media_id' => 'required|exists:type_media,id',
            'type_contenu_id' => 'required|exists:type_contenus,id',
        ]);

        if ($request->hasFile('chemin')) {
            $path = $request->file('chemin')->store('medias', 'public');
            $validated['chemin'] = $path;
        }

        \App\Models\Media::create($validated);
        return redirect()->route('media.index')->with('success', 'Media ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $media = \App\Models\Media::with(['contenu', 'typeMedia'])->findOrFail($id);
        return inertia('Media/show', [
            'media' => $media
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $media = \App\Models\Media::findOrFail($id);
        $contenus = \App\Models\Contenu::orderBy('titre')->get();
        $types = \App\Models\TypeMedia::orderBy('nom_media')->get();
        return inertia('Media/edit', [
            'media' => $media,
            'contenus' => $contenus,
            'types' => $types
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $media = \App\Models\Media::findOrFail($id);
        $validated = $request->validate([
            'chemin' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contenu_id' => 'required|exists:contenus,id',
            'type_media_id' => 'required|exists:type_media,id',
            'type_contenu_id' => 'required|exists:type_contenus,id',
        ]);
        $media->update($validated);
        return redirect()->route('media.index')->with('success', 'Media modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $media = \App\Models\Media::findOrFail($id);
        $media->delete();
        return redirect()->route('media.index')->with('success', 'Media supprimé avec succès.');
    }
}
