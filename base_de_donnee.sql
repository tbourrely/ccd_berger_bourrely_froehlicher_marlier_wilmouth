/*
Navicat MySQL Data Transfer

Source Server         : OSEF
Source Server Version : 50166
Source Host           : mysql-manghao-site.alwaysdata.net:3306
Source Database       : manghao-site_charly

Target Server Type    : MYSQL
Target Server Version : 50166
File Encoding         : 65001

Date: 2017-02-09 22:17:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for groupe
-- ----------------------------
DROP TABLE IF EXISTS `groupe`;
CREATE TABLE `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `proprietaire` int(11) NOT NULL,
  `nbUsers` int(11) NOT NULL,
  `status` text NOT NULL,
  `nbInvitationOk` int(11) NOT NULL,
  `idLogement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idLogement` (`idLogement`),
  CONSTRAINT `groupe_ibfk_1` FOREIGN KEY (`idLogement`) REFERENCES `logement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of groupe
-- ----------------------------

-- ----------------------------
-- Table structure for invitation
-- ----------------------------
DROP TABLE IF EXISTS `invitation`;
CREATE TABLE `invitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` text NOT NULL,
  `url` varchar(512) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`),
  KEY `idGroupe` (`idGroupe`),
  CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`),
  CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of invitation
-- ----------------------------

-- ----------------------------
-- Table structure for logement
-- ----------------------------
DROP TABLE IF EXISTS `logement`;
CREATE TABLE `logement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `places` int(11) NOT NULL,
  `moy` float NOT NULL,
  `occupe` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of logement
-- ----------------------------
INSERT INTO `logement` VALUES ('1', '3', '4', '0');
INSERT INTO `logement` VALUES ('2', '3', '4', '0');
INSERT INTO `logement` VALUES ('3', '4', '0', '0');
INSERT INTO `logement` VALUES ('4', '4', '0', '0');
INSERT INTO `logement` VALUES ('5', '4', '0', '0');
INSERT INTO `logement` VALUES ('6', '4', '0', '0');
INSERT INTO `logement` VALUES ('7', '4', '0', '0');
INSERT INTO `logement` VALUES ('8', '5', '0', '0');
INSERT INTO `logement` VALUES ('9', '5', '0', '0');
INSERT INTO `logement` VALUES ('10', '6', '0', '0');
INSERT INTO `logement` VALUES ('11', '6', '0', '0');
INSERT INTO `logement` VALUES ('12', '6', '0', '0');
INSERT INTO `logement` VALUES ('13', '7', '0', '0');
INSERT INTO `logement` VALUES ('14', '7', '0', '0');
INSERT INTO `logement` VALUES ('15', '8', '0', '0');
INSERT INTO `logement` VALUES ('16', '2', '0', '0');
INSERT INTO `logement` VALUES ('17', '2', '0', '0');
INSERT INTO `logement` VALUES ('18', '2', '0', '0');
INSERT INTO `logement` VALUES ('19', '2', '0', '0');
INSERT INTO `logement` VALUES ('20', '2', '0', '0');
INSERT INTO `logement` VALUES ('21', '3', '0', '0');
INSERT INTO `logement` VALUES ('22', '3', '0', '0');
INSERT INTO `logement` VALUES ('23', '3', '0', '0');
INSERT INTO `logement` VALUES ('24', '3', '0', '0');
INSERT INTO `logement` VALUES ('25', '3', '0', '0');
INSERT INTO `logement` VALUES ('26', '4', '0', '0');
INSERT INTO `logement` VALUES ('27', '3', '0', '0');

-- ----------------------------
-- Table structure for notation
-- ----------------------------
DROP TABLE IF EXISTS `notation`;
CREATE TABLE `notation` (
  `id` int(11) NOT NULL,
  `idLogement` int(11) NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notation
-- ----------------------------

-- ----------------------------
-- Table structure for notationUser
-- ----------------------------
DROP TABLE IF EXISTS `notationUser`;
CREATE TABLE `notationUser` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notationUser
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `age` int(11) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT '0',
  `gestionnaire` int(1) NOT NULL DEFAULT '0',
  `moy` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'Jeanne', 'aime la musique ♫', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'jeanne@charly.fr', '20', '1', '0', '2');
INSERT INTO `user` VALUES ('2', 'Paul', 'aime cuisiner ♨ ♪', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'paul@charly.fr', '21', '2', '0', '3.5');
INSERT INTO `user` VALUES ('3', 'Myriam', 'mange Halal ☪', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'Myriam@charly.fr', '19', '3', '0', '0');
INSERT INTO `user` VALUES ('4', 'Nicolas', 'ouvert à tous ⛄', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'nicolas@charly.fr', '19', '4', '0', '3');
INSERT INTO `user` VALUES ('5', 'Sophie', 'aime sortir ♛', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'sophie@charly.fr', '18', '5', '0', '0');
INSERT INTO `user` VALUES ('6', 'Karim', 'aime le soleil ☀', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'karim@charly.fr', '20', '6', '0', '0');
INSERT INTO `user` VALUES ('7', 'Julie', 'apprécie le calme ☕', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'julie@charly.fr', '21', '7', '0', '0');
INSERT INTO `user` VALUES ('8', 'Etienne', 'accepte jeunes et vieux ☯', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'Etienne@charly.fr', '20', '8', '0', '0');
INSERT INTO `user` VALUES ('9', 'Max', 'féru de musique moderne ☮', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'max@charly.fr', '18', '9', '0', '0');
INSERT INTO `user` VALUES ('10', 'Sabrina', 'aime les repas en commun ⛵☻', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'sabrina@charly.fr', '19', '10', '0', '0');
INSERT INTO `user` VALUES ('11', 'Nathalie', 'bricoleuse ⛽', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'nathalie@charly.fr', '20', '11', '0', '0');
INSERT INTO `user` VALUES ('12', 'Martin', 'sportif ☘ ⚽ ⚾ ⛳', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'martin@charly.fr', '21', '12', '0', '0');
INSERT INTO `user` VALUES ('13', 'Manon', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'manon@charly.fr', '22', '13', '0', '0');
INSERT INTO `user` VALUES ('14', 'Thomas', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'thomas@charly.fr', '23', '14', '0', '0');
INSERT INTO `user` VALUES ('15', 'Léa', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'lea@charly.fr', '22', '15', '0', '0');
INSERT INTO `user` VALUES ('16', 'Alexandre', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'alexandre@charly.fr', '23', '16', '0', '0');
INSERT INTO `user` VALUES ('17', 'Camille', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'camille@charly.fr', '23', '17', '0', '0');
INSERT INTO `user` VALUES ('18', 'Quentin', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'quentin@charly.fr', '24', '18', '0', '0');
INSERT INTO `user` VALUES ('19', 'Marie', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'marie@charly.fr', '21', '19', '0', '0');
INSERT INTO `user` VALUES ('20', 'Antoine', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'antoine@charly.fr', '22', '20', '0', '0');
INSERT INTO `user` VALUES ('21', 'Laura', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'laura@charly.fr', '20', '21', '0', '0');
INSERT INTO `user` VALUES ('22', 'Julien', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'pauline@charly.fr', '20', '22', '0', '0');
INSERT INTO `user` VALUES ('23', 'Pauline', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'julien@charly.fr', '18', '23', '0', '0');
INSERT INTO `user` VALUES ('24', 'Lucas', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'lucas@charly.fr', '18', '24', '0', '0');
INSERT INTO `user` VALUES ('25', 'Sarah', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'sarah@charly.fr', '19', '25', '0', '0');
INSERT INTO `user` VALUES ('26', 'Romain', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'romain@charly.fr', '21', '26', '0', '0');
INSERT INTO `user` VALUES ('27', 'Mathilde', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'mathilde@charly.fr', '21', '27', '0', '0');
INSERT INTO `user` VALUES ('28', 'Florian', '', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'florian@charly.fr', '22', '28', '0', '0');
INSERT INTO `user` VALUES ('33', 'Charly', 'Je suis Charly', '$2y$10$WzKDqs3CkFet.r0p2/CbsOd5Bi7PxAg2QCkoBDIRT4M9VNKAJ.46a', 'ccd@charly.fr', '21', '16', '1', '0');
