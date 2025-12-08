<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParlerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\Parler::with(['region', 'langue']);
        if ($search) {
            $query->whereHas('region', function($q) use ($search) {
                $q->where('nom_region', 'like', "%$search%");
            })->orWhereHas('langue', function($q) use ($search) {
                $q->where('nom_langue', 'like', "%$search%");
            });
        }
        $parlers = $query->paginate(10)->withQueryString();
        return inertia('Parler/index', [
            'parlers' => $parlers,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = \App\Models\Region::orderBy('nom_region')->get();
        $langues = \App\Models\Langue::orderBy('nom_langue')->get();
        return inertia('Parler/create', [
            'regions' => $regions,
            'langues' => $langues
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'langue_id' => 'required|exists:langues,id',
        ]);
        \App\Models\Parler::create($validated);
        return redirect()->route('parlers.index')->with('success', 'Association ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parler = \App\Models\Parler::with(['region', 'langue'])->findOrFail($id);
        return inertia('Parler/show', [
            'parler' => $parler
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parler = \App\Models\Parler::findOrFail($id);
        $regions = \App\Models\Region::orderBy('nom_region')->get();
        $langues = \App\Models\Langue::orderBy('nom_langue')->get();
        return inertia('Parler/edit', [
            'parler' => $parler,
            'regions' => $regions,
            'langues' => $langues
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parler = \App\Models\Parler::findOrFail($id);
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'langue_id' => 'required|exists:langues,id',
        ]);
        $parler->update($validated);
        return redirect()->route('parlers.index')->with('success', 'Association modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parler = \App\Models\Parler::findOrFail($id);
        $parler->delete();
        return redirect()->route('parlers.index')->with('success', 'Association supprimée avec succès.');
    }
}
