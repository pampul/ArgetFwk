-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 11 Mai 2012 à 15:37
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `basesite`
--

-- --------------------------------------------------------

--
-- Structure de la table `base_admin`
--

CREATE TABLE IF NOT EXISTS `base_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `adminlvl` smallint(1) DEFAULT NULL,
  `fonction` varchar(100) NOT NULL,
  `privilege` smallint(1) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `tel` char(15) DEFAULT NULL,
  `date_inscription` datetime NOT NULL,
  `image` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2002 ;

--
-- Contenu de la table `base_admin`
--

INSERT INTO `base_admin` (`id`, `timestamp`, `adminlvl`, `fonction`, `privilege`, `nom`, `prenom`, `email`, `password`, `tel`, `date_inscription`, `image`) VALUES
(2000, 1111111111, NULL, 'Webmaster', 9, 'M.', 'Florian', 'f.mithieux@argetweb.fr', '21232f297a57a5a743894a0e4a801fc3', '666812988', '2012-01-01 00:00:00', 'Web/img/users-avatars/florian.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `base_configuration`
--

CREATE TABLE IF NOT EXISTS `base_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- Contenu de la table `base_configuration`
--

INSERT INTO `base_configuration` (`id`, `config`, `value`) VALUES
(1000, 'site', 'www.votresite.com'),
(1001, 'key', 'votrekey');

-- --------------------------------------------------------

--
-- Structure de la table `base_log`
--

CREATE TABLE IF NOT EXISTS `base_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `adminlvl` smallint(2) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `administrateur` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `base_log`
--

INSERT INTO `base_log` (`id`, `timestamp`, `adminlvl`, `date`, `administrateur`, `type`, `categorie`, `nom`) VALUES
(16, 1336721952, 0, '2012-05-11 09:39:12', 'M. Florian', 'Ajout', 'admin', 'Test test'),
(17, 1336731655, 0, '2012-05-11 12:20:55', 'M. Florian', 'Suppression', 'admin', '-'),
(18, 1336742557, 0, '2012-05-11 15:22:37', 'M. Florian', 'Suppression', 'categorie', '-'),
(19, 1336742576, 0, '2012-05-11 15:22:56', 'M. Florian', 'Edition', 'categorie', 'PremiÃ¨re catÃ©gorie'),
(20, 1336750483, 0, '2012-05-11 17:34:43', 'M. Florian', 'Edition', 'admin', 'M. Florian'),
(21, 1336750509, 0, '2012-05-11 17:35:09', 'M. Florian', 'Edition', 'admin', 'M. Florian'),
(22, 1336750559, 0, '2012-05-11 17:35:59', 'M. Florian', 'Edition', 'admin', 'M. Florian');
