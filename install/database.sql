-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2024 at 08:11 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventlab`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `email_verified_at`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admins', 'admin@site.com', 'admin', NULL, '6624ee96387ea1713696406.png', '$2y$12$M8qCD3ygoFJARRr5G766MuXmNq11gRvvLMGe/x74SUCUUqVx3glUq', 'o8grPL7pHsrcvX9CdqijayyWnaRnnH8ymT8hTuePajGTB0sPl0WV6802mXHw', NULL, '2024-05-28 05:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `organizer_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cron_schedule_id` int NOT NULL DEFAULT '0',
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_jobs`
--

INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Cancel timeout tickets', 'cancel_timeout_tickets', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"cancelPaymentTimeoutTickets\"]', '', 1, '2024-05-29 08:12:30', '2024-05-29 07:57:30', 1, 1, '2024-03-18 06:44:11', '2024-05-29 01:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_logs`
--

CREATE TABLE `cron_job_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `cron_job_id` int NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_job_logs`
--

INSERT INTO `cron_job_logs` (`id`, `cron_job_id`, `start_at`, `end_at`, `duration`, `error`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-05-29 07:57:30', '2024-05-29 07:57:30', 0, NULL, '2024-05-29 01:57:30', '2024-05-29 01:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedules`
--

CREATE TABLE `cron_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_schedules`
--

INSERT INTO `cron_schedules` (`id`, `name`, `interval`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Every 15 Minutes', 900, 1, '2024-02-27 02:29:29', '2024-02-27 02:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `order_id` int UNSIGNED NOT NULL DEFAULT '0',
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `success_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `organizer_id` int NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `organizer_id` int NOT NULL DEFAULT '0',
  `category_id` int NOT NULL DEFAULT '0',
  `location_id` int NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `seats` int DEFAULT NULL,
  `seats_booked` int DEFAULT '0',
  `price` float DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `step` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `verification_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text COLLATE utf8mb4_unicode_ci,
  `shortcode` text COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tawk-chat', 'Tawk.to', 'Key location is shown bellow', 'tawky_big.png', '<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"------\"}}', 'twak.png', 0, '2019-10-18 23:16:05', '2022-03-22 05:22:24'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB\"}}', 'recaptcha.png', 0, '2019-10-18 23:16:05', '2024-04-10 00:49:12'),
(3, 'custom-captcha', 'Custom Captcha', 'Just put any random string', 'customcaptcha.png', NULL, '{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}', 'na', 0, '2019-10-18 23:16:05', '2024-04-23 00:28:21'),
(4, 'google-analytics', 'Google Analytics', 'Key location is shown bellow', 'google_analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>', '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}', 'ganalytics.png', 0, NULL, '2021-05-04 10:19:12'),
(5, 'fb-comment', 'Facebook Comment ', 'Key location is shown bellow', 'Facebook.png', '<div id=\"fb-root\"></div><script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1\"></script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----\"}}', 'fb_com.png', 0, NULL, '2024-04-08 03:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `act`, `form_data`, `created_at`, `updated_at`) VALUES
(2, 'user_kyc', '{\"name_of_father\":{\"name\":\"Name of Father\",\"label\":\"name_of_father\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"name_of_mother\":{\"name\":\"Name of Mother\",\"label\":\"name_of_mother\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"nid_number\":{\"name\":\"NID Number\",\"label\":\"nid_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"}}', '2024-04-07 23:12:44', '2024-04-07 23:20:43'),
(3, 'organizer_kyc', '{\"organizer_name\":{\"name\":\"Organizer Name\",\"label\":\"organizer_name\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"organizer_type\":{\"name\":\"Organizer Type\",\"label\":\"organizer_type\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"contact_person\":{\"name\":\"Contact Person\",\"label\":\"contact_person\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"contact_person\'s_position\":{\"name\":\"Contact Person\'s Position\",\"label\":\"contact_person\'s_position\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"contact_email_address\":{\"name\":\"Contact Email Address\",\"label\":\"contact_email_address\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"contact_phone_number\":{\"name\":\"Contact Phone Number\",\"label\":\"contact_phone_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"organizer_address\":{\"name\":\"Organizer Address\",\"label\":\"organizer_address\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"business_registration_number\":{\"name\":\"Business Registration Number\",\"label\":\"business_registration_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"tax_identification_number\":{\"name\":\"Tax Identification Number\",\"label\":\"tax_identification_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"legal_entity_type\":{\"name\":\"Legal Entity Type\",\"label\":\"legal_entity_type\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"date_of_establishment\":{\"name\":\"Date of Establishment\",\"label\":\"date_of_establishment\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"proof_of_address\":{\"name\":\"Proof of Address\",\"label\":\"proof_of_address\",\"is_required\":\"required\",\"extensions\":\"jpg,jpeg,png,pdf\",\"options\":[],\"type\":\"file\"}}', '2024-04-07 23:25:15', '2024-04-07 23:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint UNSIGNED NOT NULL,
  `data_keys` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"event\",\"eventmanagement\",\"eventticket\",\"tiecketselling\"],\"description\":\"Discover the ultimate solution for empowering your event management business with EventLab. As a savvy entrepreneur in the dynamic events industry, you understand the importance of efficient ticketing systems that streamline operations and maximize revenue.\",\"social_title\":\"Viserlab Limited\",\"social_description\":\"Discover the ultimate solution for empowering your event management business with EventLab. As a savvy entrepreneur in the dynamic events industry, you understand the importance of efficient ticketing systems that streamline operations and maximize revenue.\",\"image\":\"6656e11763d7f1716969751.png\"}', NULL, NULL, '', '2020-07-04 23:42:52', '2024-05-29 02:02:32'),
(25, 'blog.content', '{\"title\":\"LATEST BLOGS\",\"heading\":\"Latest Event Updates\"}', NULL, 'basic', NULL, '2020-10-28 00:51:34', '2024-04-22 05:06:26'),
(27, 'contact_us.content', '{\"heading\":\"Reach Out for Inquiries\",\"description\":\"Reach out for inquiries! We\'re here to help with any questions you have about our services.\",\"email\":\"support@eventlab.com\",\"address\":\"123 Main Street, London\",\"contact_number\":\"+44 20 1234 5678\",\"website_footer\":\"EventLab is a global self-service ticketing platform for live experiences that allows anyone to create, share, find and attend events\"}', NULL, 'basic', NULL, '2020-10-28 00:59:19', '2024-04-22 08:56:14'),
(28, 'counter.content', '{\"events_added\":\"120k\",\"events_organizers\":\"40k+\",\"events_hosted\":\"3k\",\"tickets_sold\":\"11M\"}', NULL, 'basic', NULL, '2020-10-28 01:04:02', '2024-02-13 23:04:43'),
(31, 'social_icon.element', '{\"title\":\"Whatsapp\",\"social_icon\":\"<i class=\\\"fab fa-whatsapp\\\"><\\/i>\",\"url\":\"https:\\/\\/web.whatsapp.com\\/\"}', NULL, 'basic', NULL, '2020-11-12 04:07:30', '2024-02-13 06:54:48'),
(34, 'feature.element', '{\"title\":\"asdf\",\"description\":\"asdf\",\"feature_icon\":\"asdf\"}', NULL, NULL, NULL, '2021-01-03 23:41:02', '2021-01-03 23:41:02'),
(39, 'banner.content', '{\"has_image\":\"1\",\"title\":\"Journey to New Horizons\",\"heading\":\"Locate and celebrate upcoming events.\",\"banner_image\":\"65cb26026bde51707812354.png\",\"background_image\":\"65df81ec53c6f1709146604.jpg\"}', NULL, 'basic', NULL, '2021-05-02 06:09:30', '2024-03-27 02:10:15'),
(41, 'cookie.data', '{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<div>\\r\\n  <h4>What information do we collect?<\\/h4>\\r\\n  <p>\\r\\n    We gather data from you when you register on our site, submit a request, buy\\r\\n    any services, react to an overview, or round out a structure. At the point\\r\\n    when requesting any assistance or enrolling on our site, as suitable, you\\r\\n    might be approached to enter your: name, email address, or telephone number.\\r\\n    You may, nonetheless, visit our site anonymously.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>How do we protect your information?<\\/h4>\\r\\n  <p>\\r\\n    All provided delicate\\/credit data is sent through Stripe.<br>After an\\r\\n    exchange, your private data (credit cards, social security numbers,\\r\\n    financials, and so on) won\'t be put away on our workers.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>Do we disclose any information to outside parties?<\\/h4>\\r\\n  <p>\\r\\n    We don\' t sell, exchange, or in any case move to outside gatherings by and\\r\\n    by recognizable data. This does exclude confided in outsiders who help us in\\r\\n    working our site, leading our business, or adjusting you, since those\\r\\n    gatherings consent to keep this data private. We may likewise deliver your\\r\\n    data when we accept discharge is suitable to follow the law, implement our\\r\\n    site strategies, or ensure our own or others\' rights, property, or\\r\\n    wellbeing.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>Children\' s Online Privacy Protection Act Compliance<\\/h4>\\r\\n  <p>\\r\\n    We are consistent with the prerequisites of COPPA (Children\'s Online Privacy\\r\\n    Protection Act), we don\' t gather any data from anybody under 13 years old.\\r\\n    Our site, items, and administrations are completely coordinated to\\r\\n    individuals who are in any event 13 years of age or more established.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>Changes to our Privacy Policy<\\/h4>\\r\\n  <p>\\r\\n    If we decide to change our privacy policy, we will post those changes on\\r\\n    this page.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>How long we retain your information?<\\/h4>\\r\\n  <p>\\r\\n    At the point when you register for our site, we cycle and keep your\\r\\n    information we have about you however long you don\'t erase the record or\\r\\n    withdraw yourself (subject to laws and guidelines).\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n<div>\\r\\n  <h4>What we don\\u2019t do with your data<\\/h4>\\r\\n  <p>\\r\\n    We don\' t and will never share, unveil, sell, or in any case give your\\r\\n    information to different organizations for the promoting of their items or\\r\\n    administrations.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br>\",\"status\":1}', NULL, NULL, NULL, '2020-07-04 23:42:52', '2024-04-22 07:36:17'),
(42, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<div>\\r\\n  <h4>What information do we collect?<\\/h4>\\r\\n  <p>\\r\\n    We gather data from you when you register on our site, submit a request, buy\\r\\n    any services, react to an overview, or round out a structure. At the point\\r\\n    when requesting any assistance or enrolling on our site, as suitable, you\\r\\n    might be approached to enter your: name, email address, or telephone number.\\r\\n    You may, nonetheless, visit our site anonymously.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>How do we protect your information?<\\/h4>\\r\\n  <p>\\r\\n    All provided delicate\\/credit data is sent through Stripe.<br \\/>After an\\r\\n    exchange, your private data (credit cards, social security numbers,\\r\\n    financials, and so on) won\'t be put away on our workers.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Do we disclose any information to outside parties?<\\/h4>\\r\\n  <p>\\r\\n    We don\'t sell, exchange, or in any case move to outside gatherings by and by\\r\\n    recognizable data. This does exclude confided in outsiders who help us in\\r\\n    working our site, leading our business, or adjusting you, since those\\r\\n    gatherings consent to keep this data private. We may likewise deliver your\\r\\n    data when we accept discharge is suitable to follow the law, implement our\\r\\n    site strategies, or ensure our own or others\' rights, property, or\\r\\n    wellbeing.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Children\'s Online Privacy Protection Act Compliance<\\/h4>\\r\\n  <p>\\r\\n    We are consistent with the prerequisites of COPPA (Children\'s Online Privacy\\r\\n    Protection Act), we don\'t gather any data from anybody under 13 years old.\\r\\n    Our site, items, and administrations are completely coordinated to\\r\\n    individuals who are in any event 13 years of age or more established.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Changes to our Privacy Policy<\\/h4>\\r\\n  <p>\\r\\n    If we decide to change our privacy policy, we will post those changes on\\r\\n    this page.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>How long we retain your information?<\\/h4>\\r\\n  <p>\\r\\n    At the point when you register for our site, we cycle and keep your\\r\\n    information we have about you however long you don\'t erase the record or\\r\\n    withdraw yourself (subject to laws and guidelines).\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n\\r\\n<div>\\r\\n  <h4>What we don\\u2019t do with your data<\\/h4>\\r\\n  <p>\\r\\n    We don\'t and will never share, unveil, sell, or in any case give your\\r\\n    information to different organizations for the promoting of their items or\\r\\n    administrations.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\"}', NULL, 'basic', 'privacy-policy', '2021-06-09 08:50:42', '2024-04-22 07:29:59'),
(43, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<div>\\r\\n  <h4>Terms & Conditions for Users<\\/h4>\\r\\n  <p>\\r\\n    Before getting to this site, you are consenting to be limited by these site\\r\\n    Terms and Conditions of Use, every single appropriate law, and guidelines,\\r\\n    and concur that you are answerable for consistency with any material\\r\\n    neighborhood laws. If you disagree with any of these terms, you are\\r\\n    restricted from utilizing or getting to this site.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Support<\\/h4>\\r\\n  <p>\\r\\n    Whenever you have downloaded our item, you may get in touch with us for help\\r\\n    through email and we will give a valiant effort to determine your issue. We\\r\\n    will attempt to answer using the Email for more modest bug fixes, after\\r\\n    which we will refresh the center bundle. Content help is offered to\\r\\n    confirmed clients by Tickets as it were. Backing demands made by email and\\r\\n    Livechat.\\r\\n  <\\/p>\\r\\n  <p class=\\\"my-3 font-18 font-weight-bold\\\">\\r\\n    On the off chance that your help requires extra adjustment of the System, at\\r\\n    that point, you have two alternatives:\\r\\n  <\\/p>\\r\\n  <ul>\\r\\n    <li>Hang tight for additional update discharge.<\\/li>\\r\\n    <li>\\r\\n      Or on the other hand, enlist a specialist (We offer customization for\\r\\n      extra charges).\\r\\n    <\\/li>\\r\\n  <\\/ul>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Ownership<\\/h4>\\r\\n  <p>\\r\\n    You may not guarantee scholarly or selective possession of any of our items,\\r\\n    altered or unmodified. All items are property, we created them. Our items\\r\\n    are given \\\"with no guarantees\\\" without guarantee of any sort, either\\r\\n    communicated or suggested. On no occasion will our juridical individual be\\r\\n    subject to any harms including, however not restricted to, immediate,\\r\\n    roundabout, extraordinary, accidental, or significant harms or different\\r\\n    misfortunes emerging out of the utilization of or powerlessness to utilize\\r\\n    our items.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Warranty<\\/h4>\\r\\n  <p>\\r\\n    We don\'t offer any guarantee or assurance of these Services in any way. When\\r\\n    our Services have been modified we can\'t ensure they will work with all\\r\\n    outsider plugins, modules, or internet browsers. Program similarity ought to\\r\\n    be tried against the show formats on the demo worker. If you don\'t mind\\r\\n    guarantee that the programs you use will work with the component, as we can\\r\\n    not ensure that our systems will work with all program mixes.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Unauthorized\\/Illegal Usage<\\/h4>\\r\\n  <p>\\r\\n    You may not utilize our things for any illicit or unapproved reason or may\\r\\n    you, in the utilization of the stage, disregard any laws in your locale\\r\\n    (counting yet not restricted to copyright laws) just as the laws of your\\r\\n    nation and International law. Specifically, it is disallowed to utilize the\\r\\n    things on our foundation for pages that advance: brutality, illegal\\r\\n    intimidation, hard sexual entertainment, bigotry, obscenity content or warez\\r\\n    programming joins.<br \\/><br \\/>You can\'t imitate, copy, duplicate, sell,\\r\\n    exchange or adventure any of our segment, utilization of the offered on our\\r\\n    things, or admittance to the administration without the express composed\\r\\n    consent by us or item proprietor.<br \\/><br \\/>Our Members are liable for all\\r\\n    substance posted on the discussion and demo and movement that happens under\\r\\n    your record.<br \\/><br \\/>We hold the chance of hindering your participation\\r\\n    account quickly if we will think about a particularly not allowed\\r\\n    conduct.<br \\/><br \\/>If you make a record on our site, you are liable for\\r\\n    keeping up the security of your record, and you are completely answerable\\r\\n    for all exercises that happen under the record and some other activities\\r\\n    taken regarding the record. You should quickly inform us, of any unapproved\\r\\n    employments of your record or some other penetrates of security.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Payment\\/Refund Policy<\\/h4>\\r\\n  <p>\\r\\n    No refund or cash back will be made. After a deposit has been finished, it\\r\\n    is extremely unlikely to invert it. You should utilize your equilibrium on\\r\\n    requests our administrations, Hosting, SEO campaign. You concur that once\\r\\n    you complete a deposit, you won\'t document a debate or a chargeback against\\r\\n    us in any way, shape, or form.<br \\/><br \\/>If you document a debate or\\r\\n    chargeback against us after a deposit, we claim all authority to end every\\r\\n    single future request, prohibit you from our site. False action, for\\r\\n    example, utilizing unapproved or taken charge cards will prompt the end of\\r\\n    your record. There are no special cases.\\r\\n  <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n  <h4>Free Balance \\/ Coupon Policy<\\/h4>\\r\\n  <p>\\r\\n    We offer numerous approaches to get FREE Balance, Coupons and Deposit offers\\r\\n    yet we generally reserve the privilege to audit it and deduct it from your\\r\\n    record offset with any explanation we may it is a sort of misuse. If we\\r\\n    choose to deduct a few or all of free Balance from your record balance, and\\r\\n    your record balance becomes negative, at that point the record will\\r\\n    naturally be suspended. If your record is suspended because of a negative\\r\\n    Balance you can request to make a custom payment to settle your equilibrium\\r\\n    to actuate your record.\\r\\n  <\\/p>\\r\\n<\\/div>\"}', NULL, 'basic', 'terms-of-service', '2021-06-09 08:51:18', '2024-04-22 07:32:28'),
(44, 'maintenance.data', '{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color: rgb(111, 111, 111); font-family: Nunito, sans-serif; margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px; font-family: Exo, sans-serif; color: rgb(54, 54, 54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"text-align: center; margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div>\",\"image\":\"6656e1471b1511716969799.png\"}', NULL, NULL, NULL, '2020-07-04 23:42:52', '2024-05-29 02:03:19'),
(45, 'cta.content', '{\"has_image\":\"1\",\"heading\":\"GET MY EVENTS TODAY!\",\"button_text\":\"Buy Tickets\",\"button_url\":\"events\",\"image\":\"65e230bb927ac1709322427.jpg\"}', NULL, 'basic', NULL, '2024-02-13 02:47:01', '2024-03-01 13:47:07'),
(46, 'breadcrumb.content', '{\"has_image\":\"1\",\"image\":\"65cb459d673fb1707820445.png\"}', NULL, 'basic', NULL, '2024-02-13 04:34:05', '2024-02-13 04:34:05'),
(47, 'social_icon.element', '{\"title\":\"Twitter\",\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/twitter.com\\/?lang=en\"}', NULL, 'basic', NULL, '2024-02-13 06:55:30', '2024-02-13 06:55:30'),
(48, 'social_icon.element', '{\"title\":\"Instagram\",\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', NULL, 'basic', NULL, '2024-02-13 06:55:55', '2024-02-13 06:55:55'),
(49, 'social_icon.element', '{\"title\":\"Youtube\",\"social_icon\":\"<i class=\\\"lab la-youtube\\\"><\\/i>\",\"url\":\"https:\\/\\/www.youtube.com\\/\"}', NULL, 'basic', NULL, '2024-02-13 06:56:21', '2024-02-13 06:56:21'),
(50, 'about_us.content', '{\"short_title\":\"ABOUT\",\"heading\":\"Who We Are\",\"description\":\"We are passionate about bringing people together through unforgettable events. With a commitment to excellence and innovation, we strive to redefine the event industry by providing a platform that connects organizers with attendees seamlessly. Our team comprises dedicated professionals who are driven by a shared vision of fostering community and creating memorable experiences. Whether you\'re a seasoned event organizer or an enthusiastic attendee, we\'re here to support you every step of the way. Join us on our journey to transform the way we celebrate, connect, and inspire.\",\"youtube_embed_link\":\"https:\\/\\/www.youtube.com\\/watch?v=WOb4cj7izpE\",\"button_text\":\"Explore Events\",\"button_url\":\"events\",\"has_image\":\"1\",\"image\":\"65cc49c0e04521707887040.png\"}', NULL, 'basic', NULL, '2024-02-13 23:04:00', '2024-04-22 22:34:56'),
(51, 'choose_us.content', '{\"heading\":\"Experience Seamless Event Planning\",\"description\":\"Our platform offers a seamless event planning experience, providing you with a comprehensive range of features to make organizing and attending events effortless. From user-friendly navigation to secure booking processes, we prioritize convenience at every step. With our intuitive interface and dedicated customer support, you can trust us to streamline your event journey, ensuring memorable experiences every time. Choose us for hassle-free event planning tailored to your needs.\",\"button_text\":\"Explore Events\",\"button_url\":\"events\",\"has_image\":\"1\",\"image\":\"660a6a0d720dc1711958541.png\"}', NULL, 'basic', NULL, '2024-02-13 23:05:24', '2024-04-01 02:02:21'),
(52, 'how_it_works.content', '{\"title\":\"HOW WE ARE WORKS\",\"heading\":\"How to Works\"}', NULL, 'basic', NULL, '2024-02-13 23:05:57', '2024-04-23 01:00:12'),
(53, 'how_it_works.element', '{\"title\":\"Pick a Location\",\"icon\":\"<i class=\\\"fas fa-map-marker-alt\\\"><\\/i>\",\"description\":\"Choose from a variety of locations to attend your next event.\"}', NULL, 'basic', NULL, '2024-02-13 23:06:11', '2024-04-22 04:58:37'),
(54, 'how_it_works.element', '{\"title\":\"Select a Category\",\"icon\":\"<i class=\\\"las la-book\\\"><\\/i>\",\"description\":\"Explore diverse categories ranging from music concerts to workshops.\"}', NULL, 'basic', NULL, '2024-02-13 23:06:23', '2024-04-22 04:58:42'),
(55, 'how_it_works.element', '{\"title\":\"Find Your Event\",\"icon\":\"<i class=\\\"las la-search\\\"><\\/i>\",\"description\":\"Discover the perfect event tailored to your interests and preferences.\"}', NULL, 'basic', NULL, '2024-02-13 23:08:01', '2024-04-22 04:58:50'),
(56, 'how_it_works.element', '{\"title\":\"Book Seats\",\"icon\":\"<i class=\\\"las la-ticket-alt\\\"><\\/i>\",\"description\":\"Secure your spot by easily booking seats for you and your companions.\"}', NULL, 'basic', NULL, '2024-02-13 23:08:31', '2024-04-22 04:58:57'),
(57, 'testimonials.content', '{\"title\":\"TESTIMONIALS\",\"heading\":\"What Our Users Say\"}', NULL, 'basic', NULL, '2024-02-13 23:10:47', '2024-04-23 01:14:43'),
(58, 'testimonials.element', '{\"has_image\":[\"1\"],\"name\":\"Devon Lane\",\"designation\":\"Organizer\",\"rating\":\"5\",\"description\":\"As an event organizer, this platform has been a game-changer for selling tickets. The interface is user-friendly, and the support team is incredibly helpful.\",\"image\":\"65cc4c29a6a791707887657.png\"}', NULL, 'basic', NULL, '2024-02-13 23:11:20', '2024-03-15 23:49:57'),
(59, 'testimonials.element', '{\"has_image\":[\"1\"],\"name\":\"Sarah M.\",\"designation\":\"Designer\",\"rating\":\"5\",\"description\":\"I had an amazing experience using the event selling platform! From browsing events to booking seats, everything was seamless. Highly recommended!\",\"image\":\"65cc4c4516dd21707887685.png\"}', NULL, 'basic', NULL, '2024-02-13 23:14:45', '2024-03-15 23:50:10'),
(60, 'testimonials.element', '{\"has_image\":[\"1\"],\"name\":\"Carter Grant\",\"designation\":\"Business Owner\",\"rating\":\"5\",\"description\":\"I was hesitant at first, but this platform made it easy to find events matching my interests. Now, I\'m a regular attendee, thanks to the variety and convenience it offers!\",\"image\":\"65cc4c542bb921707887700.png\"}', NULL, 'basic', NULL, '2024-02-13 23:15:00', '2024-03-15 23:52:59'),
(61, 'testimonials.element', '{\"has_image\":[\"1\"],\"name\":\"Hedwig Hays\",\"designation\":\"Musician\",\"rating\":\"4\",\"description\":\"I\'ve attended multiple events through this platform, from concerts to art exhibitions, and each time has been fantastic. It\'s my go-to for finding and booking events in my city!\",\"image\":\"65cc4c67c4a9f1707887719.png\"}', NULL, 'basic', NULL, '2024-02-13 23:15:19', '2024-03-15 23:51:51'),
(62, 'faq.content', '{\"title\":\"FAQ\'s\",\"heading\":\"We\'ve Got Answers\"}', NULL, 'basic', NULL, '2024-02-13 23:17:03', '2024-04-06 02:23:28'),
(63, 'faq.element', '{\"question\":\"How do I cancel my event booking?\",\"answer\":\"To cancel your event booking, simply log in to your account, navigate to your bookings, and select the event you wish to cancel. Follow the prompts to complete the cancellation process.\"}', NULL, 'basic', NULL, '2024-02-13 23:17:31', '2024-03-15 23:54:54'),
(64, 'faq.element', '{\"question\":\"How do I contact the event organizer?\",\"answer\":\"You can usually find contact information for the event organizer on the event page or confirmation email. If not, you can reach out to the platform\'s customer support for assistance in contacting the organizer.\"}', NULL, 'basic', NULL, '2024-02-13 23:17:36', '2024-03-15 23:57:10'),
(65, 'faq.element', '{\"question\":\"Are there any age restrictions for events?\",\"answer\":\"No there are currently no age restrictions for purchasing events.\"}', NULL, 'basic', NULL, '2024-02-13 23:17:38', '2024-03-15 23:59:57'),
(66, 'faq.element', '{\"question\":\"What payment methods are accepted for booking tickets?\",\"answer\":\"Event selling platforms typically accept a variety of payment methods, including credit\\/debit cards, PayPal, and sometimes alternative payment options like Stripe or Coinpayments. You can usually find the accepted payment methods during the checkout process.\"}', NULL, 'basic', NULL, '2024-02-13 23:17:41', '2024-03-16 00:01:54'),
(69, 'login_registrarion.content', '{\"has_image\":\"1\",\"image\":\"65cc4d643b13d1707887972.png\"}', NULL, 'basic', NULL, '2024-02-13 23:19:32', '2024-02-13 23:19:33'),
(70, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Top Trends in Corporate Event Planning for 2024\",\"short_description\":\"Stay ahead of the curve with the latest trends shaping the corporate event industry.\",\"description\":\"<div>As the corporate event landscape continues to evolve, staying informed about the latest trends and innovations is essential for event planners looking to deliver impactful and memorable experiences for their clients and attendees. From immersive technology and sustainability initiatives to hybrid formats and wellness-focused programming, there are many exciting trends shaping the future of corporate event planning. In this insightful blog post, we\'ll explore some of the top trends to watch for in 2024 and beyond.\\u00a0<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">One of the most significant trends in corporate event planning is the rise of immersive technology experiences. From virtual reality (VR) and augmented reality (AR) activations to interactive digital displays and holographic presentations, event planners are leveraging cutting-edge technology to create immersive and engaging experiences that captivate attendees\' attention and leave a lasting impression.\\u00a0<\\/div><div><br \\/><\\/div><div>Another important trend gaining momentum in the corporate event space is a focus on sustainability and environmental responsibility. With growing concerns about climate change and environmental impact, more companies are seeking ways to reduce waste, conserve resources, and minimize their carbon footprint when planning events. From eco-friendly venue selections and plant-based catering options to digital invitations and reusable decor, sustainable practices are becoming increasingly integrated into corporate event planning strategies.\\u00a0<\\/div><div><br \\/><\\/div><div>Additionally, the continued popularity of hybrid and virtual events is reshaping the way companies approach corporate gatherings. Whether it\'s due to travel restrictions, budget constraints, or evolving preferences for remote participation, hybrid and virtual event formats offer unique opportunities to reach wider audiences and deliver content in innovative ways. As technology continues to advance and connectivity improves, we can expect to see even greater integration of digital elements into corporate event programming in the years to come. By staying informed about these and other emerging trends, corporate event planners can adapt their strategies and offerings to meet the evolving needs and expectations of their clients and attendees. Embracing innovation and creativity will be key to success in the dynamic and ever-changing world of corporate event planning.<\\/div>\",\"blog_image\":\"6612280c88c971712465932.png\"}', NULL, 'basic', 'top-trends-in-corporate-event-planning-for-2024', '2024-02-22 04:16:08', '2024-04-08 09:23:58'),
(71, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"How to Plan a Successful Networking Event: Tips and Tricks\",\"short_description\":\"Learn the secrets to organizing a networking event that fosters meaningful connections and professional growth.\",\"description\":\"<div>Networking events are invaluable opportunities for professionals to expand their contacts, exchange ideas, and forge new partnerships. However, planning a successful networking event requires careful attention to detail and strategic execution. In this comprehensive guide, we\'ll share expert tips and tricks to help you plan and execute a networking event that leaves a lasting impression on attendees.<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">The first step in planning a successful networking event is to define your target audience and objectives. Consider the industries and professions you want to attract, as well as the specific goals you hope to achieve through the event. Whether it\'s facilitating business collaborations, showcasing thought leadership, or simply providing a platform for professionals to connect, having a clear purpose will guide your planning process and ensure the event resonates with attendees.<\\/div><div><br \\/><\\/div><div>Once you\'ve defined your audience and objectives, focus on creating a compelling program that encourages interaction and engagement. Incorporate elements such as panel discussions, workshops, and interactive activities that facilitate meaningful conversations and relationship-building. Additionally, consider incorporating technology tools such as event apps and networking platforms to facilitate connections before, during, and after the event.<\\/div><div><br \\/><\\/div><div>Another key aspect of planning a successful networking event is selecting the right venue and date. Choose a location that is convenient for your target audience and offers ample space for networking activities. Additionally, consider factors such as accessibility, parking, and amenities to ensure a positive attendee experience. When selecting the date, be mindful of potential conflicts with holidays, industry events, and other competing commitments.<\\/div><div><br \\/><\\/div><div>By following these tips and leveraging the power of strategic planning and creative execution, you can organize a networking event that provides value to attendees and helps them advance their professional goals.<\\/div>\",\"blog_image\":\"661228701bdf91712466032.png\"}', NULL, 'basic', 'how-to-plan-a-successful-networking-event:-tips-and-tricks', '2024-02-22 04:16:37', '2024-04-08 09:24:42'),
(72, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"The Art of Hosting a Virtual Conference: Best Practices and Insights\",\"short_description\":\"Discover how to plan and execute a successful virtual conference that engages participants and delivers impactful content.\",\"description\":\"<div>Virtual conferences have become increasingly popular in recent years, offering organizations a cost-effective and accessible way to connect with audiences from around the world. However, hosting a successful virtual conference requires careful planning, innovative technology solutions, and engaging content. In this in-depth blog post, we\'ll explore the art of hosting a virtual conference and share best practices and insights to help you deliver an exceptional experience for participants.<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">The first step in hosting a virtual conference is to define your objectives and target audience. Consider the topics and themes you want to cover, as well as the goals you hope to achieve through the conference. Whether it\'s educating attendees, generating leads, or fostering community engagement, having a clear purpose will guide your planning process and help you create a program that resonates with participants.<\\/div><div><br \\/><\\/div><div>Once you\'ve defined your objectives, focus on selecting the right technology platform to host your virtual conference. Choose a platform that offers robust features for live streaming, interactive sessions, networking, and exhibitor booths. Additionally, consider factors such as user-friendliness, technical support, and scalability to ensure a seamless experience for both organizers and attendees.<\\/div><div><br \\/><\\/div><div>Another key aspect of hosting a successful virtual conference is creating compelling content that engages participants and provides value. Consider incorporating a mix of keynote presentations, panel discussions, breakout sessions, and interactive workshops to keep attendees engaged and excited throughout the event. Additionally, leverage technology tools such as polling, Q&A sessions, and virtual networking lounges to facilitate interaction and collaboration among participants.<\\/div><div><br \\/><\\/div><div>By following these best practices and leveraging the power of technology and creative content, you can host a virtual conference that delivers meaningful value to participants and helps you achieve your organizational goals.<\\/div>\",\"blog_image\":\"6612287181a061712466033.png\"}', NULL, 'basic', 'the-art-of-hosting-a-virtual-conference:-best-practices-and-insights', '2024-02-22 04:45:20', '2024-04-08 09:25:06'),
(73, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Unlocking the Power of Experiential Marketing Events: Strategies for Success\",\"short_description\":\"Learn how to harness the power of experiential marketing events to engage consumers, drive brand awareness, and foster brand loyalty.\",\"description\":\"<div>Experiential marketing events have become a cornerstone of modern marketing strategies, offering brands the opportunity to connect with consumers on a deeper level and create memorable experiences that leave a lasting impression. From immersive pop-up activations to interactive product launches, experiential marketing events have the power to engage audiences, drive brand awareness, and foster brand loyalty in ways that traditional advertising channels cannot. In this blog post, we\'ll explore the strategies and best practices for planning and executing successful experiential marketing events that resonate with consumers and deliver tangible results for your brand.<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">The key to successful experiential marketing events lies in creating immersive and memorable experiences that captivate and engage consumers on a sensory and emotional level. Whether it\'s through interactive installations, live performances, or hands-on demonstrations, the goal is to create moments that spark curiosity, evoke emotion, and leave a lasting impression on attendees. By tapping into the power of storytelling and emotion, brands can forge deeper connections with consumers and establish a strong emotional bond that transcends traditional advertising messages.<\\/div><div><br \\/><\\/div><div>Another important aspect of experiential marketing events is the element of surprise and novelty. In a crowded marketplace where consumers are bombarded with advertising messages from all sides, brands must find creative and innovative ways to stand out and capture attention. Whether it\'s through unexpected pop-up experiences, exclusive VIP events, or interactive digital installations, the element of surprise can create buzz and excitement around your brand, driving word-of-mouth promotion and social sharing.<\\/div><div><br \\/><\\/div><div>Additionally, experiential marketing events offer brands the opportunity to collect valuable data and insights about their target audience\'s preferences, behaviors, and purchase intentions. By leveraging technology tools such as RFID wristbands, social media tracking, and interactive surveys, brands can gather real-time feedback and analytics that inform future marketing strategies and campaign development.<\\/div><div><br \\/><\\/div><div>By following these strategies and best practices, brands can unlock the full potential of experiential marketing events to engage consumers, drive brand awareness, and foster long-term brand loyalty. Whether you\'re planning a product launch, a brand activation, or a customer appreciation event, experiential marketing offers endless possibilities for creativity, innovation, and connection.<\\/div>\",\"blog_image\":\"66122873b4b641712466035.png\"}', NULL, 'basic', 'unlocking-the-power-of-experiential-marketing-events:-strategies-for-success', '2024-02-22 04:45:39', '2024-04-08 09:25:33'),
(74, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"The Power of Team Building Events: Boosting Morale and Productivity\",\"short_description\":\"Discover how team building events can strengthen employee bonds, improve communication, and enhance workplace culture.\",\"description\":\"<div>Team building events have long been recognized as valuable tools for fostering collaboration, boosting morale, and enhancing productivity in the workplace. By bringing employees together in a fun and interactive setting, these events provide opportunities for team members to get to know each other better, build trust, and develop stronger working relationships. In this blog post, we\'ll explore the benefits of team building events and share tips for planning successful and impactful experiences for your organization.<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">One of the primary benefits of team building events is their ability to break down barriers and encourage open communication among team members. Whether it\'s through icebreaker activities, group challenges, or team-building exercises, these events create opportunities for employees to collaborate, problem-solve, and communicate effectively in a relaxed and informal setting. By fostering a sense of camaraderie and mutual respect, team building events can help to reduce conflicts, improve teamwork, and enhance overall workplace culture.<\\/div><div><br \\/><\\/div><div>Additionally, team building events can have a positive impact on employee morale and motivation. By providing employees with opportunities to celebrate achievements, recognize individual contributions, and have fun together outside of the office, these events can boost morale, increase job satisfaction, and strengthen employee engagement. Moreover, team building events can serve as powerful tools for employee retention, helping to foster a sense of loyalty and commitment among team members who feel valued and appreciated by their organization.<\\/div><div><br \\/><\\/div><div>When planning a team building event, it\'s important to consider the unique needs and preferences of your team members. Whether you opt for outdoor adventures, creative workshops, or community service projects, choose activities that align with your organization\'s values and objectives and cater to the interests and personalities of your employees. Additionally, be sure to incorporate opportunities for reflection and feedback to ensure that the event serves its intended purpose and delivers meaningful outcomes for your team.<\\/div><div><br \\/><\\/div><div>By investing in team building events, organizations can reap a wide range of benefits, from improved communication and collaboration to enhanced morale and productivity. Whether you\'re a small startup or a large corporation, incorporating team building events into your organizational culture can help to create a more cohesive and high-performing team that is better equipped to tackle challenges and achieve success together.<\\/div>\",\"blog_image\":\"66122907e6e411712466183.png\"}', NULL, 'basic', 'the-power-of-team-building-events:-boosting-morale-and-productivity', '2024-02-22 04:45:59', '2024-04-08 09:25:57'),
(75, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Navigating the World of Hybrid Events: Blending Virtual and In-Person Experiences\",\"short_description\":\"Learn how to plan and execute successful hybrid events that combine the best of both virtual and in-person experiences.\",\"description\":\"<div>With the rise of remote work and digital connectivity, hybrid events have emerged as a popular and effective way to engage audiences both online and offline. By blending virtual and in-person experiences, hybrid events offer organizations the flexibility to reach wider audiences, maximize attendance, and deliver content in innovative ways. In this blog post, we\'ll explore the key components of successful hybrid events and share tips for planning and executing seamless experiences that cater to both virtual and in-person attendees.<\\/div><div><br \\/><\\/div><div style=\\\"background:#efefef;padding:15px;border-left:5px solid;font-style:italic;font-weight:500;\\\">One of the most important aspects of planning a successful hybrid event is selecting the right technology platform to support your event objectives and audience needs. Whether you\'re hosting a virtual conference, a product launch, or a networking event, choose a platform that offers robust features for live streaming, interactive sessions, networking, and audience engagement. Additionally, consider factors such as user-friendliness, technical support, and scalability to ensure a smooth and seamless experience for both virtual and in-person attendees.<\\/div><div><br \\/><\\/div><div>Another critical component of hybrid event planning is designing an engaging and interactive program that caters to both virtual and in-person audiences. Whether it\'s through live presentations, panel discussions, breakout sessions, or networking opportunities, be sure to offer a variety of content formats and engagement opportunities to keep attendees engaged and excited throughout the event. Additionally, consider leveraging technology tools such as polling, Q&A sessions, and virtual networking lounges to facilitate interaction and collaboration among participants, regardless of their physical location.<\\/div><div><br \\/><\\/div><div>When it comes to logistics and event execution, be sure to plan for the unique needs and challenges of hybrid events, including technical requirements, AV setup, and onsite support. Whether you\'re hosting a hybrid event in a physical venue or from a remote location, be prepared to adapt and troubleshoot as needed to ensure a seamless and enjoyable experience for all attendees. Additionally, be sure to communicate clear instructions and expectations to both virtual and in-person attendees to ensure they know how to participate and engage with the event effectively.<\\/div><div><br \\/><\\/div><div>By following these tips and best practices, organizations can plan and execute successful hybrid events that offer the best of both virtual and in-person experiences. Whether you\'re looking to reach wider audiences, maximize attendance, or deliver content in innovative ways, hybrid events offer endless possibilities for creativity, engagement, and connection.<\\/div>\",\"blog_image\":\"6612287611a461712466038.png\"}', NULL, 'basic', 'navigating-the-world-of-hybrid-events:-blending-virtual-and-in-person-experiences', '2024-02-22 04:46:17', '2024-04-08 09:26:25'),
(76, 'counter.element', '{\"has_image\":\"1\",\"title\":\"Events added\",\"value\":\"120K\",\"image\":\"6626774e64bf61713796942.png\"}', NULL, 'basic', NULL, '2024-02-28 15:23:13', '2024-04-22 08:42:22'),
(77, 'counter.element', '{\"has_image\":\"1\",\"title\":\"Event Organizers\",\"value\":\"30K+\",\"image\":\"66267886434e51713797254.png\"}', NULL, 'basic', NULL, '2024-02-28 15:23:33', '2024-04-22 08:47:34'),
(78, 'counter.element', '{\"has_image\":\"1\",\"title\":\"Event Hosted\",\"value\":\"3K\",\"image\":\"662677da0d1151713797082.png\"}', NULL, 'basic', NULL, '2024-02-28 15:23:48', '2024-04-22 08:44:42'),
(79, 'counter.element', '{\"has_image\":\"1\",\"title\":\"Tickets Sold\",\"value\":\"11M\",\"image\":\"66267816db70c1713797142.png\"}', NULL, 'basic', NULL, '2024-02-28 15:24:03', '2024-04-22 08:45:42'),
(80, 'login_registration.content', '{\"has_image\":\"1\",\"image\":\"66278cb02ff361713867952.png\"}', NULL, 'basic', NULL, '2024-02-29 08:57:51', '2024-04-23 04:25:53'),
(82, 'popular_location.content', '{\"title\":\"POPULAR LOCATION\",\"heading\":\"Explore By Cities\"}', NULL, 'basic', NULL, '2024-03-01 12:28:28', '2024-04-22 05:03:28'),
(83, 'featured_events.content', '{\"title\":\"FEATURED\",\"heading\":\"Featured Events\"}', NULL, 'basic', NULL, '2024-03-01 12:28:50', '2024-04-22 05:03:53'),
(84, 'featured_organizers.content', '{\"title\":\"FEATURED\",\"heading\":\"Featured Organizers\"}', NULL, 'basic', NULL, '2024-03-01 12:29:17', '2024-04-22 05:06:02'),
(85, 'subscribe.content', '{\"title\":\"Subscribe\",\"description\":\"Be the first to know about upcoming events, exclusive discounts, and insider tips! Subscribe to our newsletter now and stay connected with us.\"}', NULL, 'basic', NULL, '2024-03-05 07:05:45', '2024-03-05 07:09:55'),
(86, 'kyc_instruction.content', '{\"verification_instruction\":\"Please submit the required KYC information to verify yourself. Otherwise, you couldn\'t make any withdrawal requests to the system.\",\"pending_instruction\":\"Your submitted KYC information is pending for admin approval. Please wait till that approved their verification to see your submitted information.\"}', NULL, 'basic', NULL, '2024-03-18 00:38:20', '2024-03-18 00:38:20'),
(87, 'starting_soon_events.content', '{\"title\":\"STARTING SOON\",\"heading\":\"Starting Soon Events\"}', NULL, 'basic', NULL, '2024-04-01 00:31:46', '2024-04-22 05:05:17'),
(88, 'register_disable.content', '{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Page you are looking for doesn\'t exit or an other error occurred or temporarily unavailable.\",\"button_name\":\"Go to Home\",\"button_url\":\"#\",\"image\":\"663a0f20ecd0b1715080992.png\"}', NULL, 'basic', NULL, '2024-05-07 05:23:12', '2024-05-07 05:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` int NOT NULL DEFAULT '0',
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `image`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal', 'Paypal', '663a38d7b455d1715091671.png', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-owud61543012@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:04:38'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', '663a3920e30a31715091744.png', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 01:35:33'),
(3, 0, 103, 'Stripe Hosted', 'Stripe', '663a39861cb9d1715091846.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:48:36'),
(4, 0, 104, 'Skrill', 'Skrill', '663a39494c4a91715091785.png', 1, '{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 01:30:16'),
(5, 0, 105, 'PayTM', 'Paytm', '663a390f601191715091727.png', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 03:00:44'),
(6, 0, 106, 'Payeer', 'Payeer', '663a38c9e2e931715091657.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 13:14:22', '2022-08-28 10:11:14'),
(7, 0, 107, 'PayStack', 'Paystack', '663a38fc814e91715091708.png', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_cd330608eb47970889bca397ced55c1dd5ad3783\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_8a0b1f199362d7acc9c390bff72c4e81f74e2ac3\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 13:14:22', '2021-05-21 01:49:51'),
(8, 0, 108, 'VoguePay', 'Voguepay', NULL, 1, '{\"merchant_id\":{\"title\":\"MERCHANT ID\",\"global\":true,\"value\":\"demo\"}}', '{\"USD\":\"USD\",\"GBP\":\"GBP\",\"EUR\":\"EUR\",\"GHS\":\"GHS\",\"NGN\":\"NGN\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 01:22:38'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', '663a36c2c34d61715091138.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-06-05 11:37:45'),
(10, 0, 110, 'RazorPay', 'Razorpay', '663a393a527831715091770.png', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:51:32'),
(11, 0, 111, 'Stripe Storefront', 'StripeJs', '663a3995417171715091861.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:53:10'),
(12, 0, 112, 'Instamojo', 'Instamojo', '663a384d54a111715091533.png', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:56:20'),
(13, 0, 501, 'Blockchain', 'Blockchain', '663a35efd0c311715090927.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}', '{\"BTC\":\"BTC\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2022-03-21 07:41:56'),
(15, 0, 503, 'CoinPayments', 'Coinpayments', '663a36a8d8e1d1715091112.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2023-04-08 03:17:18'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', '663a36b7b841a1715091127.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:07:44'),
(17, 0, 505, 'Coingate', 'Coingate', '663a368e753381715091086.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2022-03-30 09:24:57'),
(18, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', '663a367e46ae51715091070.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 13:14:22', '2021-05-21 02:02:47'),
(24, 0, 113, 'Paypal Express', 'PaypalSdk', '663a38ed101a61715091693.png', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-20 23:01:08'),
(25, 0, 114, 'Stripe Checkout', 'StripeV3', '663a39afb519f1715091887.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 13:14:22', '2021-05-21 00:58:38'),
(27, 0, 115, 'Mollie', 'Mollie', '663a387ec69371715091582.png', 1, '{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"vi@gmail.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:44:45'),
(30, 0, 116, 'Cashmaal', 'Cashmaal', '663a361b16bd11715090971.png', 1, '{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}', '{\"PKR\":\"PKR\",\"USD\":\"USD\"}', 0, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}', NULL, NULL, '2021-06-22 08:05:04'),
(36, 0, 119, 'Mercado Pago', 'MercadoPago', '663a386c714a91715091564.png', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2022-09-14 07:41:14'),
(37, 0, 120, 'Authorize.net', 'Authorize', '663a35b9ca5991715090873.png', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-04-08 04:17:47'),
(46, 0, 121, 'NMI', 'NMI', '663a3897754cf1715091607.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}', '{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, NULL, '2022-08-28 10:32:31'),
(50, 0, 507, 'BTCPay', 'BTCPay', '663a35cd25a8d1715090893.png', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"HsqFVTXSeUFJu7caoYZc3CTnP8g5LErVdHhEXPVTheHf\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"4436bd706f99efae69305e7c4eff4780de1335ce\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2023-02-14 04:42:09'),
(51, 0, 508, 'Now payments hosted', 'NowPaymentsHosted', '663a38b8d57a81715091640.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2023-02-14 05:08:23'),
(52, 0, 509, 'Now payments checkout', 'NowPaymentsCheckout', '663a38a59d2541715091621.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"---------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2023-02-14 05:08:04'),
(53, 0, 122, '2Checkout', 'TwoCheckout', '663a39b8e64b91715091896.png', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2023-04-29 09:21:58'),
(54, 0, 123, 'Checkout', 'Checkout', '663a3628733351715090984.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(56, 0, 510, 'Binance', 'Binance', '663a35db4fd621715090907.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2023-02-14 05:08:04'),
(57, 0, 124, 'SslCommerz', 'SslCommerz', '663a397a70c571715091834.png', 1, '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"store_password\": {\"title\": \"Store Password\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(58, 0, 125, 'Aamarpay', 'Aamarpay', '663a34d5d1dfc1715090645.png', 1, '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"signature_key\": {\"title\": \"Signature Key\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(59, 6, 1000, 'Manual Gateway', 'manual_gateway', '665488bdc895a1716816061.png', 1, '[]', '[]', 0, NULL, '1232', '2024-05-27 07:21:01', '2024-05-27 07:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int DEFAULT NULL,
  `gateway_alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_time` int DEFAULT NULL,
  `payment_timeout` int DEFAULT NULL,
  `event_verification` tinyint(1) NOT NULL DEFAULT '0',
  `cur_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text COLLATE utf8mb4_unicode_ci,
  `firebase_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `multi_language` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `organizer_registration` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `active_template` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite_credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `system_info` text COLLATE utf8mb4_unicode_ci,
  `last_cron` datetime DEFAULT NULL,
  `available_version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_customized` tinyint(1) NOT NULL DEFAULT '0',
  `paginate_number` int NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Both, 2=>Text Only, 3=>Symbol Only',
  `max_gallery_images` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `cancel_time`, `payment_timeout`, `event_verification`, `cur_text`, `cur_sym`, `email_from`, `email_from_name`, `email_template`, `sms_template`, `sms_body`, `sms_from`, `push_title`, `push_template`, `base_color`, `secondary_color`, `mail_config`, `sms_config`, `firebase_config`, `global_shortcodes`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `organizer_registration`, `active_template`, `socialite_credentials`, `system_info`, `last_cron`, `available_version`, `system_customized`, `paginate_number`, `currency_format`, `max_gallery_images`, `created_at`, `updated_at`) VALUES
(1, 'Event Lab', 4, 1, 1, 'USD', '$', 'info@viserlab.com', NULL, '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n  <!--[if !mso]><!-->\n  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n  <!--<![endif]-->\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n  <title></title>\n  <style type=\"text/css\">\n.ReadMsgBody { width: 100%; background-color: #ffffff; }\n.ExternalClass { width: 100%; background-color: #ffffff; }\n.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }\nhtml { width: 100%; }\nbody { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }\ntable { border-spacing: 0; table-layout: fixed; margin: 0 auto;border-collapse: collapse; }\ntable table table { table-layout: auto; }\n.yshortcuts a { border-bottom: none !important; }\nimg:hover { opacity: 0.9 !important; }\na { color: #0087ff; text-decoration: none; }\n.textbutton a { font-family: \'open sans\', arial, sans-serif !important;}\n.btn-link a { color:#FFFFFF !important;}\n\n@media only screen and (max-width: 480px) {\nbody { width: auto !important; }\n*[class=\"table-inner\"] { width: 90% !important; text-align: center !important; }\n*[class=\"table-full\"] { width: 100% !important; text-align: center !important; }\n/* image */\nimg[class=\"img1\"] { width: 100% !important; height: auto !important; }\n}\n</style>\n\n\n\n  <table bgcolor=\"#414a51\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tbody><tr>\n      <td height=\"50\"></td>\n    </tr>\n    <tr>\n      <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n        <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n          <tbody><tr>\n            <td align=\"center\" width=\"600\">\n              <!--header-->\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody><tr>\n                  <td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\n                    <table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                      <tbody><tr>\n                        <td height=\"20\"></td>\n                      </tr>\n                      <tr>\n                        <td align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">This is a System Generated Email</td>\n                      </tr>\n                      <tr>\n                        <td height=\"20\"></td>\n                      </tr>\n                    </tbody></table>\n                  </td>\n                </tr>\n              </tbody></table>\n              <!--end header-->\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n                <tbody><tr>\n                  <td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n                      <tbody><tr>\n                        <td height=\"35\"></td>\n                      </tr>\n                      <!--logo-->\n                      <tr>\n                        <td align=\"center\" style=\"vertical-align:top;font-size:0;\">\n                          <a href=\"#\">\n                            <img style=\"display:block; line-height:0px; font-size:0px; border:0px;\" src=\"https://i.imgur.com/Z1qtvtV.png\" alt=\"img\">\n                          </a>\n                        </td>\n                      </tr>\n                      <!--end logo-->\n                      <tr>\n                        <td height=\"40\"></td>\n                      </tr>\n                      <!--headline-->\n                      <tr>\n                        <td align=\"center\" style=\"font-family: \'Open Sans\', Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">Hello {{fullname}} ({{username}})</td>\n                      </tr>\n                      <!--end headline-->\n                      <tr>\n                        <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n                          <table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                            <tbody><tr>\n                              <td height=\"20\" style=\" border-bottom:3px solid #0087ff;\"></td>\n                            </tr>\n                          </tbody></table>\n                        </td>\n                      </tr>\n                      <tr>\n                        <td height=\"20\"></td>\n                      </tr>\n                      <!--content-->\n                      <tr>\n                        <td align=\"left\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">{{message}}</td>\n                      </tr>\n                      <!--end content-->\n                      <tr>\n                        <td height=\"40\"></td>\n                      </tr>\n              \n                    </tbody></table>\n                  </td>\n                </tr>\n                <tr>\n                  <td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n                      <tbody><tr>\n                        <td height=\"10\"></td>\n                      </tr>\n                      <!--preference-->\n                      <tr>\n                        <td class=\"preference-link\" align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#95a5a6; font-size:14px;\">\n                          © 2024 <a href=\"#\">{{site_name}}</a>&nbsp;. All Rights Reserved. \n                        </td>\n                      </tr>\n                      <!--end preference-->\n                      <tr>\n                        <td height=\"10\"></td>\n                      </tr>\n                    </tbody></table>\n                  </td>\n                </tr>\n              </tbody></table>\n            </td>\n          </tr>\n        </tbody></table>\n      </td>\n    </tr>\n    <tr>\n      <td height=\"60\"></td>\n    </tr>\n  </tbody></table>', NULL, 'hi {{fullname}} ({{username}}), {{message}}', 'ViserAdmin', NULL, NULL, '0062ff', '060662', '{\"name\":\"php\"}', '{\"name\":\"nexmo\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}', NULL, '{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}', 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 1, 'basic', '{\"google\":{\"client_id\":\"------------\",\"client_secret\":\"-------------\",\"status\":1},\"facebook\":{\"client_id\":\"------\",\"client_secret\":\"------\",\"status\":1},\"linkedin\":{\"client_id\":\"-----\",\"client_secret\":\"-----\",\"status\":1}}', '[]', '2024-05-29 07:57:30', '0', 0, 20, 1, 20, NULL, '2024-05-29 01:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `image`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, '665467ff20fb61716807679.png', '2024-04-08 07:35:25', '2024-05-27 05:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `organizer_id` int NOT NULL DEFAULT '0',
  `sender` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_read` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci,
  `sms_body` text COLLATE utf8mb4_unicode_ci,
  `push_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', NULL, '<div>\n<div>{{amount}} {{site_currency}} has been added to your account .</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<span>Your Current Balance is : </span><span><span>{{post_balance}}  {{site_currency}} </span></span></div>\n<div><span><span> </span></span></div>\n<div>Admin note: <span>{{remark}}</span></div>', '{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"', '{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:04:28'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', NULL, '<div>{{amount}} {{site_currency}} has been subtracted from your account .</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<p><span>Your Current Balance is : </span><span><span>{{post_balance}}  {{site_currency}}</span></span></p>\n<div><span><span> </span></span></div>\n<div>Admin Note: {{remark}}</div>', '{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}', '{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:04:35'),
(3, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Completed Successfully', NULL, '<div>Your deposit of <span>{{amount}} {{site_currency}}</span> is via  <span>{{method_name}} </span>has been completed Successfully.<span><br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your Deposit :<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>Received : {{method_amount}} {{method_currency}}</div>\n<div>Paid via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div><span><span> </span></span></div>\n<div><span>Your current Balance is <span>{{post_balance}} {{site_currency}}</span></span></div>\n<div> </div>', '{{amount}} {{site_currency}} Deposit successfully by {{method_name}}', '{{amount}} {{site_currency}} Deposit successfully by {{method_name}}', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:04:55'),
(4, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Your Deposit is Approved', NULL, '<div>Your deposit request of <span>{{amount}} {{site_currency}}</span> is via  <span>{{method_name}} </span>is Approved .<span><br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your Deposit :<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>Received : {{method_amount}} {{method_currency}}</div>\n<div>Paid via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div><span><span> </span></span></div>\n<div><span>Your current Balance is <span>{{post_balance}} {{site_currency}}</span></span></div>\n<div> </div>\n<div> </div>', 'Admin Approve Your {{amount}} {{site_currency}} payment request by {{method_name}} transaction : {{trx}}', 'Admin Approve Your {{amount}} {{site_currency}} payment request by {{method_name}} transaction : {{trx}}', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:05:09'),
(5, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Your Deposit Request is Rejected', NULL, '<div>Your deposit request of <span>{{amount}} {{site_currency}}</span> is via  <span>{{method_name}} has been rejected</span>.<span><br /></span></div>\n<div> </div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>Received : {{method_amount}} {{method_currency}}</div>\n<div>Paid via :  {{method_name}}</div>\n<div>Charge: {{charge}}</div>\n<div> </div>\n<div> </div>\n<div>Transaction Number was : {{trx}}</div>\n<div> </div>\n<div>if you have any queries, feel free to contact us.</div>\n<p> </p>\n<div><br /><br /></div>\n<p><span>{{rejection_message}}</span></p>', 'Admin Rejected Your {{amount}} {{site_currency}} payment request by {{method_name}}\r\n\r\n{{rejection_message}}', 'Admin Rejected Your {{amount}} {{site_currency}} payment request by {{method_name}}\r\n\r\n{{rejection_message}}', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:05:29'),
(6, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Submitted Successfully', NULL, '<div>Your deposit request of <span>{{amount}} {{site_currency}}</span> is via  <span>{{method_name}} </span>submitted successfully<span> .<br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your Deposit :<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>Payable : {{method_amount}} {{method_currency}}</div>\n<div>Pay via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<div> </div>', '{{amount}} {{site_currency}} Deposit requested by {{method_name}}. Charge: {{charge}} . Trx: {{trx}}', '{{amount}} {{site_currency}} Deposit requested by {{method_name}}. Charge: {{charge}} . Trx: {{trx}}', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:05:37'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', NULL, '<div>We have received a request to reset the password for your account on <span>{{time}} .<br /></span></div>\n<div>Requested From IP: <span>{{ip}}</span> using <span>{{browser}}</span> on <span>{{operating_system}} </span>.</div>\n<div> </div>\n<p> </p>\n<div>\n<div>Your account recovery code is:   <span><span>{{code}}</span></span></div>\n<div> </div>\n</div>\n<div> </div>\n<div><span>If you do not wish to reset your password, please disregard this message. </span></div>\n<div><span> </span></div>', 'Your account recovery code is: {{code}}', 'Your account recovery code is: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:41'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'You have reset your password', NULL, '<p>You have successfully reset your password.</p>\n<p>You changed from  IP: <span>{{ip}}</span> using <span>{{browser}}</span> on <span>{{operating_system}} </span> on <span>{{time}}</span></p>\n<p><span> </span></p>\n<p><span><span>If you did not change that, please contact us as soon as possible.</span></span></p>', 'Your password has been changed successfully', 'Your password has been changed successfully', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:49'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Reply Support Ticket', NULL, '<div>\n<p><span><span>A member from our support team has replied to the following ticket:</span></span></p>\n<p><span><span><span> </span></span></span></p>\n<p><span>[Ticket#{{ticket_id}}] {{ticket_subject}}<br /><br />Click here to reply:  {{link}}</span></p>\n<p>----------------------------------------------</p>\n<p>Here is the reply :</p>\n<p>{{reply}}</p>\n</div>\n<div> </div>\n#BBD0E0\n»', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:57'),
(10, 'EVER_CODE', 'Verification - Email', 'Please verify your email address', NULL, '<p> </p>\n<div>\n<div>Thanks For joining us.</div>\n<div>Please use the below code to verify your email address.</div>\n<div> </div>\n<div>Your email verification code is:<span><span> {{code}}</span></span></div>\n</div>', '---', NULL, '{\"code\":\"Email verification code\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2022-04-03 02:32:07'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your phone verification code is: {{code}}', NULL, '{\"code\":\"SMS Verification Code\"}', 0, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2022-03-20 19:24:37'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdraw Request has been Processed and your money is sent', NULL, '<div>Your withdraw request of <span>{{amount}} {{site_currency}}</span>  via  <span>{{method_name}} </span>has been Processed Successfully.<span><br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your withdraw:<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>You will get: {{method_amount}} {{method_currency}}</div>\n<div>Via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<div>-----</div>\n<div> </div>\n<div><span>Details of Processed Payment :</span></div>\n<div><span><span>{{admin_details}}</span></span></div>', 'Admin Approve Your {{amount}} {{site_currency}} withdraw request by {{method_name}}. Transaction {{trx}}', 'Admin Approve Your {{amount}} {{site_currency}} withdraw request by {{method_name}}. Transaction {{trx}}', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:08:37'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdraw Request has been Rejected and your money is refunded to your account', NULL, '<div>Your withdraw request of <span>{{amount}} {{site_currency}}</span>  via  <span>{{method_name}} </span>has been Rejected.<span><br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your withdraw:<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>You should get: {{method_amount}} {{method_currency}}</div>\n<div>Via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<div> </div>\n<div>----</div>\n<div><span> </span></div>\n<div><span>{{amount}} {{currency}} has been <span>refunded </span>to your account and your current Balance is <span>{{post_balance}}</span><span> {{site_currency}}</span></span></div>\n<div> </div>\n<div>-----</div>\n<div> </div>\n<div><span>Details of Rejection :</span></div>\n<div><span><span>{{admin_details}}</span></span></div>\n<div> </div>\n<div><br /><br /><br /><br /><br /></div>\n<div> </div>\n<div> </div>\n#BBD0E0\n»', 'Admin Rejected Your {{amount}} {{site_currency}} withdraw request. Your Main Balance {{post_balance}}  {{method_name}} , Transaction {{trx}}', 'Admin Rejected Your {{amount}} {{site_currency}} withdraw request. Your Main Balance {{post_balance}}  {{method_name}} , Transaction {{trx}}', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:09:02'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdraw Request Submitted Successfully', NULL, '<div>Your withdraw request of <span>{{amount}} {{site_currency}}</span>  via  <span>{{method_name}} </span>has been submitted Successfully.<span><br /></span></div>\n<div><span> </span></div>\n<div><span>Details of your withdraw:<br /></span></div>\n<div> </div>\n<div>Amount : {{amount}} {{site_currency}}</div>\n<div>Charge: <span>{{charge}} {{site_currency}}</span></div>\n<div> </div>\n<div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div>\n<div>You will get: {{method_amount}} {{method_currency}}</div>\n<div>Via :  {{method_name}}</div>\n<div> </div>\n<div>Transaction Number : {{trx}}</div>\n<div> </div>\n<div> </div>\n<div><span>Your current Balance is <span>{{post_balance}} {{site_currency}}</span></span></div>\n<div> </div>\n<div><br /><br /><br /></div>', '{{amount}} {{site_currency}} withdraw requested by {{method_name}}. You will get {{method_amount}} {{method_currency}} Trx: {{trx}}', '{{amount}} {{site_currency}} withdraw requested by {{method_name}}. You will get {{method_amount}} {{method_currency}} Trx: {{trx}}', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:09:10'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', NULL, '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, NULL, NULL, 1, NULL, 0, '2019-09-14 13:14:22', '2024-05-29 02:04:43'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC has been approved', NULL, NULL, 'Your Kyc is Approved\r\n\r\nRegards,\r\n{{site_name}}', 'Your Kyc is Approved\r\n\r\nRegards,\r\n{{site_name}}', '[]', 1, NULL, NULL, 1, NULL, 0, NULL, '2024-05-29 02:06:47'),
(17, 'KYC_REJECT', 'KYC Rejected', 'KYC has been rejected', NULL, NULL, 'Your Kyc is Rejected\r\n\r\nRegards,\r\n{{site_name}}', 'Your Kyc is Rejected\r\n\r\nRegards,\r\n{{site_name}}', '{\"reason\":\"Rejection Reason\"}', 1, NULL, NULL, 1, NULL, 0, NULL, '2024-05-29 02:07:00'),
(18, 'ORDER_CONFIRMED', 'Order - Confirmed', 'Your Order is confirmed', NULL, '<h4>Your order is &nbsp;confirmed.</h4>\r\n<div>&nbsp; <br> <strong>Ticket Details:</strong>\r\n<div>\r\n<div>Transaction ID: {{trx}}</div>\r\n<div>Order number: {{order_number}}</div>\r\n<div>Event name: {{event_name}}</div>\r\n<div>Start date: {{start_date}}</div>\r\n<div>End date: {{end_date}}</div>\r\n<div>Price: {{currency_symbol}}{{price}}</div>\r\n<div>Quantity: {{quantity}}</div>\r\n<div>Total price: {{currency_symbol}}{{total_price}}</div>\r\n<div>Ticket link:&nbsp;{{ticket_url}}</div>\r\n<div>&nbsp;</div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>', 'We have received your payment, your order is now confirmed. Ticket Link: {{ticket_url}}', 'We have received your payment, your order is now confirmed. Ticket Link: {{ticket_url}}', '{\n    \"trx\": \"Transaction number for the action\",\n    \"order_number\": \"Order Serial Number\",\n    \"event_name\": \"Name of event\",\n    \"start_date\": \"Start date of event\",\n    \"end_date\": \"End date of event\",\n    \"price\": \"Price of single tickets\",\n    \"quantity\": \"Quantity of tickets\",\n    \"total_price\": \"Total Price of order\",\n    \"ticket_url\": \"Link of ticket\"\n  }', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:15'),
(19, 'ORDER_INITIATE', 'Order - Initiate', 'Order is Initiated', NULL, '<h4>Your order is Initiated, please pay within the time limit.</h4>\r\n<div>&nbsp; <br> <strong>Ticket Details:</strong><div>\r\n<div>Order number: {{order_number}}</div>\r\n<div>Event name: {{event_name}}</div>\r\n<div>Start date: {{start_date}}</div>\r\n<div>End date: {{end_date}}</div>\r\n<div>Price: {{currency_symbol}}{{price}}</div>\r\n<div>Quantity: {{quantity}}</div>\r\n<div>Total price: {{currency_symbol}}{{total_price}}</div>\r\n<div>Ticket link:&nbsp;{{ticket_url}}</div>\r\n<div>&nbsp;</div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>', 'Your order is Initiated, please pay within the time limit. Ticket Link: {{ticket_url}}', 'Your order is Initiated, please pay within the time limit. Ticket Link: {{ticket_url}}', '{\n    \"order_number\": \"Order Serial Number\",\n    \"event_name\": \"Name of event\",\n    \"start_date\": \"Start date of event\",\n    \"end_date\": \"End date of event\",\n    \"price\": \"Price of single tickets\",\n    \"quantity\": \"Quantity of tickets\",\n    \"total_price\": \"Total Price of order\",\n    \"ticket_url\": \"Link of ticket\"\n  }', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:23'),
(20, 'ORDER_CANCELLED', 'Order - Cancelled', 'Order is Cancelled', NULL, '<h4>Your order is cancelled.</h4>\r\n<div>&nbsp; <br> <strong>Ticket Details:</strong>\r\n<div>\r\n<div>Transaction ID: {{trx}}</div>\r\n<div>Order number: {{order_number}}</div>\r\n<div>Event name: {{event_name}}</div>\r\n<div>Start date: {{start_date}}</div>\r\n<div>End date: {{end_date}}</div>\r\n<div>Price: {{currency_symbol}}{{price}}</div>\r\n<div>Quantity: {{quantity}}</div>\r\n<div>Total price: {{currency_symbol}}{{total_price}}</div>\r\n<div>Ticket link:&nbsp;{{ticket_url}}</div>\r\n<div>&nbsp;</div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>', 'Your order is cancelled. Ticket Link: {{ticket_url}}', 'Your order is cancelled. Ticket Link: {{ticket_url}}', '{\r\n    \"trx\": \"Transaction number for the action\",\r\n    \"order_number\": \"Order Serial Number\",\r\n    \"event_name\": \"Name of event\",\r\n    \"start_date\": \"Start date of event\",\r\n    \"end_date\": \"End date of event\",\r\n    \"price\": \"Price of single tickets\",\r\n    \"quantity\": \"Quantity of tickets\",\r\n    \"total_price\": \"Total Price of order\",\r\n    \"ticket_url\": \"Link of ticket\"\r\n  }', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:07:08'),
(21, 'EVENT_VERIFYING', 'Event - Verifying', 'Event under verification', NULL, '<h6>Your event is under verification by admin.</h6>\r\n<div>&nbsp;</div>\r\n<div><strong>Event Details:</strong>\r\n<div>\r\n<div>Event ID: {{event_id}}</div>\r\n<div>Event name: {{event_name}}</div>\r\n<div>Event link: {{event_url}}<span style=\"color: var(--bs-body-color); font-size: 1rem; text-align: var(--bs-body-text-align);\">&nbsp;</span></div>\r\n<div>&nbsp;</div>\r\n<div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n</div>\r\n</div>', 'Your event is under verification by admin. Event Link: {{event_url}}', 'Your event is under verification by admin. Event Link: {{event_url}}', '{\r\n    \"event_id\": \"ID of Event\",\r\n    \"event_name\": \"Name of event\",\r\n    \"event_url\": \"Link of event\"\r\n}\r\n', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:06:09'),
(22, 'EVENT_VERIFIED', 'Event - Verified', 'Event is verified', NULL, '<h6>Your event is verified by admin.</h6>\r\n<div>&nbsp; <br><strong>Event Details:</strong>\r\n<div>\r\n<div><span>Event ID: {{event_id}}</span></div>\r\n<div><span>Event name: {{event_name}}</span></div>\r\n<div><span>Event link:&nbsp;</span><span><span>{{event_url}}</span></span></div>\r\n<div><span> <br> </span></div>\r\n<div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>', 'Your event is verified by admin. Event Link: {{event_url}}', 'Your event is verified by admin. Event Link: {{event_url}}', '{\r\n    \"event_id\": \"ID of Event\",\r\n    \"event_name\": \"Name of event\",\r\n    \"event_url\": \"Link of event\"\r\n}\r\n', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:05:52'),
(23, 'EVENT_REJECTED', 'Event - Rejected', 'Event is rejected', NULL, '<p></p><h6>Your event is rejected by admin. </h6> <br> <strong>Event Details:</strong><p></p>\r\n<div>\r\n<div><span>Event ID: {{event_id}}</span></div>\r\n<div><span>Event name: {{event_name}}</span></div>\r\n<div><span>Event link:&nbsp;</span><span><span>{{event_url}}</span></span></div>\r\n<div><span> <br> </span></div>\r\n<div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>', 'Your event is rejected by admin. Event Link: {{event_url}}', 'Your event is rejected by admin. Event Link: {{event_url}}', '{\r\n    \"event_id\": \"ID of Event\",\r\n    \"event_name\": \"Name of event\",\r\n    \"event_url\": \"Link of event\"\r\n}\r\n', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:05:44'),
(24, 'ORDER_RECEIVED', 'Order - Received', 'Order Received', NULL, '<h4>You have a new ticket order.</h4>\r\n<div>&nbsp; <br> <strong>Ticket Details:</strong>\r\n<div>\r\n<div>Transaction ID: {{trx}}</div>\r\n<div>Order number: {{order_number}}</div>\r\n<div>Event name: {{event_name}}</div>\r\n<div>Start date: {{start_date}}</div>\r\n<div>End date: {{end_date}}</div>\r\n<div>Price: {{currency_symbol}}{{price}}</div>\r\n<div>Quantity: {{quantity}}</div>\r\n<div>Total price: {{currency_symbol}}{{total_price}}</div>\r\n<div>Ticket link:&nbsp;{{ticket_url}}</div>\r\n<div>&nbsp;</div>\r\n<div>Thank you,</div>\r\n<div>{{site_name}}</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>', 'You have a new ticket order. Ticket Link: {{ticket_url}}', 'You have a new ticket order. Ticket Link: {{ticket_url}}', '{\n    \"trx\": \"Transaction number for the action\",\n    \"order_number\": \"Order Serial Number\",\n    \"event_name\": \"Name of event\",\n    \"start_date\": \"Start date of event\",\n    \"end_date\": \"End date of event\",\n    \"price\": \"Price of single tickets\",\n    \"quantity\": \"Quantity of tickets\",\n    \"total_price\": \"Total Price of order\",\n    \"ticket_url\": \"Link of ticket\"\n  }', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-29 02:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` int NOT NULL DEFAULT '0',
  `user_id` int DEFAULT '0',
  `price` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  `payment_status` tinyint NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizers`
--

CREATE TABLE `organizers` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kyc_rejection_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'contains full address',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizer_password_resets`
--

CREATE TABLE `organizer_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text COLLATE utf8mb4_unicode_ci,
  `seo_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `seo_content`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'HOME', '/', 'templates.basic.', '[\"category\",\"popular_location\",\"featured_events\",\"how_it_works\",\"starting_soon_events\",\"featured_organizers\",\"blog\",\"testimonials\",\"cta\",\"counter\"]', NULL, 1, '2020-07-11 06:23:58', '2024-04-22 04:54:38'),
(4, 'Blog', 'blog', 'templates.basic.', '[\"cta\",\"counter\"]', NULL, 1, '2020-10-22 01:14:43', '2024-04-23 01:16:38'),
(5, 'Contact', 'contact', 'templates.basic.', '[\"faq\"]', NULL, 1, '2020-10-22 01:14:53', '2024-04-22 04:50:04'),
(19, 'About', 'about', 'templates.basic.', '[\"about_us\",\"how_it_works\",\"choose_us\",\"cta\",\"counter\"]', NULL, 0, '2024-02-13 02:32:54', '2024-04-23 00:17:53'),
(22, 'Organizers', 'organizers', 'templates.basic.', '[\"starting_soon_events\",\"featured_events\",\"cta\",\"counter\"]', NULL, 1, '2024-04-22 07:45:07', '2024-04-22 07:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` bigint UNSIGNED NOT NULL,
  `time_slot_id` int NOT NULL DEFAULT '0',
  `start_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speakers`
--

CREATE TABLE `speakers` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` int NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `support_message_id` int UNSIGNED NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` int UNSIGNED NOT NULL DEFAULT '0',
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT '0',
  `organizer_id` int NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` int NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `organizer_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_logs`
--

CREATE TABLE `update_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `update_logs`
--

INSERT INTO `update_logs` (`id`, `version`, `update_log`, `created_at`, `updated_at`) VALUES
(1, '2.0', '[\"[ADD] Push Notification\",\r\n\"[ADD] Social Login\",\r\n\"[ADD] Binance Payment Gateway\",\r\n\"[ADD] Aamarpay Payment Gateway\",\r\n\"[ADD] SslCommerz Payment Gateway\",\r\n\"[ADD] Slug Management for Blogs\",\r\n\"[ADD] SEO Content Management for Blog\",\r\n\"[ADD] Slug Management for Policy Pages\",\r\n\"[ADD] SEO Content Management for Policy Pages\",\r\n\"[ADD] Input type number, url, date, and time in the Form Generator\",\r\n\"[ADD] Configurable Input Filed Width in the Form Generator\",\r\n\"[ADD] Configurable Hints/Instructions for Input Fields in the Form Generator\",\r\n\"[ADD] Sorting Option for Input Fields in the Form Generator\",\r\n\"[ADD] Controllable Login System with Google, Facebook, Linkedin\",\r\n\"[ADD] Automatic System Update\",\r\n\"[ADD] Image on Deposit And Withdraw Method\",\r\n\"[ADD] Configurable Number of Items Per Page for Pagination\",\r\n\"[ADD] Configurable Currency Display Format\",\r\n\"[ADD] Redirecting to Intended Location When Required\",\r\n\"[ADD] Resend Code Countdown on Verification Pages\",\r\n\"[ADD] Cron Jobs\",\r\n\"[ADD] Cron Schedule\",\r\n\"[ADD] Cron Job Logs\",\r\n\"[ADD] Cron job Instruction UI\",\r\n\"[UPDATE] Admin Dashboard Widget Design\",\r\n\"[UPDATE] Notification Sending Process\",\r\n\"[UPDATE] User Experience of the Admin Sidebar\",\r\n\"[UPDATE] Improved Menu Searching Functionality on the Admin Panel\",\r\n\"[UPDATE] User Experience of the Select Fields of the Admin Panel\",\r\n\"[UPDATE] Centralized Settings System\",\r\n\"[UPDATE] Form Generator UI on the Admin Panel\",\r\n\"[UPDATE] Google Analytics Script\",\r\n\"[UPDATE] Notification Toaster UI\",\r\n\"[UPDATE] Support Ticket Attachment Upload UI\",\r\n\"[UPDATE] Notification Template Content Configuration\",\r\n\"[UPDATE] Configurable Email From Name and Address for Each Template\",\r\n\"[UPDATE] Configurable SMS From for Each Template\",\r\n\"[UPDATE] Overall User Interface of the Admin Panel\",\r\n\"[PATCH] Laravel 11\",\r\n\"[PATCH] PHP 8.3\",\r\n\"[PATCH] Latest System Patch\",\r\n\"[PATCH] Latest Security Patch\"]', '2024-05-26 15:55:52', '2024-05-26 15:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_event`
--

CREATE TABLE `user_event` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `event_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer_id` int UNSIGNED NOT NULL DEFAULT '0',
  `city` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_organizer`
--

CREATE TABLE `user_organizer` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `organizer_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `organizer_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT '0.00000000',
  `max_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_2` (`slug`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizers`
--
ALTER TABLE `organizers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `organizer_password_resets`
--
ALTER TABLE `organizer_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speakers`
--
ALTER TABLE `speakers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_logs`
--
ALTER TABLE `update_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_event`
--
ALTER TABLE `user_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_organizer`
--
ALTER TABLE `user_organizer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizers`
--
ALTER TABLE `organizers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizer_password_resets`
--
ALTER TABLE `organizer_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `speakers`
--
ALTER TABLE `speakers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `update_logs`
--
ALTER TABLE `update_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_event`
--
ALTER TABLE `user_event`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_organizer`
--
ALTER TABLE `user_organizer`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
