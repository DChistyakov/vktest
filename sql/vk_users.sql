/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.5.41-0ubuntu0.12.04.1 : Database - vk_users
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vk_users` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `vk_users`;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at_d` date DEFAULT NULL,
  `created_at_dt` datetime DEFAULT NULL,
  `username` varchar(64) NOT NULL,
  `username_bin_hash` binary(16) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `expenses_today` decimal(10,2) unsigned NOT NULL,
  `expenses_yesterday` decimal(10,2) unsigned NOT NULL,
  `expenses_month` decimal(10,2) unsigned NOT NULL,
  `expenses_total` decimal(10,2) unsigned NOT NULL,
  `income_today` decimal(10,2) unsigned NOT NULL,
  `income_yesterday` decimal(10,2) unsigned NOT NULL,
  `income_month` decimal(10,2) unsigned NOT NULL,
  `income_total` decimal(10,2) unsigned NOT NULL,
  `role` enum('user','system') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_bin_hash` (`username_bin_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`created_at_d`,`created_at_dt`,`username`,`username_bin_hash`,`balance`,`expenses_today`,`expenses_yesterday`,`expenses_month`,`expenses_total`,`income_today`,`income_yesterday`,`income_month`,`income_total`,`role`) values (1,'2015-08-09','2015-08-09 12:39:22','system','T�0rT���4>q�v','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','system'),(2,'2015-08-09','2015-08-09 12:39:22','customer','���$u0H�	mjiO�','5000.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','user'),(3,'2015-08-09','2015-08-09 12:39:23','executor','��Y9�l.F%��ʿ�','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
