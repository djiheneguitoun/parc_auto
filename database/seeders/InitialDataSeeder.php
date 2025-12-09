<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $parametreId = DB::table('parametres')->insertGetId([
            'nom_entreprise' => 'Parc Auto Demo',
            'lien_archive_facture' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $utilisateurId = DB::table('utilisateurs')->insertGetId([
            'nom' => 'Administrateur',
            'cle' => 'ADMIN-001',
            'role' => 'administratif',
            'actif' => true,
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('utilisateurs')->insert([
            [
                'nom' => 'Responsable Parc',
                'cle' => 'RESP-001',
                'role' => 'responsable',
                'actif' => true,
                'email' => 'responsable@example.test',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nom' => 'Agent Parc',
                'cle' => 'AGENT-001',
                'role' => 'agent',
                'actif' => true,
                'email' => 'agent@example.test',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $chauffeurId = DB::table('chauffeurs')->insertGetId([
            'matricule' => 'CHF-001',
            'nom' => 'Doe',
            'prenom' => 'John',
            'date_naissance' => Carbon::create(1990, 4, 12),
            'date_recrutement' => Carbon::create(2020, 6, 1),
            'adresse' => '123 Rue Principale',
            'telephone' => '+212600000001',
            'numero_permis' => 'PER-2020-001',
            'date_permis' => Carbon::create(2018, 5, 20),
            'lieu_permis' => 'Casablanca',
            'statut' => 'contractuel',
            'mention' => 'bien',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $vehiculeId = DB::table('vehicules')->insertGetId([
            'numero' => 'VH-001',
            'code' => 'VHC-2025-001',
            'description' => 'SUV de pool pour les missions quotidiennes',
            'marque' => 'Toyota',
            'modele' => 'RAV4',
            'annee' => 2023,
            'couleur' => 'Gris',
            'chassis' => 'CHS-123456789',
            'chauffeur_id' => $chauffeurId,
            'date_acquisition' => Carbon::create(2023, 3, 15),
            'valeur' => '35000.00',
            'statut' => true,
            'date_creation' => $now,
            'categorie' => 'leger',
            'option_vehicule' => 'toutes_options',
            'energie' => 'diesel',
            'boite' => 'auto',
            'leasing' => 'acquisition',
            'utilisation' => 'professionnel',
            'affectation' => 'Direction technique',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('vehicule_documents')->insert([
            [
                'vehicule_id' => $vehiculeId,
                'type' => 'assurance',
                'numero' => 'ASS-2025-001',
                'libele' => 'Assurance annuelle',
                'partenaire' => 'Assureur Demo',
                'debut' => Carbon::create(2025, 1, 1),
                'expiration' => Carbon::create(2025, 12, 31),
                'valeur' => '800.00',
                'num_facture' => 'FAC-ASS-001',
                'date_facture' => Carbon::create(2025, 1, 1),
                'vidange' => null,
                'kilometrage' => null,
                'piece' => null,
                'reparateur' => null,
                'type_reparation' => null,
                'date_reparation' => null,
                'typecarburant' => null,
                'utilisation' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vehicule_id' => $vehiculeId,
                'type' => 'entretien',
                'numero' => 'ENT-2025-001',
                'libele' => 'Vidange et filtres',
                'partenaire' => 'Garage Demo',
                'debut' => Carbon::create(2025, 2, 10),
                'expiration' => Carbon::create(2025, 8, 10),
                'valeur' => '150.00',
                'num_facture' => 'FAC-ENT-001',
                'date_facture' => Carbon::create(2025, 2, 10),
                'vidange' => 'complet',
                'kilometrage' => 15000,
                'piece' => 'Filtre a huile',
                'reparateur' => 'Garage Demo',
                'type_reparation' => 'mecanique',
                'date_reparation' => Carbon::create(2025, 2, 10),
                'typecarburant' => 'gasoil',
                'utilisation' => 'trajet',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('vehicule_images')->insert([
            [
                'vehicule_id' => $vehiculeId,
                'image_path' => 'images/vehicules/vh-001-face.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vehicule_id' => $vehiculeId,
                'image_path' => 'images/vehicules/vh-001-interieur.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
