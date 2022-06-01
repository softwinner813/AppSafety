/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.14-MariaDB : Database - appsafety
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `documents` */

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `documents` */

insert  into `documents`(`id`,`name`,`file`,`user_id`,`type`,`status`,`created_at`,`updated_at`) values 
(4,NULL,NULL,NULL,NULL,0,NULL,'2022-05-30 20:29:11'),
(8,'Permits-1654011405904.pdf','uploads/documents/Permits-1654011405904.pdf033703.pdf',3,3,0,'2022-05-31 15:37:03','2022-05-31 15:37:03'),
(9,'Permits-1654011405904.pdf','uploads/documents/Permits-1654011405904.pdf040803.pdf',3,3,0,'2022-05-31 16:08:03','2022-05-31 16:08:03');

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `employees` */

insert  into `employees`(`id`,`user_id`,`email`,`created_at`,`updated_at`) values 
(8,1,'geniusdev0813@gmail.com','2022-05-26 06:01:29','2022-05-26 06:01:29');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `failed_jobs` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `jobs` */

/*Table structure for table `memberships` */

DROP TABLE IF EXISTS `memberships`;

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL DEFAULT 0,
  `pre_price` int(11) DEFAULT NULL,
  `currency_name` varchar(10) NOT NULL DEFAULT 'GBP',
  `currency` varchar(10) DEFAULT '£',
  `icon` longtext DEFAULT NULL,
  `desc` longtext DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(3) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `memberships` */

insert  into `memberships`(`id`,`price`,`pre_price`,`currency_name`,`currency`,`icon`,`desc`,`type`,`sort`,`created_at`,`updated_at`) values 
(1,150,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-primary\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M6.5,16 L7.5,16 C8.32842712,16 9,16.6715729 9,17.5 L9,19.5 C9,20.3284271 8.32842712,21 7.5,21 L6.5,21 C5.67157288,21 5,20.3284271 5,19.5 L5,17.5 C5,16.6715729 5.67157288,16 6.5,16 Z M16.5,16 L17.5,16 C18.3284271,16 19,16.6715729 19,17.5 L19,19.5 C19,20.3284271 18.3284271,21 17.5,21 L16.5,21 C15.6715729,21 15,20.3284271 15,19.5 L15,17.5 C15,16.6715729 15.6715729,16 16.5,16 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6 C3,4.8954305 3.8954305,4 5,4 Z M15.5,15 C17.4329966,15 19,13.4329966 19,11.5 C19,9.56700338 17.4329966,8 15.5,8 C13.5670034,8 12,9.56700338 12,11.5 C12,13.4329966 13.5670034,15 15.5,15 Z M15.5,13 C16.3284271,13 17,12.3284271 17,11.5 C17,10.6715729 16.3284271,10 15.5,10 C14.6715729,10 14,10.6715729 14,11.5 C14,12.3284271 14.6715729,13 15.5,13 Z M7,8 L7,8 C7.55228475,8 8,8.44771525 8,9 L8,11 C8,11.5522847 7.55228475,12 7,12 L7,12 C6.44771525,12 6,11.5522847 6,11 L6,9 C6,8.44771525 6.44771525,8 7,8 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">5 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>5 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',0,0,'2022-05-27 21:34:55','2022-05-27 21:54:24'),
(3,250,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-success\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<polygon fill=\"#000000\" points=\"14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912\"></polygon>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">10 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>10 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',0,0,'2022-05-27 21:34:55','2022-05-28 12:29:43'),
(4,400,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-danger\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 C2.99998155,19.0000663 2.99998155,19.0000652 2.99998155,19.0000642 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M13.8,12 C13.1562,12 12.4033,12.7298529 12,13.2 C11.5967,12.7298529 10.8438,12 10.2,12 C9.0604,12 8.4,12.8888719 8.4,14.0201635 C8.4,15.2733878 9.6,16.6 12,18 C14.4,16.6 15.6,15.3 15.6,14.1 C15.6,12.9687084 14.9396,12 13.8,12 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">20 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>20 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',0,0,'2022-05-27 21:34:55','2022-05-28 12:29:49'),
(5,600,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-warning\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<polygon points=\"0 0 24 0 24 24 0 24\"></polygon>\r\n<path d=\"M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">Unlimited Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>Unlimited employees</span>\r\n<span>Access all features</span>\r\n</p>',0,0,'2022-05-27 21:34:55','2022-05-28 12:30:03'),
(6,1500,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-primary\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M6.5,16 L7.5,16 C8.32842712,16 9,16.6715729 9,17.5 L9,19.5 C9,20.3284271 8.32842712,21 7.5,21 L6.5,21 C5.67157288,21 5,20.3284271 5,19.5 L5,17.5 C5,16.6715729 5.67157288,16 6.5,16 Z M16.5,16 L17.5,16 C18.3284271,16 19,16.6715729 19,17.5 L19,19.5 C19,20.3284271 18.3284271,21 17.5,21 L16.5,21 C15.6715729,21 15,20.3284271 15,19.5 L15,17.5 C15,16.6715729 15.6715729,16 16.5,16 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6 C3,4.8954305 3.8954305,4 5,4 Z M15.5,15 C17.4329966,15 19,13.4329966 19,11.5 C19,9.56700338 17.4329966,8 15.5,8 C13.5670034,8 12,9.56700338 12,11.5 C12,13.4329966 13.5670034,15 15.5,15 Z M15.5,13 C16.3284271,13 17,12.3284271 17,11.5 C17,10.6715729 16.3284271,10 15.5,10 C14.6715729,10 14,10.6715729 14,11.5 C14,12.3284271 14.6715729,13 15.5,13 Z M7,8 L7,8 C7.55228475,8 8,8.44771525 8,9 L8,11 C8,11.5522847 7.55228475,12 7,12 L7,12 C6.44771525,12 6,11.5522847 6,11 L6,9 C6,8.44771525 6.44771525,8 7,8 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">5 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>5 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',1,0,'2022-05-27 21:34:55','2022-05-27 21:56:11'),
(7,2500,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-success\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<polygon fill=\"#000000\" points=\"14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912\"></polygon>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">10 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>10 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',1,0,'2022-05-27 21:34:55','2022-05-28 12:30:09'),
(8,4000,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-danger\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 C2.99998155,19.0000663 2.99998155,19.0000652 2.99998155,19.0000642 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M13.8,12 C13.1562,12 12.4033,12.7298529 12,13.2 C11.5967,12.7298529 10.8438,12 10.2,12 C9.0604,12 8.4,12.8888719 8.4,14.0201635 C8.4,15.2733878 9.6,16.6 12,18 C14.4,16.6 15.6,15.3 15.6,14.1 C15.6,12.9687084 14.9396,12 13.8,12 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">20 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>20 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>',1,0,'2022-05-27 21:34:55','2022-05-28 12:30:13'),
(9,6000,NULL,'GBP','£','<span class=\"svg-icon svg-icon-5x svg-icon-warning\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<polygon points=\"0 0 24 0 24 24 0 24\"></polygon>\r\n<path d=\"M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>','<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">Unlimited Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>Unlimited employees</span>\r\n<span>Access all features</span>\r\n</p>',1,0,'2022-05-27 21:34:55','2022-05-28 12:30:20');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),
(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),
(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),
(6,'2016_06_01_000004_create_oauth_clients_table',1),
(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),
(8,'2019_08_19_000000_create_failed_jobs_table',1),
(9,'2021_05_31_133442_create_sessions_table',1),
(10,'2022_06_01_034415_create_jobs_table',2);

/*Table structure for table `oauth_access_tokens` */

DROP TABLE IF EXISTS `oauth_access_tokens`;

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `oauth_access_tokens` */

/*Table structure for table `oauth_auth_codes` */

DROP TABLE IF EXISTS `oauth_auth_codes`;

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `oauth_auth_codes` */

/*Table structure for table `oauth_clients` */

DROP TABLE IF EXISTS `oauth_clients`;

CREATE TABLE `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `oauth_clients` */

/*Table structure for table `oauth_personal_access_clients` */

DROP TABLE IF EXISTS `oauth_personal_access_clients`;

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `oauth_personal_access_clients` */

/*Table structure for table `oauth_refresh_tokens` */

DROP TABLE IF EXISTS `oauth_refresh_tokens`;

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `oauth_refresh_tokens` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `password_resets` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sessions` */

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `membership_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `transactions` */

insert  into `transactions`(`id`,`user_id`,`payment_id`,`membership_id`,`created_at`,`updated_at`) values 
(3,1,'PAYID-MKJDTHQ8S846230X5097784C',1,'2022-05-28 15:04:31','2022-05-28 15:04:31'),
(4,5,'PAYID-MKJD35I6VE26255TF462131M',1,'2022-05-28 15:23:33','2022-05-28 15:23:33'),
(5,1,'PAYID-MKJEOWQ56202966G1085062Y',1,'2022-05-28 16:03:07','2022-05-28 16:03:07'),
(6,1,'PAYID-MKJFS3Q9T551351AT767870J',1,'2022-05-28 17:20:12','2022-05-28 17:20:12'),
(7,1,'ch_3L4UQTC3DcEUQ8LM1LMdcpDv',1,'2022-05-28 18:33:07','2022-05-28 18:33:07'),
(8,3,'ch_3L4USDC3DcEUQ8LM1mKIJcMb',1,'2022-05-28 18:34:55','2022-05-28 18:34:55'),
(9,3,'ch_3L4UVaC3DcEUQ8LM0tcRpu3Z',1,'2022-05-28 18:38:25','2022-05-28 18:38:25'),
(10,3,'PAYID-MKJG3SI3G986739N1313384Y',1,'2022-05-28 18:47:21','2022-05-28 18:47:21'),
(11,3,'ch_3L4UfeC3DcEUQ8LM0c5gp2og',1,'2022-05-28 18:48:48','2022-05-28 18:48:48'),
(12,3,'ch_3L4UhVC3DcEUQ8LM1W35QrYW',1,'2022-05-28 18:50:43','2022-05-28 18:50:43');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(255) NOT NULL,
  `userType` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `membership_id` int(11) DEFAULT NULL,
  `membership_end_date` date DEFAULT NULL,
  `free_end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`address`,`logo`,`phonenumber`,`userType`,`remember_token`,`membership_id`,`membership_end_date`,`free_end_date`,`created_at`,`updated_at`) values 
(1,'Company1','test@test.com',NULL,'$2y$10$.F5FxzVILomGmmR5wMA2FO3Bh29CKEzJWkk9yU886fVXR3MqDmcsK','aaaa','/uploads/logos/Company1_logo.png','+1 54564 879',NULL,'2gSgPN6b3gLQ2HdlLBTDd5GeCsvEFdCYiI0cz21tCnnHGSj0qZjLJvtftEve',1,'2022-06-27',NULL,'2022-05-23 09:21:03','2022-05-28 16:03:07'),
(2,'fds','fds@fds.com',NULL,'$2y$10$Y03/RNXgGFSxlZE2Vbb4ZeOPe2t2prJXJmgalRuSZl3l9dstLHf2m','ffdsafdsa',NULL,'23423423',NULL,NULL,NULL,NULL,NULL,'2022-05-23 09:24:14','2022-05-23 09:24:14'),
(3,'fdsaf','test1@test.com',NULL,'$2y$10$r/oVMbqW5QWfSpr/rV9j5eEBzehPSsD5P3V/l4G0hbE5hsTr8wt4q','fdsafds',NULL,'4324232432',NULL,'gHLwDBBGFTeQ4miZntpHeSYdEBBRGEd4lfm1QUbihWdptbDzBfKcVR6pH54N',1,'2022-06-27',NULL,'2022-05-23 09:41:07','2022-05-28 18:34:55'),
(4,'fdafdas','fdsfdsfd@fsfd.com',NULL,'$2y$10$Ya4ddvBd47aPCqjaGg6hOOnTUddK1PJRfitloBtZbsLSI75lhEqNO','fdsfdsa',NULL,'123123',NULL,NULL,NULL,NULL,NULL,'2022-05-24 06:47:13','2022-05-24 06:47:13'),
(5,'fds','fdsfsdfds@resf.com',NULL,'$2y$10$2doL.q7SZdSczmJtxepG/OSvFgXmiIVGosROPvYPBTdA0h4KBZB2a','dfsdfafd',NULL,'12312312',NULL,NULL,1,'2022-05-31','2022-05-31','2022-05-28 15:19:58','2022-05-28 15:23:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
