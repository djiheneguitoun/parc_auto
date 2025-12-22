<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL ENUM modification requires raw SQL.
        DB::statement("ALTER TABLE `vehicules` MODIFY `energie` ENUM('essence','diesel','gpl','electrique') NULL");
    }

    public function down(): void
    {
        // Revert to previous values (will fail if rows contain 'electrique').
        DB::statement("ALTER TABLE `vehicules` MODIFY `energie` ENUM('essence','diesel','gpl') NULL");
    }
};
