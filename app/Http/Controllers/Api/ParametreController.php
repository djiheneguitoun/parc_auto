<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function show()
    {
        return Parametre::first();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nom_entreprise' => 'required|string|max:150',
            // Accept any link string to avoid rejecting valid internal or file URLs
            'lien_archive_facture' => 'nullable|string|max:255',
        ]);

        $parametre = Parametre::first();
        if (!$parametre) {
            $parametre = Parametre::create($data);
        } else {
            $parametre->update($data);
        }

        return $parametre;
    }
}
