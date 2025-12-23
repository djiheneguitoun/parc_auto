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
        $reparation = ReparationSinistre::create($data);
        $this->syncSinistreStatut($reparation->sinistre, $reparation);

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

        $this->syncSinistreStatut($reparationSinistre->sinistre, $reparationSinistre);

        return $reparationSinistre->load('sinistre');
    }

    public function destroy(ReparationSinistre $reparationSinistre)
    {
        $sinistre = $reparationSinistre->sinistre;
        $reparationSinistre->delete();

        if ($sinistre && $sinistre->statut_sinistre !== 'clos') {
            $hasActiveRep = $sinistre->reparations()->whereIn('statut_reparation', ['en_attente', 'en_cours'])->exists();
            $sinistre->update([
                'statut_sinistre' => $hasActiveRep ? 'en_reparation' : 'en_cours',
            ]);
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

    private function syncSinistreStatut(?Sinistre $sinistre, ReparationSinistre $reparation): void
    {
        if (!$sinistre) {
            return;
        }

        $statutReparation = $reparation->statut_reparation;

        if ($statutReparation === 'termine') {
            $sinistre->update(['statut_sinistre' => 'clos']);
            return;
        }

        if ($reparation->date_debut || $statutReparation === 'en_cours') {
            $sinistre->update(['statut_sinistre' => 'en_reparation']);
            return;
        }

        if ($sinistre->assurance) {
            $sinistre->update(['statut_sinistre' => 'en_cours']);
        }
    }
}
