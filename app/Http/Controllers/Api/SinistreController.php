<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SinistreController extends Controller
{
    private const TYPES = ['accident', 'panne', 'vol', 'incendie'];
    private const GRAVITES = ['mineur', 'moyen', 'grave'];
    private const RESPONSABLES = ['chauffeur', 'tiers', 'inconnu'];
    private const STATUTS = ['declare', 'en_cours', 'en_reparation', 'clos'];

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15) ?: 15;

        $query = Sinistre::with(['vehicule', 'chauffeur', 'assurance', 'reparations'])
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')))
            ->when($request->filled('chauffeur_id'), fn ($q) => $q->where('chauffeur_id', $request->get('chauffeur_id')))
            ->when($request->filled('statut'), fn ($q) => $q->where('statut_sinistre', $request->get('statut')))
            ->when($request->filled('type_sinistre'), fn ($q) => $q->where('type_sinistre', $request->get('type_sinistre')))
            ->when($request->filled('gravite'), fn ($q) => $q->where('gravite', $request->get('gravite')))
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_sinistre', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_sinistre', '<=', $request->get('date_end')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($inner) use ($request) {
                    $search = $request->get('q');
                    $inner->where('numero_sinistre', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('lieu_sinistre', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('date_sinistre');

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['statut_sinistre'] = 'declare';

        $sinistre = Sinistre::create($data);

        return response()->json($sinistre->load(['vehicule', 'chauffeur', 'assurance', 'reparations']), 201);
    }

    public function show(Sinistre $sinistre)
    {
        return $sinistre->load(['vehicule', 'chauffeur', 'assurance', 'reparations']);
    }

    public function update(Request $request, Sinistre $sinistre)
    {
        $data = $this->validateData($request, $sinistre->id, true);
        
        $sinistre->update($data);

        return $sinistre->load(['vehicule', 'chauffeur', 'assurance', 'reparations']);
    }

    public function destroy(Sinistre $sinistre)
    {
        // Delete associated assurance if exists
        if ($sinistre->assurance) {
            $sinistre->assurance->delete();
        }
        
        // Delete associated reparations
        $sinistre->reparations()->delete();
        
        // Delete the sinistre
        $sinistre->delete();

        return response()->noContent();
    }

    public function stats(Request $request)
    {
        $sinistres = Sinistre::with(['assurance', 'reparations', 'vehicule', 'chauffeur'])
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_sinistre', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_sinistre', '<=', $request->get('date_end')))
            ->get();

        $parPeriode = $sinistres
            ->groupBy(function ($s) {
                if (!$s->date_sinistre) {
                    return 'inconnu';
                }
                try {
                    return Carbon::parse($s->date_sinistre)->format('Y-m');
                } catch (\Exception $e) {
                    return 'inconnu';
                }
            })
            ->map(fn ($group, $periode) => [
                'periode' => $periode,
                'total' => $group->count(),
            ])->values();

        $coutParVehicule = $sinistres->groupBy('vehicule_id')->map(function (Collection $group) {
            $vehicule = optional($group->first()->vehicule);
            return [
                'vehicule' => [
                    'id' => $vehicule->id,
                    'label' => trim(($vehicule->code ? $vehicule->code . ' · ' : '') . ($vehicule->marque . ' ' . $vehicule->modele)) ?: ($vehicule->numero ?? 'Véhicule'),
                ],
                'cout_total' => round($group->sum(fn ($s) => $s->cout_total), 2),
            ];
        })->values()->sortByDesc('cout_total')->values();

        $classementChauffeurs = $sinistres->whereNotNull('chauffeur_id')->groupBy('chauffeur_id')->map(function (Collection $group) {
            $chauffeur = optional($group->first()->chauffeur);
            return [
                'chauffeur' => [
                    'id' => $chauffeur->id,
                    'nom' => trim(($chauffeur->nom ?? '') . ' ' . ($chauffeur->prenom ?? '')),
                ],
                'sinistres' => $group->count(),
            ];
        })->values()->sortByDesc('sinistres')->values();

        $ratios = $sinistres->filter(fn ($s) => $s->assurance && $s->cout_total > 0)
            ->map(function ($s) {
                $priseEnCharge = (float) ($s->assurance->montant_pris_en_charge ?? 0);
                return $priseEnCharge / max($s->cout_total, 0.01);
            });
        $tauxPriseEnChargeMoyen = $ratios->count() ? round($ratios->avg(), 4) : 0;

        $vehiculesPlusSinistres = $sinistres->groupBy('vehicule_id')->map(function (Collection $group) {
            $vehicule = optional($group->first()->vehicule);
            $label = [$vehicule->code, $vehicule->numero, $vehicule->marque, $vehicule->modele];
            return [
                'vehicule' => [
                    'id' => $vehicule->id,
                    'label' => implode(' · ', array_filter($label)),
                ],
                'sinistres' => $group->count(),
            ];
        })->values()->sortByDesc('sinistres')->take(5)->values();

        return [
            'par_periode' => $parPeriode,
            'cout_par_vehicule' => $coutParVehicule,
            'classement_chauffeurs' => $classementChauffeurs,
            'taux_prise_en_charge_moyen' => $tauxPriseEnChargeMoyen,
            'vehicules_plus_sinistres' => $vehiculesPlusSinistres,
        ];
    }

    private function validateData(Request $request, ?int $sinistreId = null, bool $isUpdate = false): array
    {
        $uniqueNumero = Rule::unique('sinistres', 'numero_sinistre');
        if ($sinistreId) {
            $uniqueNumero = $uniqueNumero->ignore($sinistreId);
        }

        $rules = [
            'numero_sinistre' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:120', $uniqueNumero],
            'vehicule_id' => [$isUpdate ? 'sometimes' : 'required', 'exists:vehicules,id'],
            'chauffeur_id' => ['nullable', 'exists:chauffeurs,id'],
            'date_sinistre' => [$isUpdate ? 'sometimes' : 'required', 'date'],
            // Accept HH:MM or HH:MM:SS to avoid 422 on seconds
            'heure_sinistre' => ['nullable', 'regex:/^([01]\d|2[0-3]):([0-5]\d)(:[0-5]\d)?$/'],
            'lieu_sinistre' => ['nullable', 'string', 'max:255'],
            'type_sinistre' => [$isUpdate ? 'sometimes' : 'required', Rule::in(self::TYPES)],
            'description' => ['nullable', 'string'],
            'gravite' => [$isUpdate ? 'sometimes' : 'required', Rule::in(self::GRAVITES)],
            'responsable' => [$isUpdate ? 'sometimes' : 'required', Rule::in(self::RESPONSABLES)],
            'montant_estime' => ['nullable', 'numeric', 'min:0'],
            'statut_sinistre' => ['nullable', Rule::in(self::STATUTS)],
            'cree_par' => ['nullable', 'exists:utilisateurs,id'],
            'date_creation' => ['nullable', 'date'],
        ];

        return $request->validate($rules);
    }
}
