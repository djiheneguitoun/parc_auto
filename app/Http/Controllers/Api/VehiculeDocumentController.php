<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehiculeDocument;
use Illuminate\Http\Request;

class VehiculeDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = VehiculeDocument::with('vehicule');

        if ($vehiculeId = $request->get('vehicule_id')) {
            $query->where('vehicule_id', $vehiculeId);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $document = VehiculeDocument::create($data);

        return response()->json($document->load('vehicule'), 201);
    }

    public function show(VehiculeDocument $vehiculeDocument)
    {
        return $vehiculeDocument->load('vehicule');
    }

    public function update(Request $request, VehiculeDocument $vehiculeDocument)
    {
        $data = $this->validateData($request, false);

        $vehiculeDocument->update($data);

        return $vehiculeDocument->load('vehicule');
    }

    public function destroy(VehiculeDocument $vehiculeDocument)
    {
        $vehiculeDocument->delete();

        return response()->noContent();
    }

    private function validateData(Request $request, bool $vehiculeRequired = true): array
    {
        return $request->validate([
            'vehicule_id' => ($vehiculeRequired ? 'required' : 'sometimes') . '|exists:vehicules,id',
            'type' => ($vehiculeRequired ? 'required' : 'sometimes') . '|in:assurance,vignette,controle,entretien,reparation,bon_essence',
            'numero' => 'nullable|string|max:120',
            'libele' => 'nullable|string|max:150',
            'partenaire' => 'nullable|string|max:150',
            'debut' => 'nullable|date',
            'expiration' => 'nullable|date',
            'valeur' => 'nullable|numeric|min:0',
            'num_facture' => 'nullable|string|max:120',
            'date_facture' => 'nullable|date',
            'vidange' => 'nullable|in:complet,partiel',
            'kilometrage' => 'nullable|integer|min:0',
            'piece' => 'nullable|string|max:150',
            'reparateur' => 'nullable|string|max:150',
            'type_reparation' => 'nullable|in:carosserie,mecanique',
            'date_reparation' => 'nullable|date',
            'typecarburant' => 'nullable|in:essence,gasoil,gpl',
            'utilisation' => 'nullable|in:trajet,interne',
        ]);
    }
}
