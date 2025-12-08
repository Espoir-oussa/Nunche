<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\Commentaire::with(['user', 'contenu']);
        if ($search) {
            $query->where('texte', 'like', "%$search%")
                  ->orWhere('note', 'like', "%$search%");
        }
        $commentaires = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();
        return inertia('Commentaire/index', [
            'commentaires' => $commentaires,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::orderBy('nom')->get();
        $contenus = \App\Models\Contenu::orderBy('titre')->get();
        return inertia('Commentaire/create', [
            'users' => $users,
            'contenus' => $contenus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'texte' => 'required|string',
            'note' => 'nullable|integer|min:0|max:5',
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'contenu_id' => 'required|exists:contenus,id',
        ]);
        \App\Models\Commentaire::create($validated);
        return redirect()->route('commentaires.index')->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $commentaire = \App\Models\Commentaire::with(['user', 'contenu'])->findOrFail($id);
        return inertia('Commentaire/show', [
            'commentaire' => $commentaire
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $commentaire = \App\Models\Commentaire::findOrFail($id);
        $users = \App\Models\User::orderBy('nom')->get();
        $contenus = \App\Models\Contenu::orderBy('titre')->get();
        return inertia('Commentaire/edit', [
            'commentaire' => $commentaire,
            'users' => $users,
            'contenus' => $contenus
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $commentaire = \App\Models\Commentaire::findOrFail($id);
        $validated = $request->validate([
            'texte' => 'required|string',
            'note' => 'nullable|integer|min:0|max:5',
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'contenu_id' => 'required|exists:contenus,id',
        ]);
        $commentaire->update($validated);
        return redirect()->route('commentaires.index')->with('success', 'Commentaire modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $commentaire = \App\Models\Commentaire::findOrFail($id);
        $commentaire->delete();
        return redirect()->route('commentaires.index')->with('success', 'Commentaire supprimé avec succès.');
    }
}
