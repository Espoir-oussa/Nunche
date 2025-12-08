<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    /**
     * Affiche les contenus d'une région
     */
    public function showContenus($id)
    {
        $region = \App\Models\Region::findOrFail($id);
        $contenus = \App\Models\Contenu::with(['media', 'langue', 'auteur', 'typeContenu'])
            ->where('region_id', $region->id)
            ->orderBy('date_creation', 'desc')
            ->get();
        return inertia('Region/Contenus', [
            'region' => $region,
            'contenus' => $contenus
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\Region::query();
        if ($search) {
            $query->where('nom_region', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        $regions = $query->orderBy('nom_region')->paginate(10)->withQueryString();
        return inertia('Region/index', [
            'regions' => $regions,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Region/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_region' => 'required|string|max:255',
            'description' => 'nullable|string',
            'population' => 'nullable|integer|min:0',
            'superficie' => 'nullable|numeric|min:0',
            'localisation' => 'nullable|string',
        ]);

        \App\Models\Region::create($validated);
        return redirect()->route('regions.index')->with('success', 'Région ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $region = \App\Models\Region::findOrFail($id);
        return inertia('Region/show', [
            'region' => $region
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $region = \App\Models\Region::findOrFail($id);
        return inertia('Region/edit', [
            'region' => $region
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $region = \App\Models\Region::findOrFail($id);
        $validated = $request->validate([
            'nom_region' => 'required|string|max:255',
            'description' => 'nullable|string',
            'population' => 'nullable|integer|min:0',
            'superficie' => 'nullable|numeric|min:0',
            'localisation' => 'nullable|string',
        ]);

        Log::info('Region update validated:', $validated);
        Log::info('Region before update:', $region->toArray());

        $region->update($validated);
        Log::info('Region after update:', $region->fresh()->toArray());
        return redirect()->route('regions.index')->with('success', 'Région modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $region = \App\Models\Region::findOrFail($id);
        $region->delete();
        return redirect()->route('regions.index')->with('success', 'Région supprimée avec succès.');
    }
}
