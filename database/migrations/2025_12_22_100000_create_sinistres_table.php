<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->string('numero_sinistre', 120)->unique();
            $table->foreignId('vehicule_id')->constrained('vehicules')->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->nullable()->constrained('chauffeurs')->nullOnDelete();
            $table->date('date_sinistre');
            $table->time('heure_sinistre')->nullable();
            $table->string('lieu_sinistre')->nullable();
            $table->enum('type_sinistre', ['accident', 'panne', 'vol', 'incendie']);
            $table->text('description')->nullable();
            $table->enum('gravite', ['mineur', 'moyen', 'grave'])->default('mineur');
            $table->enum('responsable', ['chauffeur', 'tiers', 'inconnu'])->default('inconnu');
            $table->enum('statut_sinistre', ['declare', 'en_cours', 'en_reparation', 'clos'])->default('declare');
            $table->decimal('montant_estime', 15, 2)->nullable();
            $table->foreignId('cree_par')->nullable()->constrained('utilisateurs')->nullOnDelete();
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sinistres');
    }
};
