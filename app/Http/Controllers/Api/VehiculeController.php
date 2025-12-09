<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chauffeur;
use App\Models\Vehicule;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicule::with(['chauffeur', 'documents', 'images']);

        if ($search = $request->get('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('numero', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('marque', 'like', "%{$search}%")
                    ->orWhere('modele', 'like', "%{$search}%");
            });
        }

        if ($chauffeurId = $request->get('chauffeur_id')) {
            $query->where('chauffeur_id', $chauffeurId);
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $vehicule = Vehicule::create($data);

        return response()->json($vehicule->load(['chauffeur']), 201);
    }

    public function show(Vehicule $vehicule)
    {
        return $vehicule->load(['chauffeur', 'documents', 'images']);
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $data = $this->validateData($request, $vehicule->id);

        $vehicule->update($data);

        return $vehicule->load(['chauffeur', 'documents', 'images']);
    }

    public function destroy(Vehicule $vehicule)
    {
        $vehicule->delete();

        return response()->noContent();
    }

    public function assignChauffeur(Request $request, Vehicule $vehicule)
    {
        $data = $request->validate([
            'chauffeur_id' => 'required|exists:chauffeurs,id',
        ]);

        $vehicule->update(['chauffeur_id' => $data['chauffeur_id']]);

        return $vehicule->load('chauffeur');
    }

    private function validateData(Request $request, ?int $vehiculeId = null): array
    {
        $uniqueNumero = 'unique:vehicules,numero';
        $uniqueCode = 'unique:vehicules,code';
        if ($vehiculeId) {
            $uniqueNumero .= ',' . $vehiculeId;
            $uniqueCode .= ',' . $vehiculeId;
        }

        return $request->validate([
            'numero' => ['required', 'string', 'max:50', $uniqueNumero],
            'code' => ['required', 'string', 'max:50', $uniqueCode],
            'description' => 'nullable|string',
            'marque' => 'nullable|string|max:100',
            'modele' => 'nullable|string|max:100',
            'annee' => 'nullable|integer|min:1900|max:' . date('Y'),
            'couleur' => 'nullable|string|max:50',
            'chassis' => 'nullable|string|max:120',
            'chauffeur_id' => 'nullable|exists:chauffeurs,id',
            'date_acquisition' => 'nullable|date',
            'valeur' => 'nullable|numeric|min:0',
            'statut' => 'boolean',
            'date_creation' => 'nullable|date',
            'categorie' => 'nullable|in:leger,lourd,transport',
            'option_vehicule' => 'nullable|in:base,base_clim,toutes_options',
            'energie' => 'nullable|in:essence,diesel,gpl',
            'boite' => 'nullable|in:semiauto,auto,manuel',
            'leasing' => 'nullable|in:location,acquisition,autre',
            'utilisation' => 'nullable|in:personnel,professionnel',
            'affectation' => 'nullable|string|max:150',
        ]);
    }
}
