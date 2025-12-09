<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChauffeurController;
use App\Http\Controllers\Api\ParametreController;
use App\Http\Controllers\Api\UtilisateurController;
use App\Http\Controllers\Api\VehiculeController;
use App\Http\Controllers\Api\VehiculeDocumentController;
use App\Http\Controllers\Api\VehiculeImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::apiResource('chauffeurs', ChauffeurController::class);

    Route::get('parametres', [ParametreController::class, 'show']);
    Route::put('parametres', [ParametreController::class, 'update']);

    Route::apiResource('utilisateurs', UtilisateurController::class);
    Route::post('utilisateurs/{utilisateur}/toggle', [UtilisateurController::class, 'toggle']);
    Route::post('utilisateurs/{utilisateur}/assign-role', [UtilisateurController::class, 'assignRole']);

    Route::apiResource('vehicules', VehiculeController::class);
    Route::post('vehicules/{vehicule}/assign-chauffeur', [VehiculeController::class, 'assignChauffeur']);

    Route::apiResource('vehicule-documents', VehiculeDocumentController::class);

    Route::apiResource('vehicule-images', VehiculeImageController::class)->only(['index', 'store', 'destroy']);
});
