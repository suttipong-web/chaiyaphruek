/*
Navicat MySQL Data Transfer

Source Server         : xammp_localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : chaiyaphruek

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-12-11 09:58:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for citizens
-- ----------------------------
DROP TABLE IF EXISTS `citizens`;
CREATE TABLE `citizens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `citizen_id` varchar(13) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `household_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `citizen_id` (`citizen_id`),
  KEY `household_id` (`household_id`),
  CONSTRAINT `citizens_ibfk_1` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of citizens
-- ----------------------------
INSERT INTO `citizens` VALUES ('1', '123456', 'suttipong', 'riponyong', '2024-12-10', 'Male', '5');

-- ----------------------------
-- Table structure for households
-- ----------------------------
DROP TABLE IF EXISTS `households`;
CREATE TABLE `households` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_no` varchar(20) NOT NULL,
  `onwer_name` varchar(254) DEFAULT NULL,
  `address` varchar(254) DEFAULT '',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `do_adddate` datetime DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of households
-- ----------------------------
INSERT INTO `households` VALUES ('5', '22222', 'AD', '-', '18.75535492', '99.01672196', '2024-12-10 13:57:08', '2024-12-10 13:57:08');
INSERT INTO `households` VALUES ('6', '3333', 'YUUYU', '', null, null, null, null);
