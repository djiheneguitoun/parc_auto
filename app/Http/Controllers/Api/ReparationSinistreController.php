<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReparationSinistre;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReparationSinistreController extends Controller
{
    private const TYPES = ['mecanique', 'carrosserie'];
    private const PRISES_EN_CHARGE = ['assurance', 'societe'];
    private const STATUTS = ['en_attente', 'en_cours', 'termine'];

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        
        // Get the sinistre to check assurance status
        $sinistre = Sinistre::with('assurance')->find($data['sinistre_id']);
        
        // RÈGLE: Vérifier que l'assurance a une décision (acceptée ou refusée) avant de créer une réparation
        if ($sinistre && $sinistre->assurance) {
            $assuranceDecision = $sinistre->assurance->decision;
            if ($assuranceDecision === 'en_attente') {
                return response()->json([
                    'message' => 'Impossible de créer une réparation : veuillez attendre la décision de l\'assurance.'
                ], 422);
            }
        }
        
        // Use provided status or fall back to default "En cours"
        $data['statut_reparation'] = $data['statut_reparation'] ?? 'en_cours';
        
        // Determine default prise_en_charge only if not provided
        if (!isset($data['prise_en_charge'])) {
            $assurance = $sinistre?->assurance;
            if (!$assurance || $assurance->decision === 'refuse') {
                $data['prise_en_charge'] = 'societe';
            } else {
                $data['prise_en_charge'] = 'assurance';
            }
        }
        
        $reparation = ReparationSinistre::create($data);
        
        // RÈGLE: Création réparation -> sinistre passe à "en_reparation"
        if ($sinistre) {
            $sinistre->update(['statut_sinistre' => 'en_reparation']);
        }

        return response()->json($reparation->load('sinistre'), 201);
    }

    public function show(ReparationSinistre $reparationSinistre)
    {
        return $reparationSinistre->load('sinistre');
    }

    public function update(Request $request, ReparationSinistre $reparationSinistre)
    {
        $data = $this->validateData($request, true);
        $reparationSinistre->update($data);

        $this->syncSinistreStatut($reparationSinistre->sinistre);

        return $reparationSinistre->load('sinistre');
    }

    public function destroy(ReparationSinistre $reparationSinistre)
    {
        $sinistre = $reparationSinistre->sinistre;
        $reparationSinistre->delete();

        if ($sinistre) {
            // Recharger les réparations restantes
            $sinistre->load('reparations');
            $reparations = $sinistre->reparations;
            
            if ($reparations->isEmpty()) {
                // Plus de réparations -> retour à "en_cours" s'il y a une assurance, sinon "declare"
                $sinistre->load('assurance');
                $newStatus = $sinistre->assurance ? 'en_cours' : 'declare';
                $sinistre->update(['statut_sinistre' => $newStatus]);
            } else {
                $hasUnfinishedRepairs = $reparations->where('statut_reparation', '!=', 'termine')->isNotEmpty();
                if ($hasUnfinishedRepairs) {
                    $sinistre->update(['statut_sinistre' => 'en_reparation']);
                } else {
                    // Toutes les réparations restantes sont terminées -> clos
                    $sinistre->update(['statut_sinistre' => 'clos']);
                }
            }
        }

        return response()->noContent();
    }

    private function validateData(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'sinistre_id' => [$isUpdate ? 'sometimes' : 'required', 'exists:sinistres,id'],
            'garage' => ['nullable', 'string', 'max:255'],
            'type_reparation' => ['nullable', Rule::in(self::TYPES)],
            'date_debut' => ['nullable', 'date'],
            'date_fin_prevue' => ['nullable', 'date'],
            'date_fin_reelle' => ['nullable', 'date'],
            'cout_reparation' => ['nullable', 'numeric', 'min:0'],
            'prise_en_charge' => ['nullable', Rule::in(self::PRISES_EN_CHARGE)],
            'statut_reparation' => ['nullable', Rule::in(self::STATUTS)],
            'facture_reference' => ['nullable', 'string', 'max:150'],
        ]);
    }

    private function syncSinistreStatut(?Sinistre $sinistre): void
    {
        if (!$sinistre) {
            return;
        }

        // Reload reparations to get fresh data
        $sinistre->load('reparations');
        $reparations = $sinistre->reparations;
        
        if ($reparations->isEmpty()) {
            return;
        }
        
        // Check if there are any non-finished repairs
        $hasUnfinishedRepairs = $reparations->where('statut_reparation', '!=', 'termine')->isNotEmpty();
        
        if ($hasUnfinishedRepairs) {
            // RÈGLE: Il reste des réparations non terminées -> "en_reparation"
            $sinistre->update(['statut_sinistre' => 'en_reparation']);
        } else {
            // RÈGLE: Toutes les réparations sont terminées -> "clos"
            $sinistre->update(['statut_sinistre' => 'clos']);
        }
    }
}
