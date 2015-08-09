/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.5.41-0ubuntu0.12.04.1 : Database - vk_orders
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vk_orders` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `vk_orders`;

/*Table structure for table `order` */

DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at_d` date DEFAULT NULL,
  `created_at_dt` datetime DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `executor_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  `amount4customer` decimal(10,2) unsigned NOT NULL,
  `amount4executor` decimal(10,2) unsigned NOT NULL,
  `amount4system` decimal(10,2) unsigned NOT NULL,
  `status` enum('new','done') NOT NULL DEFAULT 'new',
  `status_at_dt` datetime DEFAULT NULL,
  `deleted_is` enum('no','yes') NOT NULL DEFAULT 'no',
  `deleted_at_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `deleted_is` (`deleted_is`),
  KEY `status` (`status`),
  KEY `agrigated` (`customer_id`,`status`,`deleted_is`),
  KEY `created_at_d` (`created_at_d`),
  KEY `executor_id` (`executor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
