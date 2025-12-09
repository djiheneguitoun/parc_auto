<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicule_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('vehicules')->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
            $table->index('vehicule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicule_images');
    }
};
