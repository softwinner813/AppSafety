-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2022 at 07:34 AM
-- Server version: 10.5.15-MariaDB-cll-lve
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appsafel_appsafety`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `to` varchar(50) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `isCompleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `doc_histories`
--

CREATE TABLE `doc_histories` (
  `id` bigint(20) NOT NULL,
  `document_id` bigint(20) DEFAULT NULL,
  `from` varchar(50) DEFAULT NULL,
  `to` varchar(50) DEFAULT NULL,
  `fill_forms` longtext DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 1,
  `user_type` tinyint(1) NOT NULL DEFAULT 0,
  `subject` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `isDel` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `pre_price` int(11) DEFAULT NULL,
  `currency_name` varchar(10) NOT NULL DEFAULT 'GBP',
  `currency` varchar(10) DEFAULT '£',
  `count` int(3) NOT NULL DEFAULT 5,
  `icon` longtext DEFAULT NULL,
  `desc` longtext DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(3) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `price`, `pre_price`, `currency_name`, `currency`, `count`, `icon`, `desc`, `type`, `sort`, `created_at`, `updated_at`) VALUES
(1, 150, NULL, 'GBP', '£', 5, '<span class=\"svg-icon svg-icon-5x svg-icon-primary\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M6.5,16 L7.5,16 C8.32842712,16 9,16.6715729 9,17.5 L9,19.5 C9,20.3284271 8.32842712,21 7.5,21 L6.5,21 C5.67157288,21 5,20.3284271 5,19.5 L5,17.5 C5,16.6715729 5.67157288,16 6.5,16 Z M16.5,16 L17.5,16 C18.3284271,16 19,16.6715729 19,17.5 L19,19.5 C19,20.3284271 18.3284271,21 17.5,21 L16.5,21 C15.6715729,21 15,20.3284271 15,19.5 L15,17.5 C15,16.6715729 15.6715729,16 16.5,16 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6 C3,4.8954305 3.8954305,4 5,4 Z M15.5,15 C17.4329966,15 19,13.4329966 19,11.5 C19,9.56700338 17.4329966,8 15.5,8 C13.5670034,8 12,9.56700338 12,11.5 C12,13.4329966 13.5670034,15 15.5,15 Z M15.5,13 C16.3284271,13 17,12.3284271 17,11.5 C17,10.6715729 16.3284271,10 15.5,10 C14.6715729,10 14,10.6715729 14,11.5 C14,12.3284271 14.6715729,13 15.5,13 Z M7,8 L7,8 C7.55228475,8 8,8.44771525 8,9 L8,11 C8,11.5522847 7.55228475,12 7,12 L7,12 C6.44771525,12 6,11.5522847 6,11 L6,9 C6,8.44771525 6.44771525,8 7,8 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">5 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>5 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 0, 0, '2022-05-27 21:34:55', '2022-05-27 21:54:24'),
(3, 250, NULL, 'GBP', '£', 10, '<span class=\"svg-icon svg-icon-5x svg-icon-success\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<polygon fill=\"#000000\" points=\"14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912\"></polygon>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">10 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>10 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 0, 0, '2022-05-27 21:34:55', '2022-06-01 21:59:55'),
(4, 400, NULL, 'GBP', '£', 20, '<span class=\"svg-icon svg-icon-5x svg-icon-danger\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 C2.99998155,19.0000663 2.99998155,19.0000652 2.99998155,19.0000642 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M13.8,12 C13.1562,12 12.4033,12.7298529 12,13.2 C11.5967,12.7298529 10.8438,12 10.2,12 C9.0604,12 8.4,12.8888719 8.4,14.0201635 C8.4,15.2733878 9.6,16.6 12,18 C14.4,16.6 15.6,15.3 15.6,14.1 C15.6,12.9687084 14.9396,12 13.8,12 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">20 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>20 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 0, 0, '2022-05-27 21:34:55', '2022-06-01 21:59:58'),
(5, 600, NULL, 'GBP', '£', 1000000, '<span class=\"svg-icon svg-icon-5x svg-icon-warning\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<polygon points=\"0 0 24 0 24 24 0 24\"></polygon>\r\n<path d=\"M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">Unlimited Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>Unlimited employees</span>\r\n<span>Access all features</span>\r\n</p>', 0, 0, '2022-05-27 21:34:55', '2022-07-05 07:27:12'),
(6, 1500, NULL, 'GBP', '£', 5, '<span class=\"svg-icon svg-icon-5x svg-icon-primary\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M6.5,16 L7.5,16 C8.32842712,16 9,16.6715729 9,17.5 L9,19.5 C9,20.3284271 8.32842712,21 7.5,21 L6.5,21 C5.67157288,21 5,20.3284271 5,19.5 L5,17.5 C5,16.6715729 5.67157288,16 6.5,16 Z M16.5,16 L17.5,16 C18.3284271,16 19,16.6715729 19,17.5 L19,19.5 C19,20.3284271 18.3284271,21 17.5,21 L16.5,21 C15.6715729,21 15,20.3284271 15,19.5 L15,17.5 C15,16.6715729 15.6715729,16 16.5,16 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6 C3,4.8954305 3.8954305,4 5,4 Z M15.5,15 C17.4329966,15 19,13.4329966 19,11.5 C19,9.56700338 17.4329966,8 15.5,8 C13.5670034,8 12,9.56700338 12,11.5 C12,13.4329966 13.5670034,15 15.5,15 Z M15.5,13 C16.3284271,13 17,12.3284271 17,11.5 C17,10.6715729 16.3284271,10 15.5,10 C14.6715729,10 14,10.6715729 14,11.5 C14,12.3284271 14.6715729,13 15.5,13 Z M7,8 L7,8 C7.55228475,8 8,8.44771525 8,9 L8,11 C8,11.5522847 7.55228475,12 7,12 L7,12 C6.44771525,12 6,11.5522847 6,11 L6,9 C6,8.44771525 6.44771525,8 7,8 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">5 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>5 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 1, 0, '2022-05-27 21:34:55', '2022-05-27 21:56:11'),
(7, 2500, NULL, 'GBP', '£', 10, '<span class=\"svg-icon svg-icon-5x svg-icon-success\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<polygon fill=\"#000000\" points=\"14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912\"></polygon>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">10 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>10 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 1, 0, '2022-05-27 21:34:55', '2022-06-01 22:00:07'),
(8, 4000, NULL, 'GBP', '£', 20, '<span class=\"svg-icon svg-icon-5x svg-icon-danger\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect>\r\n<path d=\"M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 C2.99998155,19.0000663 2.99998155,19.0000652 2.99998155,19.0000642 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n<path d=\"M13.8,12 C13.1562,12 12.4033,12.7298529 12,13.2 C11.5967,12.7298529 10.8438,12 10.2,12 C9.0604,12 8.4,12.8888719 8.4,14.0201635 C8.4,15.2733878 9.6,16.6 12,18 C14.4,16.6 15.6,15.3 15.6,14.1 C15.6,12.9687084 14.9396,12 13.8,12 Z\" fill=\"#000000\" opacity=\"0.3\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">20 Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>20 employee\'s email</span>\r\n<span>Access all features</span>\r\n</p>', 1, 0, '2022-05-27 21:34:55', '2022-06-01 22:00:13'),
(9, 6000, NULL, 'GBP', '£', 1000000, '<span class=\"svg-icon svg-icon-5x svg-icon-warning\">\r\n<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\r\n<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\r\n<polygon points=\"0 0 24 0 24 24 0 24\"></polygon>\r\n<path d=\"M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z\" fill=\"#000000\"></path>\r\n</g>\r\n</svg>\r\n</span>', '<h4 class=\"font-size-h6 d-block font-weight-bold mb-7 text-dark-50\">Unlimited Users</h4>\r\n<p class=\"mb-15 d-flex flex-column\">\r\n<span>Unlimited employees</span>\r\n<span>Access all features</span>\r\n</p>', 1, 0, '2022-05-27 21:34:55', '2022-07-05 07:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2021_05_31_133442_create_sessions_table', 1),
(10, '2022_06_01_034415_create_jobs_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('anubisrules@gmail.com', '$2y$10$fiXXhRbMl0UCbnkLC9jSvep2KECAuy62q7Ea.udUNtMDnVQN4UTwu', '2022-06-07 14:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `membership_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(255) DEFAULT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `membership_id` int(11) DEFAULT NULL,
  `membership_end_date` date DEFAULT NULL,
  `free_end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_histories`
--
ALTER TABLE `doc_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doc_histories`
--
ALTER TABLE `doc_histories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
