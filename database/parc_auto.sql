-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 22 déc. 2025 à 15:08
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
  `matricule` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_recrutement` date DEFAULT NULL,
  `adresse` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_permis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_permis` date DEFAULT NULL,
  `lieu_permis` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('contractuel','permanent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contractuel',
  `mention` enum('tres_bien','bien','mauvais','blame') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bien',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chauffeurs_matricule_unique` (`matricule`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chauffeurs`
--

INSERT INTO `chauffeurs` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `date_recrutement`, `adresse`, `telephone`, `numero_permis`, `date_permis`, `lieu_permis`, `statut`, `mention`, `created_at`, `updated_at`) VALUES
(1, 'CHF-00100000', 'Doe', 'John', '1990-04-17', '2020-06-01', '123 Rue Principale', '+2126000000022', 'PER-2020-0012222', '2018-05-20', 'Casablancahghg', 'contractuel', 'blame', '2025-12-07 11:03:27', '2025-12-08 23:36:45'),
(16, 'jkhbjhnb', 'jbjhv', 'kjbjhb', '2025-12-21', '2025-12-11', ',nb', '4646', '65468', '2025-12-18', 'kjjb', 'contractuel', 'tres_bien', '2025-12-21 19:43:36', '2025-12-21 19:43:36');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(36, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(37, '2025_12_07_000000_create_chauffeurs_table', 1),
(38, '2025_12_07_000001_create_parametres_table', 1),
(39, '2025_12_07_000002_create_utilisateurs_table', 1),
(40, '2025_12_07_000003_create_vehicules_table', 1),
(41, '2025_12_07_000004_create_vehicule_documents_table', 1),
(42, '2025_12_07_000005_create_vehicule_images_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien_archive_facture` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `parametres`
--

INSERT INTO `parametres` (`id`, `nom_entreprise`, `lien_archive_facture`, `created_at`, `updated_at`) VALUES
(1, 'djihee', 'C:/', '2025-12-07 11:03:27', '2025-12-10 10:12:21');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Utilisateur', 1, 'api', '384c7384f3937ebe51f0e49b28fc2f4d208f6f15e33458ed148c9b2656a84c10', '[\"*\"]', '2025-12-07 11:03:44', '2025-12-07 11:03:43', '2025-12-07 11:03:44'),
(56, 'App\\Models\\Utilisateur', 1, 'api', 'd28fbc025d555ccd1338cf417f5165aac2990e067837daa1b2d65714be2d2475', '[\"*\"]', '2025-12-09 08:49:55', '2025-12-09 08:49:18', '2025-12-09 08:49:55'),
(104, 'App\\Models\\Utilisateur', 1, 'api', 'c24d9d111ef419877c9f28e4093e5174c82de1593ec65390fe350f14aebf4184', '[\"*\"]', '2025-12-21 16:51:54', '2025-12-21 16:50:36', '2025-12-21 16:51:54'),
(57, 'App\\Models\\Utilisateur', 1, 'api', '8a0dbdaa757db196bec3bf84cbf17af4b49b8f9338513b8f2c035691796dc9a2', '[\"*\"]', '2025-12-09 08:52:07', '2025-12-09 08:49:59', '2025-12-09 08:52:07'),
(61, 'App\\Models\\Utilisateur', 1, 'api', '7441065ca164020575c07106e550e21ba6bab805cfc7d59ac4c1d2edab976306', '[\"*\"]', '2025-12-09 09:10:02', '2025-12-09 09:09:07', '2025-12-09 09:10:02'),
(65, 'App\\Models\\Utilisateur', 1, 'api', '24341ed18eda23d50fa1b8ac9d3e9a64ca3ad4d89bdb09df9a20191a1a2f9a4a', '[\"*\"]', '2025-12-09 09:30:21', '2025-12-09 09:29:34', '2025-12-09 09:30:21'),
(74, 'App\\Models\\Utilisateur', 1, 'api', 'fc2d52e111c75165ef044c180ad0cac808099ce378621eb35865d941b85a100b', '[\"*\"]', '2025-12-09 16:39:34', '2025-12-09 16:39:07', '2025-12-09 16:39:34'),
(91, 'App\\Models\\Utilisateur', 1, 'api', '985c9b95e020b9b414d2a8bd0df0c4074aad4d9832a0448900b2a9616f76bef3', '[\"*\"]', '2025-12-10 10:13:16', '2025-12-10 10:11:58', '2025-12-10 10:13:16'),
(109, 'App\\Models\\Utilisateur', 1, 'api', '9bf4715f89e2b5af3a273014f632a4b7f13ffcaacf23ba9e3457d00de51b577d', '[\"*\"]', '2025-12-22 13:00:29', '2025-12-21 23:04:23', '2025-12-22 13:00:29');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cle` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('administratif','responsable','agent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agent',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateurs_cle_unique` (`cle`),
  UNIQUE KEY `utilisateurs_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `cle`, `role`, `actif`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'ADMIN-001', 'administratif', 1, 'admin@example.test', '$2y$10$Zpud7FhIUJiBO3ej6AjkCOEGrX7XL987Dths1lTsrqCb.AOXAOEz.', 'uslH00ALOn', '2025-12-07 11:03:27', '2025-12-07 11:03:27'),
(2, 'Responsable Parc', 'RESP-001', 'responsable', 1, 'responsable@example.test', '$2y$10$t8kgSB9qpQnsfudZNf8K0OKDKXi4BOFUdAmsgC1tszRqmtxTo3WMy', 'QFRQqC1FNQ', '2025-12-07 11:03:27', '2025-12-07 11:03:27'),
(3, 'Agent Parc', 'AGENT-001', 'agent', 1, 'agent@example.test', '$2y$10$xmkgVG6qw.GBRM0cgzpiSeTm86heYjdjj5B4rVQV3hVsjOMdu25Hq', '3wzHXBLDav', '2025-12-07 11:03:27', '2025-12-07 11:03:27'),
(7, 'kjsjnskjfd', 'sdjbh', 'administratif', 1, 'jqhdbjh@gmail.com', '$2y$10$xBO1hYN1fp//qmXlpIWIhuYK9AEBgsBI2rT8OroLJKYI6EhWd4An6', NULL, '2025-12-10 10:41:32', '2025-12-10 10:41:32');

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

DROP TABLE IF EXISTS `vehicules`;
CREATE TABLE IF NOT EXISTS `vehicules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `marque` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modele` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee` smallint UNSIGNED DEFAULT NULL,
  `couleur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chauffeur_id` bigint UNSIGNED DEFAULT NULL,
  `date_acquisition` date DEFAULT NULL,
  `valeur` decimal(15,2) DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categorie` enum('leger','lourd','transport','tracteur','engins') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_vehicule` enum('base','base_clim','toutes_options') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `energie` enum('essence','diesel','gpl','electrique') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boite` enum('semiauto','auto','manuel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leasing` enum('location','acquisition','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utilisation` enum('personnel','professionnel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affectation` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicules_numero_unique` (`numero`),
  UNIQUE KEY `vehicules_code_unique` (`code`),
  KEY `vehicules_chauffeur_id_foreign` (`chauffeur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicules`
--

INSERT INTO `vehicules` (`id`, `numero`, `code`, `description`, `marque`, `modele`, `annee`, `couleur`, `chassis`, `chauffeur_id`, `date_acquisition`, `valeur`, `statut`, `date_creation`, `categorie`, `option_vehicule`, `energie`, `boite`, `leasing`, `utilisation`, `affectation`, `created_at`, `updated_at`) VALUES
(1, 'VH-001', 'VHC-2025-001', 'SUV de pool pour les missions quotidiennes', 'Toyota', 'RAV4', 2023, 'Gris', 'CHS-123456789', 1, '2025-12-11', 35000.00, 1, '2025-12-06 23:00:00', 'lourd', 'toutes_options', 'diesel', 'auto', 'acquisition', 'professionnel', 'Direction technique', '2025-12-07 11:03:27', '2025-12-09 08:22:15'),
(3, '635463', 'kjsdncj', 'kjdcjsc', 's,ndb', 'sdhbj', 2000, 'jqhnsbdh', 'kjdb', 1, NULL, 6546.00, 1, '2025-12-09 23:00:00', 'engins', 'base_clim', 'essence', 'semiauto', 'acquisition', 'personnel', 'akjzd', '2025-12-09 08:24:35', '2025-12-21 23:05:35'),
(6, 'knhje', 'kdjf', NULL, 'sljdn', 'snjsdkjf', 2000, 'skj', 'ksjd', 16, NULL, 54.00, 1, '2025-12-26 23:00:00', 'engins', 'base_clim', 'electrique', 'semiauto', 'acquisition', 'personnel', 'test', '2025-12-21 23:09:56', '2025-12-21 23:14:22');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_documents`
--

DROP TABLE IF EXISTS `vehicule_documents`;
CREATE TABLE IF NOT EXISTS `vehicule_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint UNSIGNED NOT NULL,
  `type` enum('assurance','vignette','controle','entretien','reparation','bon_essence') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `libele` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partenaire` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debut` date DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `valeur` decimal(15,2) DEFAULT NULL,
  `num_facture` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_facture` date DEFAULT NULL,
  `vidange` enum('complet','partiel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kilometrage` int UNSIGNED DEFAULT NULL,
  `piece` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reparateur` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_reparation` enum('carosserie','mecanique') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reparation` date DEFAULT NULL,
  `typecarburant` enum('essence','gasoil','gpl') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utilisation` enum('trajet','interne') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicule_documents_vehicule_id_type_index` (`vehicule_id`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicule_documents`
--

INSERT INTO `vehicule_documents` (`id`, `vehicule_id`, `type`, `numero`, `libele`, `partenaire`, `debut`, `expiration`, `valeur`, `num_facture`, `date_facture`, `vidange`, `kilometrage`, `piece`, `reparateur`, `type_reparation`, `date_reparation`, `typecarburant`, `utilisation`, `created_at`, `updated_at`) VALUES
(1, 1, 'assurance', 'ASS-2025-001', 'Assurance annuelle', 'Assureur Demo', '2025-01-01', '2025-12-31', 800.00, 'FAC-ASS-001', '2025-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:03:27', '2025-12-07 11:03:27'),
(2, 1, 'entretien', 'ENT-2025-001', 'Vidange et filtres', 'Garage Demo', '2025-02-10', '2025-08-10', 150.00, 'FAC-ENT-001', '2025-02-10', 'complet', 15000, 'Filtre a huile', 'Garage Demo', 'mecanique', '2025-02-10', 'gasoil', 'trajet', '2025-12-07 11:03:27', '2025-12-07 11:03:27'),
(4, 1, 'assurance', 'jfvjhyfvhtdgcyyyyyyyydd', 'kjwdjc', 'dkjqh', '2025-12-27', '2025-12-19', 3545.00, 'hgjhh', '2025-12-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-08 23:41:17', '2025-12-08 23:41:17'),
(5, 1, 'reparation', 'jkjsdc', 'sjdb', NULL, NULL, NULL, 0.13, 'jsdhf', '2025-12-21', NULL, NULL, 'skdjcn', 'kqjndcjk', 'carosserie', '2025-12-10', NULL, NULL, '2025-12-09 08:16:17', '2025-12-09 08:16:17'),
(6, 1, 'bon_essence', 'jhbdjx', NULL, NULL, '2025-12-11', NULL, 55.00, 'djfv', '2025-12-10', NULL, 65, NULL, NULL, NULL, NULL, 'essence', 'interne', '2025-12-09 08:17:17', '2025-12-09 08:17:17'),
(7, 3, 'assurance', 'kjskdcn', 'sd,bc', 'qskn', '2025-12-21', '2025-12-27', 5.00, 'qsc', '2026-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 08:25:26', '2025-12-09 08:25:26');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_images`
--

DROP TABLE IF EXISTS `vehicule_images`;
CREATE TABLE IF NOT EXISTS `vehicule_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicule_images_vehicule_id_index` (`vehicule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicule_images`
--

INSERT INTO `vehicule_images` (`id`, `vehicule_id`, `image_path`, `created_at`, `updated_at`) VALUES
(5, 1, 'vehicules/a6BMxoDOUMjAAsWdzt9GGirUNE1CHUpKW0Q6KTn5.png', '2025-12-07 18:33:37', '2025-12-07 18:33:37'),
(6, 1, 'vehicules/zja3Bz7QX7QWGSecjIg9x0s3emA9eph7vKHMpOPO.jpg', '2025-12-08 23:37:56', '2025-12-08 23:37:56'),
(7, 2, 'vehicules/R4WN4xuB5UjrTS5JUvNUvKjlptnYx6hqayoj5bhk.jpg', '2025-12-08 23:39:08', '2025-12-08 23:39:08'),
(4, 1, 'vehicules/1yclqbxHVLLvbITPUqlOAFkmjz3ePtBdZzCShIcQ.jpg', '2025-12-07 18:33:18', '2025-12-07 18:33:18'),
(8, 4, 'vehicules/YvJ1569sf2NadgDGoybeAAqpuEjdbbc0R35Lgt7c.png', '2025-12-21 19:44:33', '2025-12-21 19:44:33'),
(9, 3, 'vehicules/xuIyFwRAHb18Nd0wFzHNaWkdiNsQEmU6KPn46S2y.png', '2025-12-21 21:57:24', '2025-12-21 21:57:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
