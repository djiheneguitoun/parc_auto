<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        return Utilisateur::orderByDesc('created_at')->paginate(15);
    }

    public function show(Utilisateur $utilisateur)
    {
        return $utilisateur;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:150',
            'cle' => 'required|string|max:120|unique:utilisateurs,cle',
            'role' => 'required|in:administratif,responsable,agent',
            'actif' => 'boolean',
            'email' => 'nullable|email|max:150|unique:utilisateurs,email',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $utilisateur = Utilisateur::create($data);

        return response()->json($utilisateur, 201);
    }

    public function update(Request $request, Utilisateur $utilisateur)
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:150',
            'cle' => 'sometimes|required|string|max:120|unique:utilisateurs,cle,' . $utilisateur->id,
            'role' => 'sometimes|required|in:administratif,responsable,agent',
            'actif' => 'sometimes|boolean',
            'email' => 'nullable|email|max:150|unique:utilisateurs,email,' . $utilisateur->id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $utilisateur->update($data);

        return $utilisateur->fresh();
    }

    public function toggle(Utilisateur $utilisateur)
    {
        $utilisateur->update(['actif' => !$utilisateur->actif]);

        return $utilisateur->fresh();
    }

    public function assignRole(Request $request, Utilisateur $utilisateur)
    {
        $data = $request->validate([
            'role' => 'required|in:administratif,responsable,agent',
        ]);

        $utilisateur->update($data);

        return $utilisateur->fresh();
    }

    public function destroy(Utilisateur $utilisateur)
    {
        $utilisateur->delete();

        return response()->noContent();
    }
}
