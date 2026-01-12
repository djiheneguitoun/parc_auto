<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssuranceSinistre;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AssuranceSinistreController extends Controller
{
    private const DECISIONS = ['accepte', 'refuse', 'en_attente'];
    private const STATUTS = ['en_cours', 'valide', 'refuse'];

    /**
     * Generate a unique insurance dossier number
     */
    private function generateNumeroDossier(): string
    {
        $year = Carbon::now()->year;
        $lastAssurance = AssuranceSinistre::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();
        
        $nextNumber = 1;
        if ($lastAssurance && $lastAssurance->numero_dossier) {
            if (preg_match('/ASS-' . $year . '-(\d+)$/', $lastAssurance->numero_dossier, $matches)) {
                $nextNumber = (int)$matches[1] + 1;
            }
        }
        
        return 'ASS-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['numero_dossier'] = $this->generateNumeroDossier(); // Auto-generate dossier number

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
        $data = $this->validateData($request, $assuranceSinistre, true);
        
        // Auto-update status based on decision
        if (isset($data['decision'])) {
            if ($data['decision'] === 'accepte') {
                $data['statut_assurance'] = 'valide'; // When accepted, status becomes "Validée"
            } elseif ($data['decision'] === 'refuse') {
                $data['statut_assurance'] = 'refuse'; // When refused, status becomes "Refusée"
            }
        }
        
        $assuranceSinistre->update($data);

        return $assuranceSinistre->load('sinistre');
    }

    public function destroy(AssuranceSinistre $assuranceSinistre)
    {
        $sinistre = $assuranceSinistre->sinistre;
        $assuranceSinistre->delete();

        // Si le sinistre n'a pas de réparations, retour à "declare"
        if ($sinistre) {
            $hasReparations = $sinistre->reparations()->exists();
            if (!$hasReparations) {
                $sinistre->update(['statut_sinistre' => 'declare']);
            }
        }

        return response()->noContent();
    }

    private function validateData(Request $request, ?AssuranceSinistre $current = null, bool $isUpdate = false): array
    {
        $uniqueSinistre = Rule::unique('assurance_sinistres', 'sinistre_id');
        if ($current) {
            $uniqueSinistre = $uniqueSinistre->ignore($current->id);
        }

        $rules = [
            'sinistre_id' => [$isUpdate ? 'sometimes' : 'required', 'exists:sinistres,id', $uniqueSinistre],
            'compagnie_assurance' => ['nullable', 'string', 'max:255'],
            'numero_dossier' => ['nullable', 'string', 'max:150'],
            'date_declaration' => ['nullable', 'date'],
            'expert_nom' => ['nullable', 'string', 'max:255'],
            'date_expertise' => ['nullable', 'date'],
            'decision' => [$isUpdate ? 'sometimes' : 'nullable', 'string', 'max:255'],
            'montant_pris_en_charge' => ['nullable', 'numeric', 'min:0'],
            'franchise' => ['nullable', 'numeric', 'min:0'],
            'date_validation' => ['nullable', 'date'],
            'statut_assurance' => [$isUpdate ? 'sometimes' : 'nullable', 'string', 'max:255'],
        ];

        return $request->validate($rules);
    }

    private function updateSinistreStatut(?Sinistre $sinistre, string $statut): void
    {
        if (!$sinistre) {
            return;
        }
        $sinistre->update(['statut_sinistre' => $statut]);
    }
}
