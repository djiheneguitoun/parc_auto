<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE vehicules ADD COLUMN etat_fonctionnel ENUM('disponible','utilisation','technique','reglementaire','incident','fin_de_vie') NOT NULL DEFAULT 'disponible' AFTER valeur;");

        DB::statement("ALTER TABLE vehicules ADD COLUMN statut_new ENUM('disponible','en_service','reserve','en_maintenance','en_panne','en_reparation','non_conforme','interdit','sinistre','en_expertise','reforme','sorti_du_parc') NOT NULL DEFAULT 'disponible' AFTER etat_fonctionnel;");

        DB::statement("UPDATE vehicules SET statut_new = CASE WHEN statut = 0 THEN 'en_panne' ELSE 'disponible' END;");

        DB::statement("ALTER TABLE vehicules DROP COLUMN statut;");
        DB::statement("ALTER TABLE vehicules CHANGE COLUMN statut_new statut ENUM('disponible','en_service','reserve','en_maintenance','en_panne','en_reparation','non_conforme','interdit','sinistre','en_expertise','reforme','sorti_du_parc') NOT NULL DEFAULT 'disponible';");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vehicules ADD COLUMN statut_old TINYINT(1) NOT NULL DEFAULT 1 AFTER valeur;");
        DB::statement("UPDATE vehicules SET statut_old = CASE WHEN statut IN ('en_panne','en_reparation','en_maintenance') THEN 0 ELSE 1 END;");

        DB::statement("ALTER TABLE vehicules DROP COLUMN statut;");
        DB::statement("ALTER TABLE vehicules CHANGE COLUMN statut_old statut TINYINT(1) NOT NULL DEFAULT 1;");

        DB::statement("ALTER TABLE vehicules DROP COLUMN etat_fonctionnel;");
    }
};
