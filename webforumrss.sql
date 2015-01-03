-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 26 Décembre 2014 à 18:47
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `webforumrss`
--
CREATE DATABASE IF NOT EXISTS `webforumrss` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `webforumrss`;

-- --------------------------------------------------------

--
-- Structure de la table `categoriesforum`
--

DROP TABLE IF EXISTS `categoriesforum`;
CREATE TABLE IF NOT EXISTS `categoriesforum` (
  `CatId` int(11) NOT NULL AUTO_INCREMENT,
  `CatLibelle` varchar(100) NOT NULL,
  `CatDate` datetime NOT NULL,
  `UserId` int(11) NOT NULL,
  PRIMARY KEY (`CatId`),
  KEY `UserId` (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `categoriesforum`
--

INSERT INTO `categoriesforum` (`CatId`, `CatLibelle`, `CatDate`, `UserId`) VALUES
(1, 'Categorie 1', '2014-11-11 11:30:08', 12),
(2, 'Categorie 2', '2014-11-11 11:30:22', 12),
(3, 'Categorie 3', '2014-11-11 11:30:32', 12);

-- --------------------------------------------------------

--
-- Structure de la table `categoriesrss`
--

DROP TABLE IF EXISTS `categoriesrss`;
CREATE TABLE IF NOT EXISTS `categoriesrss` (
  `CatId` int(11) NOT NULL AUTO_INCREMENT,
  `CatLibelle` varchar(100) NOT NULL,
  `CatDate` datetime NOT NULL,
  `UserId` int(11) NOT NULL,
  PRIMARY KEY (`CatId`),
  KEY `UserId` (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `categoriesrss`
--

INSERT INTO `categoriesrss` (`CatId`, `CatLibelle`, `CatDate`, `UserId`) VALUES
(7, 'Jeux Vidéos', '2014-12-26 18:35:21', 12);

-- --------------------------------------------------------

--
-- Structure de la table `fluxrss`
--

DROP TABLE IF EXISTS `fluxrss`;
CREATE TABLE IF NOT EXISTS `fluxrss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `URL` varchar(255) NOT NULL,
  `Cat_id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Cat_id` (`Cat_id`),
  KEY `Cat_id_2` (`Cat_id`),
  KEY `User_Id` (`User_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `fluxrss`
--

INSERT INTO `fluxrss` (`id`, `nom`, `URL`, `Cat_id`, `User_Id`) VALUES
(1, 'JV.com', 'http://www.jeuxvideo.com/rss/rss-pc.xml', 7, 12),
(2, 'IGN', 'http://feeds.ign.com/ign/pc-all?format=xml', 7, 12);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `MesId` int(11) NOT NULL AUTO_INCREMENT,
  `MesText` varchar(500) NOT NULL,
  `MesDate` datetime NOT NULL,
  `UserId` int(11) NOT NULL,
  `TopicId` int(11) NOT NULL,
  PRIMARY KEY (`MesId`),
  KEY `UserId` (`UserId`),
  KEY `TopicId` (`TopicId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `messages`
--

INSERT INTO `messages` (`MesId`, `MesText`, `MesDate`, `UserId`, `TopicId`) VALUES
(1, 'message 1', '2014-11-11 11:37:01', 12, 1),
(2, 'message 2', '2014-11-11 11:37:01', 13, 1),
(3, 'message 3', '2014-11-11 11:37:01', 14, 1),
(4, 'message 1', '2014-11-11 11:37:01', 12, 2),
(5, 'message 2', '2014-11-11 11:37:01', 13, 2),
(6, 'message 3', '2014-11-11 11:37:01', 14, 2),
(7, 'message 1', '2014-11-11 11:37:01', 12, 3),
(8, 'message 2', '2014-11-11 11:37:01', 13, 3),
(9, 'message 3', '2014-11-11 11:37:01', 14, 3),
(10, 'message 1', '2014-11-11 11:37:01', 12, 4),
(11, 'message 2', '2014-11-11 11:37:01', 13, 4),
(12, 'message 3', '2014-11-11 11:37:01', 14, 4),
(13, 'message 1', '2014-11-11 11:37:01', 12, 5),
(14, 'message 2', '2014-11-11 11:37:01', 13, 5),
(15, 'message 3', '2014-11-11 11:37:01', 14, 5),
(16, 'message 1', '2014-11-11 11:37:01', 12, 6),
(17, 'message 2', '2014-11-11 11:37:01', 13, 6),
(18, 'message 3', '2014-11-11 11:37:01', 14, 6);

-- --------------------------------------------------------

--
-- Structure de la table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `TopicId` int(11) NOT NULL AUTO_INCREMENT,
  `TopicLibelle` varchar(100) NOT NULL,
  `TopicDate` datetime NOT NULL,
  `UserId` int(11) NOT NULL,
  `CatId` int(11) NOT NULL,
  PRIMARY KEY (`TopicId`),
  KEY `UserId` (`UserId`),
  KEY `CatId` (`CatId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `topics`
--

INSERT INTO `topics` (`TopicId`, `TopicLibelle`, `TopicDate`, `UserId`, `CatId`) VALUES
(1, 'Topic 1.1', '2014-11-11 11:33:02', 12, 1),
(2, 'Topic 1.2', '2014-11-11 11:33:02', 13, 1),
(3, 'Topic 2.1', '2014-11-11 11:33:02', 12, 2),
(4, 'Topic 2.2', '2014-11-11 11:33:02', 13, 2),
(5, 'Topic 3.1', '2014-11-11 11:33:02', 12, 3),
(6, 'Topic 3.2', '2014-11-11 11:33:02', 13, 3);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `UserLogin` varchar(50) NOT NULL,
  `UserPassword` varchar(50) NOT NULL,
  `UserRole` int(11) NOT NULL,
  `UserAvatar` varchar(200) NOT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `UserLogin` (`UserLogin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`UserId`, `UserLogin`, `UserPassword`, `UserRole`, `UserAvatar`) VALUES
(12, 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'defaut.jpg'),
(13, 'moderateur', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 2, 'defaut.jpg'),
(14, 'utilisateur', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 1, 'defaut.jpg'),
(15, 'banni', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 0, 'defaut.jpg');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categoriesforum`
--
ALTER TABLE `categoriesforum`
  ADD CONSTRAINT `categoriesforum_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`);

--
-- Contraintes pour la table `categoriesrss`
--
ALTER TABLE `categoriesrss`
  ADD CONSTRAINT `categoriesRss_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`);

--
-- Contraintes pour la table `fluxrss`
--
ALTER TABLE `fluxrss`
  ADD CONSTRAINT `fluxrss_ibfk_2` FOREIGN KEY (`User_Id`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `fluxrss_ibfk_1` FOREIGN KEY (`Cat_id`) REFERENCES `categoriesrss` (`CatId`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`TopicId`) REFERENCES `topics` (`TopicId`);

--
-- Contraintes pour la table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`CatId`) REFERENCES `categoriesforum` (`CatId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
