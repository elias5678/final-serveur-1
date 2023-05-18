DROP DATABASE IF EXISTS `LIVRE`;
CREATE DATABASE `LIVRE`;
USE `LIVRE`;

CREATE TABLE `auteurs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `distributeur`varchar(100),
  `nbr_livre` int(10),
  `note`int(10),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `livres` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
  `titre` varchar(50) NOT NULL,
  `location` boolean not null,
  `idnuméro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resumer` text NOT NULL,
  `idAuteurs` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idAuteurs`) REFERENCES `auteurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `auteurs` VALUES(NULL, 'charle','belle_chase',2,5);
INSERT INTO `auteurs` VALUES(NULL, 'bob','belle_chase',1,8);

INSERT INTO `livres` VALUES(NULL, 'gaston',0,null'Informations', 1);
INSERT INTO `livres` VALUES(NULL, 'gamer',1,null'Informations gamer', 2);
INSERT INTO `livres` VALUES(NULL, 'lilie',1,null'Informations lilie', 1);

-- DROP DATABASE IF EXISTS `exam3`;
-- CREATE DATABASE `exam3`;
-- USE `exam3`;

-- CREATE TABLE `gender` (
--   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `name` varchar(50) NOT NULL,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `name_UNIQUE` (`name`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- CREATE TABLE `user` (
--   `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
--   `user` varchar(50) NOT NULL,
--   `pwd` varchar(255) NOT NULL,
--   `msg` text NOT NULL,
--   `idGender` int(10) unsigned NOT NULL,
--   PRIMARY KEY (`id`),
--   FOREIGN KEY (`idGender`) REFERENCES `gender` (`id`) ON DELETE CASCADE ON UPDATE CASCADE 
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- INSERT INTO `gender` VALUES(NULL, 'Masculin');
-- INSERT INTO `gender` VALUES(NULL, 'Féminin');
-- INSERT INTO `gender` VALUES(NULL, 'Non-Binaire');
-- INSERT INTO `gender` VALUES(NULL, 'Préfère ne pas dire');

-- INSERT INTO `user` VALUES(NULL, 'Grogu', 'etd123', 'Informations grogu', 1);
-- INSERT INTO `user` VALUES(NULL, 'Link', 'etd123', 'Informations sur les aventures de Link', 1);
-- INSERT INTO `user` VALUES(NULL, 'PrincessPeach', 'etd123', 'Informations sur les expériences de kidnapping de Peach', 2);
-- INSERT INTO `user` VALUES(NULL, 'LordMomonga', 'etd123', 'Informations sur Nazarick', 4);