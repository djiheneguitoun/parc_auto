<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssuranceSinistre;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssuranceSinistreController extends Controller
{
    private const DECISIONS = ['accepte', 'refuse', 'en_attente'];
    private const STATUTS = ['en_cours', 'valide', 'refuse'];

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $assurance = AssuranceSinistre::create($data);
        $this->updateSinistreStatut($assurance->sinistre, 'en_cours');

        return response()->json($assurance->load('sinistre'), 201);
    }

    public function show(AssuranceSinistre $assuranceSinistre)
    {
        return $assuranceSinistre->load('sinistre');
    }

    public function update(Request $request, AssuranceSinistre $assuranceSinistre)
    {
        $data = $this->validateData($request, $assuranceSinistre);
        $assuranceSinistre->update($data);

        $this->updateSinistreStatut($assuranceSinistre->sinistre, 'en_cours');

        return $assuranceSinistre->load('sinistre');
    }

    public function destroy(AssuranceSinistre $assuranceSinistre)
    {
        $sinistre = $assuranceSinistre->sinistre;
        $assuranceSinistre->delete();

        if ($sinistre && $sinistre->statut_sinistre !== 'clos') {
            $hasActiveRep = $sinistre->reparations()->whereIn('statut_reparation', ['en_attente', 'en_cours'])->exists();
            $sinistre->update([
                'statut_sinistre' => $hasActiveRep ? 'en_reparation' : 'declare',
            ]);
        }

        return response()->noContent();
    }

    private function validateData(Request $request, ?AssuranceSinistre $current = null): array
    {
        $uniqueSinistre = Rule::unique('assurance_sinistres', 'sinistre_id');
        if ($current) {
            $uniqueSinistre = $uniqueSinistre->ignore($current->id);
        }

        return $request->validate([
            'sinistre_id' => ['required', 'exists:sinistres,id', $uniqueSinistre],
            'compagnie_assurance' => ['nullable', 'string', 'max:255'],
            'numero_dossier' => ['nullable', 'string', 'max:150'],
            'date_declaration' => ['nullable', 'date'],
            'expert_nom' => ['nullable', 'string', 'max:255'],
            'date_expertise' => ['nullable', 'date'],
            'decision' => ['required', Rule::in(self::DECISIONS)],
            'montant_pris_en_charge' => ['nullable', 'numeric', 'min:0'],
            'franchise' => ['nullable', 'numeric', 'min:0'],
            'date_validation' => ['nullable', 'date'],
            'statut_assurance' => ['required', Rule::in(self::STATUTS)],
        ]);
    }

    private function updateSinistreStatut(?Sinistre $sinistre, string $statut): void
    {
        if (!$sinistre) {
            return;
        }
        if ($sinistre->statut_sinistre === 'clos') {
            return; // ne pas rouvrir un dossier clôturé
        }
        $sinistre->update(['statut_sinistre' => $statut]);
    }
}
