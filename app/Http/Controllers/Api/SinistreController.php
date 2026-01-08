<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Mpdf\Mpdf;

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

    public function exportPdf(Sinistre $sinistre)
    {
        $sinistre->load(['vehicule', 'chauffeur', 'assurance', 'reparations']);
        
        // Format helpers
        $formatDate = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '-';
        $formatCurrency = fn($v) => number_format((float)$v, 2, ',', ' ') . ' DH';
        $formatTime = fn($t) => $t ? substr($t, 0, 5) : '-';
        
        $typeLabels = ['accident' => 'Accident', 'panne' => 'Panne', 'vol' => 'Vol', 'incendie' => 'Incendie'];
        $graviteLabels = ['mineur' => 'Mineur', 'moyen' => 'Moyen', 'grave' => 'Grave'];
        $responsableLabels = ['chauffeur' => 'Chauffeur', 'tiers' => 'Tiers', 'inconnu' => 'Inconnu'];
        $statutLabels = ['declare' => 'Déclaré', 'en_cours' => 'En cours', 'en_reparation' => 'En réparation', 'clos' => 'Clôturé'];
        $assuranceStatutLabels = ['en_attente' => 'En attente', 'en_cours' => 'En cours', 'valide' => 'Validé'];
        $decisionLabels = ['en_attente' => 'En attente', 'accepte' => 'Acceptée', 'refuse' => 'Refusée', 'partiel' => 'Partielle'];
        $reparationStatutLabels = ['en_attente' => 'En attente', 'en_cours' => 'En cours', 'termine' => 'Terminé'];
        $priseEnChargeLabels = ['assurance' => 'Assurance', 'entreprise' => 'Entreprise', 'chauffeur' => 'Chauffeur'];
        
        $vehicule = $sinistre->vehicule;
        $chauffeur = $sinistre->chauffeur;
        $assurance = $sinistre->assurance;
        $reparations = $sinistre->reparations;
        
        $vehiculeLabel = $vehicule ? trim(($vehicule->numero ?? '') . ' ' . ($vehicule->marque ?? '') . ' ' . ($vehicule->modele ?? '')) : 'Non spécifié';
        $chauffeurLabel = $chauffeur ? trim(($chauffeur->prenom ?? '') . ' ' . ($chauffeur->nom ?? '')) : 'Non assigné';
        
        $html = '
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
            .header { background: #1e3a5f; color: white; padding: 20px; margin-bottom: 20px; }
            .header h1 { margin: 0 0 5px 0; font-size: 20px; }
            .header .subtitle { opacity: 0.8; font-size: 12px; }
            .section { margin-bottom: 15px; }
            .section-title { background: #f5f5f5; padding: 8px 12px; font-weight: bold; color: #1e3a5f; border-left: 4px solid #1e3a5f; margin-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            table th, table td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #eee; }
            table th { background: #f8f9fa; font-weight: 600; width: 35%; color: #666; }
            table td { color: #333; }
            .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }
            .badge-declare { background: #e3f2fd; color: #1976d2; }
            .badge-en_cours { background: #fff3e0; color: #f57c00; }
            .badge-en_reparation { background: #fce4ec; color: #c2185b; }
            .badge-clos { background: #e8f5e9; color: #388e3c; }
            .badge-mineur { background: #e8f5e9; color: #388e3c; }
            .badge-moyen { background: #fff3e0; color: #f57c00; }
            .badge-grave { background: #ffebee; color: #d32f2f; }
            .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #999; text-align: center; }
        </style>
        
        <div class="header">
            <h1>Dossier Sinistre</h1>
            <div class="subtitle">N° ' . htmlspecialchars($sinistre->numero_sinistre ?? '-') . ' | Généré le ' . Carbon::now()->format('d/m/Y à H:i') . '</div>
        </div>
        
        <div class="section">
            <div class="section-title">Informations générales</div>
            <table>
                <tr><th>N° Sinistre</th><td>' . htmlspecialchars($sinistre->numero_sinistre ?? '-') . '</td></tr>
                <tr><th>Statut</th><td><span class="badge badge-' . ($sinistre->statut_sinistre ?? 'declare') . '">' . ($statutLabels[$sinistre->statut_sinistre] ?? '-') . '</span></td></tr>
                <tr><th>Date du sinistre</th><td>' . $formatDate($sinistre->date_sinistre) . '</td></tr>
                <tr><th>Heure</th><td>' . $formatTime($sinistre->heure_sinistre) . '</td></tr>
                <tr><th>Lieu</th><td>' . htmlspecialchars($sinistre->lieu_sinistre ?? '-') . '</td></tr>
                <tr><th>Type</th><td>' . ($typeLabels[$sinistre->type_sinistre] ?? '-') . '</td></tr>
                <tr><th>Gravité</th><td><span class="badge badge-' . ($sinistre->gravite ?? 'mineur') . '">' . ($graviteLabels[$sinistre->gravite] ?? '-') . '</span></td></tr>
                <tr><th>Responsable</th><td>' . ($responsableLabels[$sinistre->responsable] ?? '-') . '</td></tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Véhicule & Chauffeur</div>
            <table>
                <tr><th>Véhicule</th><td>' . htmlspecialchars($vehiculeLabel) . '</td></tr>
                <tr><th>Chauffeur</th><td>' . htmlspecialchars($chauffeurLabel) . '</td></tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Coûts</div>
            <table>
                <tr><th>Montant estimé</th><td>' . $formatCurrency($sinistre->montant_estime) . '</td></tr>
                <tr><th>Coût total</th><td><strong>' . $formatCurrency($sinistre->cout_total) . '</strong></td></tr>
            </table>
        </div>';
        
        if ($sinistre->description) {
            $html .= '
            <div class="section">
                <div class="section-title">Description</div>
                <p style="padding: 10px; background: #f8f9fa; border-radius: 4px;">' . nl2br(htmlspecialchars($sinistre->description)) . '</p>
            </div>';
        }
        
        // Assurance section
        if ($assurance) {
            $html .= '
            <div class="section">
                <div class="section-title">Assurance</div>
                <table>
                    <tr><th>Compagnie</th><td>' . htmlspecialchars($assurance->compagnie_assurance ?? '-') . '</td></tr>
                    <tr><th>N° Dossier</th><td>' . htmlspecialchars($assurance->numero_dossier ?? '-') . '</td></tr>
                    <tr><th>Date déclaration</th><td>' . $formatDate($assurance->date_declaration) . '</td></tr>
                    <tr><th>Expert</th><td>' . htmlspecialchars($assurance->expert ?? '-') . '</td></tr>
                    <tr><th>Date expertise</th><td>' . $formatDate($assurance->date_expertise) . '</td></tr>
                    <tr><th>Décision</th><td>' . ($decisionLabels[$assurance->decision] ?? '-') . '</td></tr>
                    <tr><th>Montant pris en charge</th><td>' . $formatCurrency($assurance->montant_pris_en_charge) . '</td></tr>
                    <tr><th>Franchise</th><td>' . $formatCurrency($assurance->franchise) . '</td></tr>
                    <tr><th>Statut</th><td>' . ($assuranceStatutLabels[$assurance->statut_assurance] ?? '-') . '</td></tr>
                </table>
            </div>';
        }
        
        // Réparations section
        if ($reparations && $reparations->count() > 0) {
            $html .= '
            <div class="section">
                <div class="section-title">Réparations (' . $reparations->count() . ')</div>';
            
            foreach ($reparations as $i => $rep) {
                $html .= '
                <table style="margin-bottom: 15px;">
                    <tr><th colspan="2" style="background: #e3f2fd;">Réparation #' . ($i + 1) . ' - ' . htmlspecialchars($rep->garage ?? 'Garage') . '</th></tr>
                    <tr><th>Type</th><td>' . ($rep->type_reparation == 'mecanique' ? 'Mécanique' : 'Carrosserie') . '</td></tr>
                    <tr><th>Date début</th><td>' . $formatDate($rep->date_debut) . '</td></tr>
                    <tr><th>Date fin prévue</th><td>' . $formatDate($rep->date_fin_prevue) . '</td></tr>
                    <tr><th>Date fin réelle</th><td>' . $formatDate($rep->date_fin_reelle) . '</td></tr>
                    <tr><th>Coût</th><td>' . $formatCurrency($rep->cout_reparation) . '</td></tr>
                    <tr><th>Prise en charge</th><td>' . ($priseEnChargeLabels[$rep->prise_en_charge] ?? '-') . '</td></tr>
                    <tr><th>Statut</th><td>' . ($reparationStatutLabels[$rep->statut_reparation] ?? '-') . '</td></tr>
                </table>';
            }
            
            $html .= '</div>';
        }
        
        $html .= '
        <div class="footer">
            Document généré automatiquement par le système de gestion de parc automobile - ' . config('app.name', 'Parc Auto') . '
        </div>';
        
        // Generate PDF
        $mpdf = new Mpdf([
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        
        $mpdf->SetTitle('Dossier Sinistre - ' . ($sinistre->numero_sinistre ?? 'N/A'));
        $mpdf->WriteHTML($html);
        
        $filename = 'dossier_sinistre_' . ($sinistre->numero_sinistre ?? $sinistre->id) . '.pdf';
        
        return response($mpdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
