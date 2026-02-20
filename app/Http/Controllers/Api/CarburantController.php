<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarburantPlein;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Mpdf\Mpdf;

class CarburantController extends Controller
{
    private const TYPES_CARBURANT = ['essence', 'diesel', 'gpl', 'electrique'];
    private const MODES_PAIEMENT  = ['especes', 'carte_carburant', 'bon', 'cheque'];

    // ========================================================================
    // CRUD Pleins Carburant
    // ========================================================================

    /**
     * Liste paginée avec filtres
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15) ?: 15;

        $query = CarburantPlein::with(['vehicule', 'chauffeur'])
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')))
            ->when($request->filled('chauffeur_id'), fn ($q) => $q->where('chauffeur_id', $request->get('chauffeur_id')))
            ->when($request->filled('type_carburant'), fn ($q) => $q->where('type_carburant', $request->get('type_carburant')))
            ->when($request->filled('mode_paiement'), fn ($q) => $q->where('mode_paiement', $request->get('mode_paiement')))
            ->when($request->filled('station'), fn ($q) => $q->where('station', 'like', '%' . $request->get('station') . '%'))
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_plein', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_plein', '<=', $request->get('date_end')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->get('q');
                $q->where(function ($inner) use ($search) {
                    $inner->where('station', 'like', "%{$search}%")
                          ->orWhere('observation', 'like', "%{$search}%")
                          ->orWhereHas('vehicule', fn($v) => $v->where('numero', 'like', "%{$search}%")
                              ->orWhere('marque', 'like', "%{$search}%")
                              ->orWhere('modele', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('date_plein');

        return $query->paginate($perPage);
    }

    /**
     * Créer un plein carburant
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Calcul automatique montant total
        $data['montant_total'] = round(($data['quantite'] ?? 0) * ($data['prix_unitaire'] ?? 0), 2);

        // Contrôle kilométrage cohérent (km doit être >= dernier km du même véhicule)
        $this->checkKilometrage($data['vehicule_id'], $data['kilometrage']);

        $plein = CarburantPlein::create($data);

        return response()->json($plein->load(['vehicule', 'chauffeur']), 201);
    }

    /**
     * Afficher un plein
     */
    public function show(CarburantPlein $carburant)
    {
        return $carburant->load(['vehicule', 'chauffeur']);
    }

    /**
     * Mettre à jour un plein
     */
    public function update(Request $request, CarburantPlein $carburant)
    {
        $data = $this->validateData($request, $carburant->id);

        // Recalcul automatique montant total
        $data['montant_total'] = round(($data['quantite'] ?? $carburant->quantite) * ($data['prix_unitaire'] ?? $carburant->prix_unitaire), 2);

        $carburant->update($data);

        return $carburant->load(['vehicule', 'chauffeur']);
    }

    /**
     * Supprimer un plein
     */
    public function destroy(CarburantPlein $carburant)
    {
        $carburant->delete();
        return response()->noContent();
    }

    // ========================================================================
    // STATISTIQUES & CONSOMMATION
    // ========================================================================

    /**
     * Statistiques globales carburant
     */
    public function stats(Request $request)
    {
        $query = CarburantPlein::with(['vehicule', 'chauffeur'])
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_plein', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_plein', '<=', $request->get('date_end')))
            ->when($request->filled('type_carburant'), fn ($q) => $q->where('type_carburant', $request->get('type_carburant')))
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')));

        $pleins = $query->orderBy('date_plein')->get();

        // Totaux globaux
        $totalPleins     = $pleins->count();
        $totalLitres     = (float) $pleins->sum('quantite');
        $totalDepenses   = (float) $pleins->sum('montant_total');
        $prixMoyenLitre  = $totalLitres > 0 ? round($totalDepenses / $totalLitres, 2) : 0;

        // Consommation par véhicule
        $parVehicule = $this->calculerConsommationParVehicule($pleins);

        // Consommation par conducteur
        $parConducteur = $this->calculerConsommationParConducteur($pleins);

        // Dépenses par période (mois)
        $parPeriode = $pleins->groupBy(function ($p) {
            return Carbon::parse($p->date_plein)->format('Y-m');
        })->map(function ($group, $period) {
            return [
                'periode'   => $period,
                'pleins'    => $group->count(),
                'litres'    => round((float) $group->sum('quantite'), 2),
                'depenses'  => round((float) $group->sum('montant_total'), 2),
            ];
        })->sortKeys()->values();

        // Dépenses par type carburant
        $parType = $pleins->groupBy('type_carburant')->map(function ($group, $type) {
            return [
                'type'     => $type,
                'pleins'   => $group->count(),
                'litres'   => round((float) $group->sum('quantite'), 2),
                'depenses' => round((float) $group->sum('montant_total'), 2),
            ];
        })->values();

        // Dépenses par mode paiement
        $parMode = $pleins->groupBy('mode_paiement')->map(function ($group, $mode) {
            return [
                'mode'     => $mode,
                'pleins'   => $group->count(),
                'depenses' => round((float) $group->sum('montant_total'), 2),
            ];
        })->values();

        // Moyenne du parc (coût/km moyen)
        $moyenneCoutKm = 0;
        $vehiculesAvecCout = collect($parVehicule)->filter(fn($v) => $v['cout_par_km'] > 0);
        if ($vehiculesAvecCout->count() > 0) {
            $moyenneCoutKm = round($vehiculesAvecCout->avg('cout_par_km'), 2);
        }

        // Coût mensuel par véhicule
        $coutMensuelParVehicule = $pleins->groupBy('vehicule_id')->map(function ($group) {
            $vehicule = $group->first()->vehicule;
            $parMois = $group->groupBy(function ($p) {
                return Carbon::parse($p->date_plein)->format('Y-m');
            })->map(function ($moisGroup, $periode) {
                return [
                    'periode'  => $periode,
                    'litres'   => round((float) $moisGroup->sum('quantite'), 2),
                    'depenses' => round((float) $moisGroup->sum('montant_total'), 2),
                    'pleins'   => $moisGroup->count(),
                ];
            })->sortKeys()->values();

            $nbMois = max(1, $parMois->count());
            $totalDep = round((float) $group->sum('montant_total'), 2);

            return [
                'vehicule'       => $vehicule,
                'cout_mensuel'   => round($totalDep / $nbMois, 2),
                'total_depenses' => $totalDep,
                'nb_mois'        => $nbMois,
                'details'        => $parMois,
            ];
        })->sortByDesc('cout_mensuel')->values()->toArray();

        // Coût global du parc
        $coutGlobalParc = [
            'total_depenses'  => round($totalDepenses, 2),
            'total_litres'    => round($totalLitres, 2),
            'nb_vehicules'    => $pleins->pluck('vehicule_id')->unique()->count(),
            'cout_mensuel_moyen' => 0,
        ];
        $nbMoisGlobal = $pleins->groupBy(function ($p) {
            return Carbon::parse($p->date_plein)->format('Y-m');
        })->count();
        if ($nbMoisGlobal > 0) {
            $coutGlobalParc['cout_mensuel_moyen'] = round($totalDepenses / $nbMoisGlobal, 2);
        }

        return [
            'totaux' => [
                'pleins'          => $totalPleins,
                'litres'          => round($totalLitres, 2),
                'depenses'        => round($totalDepenses, 2),
                'prix_moyen_litre' => $prixMoyenLitre,
                'moyenne_cout_km'  => $moyenneCoutKm,
            ],
            'par_vehicule'          => $parVehicule,
            'par_conducteur'        => $parConducteur,
            'par_periode'           => $parPeriode,
            'par_type'              => $parType,
            'par_mode'              => $parMode,
            'cout_mensuel_vehicule' => $coutMensuelParVehicule,
            'cout_global_parc'      => $coutGlobalParc,
        ];
    }

    /**
     * Comparaison véhicules
     */
    public function comparaison(Request $request)
    {
        $query = CarburantPlein::with(['vehicule'])
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_plein', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_plein', '<=', $request->get('date_end')))
            ->when($request->filled('type_carburant'), fn ($q) => $q->where('type_carburant', $request->get('type_carburant')))
            ->when($request->filled('categorie'), function ($q) use ($request) {
                $q->whereHas('vehicule', fn($v) => $v->where('categorie', $request->get('categorie')));
            });

        $pleins = $query->orderBy('date_plein')->get();

        $parVehicule = $this->calculerConsommationParVehicule($pleins);

        // Calcul de la moyenne du parc
        $vehiculesAvecCout = collect($parVehicule)->filter(fn($v) => $v['cout_par_km'] > 0);
        $moyenneCoutKm = $vehiculesAvecCout->count() > 0 ? $vehiculesAvecCout->avg('cout_par_km') : 0;

        // Ajout du statut pour chaque véhicule
        $comparaison = collect($parVehicule)->map(function ($v) use ($moyenneCoutKm) {
            $statut = 'normal';
            if ($moyenneCoutKm > 0 && $v['cout_par_km'] > 0) {
                $ecart = (($v['cout_par_km'] - $moyenneCoutKm) / $moyenneCoutKm) * 100;
                if ($ecart > 20) {
                    $statut = 'eleve';
                } elseif ($ecart > 10) {
                    $statut = 'a_surveiller';
                }
            }
            $v['statut'] = $statut;
            $v['moyenne_parc'] = round($moyenneCoutKm, 2);
            return $v;
        })->sortByDesc('cout_par_km')->values();

        return [
            'comparaison'     => $comparaison,
            'moyenne_cout_km' => round($moyenneCoutKm, 2),
        ];
    }

    /**
     * Alertes carburant (surconsommation, km incohérent, pleins trop rapprochés)
     */
    public function alertes(Request $request)
    {
        $pleins = CarburantPlein::with(['vehicule', 'chauffeur'])
            ->orderBy('vehicule_id')
            ->orderBy('date_plein')
            ->get();

        $alertes = [];

        // Grouper par véhicule
        $parVehicule = $pleins->groupBy('vehicule_id');

        foreach ($parVehicule as $vehiculeId => $vehiculePleins) {
            $sorted = $vehiculePleins->sortBy('date_plein')->values();

            for ($i = 1; $i < $sorted->count(); $i++) {
                $precedent = $sorted[$i - 1];
                $actuel    = $sorted[$i];
                $vehicule  = $actuel->vehicule;

                // Kilométrage incohérent
                if ($actuel->kilometrage && $precedent->kilometrage && $actuel->kilometrage < $precedent->kilometrage) {
                    $alertes[] = [
                        'type'      => 'km_incoherent',
                        'vehicule'  => $vehicule,
                        'plein'     => $actuel,
                        'message'   => "Kilométrage incohérent : {$actuel->kilometrage} km < {$precedent->kilometrage} km (plein précédent)",
                    ];
                }

                // Pleins trop rapprochés (même jour ou jour suivant avec même quantité)
                $diffJours = Carbon::parse($precedent->date_plein)->diffInDays(Carbon::parse($actuel->date_plein));
                if ($diffJours <= 1 && $actuel->quantite > 0) {
                    $alertes[] = [
                        'type'      => 'plein_rapproche',
                        'vehicule'  => $vehicule,
                        'plein'     => $actuel,
                        'message'   => "Plein trop rapproché : {$diffJours}j après le précédent",
                    ];
                }

                // Surconsommation anormale (> 0.15 L/km pour léger, > 0.35 L/km pour lourd)
                if ($actuel->kilometrage && $precedent->kilometrage && $actuel->quantite > 0) {
                    $kmParcourus = $actuel->kilometrage - $precedent->kilometrage;
                    if ($kmParcourus > 0) {
                        $conso = $actuel->quantite / $kmParcourus;
                        $seuilLkm = ($vehicule && $vehicule->categorie === 'lourd') ? 0.35 : 0.15;
                        if ($conso > $seuilLkm) {
                            $alertes[] = [
                                'type'      => 'surconsommation',
                                'vehicule'  => $vehicule,
                                'plein'     => $actuel,
                                'message'   => "Surconsommation : " . round($conso, 2) . " L/km (seuil : {$seuilLkm})",
                            ];
                        }
                    }
                }
            }
        }

        return [
            'alertes'       => $alertes,
            'total_alertes' => count($alertes),
        ];
    }

    // ========================================================================
    // PRIVATE HELPERS
    // ========================================================================

    /**
     * Validation des données d'un plein
     */
    private function validateData(Request $request, $excludeId = null)
    {
        return $request->validate([
            'vehicule_id'    => 'required|exists:vehicules,id',
            'chauffeur_id'   => 'nullable|exists:chauffeurs,id',
            'date_plein'     => 'required|date',
            'kilometrage'    => 'required|integer|min:0',
            'quantite'       => 'required|numeric|min:0.01',
            'prix_unitaire'  => 'required|numeric|min:0.01',
            'type_carburant' => ['required', Rule::in(self::TYPES_CARBURANT)],
            'station'        => 'nullable|string|max:255',
            'mode_paiement'  => ['required', Rule::in(self::MODES_PAIEMENT)],
            'observation'    => 'nullable|string|max:1000',
        ]);
    }

    /**
     * Contrôle kilométrage (bloquer si km < km précédent)
     */
    private function checkKilometrage($vehiculeId, $km)
    {
        $dernierPlein = CarburantPlein::where('vehicule_id', $vehiculeId)
            ->orderByDesc('date_plein')
            ->orderByDesc('id')
            ->first();

        if ($dernierPlein && $km < $dernierPlein->kilometrage) {
            abort(422, "Kilométrage incohérent : {$km} km est inférieur au dernier relevé ({$dernierPlein->kilometrage} km). Le kilométrage doit être croissant.");
        }
    }

    /**
     * Calcul de la consommation par véhicule (méthode plein-à-plein)
     * Formules :
     *  - Km parcourus = Km du dernier plein - Km du premier plein
     *  - Litres consommés = somme des litres SAUF le dernier plein (en cours de consommation)
     *  - Dépenses consommées = somme des montants SAUF le dernier plein
     *  - Consommation (L/km) = Litres consommés / Km parcourus
     *  - Coût par km = Dépenses consommées / Km parcourus
     *
     * Le dernier plein est exclu des litres/dépenses car son carburant
     * est encore en cours de consommation (trajet non terminé).
     */
    private function calculerConsommationParVehicule($pleins)
    {
        return $pleins->groupBy('vehicule_id')->map(function ($group) {
            $vehicule = $group->first()->vehicule;
            $sorted = $group->sortBy('kilometrage')->values();
            $nbPleins = $sorted->count();

            $kmMin       = (int) $sorted->first()->kilometrage;
            $kmMax       = (int) $sorted->last()->kilometrage;
            $kmParcourus = max(0, $kmMax - $kmMin);

            // Totaux globaux (tous les pleins) pour affichage brut
            $totalLitresTous   = round((float) $group->sum('quantite'), 2);
            $totalDepensesTous = round((float) $group->sum('montant_total'), 2);

            // Pour le calcul de consommation : exclure le dernier plein
            // car son carburant est en cours de consommation
            if ($nbPleins >= 2) {
                $pleinsConsommes = $sorted->slice(0, $nbPleins - 1);
                $litresConsommes   = round((float) $pleinsConsommes->sum('quantite'), 2);
                $depensesConsommees = round((float) $pleinsConsommes->sum('montant_total'), 2);
            } else {
                // Un seul plein : impossible de calculer la consommation
                $litresConsommes    = 0;
                $depensesConsommees = 0;
            }

            // Consommation moyenne (L/km)
            $consoMoyenne = ($kmParcourus > 0 && $litresConsommes > 0)
                ? round($litresConsommes / $kmParcourus, 2)
                : 0;

            // Coût par km
            $coutParKm = ($kmParcourus > 0 && $depensesConsommees > 0)
                ? round($depensesConsommees / $kmParcourus, 2)
                : 0;

            return [
                'vehicule_id'    => $vehicule->id ?? null,
                'vehicule'       => $vehicule,
                'km_parcourus'   => $kmParcourus,
                'total_litres'   => $totalLitresTous,
                'total_depenses' => $totalDepensesTous,
                'nb_pleins'      => $nbPleins,
                'conso_moyenne'  => $consoMoyenne,
                'cout_par_km'    => $coutParKm,
            ];
        })->values()->toArray();
    }

    /**
     * Calcul de la consommation par conducteur
     */
    private function calculerConsommationParConducteur($pleins)
    {
        return $pleins->filter(fn($p) => $p->chauffeur_id)
            ->groupBy('chauffeur_id')
            ->map(function ($group) {
                $chauffeur = $group->first()->chauffeur;

                $totalLitres   = round((float) $group->sum('quantite'), 2);
                $totalDepenses = round((float) $group->sum('montant_total'), 2);
                $nbPleins      = $group->count();

                return [
                    'chauffeur_id'   => $chauffeur->id ?? null,
                    'chauffeur'      => $chauffeur,
                    'total_litres'   => $totalLitres,
                    'total_depenses' => $totalDepenses,
                    'nb_pleins'      => $nbPleins,
                ];
            })->values()->toArray();
    }

    // ========================================================================
    // EXPORT PDF
    // ========================================================================

    /**
     * Export PDF – Liste des pleins carburant
     */
    public function exportPleinsPdf(Request $request)
    {
        $pleins = CarburantPlein::with(['vehicule', 'chauffeur'])
            ->when($request->filled('vehicule_id'), fn ($q) => $q->where('vehicule_id', $request->get('vehicule_id')))
            ->when($request->filled('type_carburant'), fn ($q) => $q->where('type_carburant', $request->get('type_carburant')))
            ->when($request->filled('mode_paiement'), fn ($q) => $q->where('mode_paiement', $request->get('mode_paiement')))
            ->when($request->filled('date_start'), fn ($q) => $q->whereDate('date_plein', '>=', $request->get('date_start')))
            ->when($request->filled('date_end'), fn ($q) => $q->whereDate('date_plein', '<=', $request->get('date_end')))
            ->orderByDesc('date_plein')
            ->get();

        $typeLabels = ['essence' => 'Essence', 'diesel' => 'Diesel', 'gpl' => 'GPL', 'electrique' => 'Electrique'];
        $modeLabels = ['especes' => 'Espèces', 'carte_carburant' => 'Carte carburant', 'bon' => 'Bon', 'cheque' => 'Chèque'];

        $totalMontant = $pleins->sum('montant_total');
        $totalLitres  = $pleins->sum('quantite');

        $rows = '';
        foreach ($pleins as $p) {
            $vLabel = $p->vehicule ? ($p->vehicule->numero ?: ($p->vehicule->marque . ' ' . $p->vehicule->modele)) : '-';
            $cLabel = $p->chauffeur ? (trim($p->chauffeur->nom . ' ' . $p->chauffeur->prenom) ?: '-') : '-';
            $rows .= '<tr>
                <td>' . ($p->date_plein ? Carbon::parse($p->date_plein)->format('d/m/Y') : '-') . '</td>
                <td>' . e($vLabel) . '</td>
                <td>' . ($typeLabels[$p->type_carburant] ?? $p->type_carburant) . '</td>
                <td style="text-align:right">' . number_format($p->kilometrage, 0, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($p->quantite, 2, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($p->prix_unitaire, 2, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($p->montant_total, 2, ',', ' ') . '</td>
                <td>' . ($modeLabels[$p->mode_paiement] ?? $p->mode_paiement) . '</td>
                <td>' . e($cLabel) . '</td>
            </tr>';
        }

        $html = $this->pdfWrapper(
            'Liste des pleins carburant',
            '<table>
                <thead><tr>
                    <th>Date</th><th>Véhicule</th><th>Type</th><th style="text-align:right">Km</th>
                    <th style="text-align:right">Qté (L)</th><th style="text-align:right">Prix/L</th>
                    <th style="text-align:right">Montant</th><th>Mode</th><th>Conducteur</th>
                </tr></thead>
                <tbody>' . $rows . '</tbody>
                <tfoot><tr style="font-weight:bold; background:#e8edf5;">
                    <td colspan="4">TOTAL (' . $pleins->count() . ' pleins)</td>
                    <td style="text-align:right">' . number_format($totalLitres, 2, ',', ' ') . ' L</td>
                    <td></td>
                    <td style="text-align:right">' . number_format($totalMontant, 2, ',', ' ') . ' DA</td>
                    <td colspan="2"></td>
                </tr></tfoot>
            </table>'
        );

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->SetTitle('Pleins carburant');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="pleins-carburant-' . date('Y-m-d') . '.pdf"',
        ]);
    }

    /**
     * Export PDF – Comparaison véhicules
     */
    public function exportComparaisonPdf(Request $request)
    {
        $data = $this->comparaison($request);
        $comparaison = $data['comparaison'] ?? [];
        $moyenne = $data['moyenne_cout_km'] ?? 0;

        $statutLabels = ['normal' => 'Normal', 'a_surveiller' => 'À surveiller', 'eleve' => 'Élevé'];
        $statutColors = ['normal' => '#27ae60', 'a_surveiller' => '#f39c12', 'eleve' => '#e74c3c'];

        $rows = '';
        foreach ($comparaison as $v) {
            $vLabel = $v['vehicule'] ? ($v['vehicule']['numero'] ?? (($v['vehicule']['marque'] ?? '') . ' ' . ($v['vehicule']['modele'] ?? ''))) : '-';
            $statut = $v['statut'] ?? 'normal';
            $sColor = $statutColors[$statut] ?? '#666';
            $sLabel = $statutLabels[$statut] ?? $statut;
            $rows .= '<tr>
                <td>' . e(trim($vLabel)) . '</td>
                <td style="text-align:right">' . number_format($v['km_parcourus'], 0, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($v['total_depenses'], 2, ',', ' ') . '</td>
                <td style="text-align:right;font-weight:bold">' . number_format($v['cout_par_km'], 2, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($v['conso_moyenne'], 2, ',', ' ') . '</td>
                <td style="text-align:center">' . $v['nb_pleins'] . '</td>
                <td style="text-align:center"><span style="color:' . $sColor . ';font-weight:bold">' . $sLabel . '</span></td>
            </tr>';
        }

        $html = $this->pdfWrapper(
            'Comparaison véhicules – Carburant',
            '<p style="margin-bottom:10px;font-size:12px;">Moyenne du parc : <strong>' . number_format($moyenne, 2, ',', ' ') . ' DA/km</strong></p>
            <table>
                <thead><tr>
                    <th>Véhicule</th><th style="text-align:right">Km</th><th style="text-align:right">Dépense (DA)</th>
                    <th style="text-align:right">Coût/km</th><th style="text-align:right">Conso (L/km)</th>
                    <th style="text-align:center">Pleins</th><th style="text-align:center">Statut</th>
                </tr></thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->SetTitle('Comparaison véhicules carburant');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="comparaison-carburant-' . date('Y-m-d') . '.pdf"',
        ]);
    }

    /**
     * Export PDF – Statistiques consommation
     */
    public function exportStatsPdf(Request $request)
    {
        $stats = $this->stats($request);
        $totaux = $stats['totaux'] ?? [];
        $parVehicule = $stats['par_vehicule'] ?? [];
        $parConducteur = $stats['par_conducteur'] ?? [];
        $coutGlobal = $stats['cout_global_parc'] ?? [];

        // KPIs
        $kpis = '<div style="margin-bottom:15px;">
            <table style="width:100%;border:none;">
                <tr>
                    <td style="border:none;text-align:center;padding:10px;background:#e8edf5;"><strong>' . ($totaux['pleins'] ?? 0) . '</strong><br><small>Total pleins</small></td>
                    <td style="border:none;text-align:center;padding:10px;background:#e8edf5;"><strong>' . number_format($totaux['litres'] ?? 0, 2, ',', ' ') . ' L</strong><br><small>Total litres</small></td>
                    <td style="border:none;text-align:center;padding:10px;background:#e8edf5;"><strong>' . number_format($totaux['depenses'] ?? 0, 2, ',', ' ') . ' DA</strong><br><small>Dépenses totales</small></td>
                    <td style="border:none;text-align:center;padding:10px;background:#e8edf5;"><strong>' . number_format($totaux['prix_moyen_litre'] ?? 0, 2, ',', ' ') . ' DA</strong><br><small>Prix moyen/L</small></td>
                    <td style="border:none;text-align:center;padding:10px;background:#e8edf5;"><strong>' . number_format($coutGlobal['cout_mensuel_moyen'] ?? 0, 2, ',', ' ') . ' DA</strong><br><small>Coût mensuel parc</small></td>
                </tr>
            </table>
        </div>';

        // Par véhicule
        $vRows = '';
        foreach ($parVehicule as $v) {
            $vLabel = $v['vehicule'] ? ($v['vehicule']['numero'] ?? (($v['vehicule']['marque'] ?? '') . ' ' . ($v['vehicule']['modele'] ?? ''))) : '-';
            $vRows .= '<tr>
                <td>' . e(trim($vLabel)) . '</td>
                <td style="text-align:right">' . number_format($v['km_parcourus'], 0, ',', ' ') . '</td>
                <td style="text-align:center">' . $v['nb_pleins'] . '</td>
                <td style="text-align:right">' . number_format($v['total_litres'], 2, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($v['total_depenses'], 2, ',', ' ') . '</td>
                <td style="text-align:right;font-weight:bold">' . number_format($v['cout_par_km'], 2, ',', ' ') . '</td>
                <td style="text-align:right;font-weight:bold">' . number_format($v['conso_moyenne'], 2, ',', ' ') . '</td>
            </tr>';
        }

        // Par conducteur
        $cRows = '';
        foreach ($parConducteur as $c) {
            $cLabel = $c['chauffeur'] ? (trim(($c['chauffeur']['nom'] ?? '') . ' ' . ($c['chauffeur']['prenom'] ?? '')) ?: '-') : '-';
            $cRows .= '<tr>
                <td>' . e($cLabel) . '</td>
                <td style="text-align:center">' . $c['nb_pleins'] . '</td>
                <td style="text-align:right">' . number_format($c['total_litres'], 2, ',', ' ') . '</td>
                <td style="text-align:right">' . number_format($c['total_depenses'], 2, ',', ' ') . '</td>
            </tr>';
        }

        $html = $this->pdfWrapper(
            'Statistiques Carburant',
            $kpis .
            '<h3 style="font-size:13px;margin:15px 0 5px;color:#1e2d78;">Consommation par véhicule</h3>
            <table>
                <thead><tr>
                    <th>Véhicule</th><th style="text-align:right">Km</th><th style="text-align:center">Pleins</th>
                    <th style="text-align:right">Litres</th><th style="text-align:right">Dépense</th>
                    <th style="text-align:right">Coût/km</th><th style="text-align:right">Conso (L/km)</th>
                </tr></thead>
                <tbody>' . $vRows . '</tbody>
            </table>
            <h3 style="font-size:13px;margin:15px 0 5px;color:#1e2d78;">Consommation par conducteur</h3>
            <table>
                <thead><tr>
                    <th>Conducteur</th><th style="text-align:center">Pleins</th>
                    <th style="text-align:right">Litres</th><th style="text-align:right">Dépense</th>
                </tr></thead>
                <tbody>' . $cRows . '</tbody>
            </table>'
        );

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->SetTitle('Statistiques carburant');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="stats-carburant-' . date('Y-m-d') . '.pdf"',
        ]);
    }

    /**
     * Helper – Enveloppe HTML pour les exports PDF
     */
    private function pdfWrapper($title, $body)
    {
        return '
        <html><head><style>
            body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
            h2 { color: #1e2d78; font-size: 16px; margin-bottom: 5px; }
            .subtitle { color: #666; font-size: 11px; margin-bottom: 15px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            th { background: #1e2d78; color: #fff; padding: 6px 8px; font-size: 10px; text-align: left; }
            td { border-bottom: 1px solid #ddd; padding: 5px 8px; font-size: 10px; }
            tr:nth-child(even) td { background: #f9fafc; }
            tfoot td { border-top: 2px solid #1e2d78; }
        </style></head><body>
            <h2>' . $title . '</h2>
            <p class="subtitle">Généré le ' . Carbon::now()->format('d/m/Y à H:i') . '</p>
            ' . $body . '
        </body></html>';
    }
}
