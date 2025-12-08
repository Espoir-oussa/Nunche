<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'nullable|date',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|integer|exists:roles,id',
            'langue_id' => 'required|integer|exists:langues,id',
            'statut' => 'required|string',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'sexe' => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'langue_id' => $request->langue_id,
            'statut' => $request->statut,
        ]);

        event(new Registered($user));
        Auth::login($user);
        // Redirection selon le rôle
        if ($user->role_id && \App\Enums\RoleEnum::tryFrom($user->role->slug ?? '') === \App\Enums\RoleEnum::ADMIN) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('home');
    }
}
