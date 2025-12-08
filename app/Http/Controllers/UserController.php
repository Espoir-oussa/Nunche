<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Langue;
use App\Enums\RoleEnum;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, fn($q) => $q->where('nom', 'like', "%$search%")
                ->orWhere('prenom', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Utilisateur/index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langues = \App\Models\Langue::all();
        $roles = array_map(fn($case) => $case->value, \App\Enums\RoleEnum::cases());
        return Inertia::render('Utilisateur/create', [
            'langues' => $langues,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'sexe' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'statut' => 'required|string',
            'role_id' => 'required|string',
            'langue_id' => 'required|exists:langues,id',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        // Convertit le nom du rôle en id
        $role = \App\Models\Role::where('nom_role', $validated['role_id'])->first();
        $validated['role_id'] = $role ? $role->id : null;
        User::create($validated);
        return redirect()->route('users.index')->with('success', 'Utilisateur créé !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $langue = Langue::find($user->langue_id);
        $roles = array_map(fn($case) => $case->value, RoleEnum::cases());
        $roleName = optional($user->role)->nom_role;
        $userArray = $user->toArray();
        $userArray['role_nom'] = $roleName;
        return Inertia::render('Utilisateur/show', [
            'user' => $userArray,
            'langue' => $langue,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $langues = Langue::all();
        $roles = array_map(fn($case) => $case->value, RoleEnum::cases());
        // Récupère le nom du rôle pour le select
        $role = optional($user->role)->nom_role;
        $userArray = $user->toArray();
        $userArray['role_id'] = $role;
        return Inertia::render('Utilisateur/edit', [
            'user' => $userArray,
            'langues' => $langues,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'sexe' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'statut' => 'required|string',
            'role_id' => 'required|string',
            'langue_id' => 'required|exists:langues,id',
        ]);
        // Convertit le nom du rôle en id
        $role = \App\Models\Role::where('nom_role', $validated['role_id'])->first();
        $validated['role_id'] = $role ? $role->id : null;
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'Utilisateur modifié !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé !');
    }
}
