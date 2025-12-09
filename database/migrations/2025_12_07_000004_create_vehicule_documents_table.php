<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicule_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('vehicules')->cascadeOnDelete();
            $table->enum('type', ['assurance', 'vignette', 'controle', 'entretien', 'reparation', 'bon_essence']);
            $table->string('numero', 120)->nullable();
            $table->string('libele', 150)->nullable();
            $table->string('partenaire', 150)->nullable();
            $table->date('debut')->nullable();
            $table->date('expiration')->nullable();
            $table->decimal('valeur', 15, 2)->nullable();
            $table->string('num_facture', 120)->nullable();
            $table->date('date_facture')->nullable();
            $table->enum('vidange', ['complet', 'partiel'])->nullable();
            $table->unsignedInteger('kilometrage')->nullable();
            $table->string('piece', 150)->nullable();
            $table->string('reparateur', 150)->nullable();
            $table->enum('type_reparation', ['carosserie', 'mecanique'])->nullable();
            $table->date('date_reparation')->nullable();
            $table->enum('typecarburant', ['essence', 'gasoil', 'gpl'])->nullable();
            $table->enum('utilisation', ['trajet', 'interne'])->nullable();
            $table->timestamps();
            $table->index(['vehicule_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicule_documents');
    }
};
