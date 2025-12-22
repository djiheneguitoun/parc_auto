ALTER TABLE vehicules MODIFY categorie ENUM('leger','lourd','transport','tracteur','engins') NULL;


ALTER TABLE vehicules MODIFY energie ENUM('essence','diesel','gpl','electrique') NULL;



ALTER TABLE vehicules
  ADD COLUMN etat_fonctionnel ENUM(
    'disponible','utilisation','technique',
    'reglementaire','incident','fin_de_vie'
  ) NOT NULL DEFAULT 'disponible' AFTER valeur;

ALTER TABLE vehicules
  MODIFY statut ENUM(
    'disponible','en_service','reserve',
    'en_maintenance','en_panne','en_reparation',
    'non_conforme','interdit',
    'sinistre','en_expertise',
    'reforme','sorti_du_parc'
  ) NOT NULL DEFAULT 'disponible';



ALTER TABLE chauffeurs
  ADD COLUMN comportement ENUM(
    'excellent','tres_bon','satisfaisant','a_ameliorer','insuffisant','non_conforme','a_risque'
  )  NOT NULL DEFAULT 'satisfaisant';


ALTER TABLE chauffeurs
  MODIFY mention ENUM('excellent','tres_bon','bon','moyen','insuffisant') NOT NULL DEFAULT 'bon';