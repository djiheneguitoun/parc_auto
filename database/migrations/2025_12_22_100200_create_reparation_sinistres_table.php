<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reparation_sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinistre_id')->constrained('sinistres')->cascadeOnDelete();
            $table->string('garage')->nullable();
            $table->enum('type_reparation', ['mecanique', 'carrosserie'])->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_reelle')->nullable();
            $table->decimal('cout_reparation', 15, 2)->nullable();
            $table->enum('prise_en_charge', ['assurance', 'societe'])->default('societe');
            $table->enum('statut_reparation', ['en_attente', 'en_cours', 'termine'])->default('en_attente');
            $table->string('facture_reference', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparation_sinistres');
    }
};
