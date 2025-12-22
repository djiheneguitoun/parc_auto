<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL ENUM modification requires raw SQL.
        DB::statement("ALTER TABLE `vehicules` MODIFY `categorie` ENUM('leger','lourd','transport','tracteur','engins') NULL");
    }

    public function down(): void
    {
        // Revert to previous values (will fail if rows contain 'tracteur'/'engins').
        DB::statement("ALTER TABLE `vehicules` MODIFY `categorie` ENUM('leger','lourd','transport') NULL");
    }
};
