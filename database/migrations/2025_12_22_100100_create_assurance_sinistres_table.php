<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assurance_sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinistre_id')->unique()->constrained('sinistres')->cascadeOnDelete();
            $table->string('compagnie_assurance')->nullable();
            $table->string('numero_dossier', 150)->nullable();
            $table->date('date_declaration')->nullable();
            $table->string('expert_nom')->nullable();
            $table->date('date_expertise')->nullable();
            $table->enum('decision', ['accepte', 'refuse', 'en_attente'])->default('en_attente');
            $table->decimal('montant_pris_en_charge', 15, 2)->nullable();
            $table->decimal('franchise', 15, 2)->nullable();
            $table->date('date_validation')->nullable();
            $table->enum('statut_assurance', ['en_cours', 'valide', 'refuse'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assurance_sinistres');
    }
};
