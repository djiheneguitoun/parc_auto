<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehiculeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculeImageController extends Controller
{
    public function index(Request $request)
    {
        $query = VehiculeImage::query();

        if ($vehiculeId = $request->get('vehicule_id')) {
            $query->where('vehicule_id', $vehiculeId);
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'image' => 'nullable|image|max:4096',
            'image_path' => 'required_without:image|string|max:255',
        ]);

        $path = $data['image_path'] ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('vehicules', 'public');
        }

        $image = VehiculeImage::create([
            'vehicule_id' => $data['vehicule_id'],
            'image_path' => $path,
        ]);

        return response()->json($image, 201);
    }

    public function destroy(VehiculeImage $vehiculeImage)
    {
        if ($vehiculeImage->image_path && Storage::disk('public')->exists($vehiculeImage->image_path)) {
            Storage::disk('public')->delete($vehiculeImage->image_path);
        }

        $vehiculeImage->delete();

        return response()->noContent();
    }
}
