-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 06 avr. 2025 à 12:34
-- Version du serveur : 5.7.17
-- Version de PHP : 8.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `kéké`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `offre_stage_id` int(11) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `lettre_motivation` varchar(255) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `date_postulation` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `adresse`, `email`) VALUES
(1, 'dajaj', NULL, 'jaja@gmail.com'),
(2, 'cesi', 'baraki', 'cesi@gmail.com'),
(4, 'cate', '', 'oizz@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `notations`
--

CREATE TABLE `notations` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `date_vote` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `notations`
--

INSERT INTO `notations` (`id`, `entreprise_id`, `utilisateur_id`, `note`, `date_vote`) VALUES
(1, 2, 4, 3, '2025-04-03 21:31:44');

-- --------------------------------------------------------

--
-- Structure de la table `offres_stage`
--

CREATE TABLE `offres_stage` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `offres_stage`
--

INSERT INTO `offres_stage` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `entreprise_id`, `specialite`, `date_creation`) VALUES
(1, 'stage 3 mois chez charikat eldajaj', 'recherche développeur  fullstack pour la réalisation d\'un site web', '2025-04-01', '2025-07-01', 1, 'fullstack', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `role` enum('admin','pilote','etudiant') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `motdepasse`, `role`) VALUES
(6, 'pilote@web4all.fr', '$2y$10$82BmzYN/.BoRrxfoTzivTOvHlY/p9vvzenmArJtNpp063k7xAbR/C', 'pilote'),
(4, 'admin@web4all.fr', '$2y$10$jnI92ToOUDokoiwarskdaeZeLSZ4Q2/lb5Ef6RHirGhLqNYsXfKL6', 'admin'),
(5, 'rigel@gmail.com', '$2y$10$O5.iiRzGbO26BtWsz3hlq..WHUbhPu4f4Qy5bmyGSM91SRbEPnxB2', 'etudiant');

-- --------------------------------------------------------

--
-- Structure de la table `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `offre_stage_id` int(11) DEFAULT NULL,
  `date_ajout` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `wish_list`
--

INSERT INTO `wish_list` (`id`, `utilisateur_id`, `offre_stage_id`, `date_ajout`) VALUES
(3, 4, 1, '2025-04-05');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `offre_stage_id` (`offre_stage_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notations`
--
ALTER TABLE `notations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `offres_stage`
--
ALTER TABLE `offres_stage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `offre_stage_id` (`offre_stage_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `notations`
--
ALTER TABLE `notations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `offres_stage`
--
ALTER TABLE `offres_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `wish_list`
--
ALTER TABLE `wish_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
