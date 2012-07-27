--
-- @author Galsungen - http://blog.galsungen.net
-- Année 2010-11
-- CNAM - NFE114 - Système d'information web
--


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Base de données: `gd_galeriephotos`

-- Structure de la table `gd_liensphta`
DROP TABLE IF EXISTS `gd_liensphta`;
CREATE TABLE IF NOT EXISTS `gd_liensphta` (
  `id_ph` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  KEY `id_ph` (`id_ph`,`id_ta`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Contenu de la table `gd_liensphta`
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(1, 3);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(1, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(1, 12);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(4, 1);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(4, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(6, 1);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(6, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(6, 21);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(7, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(7, 15);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(8, 5);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(8, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(8, 8);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(14, 5);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(14, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(15, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(15, 7);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(15, 9);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(15, 15);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(16, 3);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(16, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(17, 1);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(17, 6);
INSERT INTO `gd_liensphta` (`id_ph`, `id_ta`) VALUES(17, 21);

-- Structure de la table `gd_photos`
DROP TABLE IF EXISTS `gd_photos`;
CREATE TABLE IF NOT EXISTS `gd_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_photo` text NOT NULL,
  `chemin_thumbnail` text NOT NULL,
  `nom` text NOT NULL,
  `titre_photo` varchar(100) NOT NULL,
  `auteur` varchar(50) NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `date_up` date NOT NULL,
  `date_modify` date NOT NULL,
  `ip_add` varchar(15) NOT NULL,
  `ip_modify` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- Contenu de la table `gd_photos`
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(1, '20110129151453_flowers2.jpg', '20110129151453_tb_flowers2.jpg', 'flowers2.jpg', 'Fleurs', 'unknow', 'somewhere', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(15, '20110206224603_19.jpg', '20110206224603_tb_19.jpg', '19.jpg', 'Tortue', 'Coustaud', 'OcÃ©an Indien', '2011-02-06', '2011-02-06', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(16, '20110206224637_15a.jpg', '20110206224637_tb_15a.jpg', '15a.jpg', 'Un Champ', 'un Paysan', 'Ukraine', '2011-02-06', '2011-02-06', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(4, '20110129151715_24.jpg', '20110129151715_tb_24.jpg', '24.jpg', 'A Bird', 'someone', 'unknow', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(5, '20110129174044_33.jpg', '20110129174044_tb_33.jpg', '33.jpg', 'FÃ©lin', 'Anyone', 'Zoo', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(6, '20110129174056_3.jpg', '20110129174056_tb_3.jpg', '3.jpg', 'Perroquet', 'Somebody', 'Africa', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(7, '20110129174110_21.jpg', '20110129174110_tb_21.jpg', '21.jpg', 'Poisson Bleu', 'Theo', 'water', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(8, '20110129174124_18a.jpg', '20110129174124_tb_18a.jpg', '18a.jpg', 'Poivrons', 'Un Marchand', 'MarchÃ©', '2011-01-29', '2011-01-29', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(17, '20110206224701_3.jpg', '20110206224701_tb_3.jpg', '3.jpg', 'Un Perroquet', 'Un Animalier', 'Zoo', '2011-02-06', '2011-02-06', '127.0.0.1', '127.0.0.1');
INSERT INTO `gd_photos` (`id`, `chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`, `date_modify`, `ip_add`, `ip_modify`) VALUES(14, '20110205224621_11.jpg', '20110205224621_tb_11.jpg', '11.jpg', 'Pomme', 'Somebody', 'USA', '2011-02-05', '2011-02-05', '127.0.0.1', '127.0.0.1');

-- Structure de la table `gd_tags`
DROP TABLE IF EXISTS `gd_tags`;
CREATE TABLE IF NOT EXISTS `gd_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- Contenu de la table `gd_tags`
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(1, 'oiseau');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(2, 'chien');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(3, 'paysage');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(4, 'ville');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(5, 'fruit');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(6, 'nature');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(7, 'Indien');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(8, 'LÃ©gume');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(9, 'Soleil');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(10, 'voiture');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(11, 'moto');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(12, 'fleur');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(13, 'feuille');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(15, 'mer');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(16, 'lune');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(17, 'Paris');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(18, 'OrlÃ©ans');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(19, 'New-York');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(20, 'Chicago');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(21, 'Perroquet');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(22, 'Africa');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(26, 'ballon');
INSERT INTO `gd_tags` (`id`, `tag`) VALUES(24, 'geek');
