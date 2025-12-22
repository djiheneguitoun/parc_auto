<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chauffeur;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VehiculeController extends Controller
{
    private const ETATS_FONCTIONNELS = [
        'disponible',
        'utilisation',
        'technique',
        'reglementaire',
        'incident',
        'fin_de_vie',
    ];

    private const STATUTS = [
        'disponible',
        'en_service',
        'reserve',
        'en_maintenance',
        'en_panne',
        'en_reparation',
        'non_conforme',
        'interdit',
        'sinistre',
        'en_expertise',
        'reforme',
        'sorti_du_parc',
    ];

    private const ETAT_STATUT_MAP = [
        'disponible' => ['disponible'],
        'utilisation' => ['en_service', 'reserve'],
        'technique' => ['en_maintenance', 'en_panne', 'en_reparation'],
        'reglementaire' => ['non_conforme', 'interdit'],
        'incident' => ['sinistre', 'en_expertise'],
        'fin_de_vie' => ['reforme', 'sorti_du_parc'],
    ];

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

        $validator = Validator::make($request->all(), [
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
            'etat_fonctionnel' => ['required', Rule::in(self::ETATS_FONCTIONNELS)],
            'statut' => ['required', Rule::in(self::STATUTS)],
            'date_creation' => 'nullable|date',
            'categorie' => 'nullable|in:leger,lourd,transport,tracteur,engins',
            'option_vehicule' => 'nullable|in:base,base_clim,toutes_options',
            'energie' => 'nullable|in:essence,diesel,gpl,electrique',
            'boite' => 'nullable|in:semiauto,auto,manuel',
            'leasing' => 'nullable|in:location,acquisition,autre',
            'utilisation' => 'nullable|in:personnel,professionnel',
            'affectation' => 'nullable|string|max:150',
        ]);

        $validator->after(function ($validator) use ($request) {
            $etat = $request->input('etat_fonctionnel');
            $statut = $request->input('statut');
            if ($etat && $statut) {
                $allowed = self::ETAT_STATUT_MAP[$etat] ?? [];
                if (!in_array($statut, $allowed, true)) {
                    $validator->errors()->add('statut', 'Statut incompatible avec l\'état fonctionnel choisi.');
                }
            }
        });

        return $validator->validate();
    }
}
