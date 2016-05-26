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
  `status` enum('new','reserved','done') NOT NULL DEFAULT 'new',
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `order` */

insert  into `order`(`id`,`created_at_d`,`created_at_dt`,`customer_id`,`executor_id`,`title`,`description`,`amount4customer`,`amount4executor`,`amount4system`,`status`,`status_at_dt`,`deleted_is`,`deleted_at_dt`) values (1,'2015-08-09','2015-08-09 17:10:22',2,NULL,'Небольшое тестовое задание','Кликни и разбогатей','100.00','0.00','0.00','new','2015-08-09 17:10:27','no',NULL),(2,'2015-08-09','2015-08-09 17:13:10',2,NULL,'Еще одно задание','Еще один клик до богатства','100.00','0.00','0.00','new','2015-08-09 17:13:10','no',NULL),(3,'2015-08-09','2015-08-09 21:24:51',2,NULL,'Задание номер три','Снова кликаем и продолжаем рубить баблос','100.00','0.00','0.00','new','2015-08-09 21:24:51','no',NULL),(4,'2015-08-09','2015-08-09 21:25:13',2,NULL,'Задание номер 4','А вот и денежка капает','100.00','0.00','0.00','new','2015-08-09 21:25:13','no',NULL),(5,'2015-08-09','2015-08-09 21:25:43',2,NULL,'Вот и хавчик идет','Хрум-хрум-хрум','100.00','0.00','0.00','new','2015-08-09 21:25:43','no',NULL),(6,'2015-08-09','2015-08-09 21:26:37',2,NULL,'Задание исполни это','На свой счет рублик получи желанный','100.00','0.00','0.00','new','2015-08-10 13:03:08','no',NULL),(7,'2015-08-09','2015-08-09 21:27:20',2,NULL,'Укради Мехос с Глоркса','Стань вандалом и героем супа существ','100.00','0.00','0.00','new','2015-08-10 13:01:06','no',NULL);

/* Trigger structure for table `order` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `update` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `update` BEFORE UPDATE ON `order` FOR EACH ROW BEGIN
	IF old.`status` != new.`status` THEN
		INSERT INTO 	`vk_log`.`order_status`
		SET	 	`created_at_d` = CURDATE(),
				`created_at_dt` = NOW(),
				order_id = old.id,
				status_old = old.`status`,
				status_new = new.`status`;
	END IF;
    END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
