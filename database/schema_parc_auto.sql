-- Schema for parc_auto management application
-- MySQL 8+ compatible. Import into phpMyAdmin.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS vehicule_images;
DROP TABLE IF EXISTS vehicule_documents;
DROP TABLE IF EXISTS vehicules;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS parametres;
DROP TABLE IF EXISTS chauffeurs;

CREATE TABLE chauffeurs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NULL,
    date_recrutement DATE NULL,
    adresse VARCHAR(255) NULL,
    telephone VARCHAR(30) NULL,
    numero_permis VARCHAR(100) NULL,
    date_permis DATE NULL,
    lieu_permis VARCHAR(150) NULL,
    statut ENUM('contractuel','permanent') NOT NULL DEFAULT 'contractuel',
    mention ENUM('tres_bien','bien','mauvais','blame') NOT NULL DEFAULT 'bien',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE parametres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom_entreprise VARCHAR(150) NOT NULL,
    lien_archive_facture VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE utilisateurs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    cle VARCHAR(120) NOT NULL UNIQUE,
    role ENUM('administratif','responsable','agent') NOT NULL DEFAULT 'agent',
    actif TINYINT(1) NOT NULL DEFAULT 1,
    email VARCHAR(150) NULL UNIQUE,
    password VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE vehicules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(50) NOT NULL UNIQUE,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    marque VARCHAR(100) NULL,
    modele VARCHAR(100) NULL,
    annee YEAR NULL,
    couleur VARCHAR(50) NULL,
    chassis VARCHAR(120) NULL,
    chauffeur_id BIGINT UNSIGNED NULL,
    date_acquisition DATE NULL,
    valeur DECIMAL(15,2) NULL,
    statut TINYINT(1) NOT NULL DEFAULT 1,
    date_creation TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    categorie ENUM('leger','lourd','transport') NULL,
    option_vehicule ENUM('base','base_clim','toutes_options') NULL,
    energie ENUM('essence','diesel','gpl') NULL,
    boite ENUM('semiauto','auto','manuel') NULL,
    leasing ENUM('location','acquisition','autre') NULL,
    utilisation ENUM('personnel','professionnel') NULL,
    affectation VARCHAR(150) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_vehicules_chauffeur FOREIGN KEY (chauffeur_id) REFERENCES chauffeurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE vehicule_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicule_id BIGINT UNSIGNED NOT NULL,
    type ENUM('assurance','vignette','controle','entretien','reparation','bon_essence') NOT NULL,
    numero VARCHAR(120) NULL,
    libele VARCHAR(150) NULL,
    partenaire VARCHAR(150) NULL,
    debut DATE NULL,
    expiration DATE NULL,
    valeur DECIMAL(15,2) NULL,
    num_facture VARCHAR(120) NULL,
    date_facture DATE NULL,
    vidange ENUM('complet','partiel') NULL,
    kilometrage INT NULL,
    piece VARCHAR(150) NULL,
    reparateur VARCHAR(150) NULL,
    type_reparation ENUM('carosserie','mecanique') NULL,
    date_reparation DATE NULL,
    typecarburant ENUM('essence','gasoil','gpl') NULL,
    utilisation ENUM('trajet','interne') NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_documents_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    INDEX idx_documents_vehicule (vehicule_id),
    INDEX idx_documents_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE vehicule_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicule_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_images_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    INDEX idx_images_vehicule (vehicule_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO chauffeurs (matricule, nom, prenom, date_naissance, date_recrutement, adresse, telephone, numero_permis, date_permis, lieu_permis, statut, mention)
VALUES
('CH-001', 'Diallo', 'Aissatou', '1985-03-12', '2015-05-01', 'Rue 12, Dakar', '772233445', 'PERM-9988', '2010-02-01', 'Dakar', 'permanent', 'tres_bien'),
('CH-002', 'Ndiaye', 'Moussa', '1990-07-20', '2018-01-15', 'Plateau, Dakar', '776601122', 'PERM-8831', '2012-06-15', 'Kaolack', 'contractuel', 'bien'),
('CH-003', 'Sow', 'Fatou', '1992-11-02', '2019-09-10', 'Thies', '774455668', 'PERM-7721', '2014-08-22', 'Thiès', 'permanent', 'bien');

INSERT INTO parametres (nom_entreprise, lien_archive_facture)
VALUES ('Parc Auto SA', 'https://archives.parc-auto.local');

INSERT INTO utilisateurs (nom, cle, role, actif, email, password)
VALUES
('Admin Principal', 'admin-key', 'responsable', 1, 'admin@parc-auto.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Agent Bureau', 'agent-cle', 'agent', 1, 'agent@parc-auto.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Responsable Parc', 'resp-cle', 'responsable', 1, 'resp@parc-auto.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO vehicules (numero, code, description, marque, modele, annee, couleur, chassis, chauffeur_id, date_acquisition, valeur, statut, date_creation, categorie, option_vehicule, energie, boite, leasing, utilisation, affectation)
VALUES
('VEH-100', 'V100', 'Pickup 4x4 pour service terrain', 'Toyota', 'Hilux', 2021, 'Blanc', 'JT123456789HILUX', 1, '2021-03-12', 28000000.00, 1, NOW(), 'lourd', 'toutes_options', 'diesel', 'manuel', 'acquisition', 'professionnel', 'Equipe terrain'),
('VEH-200', 'V200', 'Berline direction générale', 'Hyundai', 'Sonata', 2022, 'Noir', 'HY987654321SON', 3, '2022-07-05', 24000000.00, 1, NOW(), 'leger', 'base_clim', 'essence', 'auto', 'leasing', 'professionnel', 'Direction'),
('VEH-300', 'V300', 'Mini bus transport interne', 'Mercedes', 'Sprinter', 2019, 'Gris', 'MB555123SPR', 2, '2019-11-20', 35000000.00, 1, NOW(), 'transport', 'base', 'diesel', 'semiauto', 'location', 'professionnel', 'Navette staff');

INSERT INTO vehicule_documents (vehicule_id, type, numero, libele, partenaire, debut, expiration, valeur, num_facture, date_facture, vidange, kilometrage, piece, reparateur, type_reparation, date_reparation, typecarburant, utilisation)
VALUES
(1, 'assurance', 'ASS-2025-01', 'Assurance flotte', 'NSIA', '2025-01-01', '2025-12-31', 450000.00, 'FAC-ASS-01', '2025-01-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1, 'entretien', 'ENT-445', 'Entretien complet', 'Garage Dakar', '2025-02-10', '2025-02-11', 125000.00, 'FAC-ENT-445', '2025-02-11', 'complet', 45200, 'Filtre + huile', 'Garage Dakar', 'mecanique', '2025-02-11', NULL, NULL),
(2, 'vignette', 'VIG-889', 'Vignette annuelle', 'Trésor', '2025-01-15', '2026-01-14', 95000.00, 'FAC-VIG-889', '2025-01-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'controle', 'CTL-2025-07', 'Visite technique', 'Contrôle Auto', '2025-07-01', '2026-06-30', 75000.00, 'FAC-CTL-2025-07', '2025-07-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'bon_essence', 'BON-556', NULL, NULL, '2025-03-05', NULL, 60000.00, 'FAC-BON-556', '2025-03-05', NULL, 60200, NULL, NULL, NULL, NULL, 'gasoil', 'trajet');

INSERT INTO vehicule_images (vehicule_id, image_path)
VALUES
(1, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(2, 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?w=800'),
(3, 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=800');

SET FOREIGN_KEY_CHECKS = 1;
