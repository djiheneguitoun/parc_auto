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


-- Module Sinistres
CREATE TABLE sinistres (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero_sinistre VARCHAR(120) NOT NULL UNIQUE,
  vehicule_id BIGINT UNSIGNED NOT NULL,
  chauffeur_id BIGINT UNSIGNED NULL,
  date_sinistre DATE NOT NULL,
  heure_sinistre TIME NULL,
  lieu_sinistre VARCHAR(255) NULL,
  type_sinistre ENUM('accident','panne','vol','incendie') NOT NULL,
  description TEXT NULL,
  gravite ENUM('mineur','moyen','grave') NOT NULL DEFAULT 'mineur',
  responsable ENUM('chauffeur','tiers','inconnu') NOT NULL DEFAULT 'inconnu',
  statut_sinistre ENUM('declare','en_cours','en_reparation','clos') NOT NULL DEFAULT 'declare',
  montant_estime DECIMAL(15,2) NULL,
  cree_par BIGINT UNSIGNED NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_sinistre_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
  CONSTRAINT fk_sinistre_chauffeur FOREIGN KEY (chauffeur_id) REFERENCES chauffeurs(id) ON DELETE SET NULL,
  CONSTRAINT fk_sinistre_utilisateur FOREIGN KEY (cree_par) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE assurance_sinistres (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sinistre_id INT UNSIGNED NOT NULL UNIQUE,
  compagnie_assurance VARCHAR(255) NULL,
  numero_dossier VARCHAR(150) NULL,
  date_declaration DATE NULL,
  expert_nom VARCHAR(255) NULL,
  date_expertise DATE NULL,
  decision ENUM('accepte','refuse','en_attente') NOT NULL DEFAULT 'en_attente',
  montant_pris_en_charge DECIMAL(15,2) NULL,
  franchise DECIMAL(15,2) NULL,
  date_validation DATE NULL,
  statut_assurance ENUM('en_cours','valide','refuse') NOT NULL DEFAULT 'en_cours',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_assurance_sinistre FOREIGN KEY (sinistre_id) REFERENCES sinistres(id) ON DELETE CASCADE
);

CREATE TABLE reparation_sinistres (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sinistre_id INT UNSIGNED NOT NULL,
  garage VARCHAR(255) NULL,
  type_reparation ENUM('mecanique','carrosserie') NULL,
  date_debut DATE NULL,
  date_fin_prevue DATE NULL,
  date_fin_reelle DATE NULL,
  cout_reparation DECIMAL(15,2) NULL,
  prise_en_charge ENUM('assurance','societe') NOT NULL DEFAULT 'societe',
  statut_reparation ENUM('en_attente','en_cours','termine') NOT NULL DEFAULT 'en_attente',
  facture_reference VARCHAR(150) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_reparation_sinistre FOREIGN KEY (sinistre_id) REFERENCES sinistres(id) ON DELETE CASCADE
);


ALTER TABLE vehicules MODIFY COLUMN date_creation TIMESTAMP NULL DEFAULT NULL;

