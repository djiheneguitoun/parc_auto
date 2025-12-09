-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 07 déc. 2025 à 11:32
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `parc_auto`
--

-- --------------------------------------------------------

--
-- Structure de la table `chauffeurs`
--

DROP TABLE IF EXISTS `chauffeurs`;
CREATE TABLE IF NOT EXISTS `chauffeurs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `matricule` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_recrutement` date DEFAULT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_permis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_permis` date DEFAULT NULL,
  `lieu_permis` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('contractuel','permanent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contractuel',
  `mention` enum('tres_bien','bien','mauvais','blame') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bien',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chauffeurs_matricule_unique` (`matricule`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(15, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(16, '2025_12_07_000000_create_chauffeurs_table', 1),
(17, '2025_12_07_000001_create_parametres_table', 1),
(18, '2025_12_07_000002_create_utilisateurs_table', 1),
(19, '2025_12_07_000003_create_vehicules_table', 1),
(20, '2025_12_07_000004_create_vehicule_documents_table', 1),
(21, '2025_12_07_000005_create_vehicule_images_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien_archive_facture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('administratif','responsable','agent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agent',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateurs_cle_unique` (`cle`),
  UNIQUE KEY `utilisateurs_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

DROP TABLE IF EXISTS `vehicules`;
CREATE TABLE IF NOT EXISTS `vehicules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `marque` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modele` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee` smallint UNSIGNED DEFAULT NULL,
  `couleur` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chauffeur_id` bigint UNSIGNED DEFAULT NULL,
  `date_acquisition` date DEFAULT NULL,
  `valeur` decimal(15,2) DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categorie` enum('leger','lourd','transport') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_vehicule` enum('base','base_clim','toutes_options') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `energie` enum('essence','diesel','gpl') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boite` enum('semiauto','auto','manuel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leasing` enum('location','acquisition','autre') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utilisation` enum('personnel','professionnel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affectation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicules_numero_unique` (`numero`),
  UNIQUE KEY `vehicules_code_unique` (`code`),
  KEY `vehicules_chauffeur_id_foreign` (`chauffeur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_documents`
--

DROP TABLE IF EXISTS `vehicule_documents`;
CREATE TABLE IF NOT EXISTS `vehicule_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint UNSIGNED NOT NULL,
  `type` enum('assurance','vignette','controle','entretien','reparation','bon_essence') COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `libele` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partenaire` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debut` date DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `valeur` decimal(15,2) DEFAULT NULL,
  `num_facture` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_facture` date DEFAULT NULL,
  `vidange` enum('complet','partiel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kilometrage` int UNSIGNED DEFAULT NULL,
  `piece` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reparateur` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_reparation` enum('carosserie','mecanique') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reparation` date DEFAULT NULL,
  `typecarburant` enum('essence','gasoil','gpl') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utilisation` enum('trajet','interne') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicule_documents_vehicule_id_type_index` (`vehicule_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_images`
--

DROP TABLE IF EXISTS `vehicule_images`;
CREATE TABLE IF NOT EXISTS `vehicule_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicule_images_vehicule_id_index` (`vehicule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;


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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
