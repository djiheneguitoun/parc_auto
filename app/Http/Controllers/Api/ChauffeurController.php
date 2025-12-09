<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chauffeur;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    public function index(Request $request)
    {
        $query = Chauffeur::query();

        if ($search = $request->get('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('matricule', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|string|max:50|unique:chauffeurs,matricule',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'nullable|date',
            'date_recrutement' => 'nullable|date',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'numero_permis' => 'nullable|string|max:100',
            'date_permis' => 'nullable|date',
            'lieu_permis' => 'nullable|string|max:150',
            'statut' => 'required|in:contractuel,permanent',
            'mention' => 'required|in:tres_bien,bien,mauvais,blame',
        ]);

        $chauffeur = Chauffeur::create($data);

        return response()->json($chauffeur, 201);
    }

    public function show(Chauffeur $chauffeur)
    {
        return $chauffeur;
    }

    public function update(Request $request, Chauffeur $chauffeur)
    {
        $data = $request->validate([
            'matricule' => 'sometimes|required|string|max:50|unique:chauffeurs,matricule,' . $chauffeur->id,
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'date_naissance' => 'nullable|date',
            'date_recrutement' => 'nullable|date',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'numero_permis' => 'nullable|string|max:100',
            'date_permis' => 'nullable|date',
            'lieu_permis' => 'nullable|string|max:150',
            'statut' => 'sometimes|required|in:contractuel,permanent',
            'mention' => 'sometimes|required|in:tres_bien,bien,mauvais,blame',
        ]);

        $chauffeur->update($data);

        return $chauffeur->fresh();
    }

    public function destroy(Chauffeur $chauffeur)
    {
        $chauffeur->delete();

        return response()->noContent();
    }
}
