<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 50)->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
            $table->date('date_recrutement')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('numero_permis', 100)->nullable();
            $table->date('date_permis')->nullable();
            $table->string('lieu_permis', 150)->nullable();
            $table->enum('statut', ['contractuel', 'permanent'])->default('contractuel');
            $table->enum('mention', ['excellent', 'tres_bon', 'bon', 'moyen', 'insuffisant'])->default('bon');
            $table->enum('comportement', [
                'excellent',
                'tres_bon',
                'satisfaisant',
                'a_ameliorer',
                'insuffisant',
                'non_conforme',
                'a_risque',
            ])->default('satisfaisant');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chauffeurs');
    }
};
