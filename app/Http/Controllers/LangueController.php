<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\Langue::query();
        if ($search) {
            $query->where('nom_langue', 'like', "%$search%")
                  ->orWhere('code_langue', 'like', "%$search%");
        }
        $langues = $query->orderBy('nom_langue')->paginate(10)->withQueryString();
        return inertia('Langue/index', [
            'langues' => $langues,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Langue/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_langue' => 'required|string|max:100',
            'code_langue' => 'required|string|max:10|unique:langues,code_langue',
            'description' => 'nullable|string',
        ]);
        \App\Models\Langue::create($validated);
        return redirect()->route('langues.index')->with('success', 'Langue créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $langue = \App\Models\Langue::findOrFail($id);
        return inertia('Langue/show', [
            'langue' => $langue
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $langue = \App\Models\Langue::findOrFail($id);
        return inertia('Langue/edit', [
            'langue' => $langue
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $langue = \App\Models\Langue::findOrFail($id);
        $validated = $request->validate([
            'nom_langue' => 'required|string|max:100',
            'code_langue' => 'required|string|max:10|unique:langues,code_langue,' . $langue->id,
            'description' => 'nullable|string',
        ]);
        $langue->update($validated);
        return redirect()->route('langues.index')->with('success', 'Langue modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $langue = \App\Models\Langue::findOrFail($id);
        $langue->delete();
        return redirect()->route('langues.index')->with('success', 'Langue supprimée avec succès.');
    }
}
