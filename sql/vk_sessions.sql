/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.5.41-0ubuntu0.12.04.1 : Database - vk_sessions
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vk_sessions` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `vk_sessions`;

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at_dt` datetime DEFAULT NULL,
  `expire_at_dt` datetime DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `user_ip` varchar(32) DEFAULT NULL,
  `user_agent` varchar(256) DEFAULT NULL,
  `session_key` varchar(32) DEFAULT NULL,
  `session_key_bin_hash` binary(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_bin_hash` (`session_key_bin_hash`),
  UNIQUE KEY `agregated` (`session_key_bin_hash`,`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Data for the table `session` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
