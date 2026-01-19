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


-- ============================================================================
-- MODULE ENTRETIEN & RÉPARATION (INTERVENTIONS)
-- ============================================================================

-- Table intervention_type : Distingue Entretien / Réparation
CREATE TABLE intervention_types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  libelle VARCHAR(50) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Données fixes intervention_type
INSERT INTO intervention_types (code, libelle) VALUES
('ENT', 'Entretien'),
('REP', 'Réparation');

-- Table intervention_categorie : Catégories techniques communes
CREATE TABLE intervention_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  libelle VARCHAR(100) NOT NULL,
  actif BOOLEAN DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Données initiales intervention_categorie
INSERT INTO intervention_categories (code, libelle) VALUES
('MOT', 'Moteur'),
('FRE', 'Freinage'),
('ELE', 'Électricité'),
('PNE', 'Pneumatiques'),
('CLI', 'Climatisation'),
('CAR', 'Carrosserie'),
('REG', 'Réglementaire'),
('MEC', 'Mécanique générale'),
('TRA', 'Transmission'),
('SUS', 'Suspension'),
('DIR', 'Direction'),
('ECH', 'Échappement'),
('FIL', 'Filtration'),
('LUB', 'Lubrification'),
('REF', 'Refroidissement');

-- Table intervention_operation : Catalogue normalisé des opérations
CREATE TABLE intervention_operations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(30) NOT NULL UNIQUE,
  libelle VARCHAR(150) NOT NULL,
  type_id INT UNSIGNED NOT NULL,
  categorie_id INT UNSIGNED NOT NULL,
  
  periodicite_km INT NULL COMMENT 'Périodicité en km (uniquement pour entretien)',
  periodicite_mois INT NULL COMMENT 'Périodicité en mois (uniquement pour entretien)',
  cout_estime DECIMAL(12,2) NULL COMMENT 'Coût estimé par défaut',
  actif BOOLEAN DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_operation_type FOREIGN KEY (type_id) REFERENCES intervention_types(id) ON DELETE RESTRICT,
  CONSTRAINT fk_operation_categorie FOREIGN KEY (categorie_id) REFERENCES intervention_categories(id) ON DELETE RESTRICT
);

-- Données initiales intervention_operation (Entretiens)
INSERT INTO intervention_operations (code, libelle, type_id, categorie_id, periodicite_km, periodicite_mois) VALUES
-- Entretiens périodiques (type_id = 1 = ENT)
('VID_MOT', 'Vidange moteur', 1, (SELECT id FROM intervention_categories WHERE code='LUB'), 10000, 6),
('FILT_HUILE', 'Remplacement filtre à huile', 1, (SELECT id FROM intervention_categories WHERE code='FIL'), 10000, 6),
('FILT_AIR', 'Remplacement filtre à air', 1, (SELECT id FROM intervention_categories WHERE code='FIL'), 20000, 12),
('FILT_HABIT', 'Remplacement filtre habitacle', 1, (SELECT id FROM intervention_categories WHERE code='FIL'), NULL, 12),
('FILT_CARB', 'Remplacement filtre à carburant', 1, (SELECT id FROM intervention_categories WHERE code='FIL'), 40000, 24),
('CTRL_FREIN', 'Contrôle plaquettes/disques', 1, (SELECT id FROM intervention_categories WHERE code='FRE'), 20000, 12),
('LIQ_FREIN', 'Vidange liquide de frein', 1, (SELECT id FROM intervention_categories WHERE code='FRE'), 60000, 24),
('LIQ_REFROID', 'Vidange liquide de refroidissement', 1, (SELECT id FROM intervention_categories WHERE code='REF'), 60000, 36),
('CTRL_PNEU', 'Contrôle pression/usure pneus', 1, (SELECT id FROM intervention_categories WHERE code='PNE'), 5000, 3),
('EQUIL_PNEU', 'Équilibrage pneus', 1, (SELECT id FROM intervention_categories WHERE code='PNE'), 20000, 12),
('PARALL', 'Parallélisme', 1, (SELECT id FROM intervention_categories WHERE code='DIR'), 20000, 12),
('COURR_DIST', 'Remplacement courroie distribution', 1, (SELECT id FROM intervention_categories WHERE code='MOT'), 100000, 60),
('COURR_ACC', 'Remplacement courroie accessoires', 1, (SELECT id FROM intervention_categories WHERE code='MOT'), 80000, 48),
('BOUGIES', 'Remplacement bougies', 1, (SELECT id FROM intervention_categories WHERE code='MOT'), 60000, 48),
('CTRL_BATT', 'Contrôle batterie', 1, (SELECT id FROM intervention_categories WHERE code='ELE'), 20000, 12),
('CTRL_CLIM', 'Contrôle climatisation', 1, (SELECT id FROM intervention_categories WHERE code='CLI'), NULL, 12),
('RECHG_CLIM', 'Recharge climatisation', 1, (SELECT id FROM intervention_categories WHERE code='CLI'), NULL, 24),
('CTRL_TECH', 'Contrôle technique', 1, (SELECT id FROM intervention_categories WHERE code='REG'), NULL, 12),
('CTRL_ANTI', 'Contrôle antipollution', 1, (SELECT id FROM intervention_categories WHERE code='REG'), NULL, 12);

-- Données initiales intervention_operation (Réparations - sans périodicité)
INSERT INTO intervention_operations (code, libelle, type_id, categorie_id, periodicite_km, periodicite_mois) VALUES
-- Réparations (type_id = 2 = REP)
('REP_EMBR', 'Remplacement embrayage', 2, (SELECT id FROM intervention_categories WHERE code='TRA'), NULL, NULL),
('REP_DEMARR', 'Remplacement démarreur', 2, (SELECT id FROM intervention_categories WHERE code='ELE'), NULL, NULL),
('REP_ALTER', 'Remplacement alternateur', 2, (SELECT id FROM intervention_categories WHERE code='ELE'), NULL, NULL),
('REP_BATT', 'Remplacement batterie', 2, (SELECT id FROM intervention_categories WHERE code='ELE'), NULL, NULL),
('REP_PLAQ', 'Remplacement plaquettes de frein', 2, (SELECT id FROM intervention_categories WHERE code='FRE'), NULL, NULL),
('REP_DISQ', 'Remplacement disques de frein', 2, (SELECT id FROM intervention_categories WHERE code='FRE'), NULL, NULL),
('REP_PNEU', 'Remplacement pneu(s)', 2, (SELECT id FROM intervention_categories WHERE code='PNE'), NULL, NULL),
('REP_AMORT', 'Remplacement amortisseur(s)', 2, (SELECT id FROM intervention_categories WHERE code='SUS'), NULL, NULL),
('REP_ROULE', 'Remplacement roulement(s)', 2, (SELECT id FROM intervention_categories WHERE code='SUS'), NULL, NULL),
('REP_POMPE_EAU', 'Remplacement pompe à eau', 2, (SELECT id FROM intervention_categories WHERE code='REF'), NULL, NULL),
('REP_RADIAT', 'Remplacement radiateur', 2, (SELECT id FROM intervention_categories WHERE code='REF'), NULL, NULL),
('REP_INJECTEUR', 'Remplacement injecteur(s)', 2, (SELECT id FROM intervention_categories WHERE code='MOT'), NULL, NULL),
('REP_VANNE_EGR', 'Remplacement vanne EGR', 2, (SELECT id FROM intervention_categories WHERE code='MOT'), NULL, NULL),
('REP_TURBO', 'Remplacement turbo', 2, (SELECT id FROM intervention_categories WHERE code='MOT'), NULL, NULL),
('REP_COMPRES', 'Remplacement compresseur clim', 2, (SELECT id FROM intervention_categories WHERE code='CLI'), NULL, NULL),
('REP_ECHAPP', 'Réparation/remplacement échappement', 2, (SELECT id FROM intervention_categories WHERE code='ECH'), NULL, NULL),
('REP_CARROSS', 'Réparation carrosserie', 2, (SELECT id FROM intervention_categories WHERE code='CAR'), NULL, NULL),
('REP_PARE_BRISE', 'Remplacement pare-brise', 2, (SELECT id FROM intervention_categories WHERE code='CAR'), NULL, NULL),
('REP_MOTEUR', 'Réparation moteur', 2, (SELECT id FROM intervention_categories WHERE code='MOT'), NULL, NULL),
('REP_BOITE', 'Réparation boîte de vitesses', 2, (SELECT id FROM intervention_categories WHERE code='TRA'), NULL, NULL);

-- Table intervention_vehicule : Historique réel des interventions
CREATE TABLE intervention_vehicules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  vehicule_id BIGINT UNSIGNED NOT NULL,
  operation_id INT UNSIGNED NOT NULL,
  date_intervention DATE NOT NULL,
  description TEXT NULL,
  kilometrage INT NULL,
  cout DECIMAL(12,2) NULL,
  prestataire VARCHAR(255) NULL COMMENT 'Nom du garage/prestataire',
  immobilisation_jours INT NULL DEFAULT 0,

  statut ENUM('planifie', 'en_cours', 'termine', 'annule') NOT NULL DEFAULT 'termine',
  date_prochaine_km INT NULL COMMENT 'Kilométrage prévu pour prochaine intervention',
  date_prochaine DATE NULL COMMENT 'Date prévue pour prochaine intervention',
  pieces_changees TEXT NULL COMMENT 'Liste des pièces changées',
  observations TEXT NULL,
  cree_par BIGINT UNSIGNED NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


  CONSTRAINT fk_interv_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
  CONSTRAINT fk_interv_operation FOREIGN KEY (operation_id) REFERENCES intervention_operations(id) ON DELETE RESTRICT,
  CONSTRAINT fk_interv_utilisateur FOREIGN KEY (cree_par) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- Table intervention_suivi (optionnel) : Pour stocker les échéances calculées
CREATE TABLE intervention_suivis (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  vehicule_id BIGINT UNSIGNED NOT NULL,
  operation_id INT UNSIGNED NOT NULL,
  dernier_km INT NULL,
  derniere_date DATE NULL,
  prochaine_echeance_km INT NULL,
  prochaine_echeance_date DATE NULL,
  alerte_envoyee BOOLEAN DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_suivi_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
  CONSTRAINT fk_suivi_operation FOREIGN KEY (operation_id) REFERENCES intervention_operations(id) ON DELETE CASCADE,
  UNIQUE KEY unique_vehicule_operation (vehicule_id, operation_id)
);

