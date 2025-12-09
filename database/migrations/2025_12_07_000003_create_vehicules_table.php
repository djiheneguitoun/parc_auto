<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->unsignedSmallInteger('annee')->nullable();
            $table->string('couleur', 50)->nullable();
            $table->string('chassis', 120)->nullable();
            $table->foreignId('chauffeur_id')->nullable()->constrained('chauffeurs')->nullOnDelete();
            $table->date('date_acquisition')->nullable();
            $table->decimal('valeur', 15, 2)->nullable();
            $table->boolean('statut')->default(true);
            $table->timestamp('date_creation')->useCurrent();
            $table->enum('categorie', ['leger', 'lourd', 'transport'])->nullable();
            $table->enum('option_vehicule', ['base', 'base_clim', 'toutes_options'])->nullable();
            $table->enum('energie', ['essence', 'diesel', 'gpl'])->nullable();
            $table->enum('boite', ['semiauto', 'auto', 'manuel'])->nullable();
            $table->enum('leasing', ['location', 'acquisition', 'autre'])->nullable();
            $table->enum('utilisation', ['personnel', 'professionnel'])->nullable();
            $table->string('affectation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
