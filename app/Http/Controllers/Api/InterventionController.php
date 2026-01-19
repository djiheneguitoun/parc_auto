<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InterventionType;
use App\Models\InterventionCategorie;
use App\Models\InterventionOperation;
use App\Models\InterventionVehicule;
use App\Models\InterventionSuivi;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class InterventionController extends Controller
{
    /**
     * Safe accessor for nested object properties
     */
    private function safe($obj, ...$props) {
        foreach ($props as $prop) {
            if ($obj === null) return null;
            $obj = $obj->{$prop} ?? null;
        }
        return $obj;
    }

    // ========================================================================
    // RÉFÉRENTIELS (Types, Catégories, Opérations)
    // ========================================================================

    /**
     * Liste des types d'intervention (ENT, REP)
     */
    public function types()
    {
        return InterventionType::all();
    }

    /**
     * Liste des catégories d'intervention
     */
    public function categories(Request $request)
    {
        $query = InterventionCategorie::query();
        
        if ($request->boolean('actif_only', true)) {
            $query->actif();
        }
        
        return $query->orderBy('libelle')->get();
    }

    /**
     * Créer une nouvelle catégorie
     */
    public function storeCategorie(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:intervention_categories,code',
            'libelle' => 'required|string|max:100',
            'actif' => 'boolean',
        ]);

        $categorie = InterventionCategorie::create($data);
        return response()->json($categorie, 201);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function updateCategorie(Request $request, InterventionCategorie $categorie)
    {
        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('intervention_categories', 'code')->ignore($categorie->id)],
            'libelle' => 'sometimes|string|max:100',
            'actif' => 'boolean',
        ]);

        $categorie->update($data);
        return $categorie;
    }

    /**
     * Liste des opérations d'intervention
     */
    public function operations(Request $request)
    {
        $query = InterventionOperation::with(['type', 'categorie']);
        
        if ($request->boolean('actif_only', true)) {
            $query->actif();
        }
        
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->get('type_id'));
        }
        
        if ($request->filled('type_code')) {
            $query->whereHas('type', fn($q) => $q->where('code', $request->get('type_code')));
        }
        
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->get('categorie_id'));
        }
        
        return $query->orderBy('libelle')->get();
    }

    /**
     * Créer une nouvelle opération
     */
    public function storeOperation(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:30|unique:intervention_operations,code',
            'libelle' => 'required|string|max:150',
            'type_id' => 'required|exists:intervention_types,id',
            'categorie_id' => 'required|exists:intervention_categories,id',
            'periodicite_km' => 'nullable|integer|min:0',
            'periodicite_mois' => 'nullable|integer|min:0',
            'actif' => 'boolean',
        ]);

        // Vérifier la cohérence : pas de périodicité pour les réparations
        $type = InterventionType::find($data['type_id']);
        if ($type && $type->code === 'REP') {
            $data['periodicite_km'] = null;
            $data['periodicite_mois'] = null;
        }

        $operation = InterventionOperation::create($data);
        return response()->json($operation->load(['type', 'categorie']), 201);
    }

    /**
     * Mettre à jour une opération
     */
    public function updateOperation(Request $request, InterventionOperation $operation)
    {
        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:30', Rule::unique('intervention_operations', 'code')->ignore($operation->id)],
            'libelle' => 'sometimes|string|max:150',
            'type_id' => 'sometimes|exists:intervention_types,id',
            'categorie_id' => 'sometimes|exists:intervention_categories,id',
            'periodicite_km' => 'nullable|integer|min:0',
            'periodicite_mois' => 'nullable|integer|min:0',
            'actif' => 'boolean',
        ]);

        // Vérifier la cohérence : pas de périodicité pour les réparations
        $typeId = $data['type_id'] ?? $operation->type_id;
        $type = InterventionType::find($typeId);
        if ($type && $type->code === 'REP') {
            $data['periodicite_km'] = null;
            $data['periodicite_mois'] = null;
        }

        $operation->update($data);
        return $operation->load(['type', 'categorie']);
    }

    // ========================================================================
    // INTERVENTIONS VÉHICULES (CRUD)
    // ========================================================================

    /**
     * Liste des interventions avec filtres
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15) ?: 15;

        $query = InterventionVehicule::with(['vehicule', 'operation.type', 'operation.categorie'])
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')))
            ->when($request->filled('operation_id'), fn ($q) => $q->where('operation_id', $request->get('operation_id')))
            ->when($request->filled('type_code'), fn ($q) => $q->whereHas('operation.type', fn($inner) => $inner->where('code', $request->get('type_code'))))
            ->when($request->filled('categorie_id'), fn ($q) => $q->whereHas('operation', fn($inner) => $inner->where('categorie_id', $request->get('categorie_id'))))
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_intervention', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_intervention', '<=', $request->get('date_end')))
            ->when($request->filled('prestataire'), fn ($q) => $q->where('prestataire', 'like', '%' . $request->get('prestataire') . '%'))
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->get('q');
                $q->where(function ($inner) use ($search) {
                    $inner->where('description', 'like', "%{$search}%")
                          ->orWhere('prestataire', 'like', "%{$search}%")
                          ->orWhereHas('operation', fn($op) => $op->where('libelle', 'like', "%{$search}%"))
                          ->orWhereHas('vehicule', fn($v) => $v->where('numero', 'like', "%{$search}%")
                              ->orWhere('marque', 'like', "%{$search}%")
                              ->orWhere('modele', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('date_intervention');

        return $query->paginate($perPage);
    }

    /**
     * Créer une intervention
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        
        $intervention = InterventionVehicule::create($data);

        return response()->json($intervention->load(['vehicule', 'operation.type', 'operation.categorie']), 201);
    }

    /**
     * Afficher une intervention
     */
    public function show(InterventionVehicule $intervention)
    {
        return $intervention->load(['vehicule', 'operation.type', 'operation.categorie']);
    }

    /**
     * Mettre à jour une intervention
     */
    public function update(Request $request, InterventionVehicule $intervention)
    {
        $data = $this->validateData($request, $intervention->id);
        
        $intervention->update($data);

        return $intervention->load(['vehicule', 'operation.type', 'operation.categorie']);
    }

    /**
     * Supprimer une intervention
     */
    public function destroy(InterventionVehicule $intervention)
    {
        $intervention->delete();
        return response()->noContent();
    }

    // ========================================================================
    // SUIVI & ALERTES
    // ========================================================================

    /**
     * Liste des suivis avec alertes
     */
    public function suivis(Request $request)
    {
        $query = InterventionSuivi::with(['vehicule', 'operation.type', 'operation.categorie']);
        
        if ($request->filled('vehicule_id')) {
            $query->where('vehicule_id', $request->get('vehicule_id'));
        }
        
        if ($request->boolean('echeances_proches')) {
            $jours = $request->get('jours_avant', 30);
            $query->echeancesProches($jours);
        }
        
        if ($request->boolean('echeances_depassees')) {
            $query->echeancesDepassees();
        }
        
        return $query->orderBy('prochaine_echeance_date')->get();
    }

    /**
     * Alertes à venir (échéances proches ou dépassées)
     */
    public function alertes(Request $request)
    {
        $joursAvant = $request->get('jours', 30);
        
        $echeancesProches = InterventionSuivi::with(['vehicule', 'operation.type', 'operation.categorie'])
            ->echeancesProches($joursAvant)
            ->get()
            ->map(fn($s) => [
                'type' => 'proche',
                'suivi' => $s,
                'jours_restants' => $s->joursRestants(),
            ]);

        $echeancesDepassees = InterventionSuivi::with(['vehicule', 'operation.type', 'operation.categorie'])
            ->echeancesDepassees()
            ->get()
            ->map(fn($s) => [
                'type' => 'depassee',
                'suivi' => $s,
                'jours_retard' => abs($s->joursRestants()),
            ]);

        return [
            'proches' => $echeancesProches,
            'depassees' => $echeancesDepassees,
            'total_alertes' => $echeancesProches->count() + $echeancesDepassees->count(),
        ];
    }

    // ========================================================================
    // STATISTIQUES
    // ========================================================================

    /**
     * Statistiques globales des interventions
     */
    public function stats(Request $request)
    {
        $self = $this;
        
        $interventions = InterventionVehicule::with(['operation.type', 'operation.categorie', 'vehicule'])
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_intervention', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_intervention', '<=', $request->get('date_end')))
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')))
            ->get();

        // Totaux par type
        $parType = $interventions->groupBy(function($i) use ($self) {
                return $self->safe($i, 'operation', 'type', 'code') ?? 'INCONNU';
            })
            ->map(fn($group, $code) => [
                'code' => $code,
                'libelle' => $code === 'ENT' ? 'Entretien' : ($code === 'REP' ? 'Réparation' : 'Inconnu'),
                'total' => $group->count(),
                'cout_total' => round($group->sum('cout'), 2),
            ])->values();

        // Totaux par catégorie
        $parCategorie = $interventions->groupBy(function($i) use ($self) {
                return $self->safe($i, 'operation', 'categorie_id') ?? 0;
            })
            ->map(function($group) use ($self) {
                $categorie = $self->safe($group->first(), 'operation', 'categorie');
                return [
                    'categorie' => $categorie ? [
                        'id' => $categorie->id,
                        'code' => $categorie->code,
                        'libelle' => $categorie->libelle,
                    ] : null,
                    'total' => $group->count(),
                    'cout_total' => round($group->sum('cout'), 2),
                ];
            })->values()->sortByDesc('total')->values();

        // Coût par véhicule
        $coutParVehicule = $interventions->groupBy('vehicule_id')->map(function (Collection $group) use ($self) {
            $vehicule = $group->first()->vehicule;
            return [
                'vehicule' => $vehicule ? [
                    'id' => $vehicule->id,
                    'label' => trim(($vehicule->code ? $vehicule->code . ' · ' : '') . ($vehicule->marque . ' ' . $vehicule->modele)) ?: ($vehicule->numero ?? 'Véhicule'),
                ] : null,
                'entretiens' => $group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'ENT'; })->count(),
                'reparations' => $group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'REP'; })->count(),
                'cout_entretien' => round($group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'ENT'; })->sum('cout'), 2),
                'cout_reparation' => round($group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'REP'; })->sum('cout'), 2),
                'cout_total' => round($group->sum('cout'), 2),
                'immobilisation_totale' => $group->sum('immobilisation_jours'),
            ];
        })->values()->sortByDesc('cout_total')->values();

        // Par période (mois)
        $parPeriode = $interventions
            ->groupBy(fn($i) => $i->date_intervention ? Carbon::parse($i->date_intervention)->format('Y-m') : 'inconnu')
            ->map(function ($group, $periode) use ($self) {
                return [
                    'periode' => $periode,
                    'total' => $group->count(),
                    'cout_total' => round($group->sum('cout'), 2),
                    'entretiens' => $group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'ENT'; })->count(),
                    'reparations' => $group->filter(function($i) use ($self) { return $self->safe($i, 'operation', 'type', 'code') === 'REP'; })->count(),
                ];
            })->values()->sortBy('periode')->values();

        // Opérations les plus fréquentes
        $operationsFrequentes = $interventions->groupBy('operation_id')
            ->map(function($group) use ($self) {
                $operation = $group->first()->operation;
                return [
                    'operation' => $operation ? [
                        'id' => $operation->id,
                        'code' => $operation->code,
                        'libelle' => $operation->libelle,
                        'type' => $self->safe($operation, 'type', 'libelle'),
                    ] : null,
                    'total' => $group->count(),
                    'cout_total' => round($group->sum('cout'), 2),
                ];
            })->values()->sortByDesc('total')->take(10)->values();

        // Alertes (échéances proches)
        $alertes = InterventionSuivi::with(['vehicule', 'operation'])
            ->echeancesProches(30)
            ->count();
            
        $alertesDepassees = InterventionSuivi::echeancesDepassees()->count();

        return [
            'totaux' => [
                'interventions' => $interventions->count(),
                'cout_global' => round($interventions->sum('cout'), 2),
                'immobilisation_totale' => $interventions->sum('immobilisation_jours'),
            ],
            'par_type' => $parType,
            'par_categorie' => $parCategorie,
            'cout_par_vehicule' => $coutParVehicule,
            'par_periode' => $parPeriode,
            'operations_frequentes' => $operationsFrequentes,
            'alertes' => [
                'proches' => $alertes,
                'depassees' => $alertesDepassees,
            ],
        ];
    }

    // ========================================================================
    // HELPERS
    // ========================================================================

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'operation_id' => 'required|exists:intervention_operations,id',
            'date_intervention' => 'required|date',
            'description' => 'nullable|string',
            'kilometrage' => 'nullable|integer|min:0',
            'cout' => 'nullable|numeric|min:0',
            'prestataire' => 'nullable|string|max:255',
            'immobilisation_jours' => 'nullable|integer|min:0',
        ]);
    }
}
