-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 27 jan. 2025 à 13:15
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `to_do_list`
--

-- --------------------------------------------------------

--
-- Structure de la table `list`
--

DROP TABLE IF EXISTS `list`;
CREATE TABLE IF NOT EXISTS `list` (
  `id_list` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` date NOT NULL,
  `limit_date` date NOT NULL,
  `creator_id` int NOT NULL,
  `creator_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_list`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `list`
--

INSERT INTO `list` (`id_list`, `name`, `description`, `creation_date`, `limit_date`, `creator_id`, `creator_name`) VALUES
(44, 'Liste famille', 'Les tâches à faire à la maison 02/25', '2025-01-27', '2025-02-28', 2, 'test'),
(45, 'Liste travail', 'tâches pour le travail', '2025-01-27', '2025-02-09', 2, 'test'),
(46, 'Liste perso', 'Tâches perso', '2025-01-27', '2025-02-10', 1, 'thomas');

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id_task` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` date NOT NULL,
  `limit_date` date NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '0',
  `id_list` int NOT NULL,
  PRIMARY KEY (`id_task`),
  KEY `TASK_LIST` (`id_list`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id_task`, `name`, `description`, `creation_date`, `limit_date`, `statut`, `id_list`) VALUES
(59, 'Acheter du pain', 'Acheter du pain tous les 2j', '2025-01-27', '2025-02-28', 0, 44),
(60, 'faire le ménage', 'Passer l\'aspirateur 2x par semaine', '2025-01-27', '2025-02-28', 0, 44),
(61, 'Rdv coiffeur', 'Aller chez le coiffeur', '2025-01-27', '2025-01-28', 1, 44),
(62, 'Virement banquaire', 'Virer de l\'argent sur le compte', '2025-01-27', '2025-02-03', 0, 44),
(63, 'Finir dossier', 'Dossier n°471 à finir', '2025-01-27', '2025-01-31', 0, 45),
(64, 'Rdv patron', 'Aller voir l\'autre c*n', '2025-01-27', '2025-01-29', 1, 45),
(65, 'Devenir trop fort en dev', 'Travailler pour devenir un monstre', '2025-01-27', '2025-10-06', 0, 46);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `signing_date` date NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `name`, `email`, `password`, `signing_date`) VALUES
(1, 'thomas', 'thomas@gmail.com', '$2y$10$iBFauiC3kEeU.LfMMk8h6O64Y0oPSBmEV8sv5r7J8o0LeYGJTNg2i', '2025-01-21'),
(2, 'test', 'test@gmail.com', '$2y$10$W10zj4DDtfct28IAvzD4bu.2RW7cKhJDmx/0rFK9MCXR8TA08o5ZS', '2025-01-21'),
(3, 'Mel00w', 'mel00w.tv@gmail.com', '$2y$10$Co5qSzUbC7cyfAIRzrU4Ne/iMrLBGBH8BXmljJRUwyCJMv9.kmR7y', '2025-01-22'),
(4, 'samy', 'mechiche.samysm@gmail.com', '$2y$10$7r8povgyRhDPvBENditaHOBua1eYxO5dIeZ2QRia6w6RMwFOObqfC', '2025-01-23');

-- --------------------------------------------------------

--
-- Structure de la table `user_list`
--

DROP TABLE IF EXISTS `user_list`;
CREATE TABLE IF NOT EXISTS `user_list` (
  `id_user` int NOT NULL,
  `id_list` int NOT NULL,
  KEY `USER` (`id_user`),
  KEY `LIST` (`id_list`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_list`
--

INSERT INTO `user_list` (`id_user`, `id_list`) VALUES
(2, 44),
(1, 44),
(2, 45),
(1, 46);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`id_list`) REFERENCES `list` (`id_list`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_list`
--
ALTER TABLE `user_list`
  ADD CONSTRAINT `user_list_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_list_ibfk_2` FOREIGN KEY (`id_list`) REFERENCES `list` (`id_list`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
