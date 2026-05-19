-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2026 at 06:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `courseenglishxpioneersedu`
--

-- --------------------------------------------------------

--
-- Table structure for table `accreditations`
--

CREATE TABLE `accreditations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `picture` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accreditations`
--

INSERT INTO `accreditations` (`id`, `name`, `ar_name`, `picture`, `created_at`, `updated_at`) VALUES
(1, 'British Council', 'المجلس الثقافي البريطاني', 'accreditations/M7BsPrTFIAzJW3Li1srTJp8DJtajUCbWsHQ4cy5P.png', '2025-01-27 06:30:42', '2025-01-27 06:30:42'),
(2, 'English UK', 'English UK', 'accreditations/xSS9zIIlacxebWVltZRS9QOfJOnKNtCPdsGk1i3d.png', '2025-03-26 04:28:15', '2025-03-26 04:28:15'),
(3, 'Trinity College London', 'كلية ترينيتي في لندن', 'accreditations/iTmY1Fl4pt3QTOtJ6E73Wm8pkjawW2Wdjhbt43rj.png', '2025-04-01 20:38:52', '2025-04-01 20:38:52'),
(6, 'UCAS', 'خدمة القبول في الجامعات والكليات', 'accreditations/G27TjHGtNq3C7g3emNPHiL2FoeSJ9EIduznLuBae.png', '2025-04-01 20:41:48', '2025-04-01 20:41:48'),
(7, 'IELTS', 'IELTS', 'accreditations/rwNKd1eOzVc9Q5f4nNVYoDwzwM1Xw2FS2dxkujuT.png', '2025-04-01 20:47:15', '2025-04-01 20:47:15'),
(8, 'IALC', 'IALC', 'accreditations/fV32Rvt9eRHnAM5pgCFfrJR5UNhIn6qmMmf4W5Jq.jpg', '2025-04-01 20:53:40', '2025-04-01 20:53:40'),
(9, 'IEA', 'IEA', 'accreditations/n0dbbZzY7JGxuN980rdPQkXWwdhfoHf9HtLuupMe.png', '2025-04-01 20:54:05', '2025-04-01 20:54:05'),
(10, 'Cambridge Assessment English', 'Cambridge Assessment English', 'accreditations/n8HetHD9ckGYMMttAVw0terGYTZqSpMsMaTR2GJt.png', '2025-04-01 20:54:55', '2025-04-01 20:54:55'),
(11, 'Quality English', 'Quality English', 'accreditations/VzM7DgftVvEAqYmk06neITgLcb2UmPKfPpygOsPn.png', '2025-04-01 20:55:29', '2025-04-01 20:55:29'),
(12, 'The English Network', 'The English Network', 'accreditations/vtBkMlNYWLl7hg9pckkSGjRiUXaLHvFJleecaIt4.png', '2025-04-01 20:56:13', '2025-04-01 20:56:13'),
(13, 'Language Cert', 'Language Cert', 'accreditations/FMuq1qRjqAOIHpjff4lzs3HqTJhDd2grO9VR6CDi.png', '2025-04-01 21:37:48', '2025-04-01 21:37:48');

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `referral_code` varchar(255) NOT NULL,
  `referral_discount` decimal(5,2) NOT NULL DEFAULT 0.00,
  `commission_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `referral_joined_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `user_id`, `company_name`, `phone`, `status`, `referral_code`, `referral_discount`, `commission_percent`, `referral_joined_at`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'Pioneersedu.com', '01933664811', 'approved', 'OK9MU59Q', 10.00, 5.00, NULL, NULL, '2026-02-08 03:04:37', '2026-02-08 03:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `agent_students`
--

CREATE TABLE `agent_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `onboarding_token` varchar(64) DEFAULT NULL,
  `onboarding_token_expires_at` timestamp NULL DEFAULT NULL,
  `onboarded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `citizenship` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `nationality_other` varchar(255) DEFAULT NULL,
  `highest_education` varchar(255) DEFAULT NULL,
  `grade_average` varchar(255) DEFAULT NULL,
  `has_english_test` tinyint(1) NOT NULL DEFAULT 0,
  `english_test_type` varchar(255) DEFAULT NULL,
  `english_test_score` varchar(255) DEFAULT NULL,
  `destination_interest` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`destination_interest`)),
  `destinations_other` varchar(255) DEFAULT NULL,
  `preferred_intake` varchar(255) DEFAULT NULL,
  `budget_range` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending','submitted','reviewing','contacted','accepted','rejected','invalid') DEFAULT 'draft',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_role` varchar(255) DEFAULT NULL,
  `status_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bathroom_types`
--

CREATE TABLE `bathroom_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bathroom_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bathroom_types`
--

INSERT INTO `bathroom_types` (`id`, `bathroom_code`, `name`, `ar_name`, `description`, `ar_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'SHARED_BATHROOM', 'Shared Bathroom', 'حمام مشترك', NULL, NULL, '2026-02-11 23:35:09', '2026-02-11 23:35:09', NULL),
(3, 'PRIVATE_BATHROOM', 'Private Bathroom', 'حمام خاص', NULL, NULL, '2026-02-11 23:35:09', '2026-02-11 23:35:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bedroom_types`
--

CREATE TABLE `bedroom_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bedroom_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bedroom_types`
--

INSERT INTO `bedroom_types` (`id`, `bedroom_code`, `name`, `ar_name`, `description`, `ar_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'PRIVATE_ROOM', 'Private Room', 'غرفة خاصة', NULL, NULL, '2026-02-11 19:42:53', '2026-02-11 19:42:53', NULL),
(4, 'SINGLE_ROOM', 'Single Room', 'غرفة فردية', NULL, NULL, '2026-02-11 19:42:53', '2026-02-11 19:42:53', NULL),
(5, 'TWIN_ROOM', 'Twin room', 'غرفة مزدوجة', NULL, NULL, '2026-02-11 19:42:53', '2026-02-11 19:42:53', NULL),
(6, 'SHARED_ROOM', 'Shared Room', 'غرفة مشتركة', NULL, NULL, '2026-02-11 19:42:53', '2026-02-11 19:42:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `ar_summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `ar_content` longtext DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `audience_scope` enum('university','school','all') NOT NULL DEFAULT 'all',
  `featured_image` varchar(255) DEFAULT NULL,
  `publisher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `ar_title`, `slug`, `summary`, `ar_summary`, `content`, `ar_content`, `category_id`, `audience_scope`, `featured_image`, `publisher_id`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Top 10 Tips for Learning English Fast', 'أفضل 10 نصائح لتعلم الإنجليزية بسرعة', 'top-10-tips-for-learning-english-fast', 'Simple habits that improve your English every day.', 'عادات بسيطة تُحسّن لغتك الإنجليزية يوميًا.', '<div>Build a daily routine, practice speaking, and immerse yourself in English media.</div>', '<div dir=\"rtl\">ابنِ روتينًا يوميًا، وتدرّب على التحدث، واغمر نفسك في وسائل الإعلام الإنجليزية.</div>', 1, 'all', 'blog-images/FPBtiXeE1KChZbAc2MgxSOFfU5KVyyUrsxRBD91V.png', NULL, '2026-02-12 07:40:00', '2026-02-12 07:40:18', '2026-02-12 23:58:07', NULL),
(2, 'Why Study in the UK?', 'لماذا الدراسة في المملكة المتحدة؟', 'why-study-in-the-uk', 'World-class education and a vibrant student experience.', 'تعليم عالمي وتجربة طلابية غنية.', 'The UK offers globally ranked institutions, diverse culture, and strong career pathways.', 'توفر المملكة المتحدة مؤسسات عالمية، وثقافة متنوعة، ومسارات مهنية قوية.', 2, 'all', 'blog_images/study-uk.jpg', NULL, '2026-02-12 07:40:18', '2026-02-12 07:40:18', '2026-02-12 07:40:18', NULL),
(3, 'Mastering English Grammar: A Beginner’s Guide', 'إتقان قواعد الإنجليزية: دليل للمبتدئين', 'mastering-english-grammar-beginners-guide', 'Understand the essentials without overwhelm.', 'افهم الأساسيات بدون تعقيد.', 'Start with tenses, sentence structure, and common grammar patterns.', 'ابدأ بالأزمنة وبناء الجملة وأنماط القواعد الشائعة.', 3, 'all', 'blog_images/grammar-guide.jpg', NULL, '2026-02-12 07:40:18', '2026-02-12 07:40:18', '2026-02-12 07:40:18', NULL),
(4, 'IELTS Preparation Checklist', 'قائمة التحضير لاختبار IELTS', 'ielts-preparation-checklist', 'A focused plan to prepare efficiently.', 'خطة مركّزة للتحضير بكفاءة.', 'Review band descriptors, take mock tests, and improve writing structure.', 'راجع معايير الدرجات، وأدِّ اختبارات تجريبية، وحسّن بنية الكتابة.', 4, 'all', 'blog_images/ielts-checklist.jpg', NULL, '2026-02-12 07:40:18', '2026-02-12 07:40:18', '2026-02-12 07:40:18', NULL),
(5, 'How English Skills Boost Your Career', 'كيف تعزز مهارات الإنجليزية مسارك المهني', 'how-english-skills-boost-your-career', 'Communicate better and access more opportunities.', 'تواصل أفضل واغتنم فرصًا أكثر.', 'English proficiency improves hiring prospects and global mobility.', 'إتقان الإنجليزية يرفع فرص التوظيف والتنقل عالميًا.', 5, 'all', 'blog_images/career-growth.jpg', NULL, '2026-02-12 07:40:18', '2026-02-12 07:40:18', '2026-02-12 07:40:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_blog_tag`
--

CREATE TABLE `blog_blog_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `blog_tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `color` varchar(9) DEFAULT NULL,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `ar_name`, `slug`, `description`, `ar_description`, `color`, `display_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Learning Tips', 'نصائح التعلم', 'learning-tips', 'Short, practical tips to learn English faster.', 'نصائح عملية ومختصرة لتعلم الإنجليزية بشكل أسرع.', '#1F63AE', 1, 1, '2026-02-12 07:40:16', '2026-02-12 07:40:16', NULL),
(2, 'Study Abroad', 'الدراسة في الخارج', 'study-abroad', 'Guides and checklists for studying abroad.', 'أدلة وقوائم تحقق للدراسة في الخارج.', '#0E7C86', 2, 1, '2026-02-12 07:40:16', '2026-02-12 07:40:16', NULL),
(3, 'Grammar', 'القواعد', 'grammar', 'Clear explanations of English grammar rules.', 'شروحات واضحة لقواعد اللغة الإنجليزية.', '#6A5ACD', 3, 1, '2026-02-12 07:40:17', '2026-02-12 07:40:17', NULL),
(4, 'Exams', 'الاختبارات', 'exams', 'IELTS and exam preparation guidance.', 'إرشادات التحضير لاختبار IELTS والاختبارات الأخرى.', '#E67E22', 4, 1, '2026-02-12 07:40:17', '2026-02-12 07:40:17', NULL),
(5, 'Careers', 'المسار المهني', 'careers', 'How English skills boost career growth.', 'كيف تساعد مهارات الإنجليزية في تطوير المسار المهني.', '#2E7D32', 5, 1, '2026-02-12 07:40:17', '2026-02-12 07:40:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-settings.cache', 'a:8:{s:9:\"site_name\";s:19:\"Pioneers Admissions\";s:10:\"site_email\";s:20:\"info@pioneers.edu.sa\";s:10:\"site_phone\";s:16:\"+966 50 123 4567\";s:12:\"site_address\";s:20:\"Riyadh, Saudi Arabia\";s:12:\"social_links\";a:4:{i:0;a:2:{s:8:\"platform\";s:8:\"Facebook\";s:3:\"url\";s:1:\"#\";}i:1;a:2:{s:8:\"platform\";s:7:\"Twitter\";s:3:\"url\";s:1:\"#\";}i:2;a:2:{s:8:\"platform\";s:9:\"Instagram\";s:3:\"url\";s:1:\"#\";}i:3;a:2:{s:8:\"platform\";s:8:\"LinkedIn\";s:3:\"url\";s:1:\"#\";}}s:19:\"contact_description\";s:115:\"Can\'t make it to an office? No problem. Fill out the form or reach out to us directly through our general channels.\";s:12:\"app.branding\";a:4:{s:8:\"app_name\";s:7:\"Laravel\";s:13:\"primary_color\";s:7:\"#6366f1\";s:4:\"logo\";s:53:\"branding/fEUJJwntLjx3czmWhKvZ5XC0qmBPotcmR9HXEOPI.png\";s:7:\"favicon\";N;}s:8:\"branding\";a:4:{s:3:\"app\";s:13:\"courseenglish\";s:6:\"header\";a:6:{s:4:\"logo\";a:2:{s:4:\"main\";s:9:\"/logo.png\";s:2:\"ar\";s:9:\"/logo.png\";}s:7:\"top_nav\";a:6:{i:0;a:4:{s:5:\"label\";s:19:\"Language Institutes\";s:8:\"ar_label\";s:21:\"معاهد اللغة\";s:3:\"url\";s:20:\"/language-institutes\";s:4:\"href\";s:20:\"/language-institutes\";}i:1;a:4:{s:5:\"label\";s:15:\"Summer Programs\";s:8:\"ar_label\";s:21:\"برامج الصيف\";s:3:\"url\";s:16:\"/summer-programs\";s:4:\"href\";s:16:\"/summer-programs\";}i:2;a:4:{s:5:\"label\";s:14:\"Online Courses\";s:8:\"ar_label\";s:37:\"الدورات الإلكترونية\";s:3:\"url\";s:15:\"/online-courses\";s:4:\"href\";s:15:\"/online-courses\";}i:3;a:4:{s:5:\"label\";s:21:\"University Admissions\";s:8:\"ar_label\";s:27:\"القبول الجامعي\";s:3:\"url\";s:22:\"/university-admissions\";s:4:\"href\";s:22:\"/university-admissions\";}i:4;a:4:{s:5:\"label\";s:16:\"Travel & Tourism\";s:8:\"ar_label\";s:27:\"السفر والسياحة\";s:3:\"url\";s:19:\"/travel-and-tourism\";s:4:\"href\";s:19:\"/travel-and-tourism\";}i:5;a:4:{s:5:\"label\";s:31:\"Training & Professional Courses\";s:8:\"ar_label\";s:46:\"التدريب والدورات المهنية\";s:3:\"url\";s:34:\"/training-and-professional-courses\";s:4:\"href\";s:34:\"/training-and-professional-courses\";}}s:8:\"main_nav\";a:5:{i:0;a:4:{s:5:\"label\";s:4:\"Home\";s:8:\"ar_label\";s:16:\"الرئيسية\";s:3:\"url\";s:1:\"/\";s:4:\"href\";s:1:\"/\";}i:1;a:4:{s:5:\"label\";s:6:\"Offers\";s:8:\"ar_label\";s:12:\"العروض\";s:3:\"url\";s:7:\"/offers\";s:4:\"href\";s:7:\"/offers\";}i:2;a:4:{s:5:\"label\";s:8:\"About Us\";s:8:\"ar_label\";s:11:\"من نحن\";s:3:\"url\";s:9:\"/about-us\";s:4:\"href\";s:9:\"/about-us\";}i:3;a:4:{s:5:\"label\";s:10:\"Contact Us\";s:8:\"ar_label\";s:15:\"اتصل بنا\";s:3:\"url\";s:11:\"/contact-us\";s:4:\"href\";s:11:\"/contact-us\";}i:4;a:4:{s:5:\"label\";s:8:\"Articles\";s:8:\"ar_label\";s:16:\"المقالات\";s:3:\"url\";s:9:\"/articles\";s:4:\"href\";s:9:\"/articles\";}}s:10:\"currencies\";a:2:{i:0;a:4:{s:4:\"code\";s:3:\"SAR\";s:5:\"label\";s:11:\"Saudi Riyal\";s:6:\"symbol\";s:3:\"﷼\";s:4:\"icon\";s:15:\"/assets/sar.svg\";}i:1;a:4:{s:4:\"code\";s:3:\"GBP\";s:5:\"label\";s:13:\"British Pound\";s:6:\"symbol\";s:2:\"£\";s:4:\"icon\";s:15:\"/assets/gbp.svg\";}}s:9:\"languages\";a:2:{i:0;a:3:{s:4:\"code\";s:2:\"en\";s:5:\"label\";s:7:\"English\";s:4:\"flag\";s:20:\"/assets/flags/gb.svg\";}i:1;a:3:{s:4:\"code\";s:2:\"ar\";s:5:\"label\";s:6:\"Arabic\";s:4:\"flag\";s:20:\"/assets/flags/sa.svg\";}}s:7:\"buttons\";a:3:{s:7:\"compare\";a:2:{s:4:\"icon\";s:7:\"compare\";s:3:\"url\";s:8:\"/compare\";}s:8:\"wishlist\";a:2:{s:4:\"icon\";s:5:\"heart\";s:3:\"url\";s:9:\"/wishlist\";}s:7:\"account\";a:4:{s:5:\"label\";s:10:\"My Account\";s:8:\"ar_label\";s:10:\"حسابي\";s:3:\"url\";s:18:\"/student/dashboard\";s:4:\"icon\";s:4:\"user\";}}}s:6:\"footer\";a:6:{s:9:\"subscribe\";a:3:{s:7:\"heading\";s:16:\"Stay in the loop\";s:11:\"placeholder\";s:16:\"Enter your email\";s:11:\"button_text\";s:9:\"Subscribe\";}s:7:\"columns\";a:2:{i:0;a:2:{s:5:\"title\";s:8:\"Programs\";s:5:\"items\";a:4:{i:0;a:3:{s:5:\"label\";s:19:\"Language Institutes\";s:8:\"ar_label\";s:21:\"معاهد اللغة\";s:3:\"url\";s:20:\"/language-institutes\";}i:1;a:3:{s:5:\"label\";s:15:\"Summer Programs\";s:8:\"ar_label\";s:21:\"برامج الصيف\";s:3:\"url\";s:16:\"/summer-programs\";}i:2;a:3:{s:5:\"label\";s:17:\"Distance Learning\";s:8:\"ar_label\";s:24:\"التعلم عن بعد\";s:3:\"url\";s:15:\"/online-courses\";}i:3;a:3:{s:5:\"label\";s:16:\"Travel & Tourism\";s:8:\"ar_label\";s:27:\"السفر والسياحة\";s:3:\"url\";s:19:\"/travel-and-tourism\";}}}i:1;a:2:{s:5:\"title\";s:7:\"Company\";s:5:\"items\";a:4:{i:0;a:3:{s:5:\"label\";s:8:\"About Us\";s:8:\"ar_label\";s:11:\"من نحن\";s:3:\"url\";s:9:\"/about-us\";}i:1;a:3:{s:5:\"label\";s:7:\"Careers\";s:8:\"ar_label\";s:10:\"وظائف\";s:3:\"url\";s:8:\"/careers\";}i:2;a:3:{s:5:\"label\";s:4:\"Blog\";s:8:\"ar_label\";s:14:\"المدونة\";s:3:\"url\";s:9:\"/articles\";}i:3;a:3:{s:5:\"label\";s:7:\"Contact\";s:8:\"ar_label\";s:8:\"اتصل\";s:3:\"url\";s:11:\"/contact-us\";}}}}s:11:\"description\";s:104:\"CourseEnglish helps learners find the right English programs, compare options, and book with confidence.\";s:6:\"social\";a:4:{i:0;a:2:{s:8:\"platform\";s:8:\"linkedin\";s:3:\"url\";s:1:\"#\";}i:1;a:2:{s:8:\"platform\";s:8:\"facebook\";s:3:\"url\";s:1:\"#\";}i:2;a:2:{s:8:\"platform\";s:9:\"instagram\";s:3:\"url\";s:1:\"#\";}i:3;a:2:{s:8:\"platform\";s:7:\"twitter\";s:3:\"url\";s:1:\"#\";}}s:9:\"copyright\";s:27:\"All rights reserved © 2026\";s:5:\"brand\";s:13:\"CourseEnglish\";}s:6:\"mobile\";a:8:{s:5:\"promo\";a:2:{s:4:\"text\";s:117:\"Our exclusive offers guarantee the best prices and services. If you find a better price or service, we’ll match it.\";s:4:\"icon\";s:23:\"/assets/icons/offer.png\";}s:4:\"logo\";s:9:\"/logo.png\";s:3:\"nav\";a:6:{i:0;a:3:{s:5:\"label\";s:19:\"Language Institutes\";s:8:\"ar_label\";s:21:\"معاهد اللغة\";s:3:\"url\";s:20:\"/language-institutes\";}i:1;a:3:{s:5:\"label\";s:15:\"Summer Programs\";s:8:\"ar_label\";s:21:\"برامج الصيف\";s:3:\"url\";s:16:\"/summer-programs\";}i:2;a:3:{s:5:\"label\";s:14:\"Online Courses\";s:8:\"ar_label\";s:37:\"الدورات الإلكترونية\";s:3:\"url\";s:15:\"/online-courses\";}i:3;a:3:{s:5:\"label\";s:21:\"University Admissions\";s:8:\"ar_label\";s:27:\"القبول الجامعي\";s:3:\"url\";s:22:\"/university-admissions\";}i:4;a:3:{s:5:\"label\";s:16:\"Travel & Tourism\";s:8:\"ar_label\";s:27:\"السفر والسياحة\";s:3:\"url\";s:19:\"/travel-and-tourism\";}i:5;a:3:{s:5:\"label\";s:31:\"Training & Professional Courses\";s:8:\"ar_label\";s:46:\"التدريب والدورات المهنية\";s:3:\"url\";s:34:\"/training-and-professional-courses\";}}s:11:\"quick_links\";a:3:{i:0;a:3:{s:5:\"label\";s:4:\"Home\";s:4:\"icon\";s:4:\"home\";s:3:\"url\";s:1:\"/\";}i:1;a:3:{s:5:\"label\";s:10:\"Contact Us\";s:4:\"icon\";s:5:\"phone\";s:3:\"url\";s:11:\"/contact-us\";}i:2;a:3:{s:5:\"label\";s:3:\"FAQ\";s:4:\"icon\";s:8:\"question\";s:3:\"url\";s:9:\"/articles\";}}s:7:\"actions\";a:5:{i:0;a:3:{s:5:\"label\";s:10:\"My Account\";s:4:\"icon\";s:4:\"user\";s:3:\"url\";s:18:\"/student/dashboard\";}i:1;a:3:{s:5:\"label\";s:7:\"Compare\";s:4:\"icon\";s:7:\"compare\";s:3:\"url\";s:8:\"/compare\";}i:2;a:4:{s:5:\"label\";s:8:\"Wishlist\";s:4:\"icon\";s:5:\"heart\";s:3:\"url\";s:9:\"/wishlist\";s:5:\"badge\";i:4;}i:3;a:3:{s:5:\"label\";s:10:\"Institutes\";s:4:\"icon\";s:8:\"building\";s:3:\"url\";s:20:\"/language-institutes\";}i:4;a:3:{s:5:\"label\";s:4:\"Home\";s:4:\"icon\";s:4:\"home\";s:3:\"url\";s:1:\"/\";}}s:12:\"drawer_links\";a:7:{i:0;a:2:{s:5:\"label\";s:6:\"Offers\";s:3:\"url\";s:7:\"/offers\";}i:1;a:2:{s:5:\"label\";s:8:\"About Us\";s:3:\"url\";s:9:\"/about-us\";}i:2;a:2:{s:5:\"label\";s:4:\"Team\";s:3:\"url\";s:5:\"/team\";}i:3;a:2:{s:5:\"label\";s:4:\"Blog\";s:3:\"url\";s:9:\"/articles\";}i:4;a:2:{s:5:\"label\";s:24:\"English Language Schools\";s:3:\"url\";s:20:\"/language-institutes\";}i:5;a:2:{s:5:\"label\";s:14:\"Summer Program\";s:3:\"url\";s:16:\"/summer-programs\";}i:6;a:2:{s:5:\"label\";s:20:\"University Admission\";s:3:\"url\";s:22:\"/university-admissions\";}}s:13:\"drawer_social\";a:4:{i:0;a:2:{s:8:\"platform\";s:8:\"linkedin\";s:3:\"url\";s:1:\"#\";}i:1;a:2:{s:8:\"platform\";s:8:\"facebook\";s:3:\"url\";s:1:\"#\";}i:2;a:2:{s:8:\"platform\";s:9:\"instagram\";s:3:\"url\";s:1:\"#\";}i:3;a:2:{s:8:\"platform\";s:7:\"twitter\";s:3:\"url\";s:1:\"#\";}}s:12:\"drawer_legal\";a:2:{i:0;a:2:{s:5:\"label\";s:14:\"Privacy Policy\";s:3:\"url\";s:8:\"/privacy\";}i:1;a:2:{s:5:\"label\";s:18:\"Terms & Conditions\";s:3:\"url\";s:6:\"/terms\";}}}}}', 1771122249);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `ar_subtitle` varchar(255) DEFAULT NULL,
  `certificate_image` varchar(255) DEFAULT NULL,
  `certification_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `title`, `ar_title`, `subtitle`, `ar_subtitle`, `certificate_image`, `certification_link`, `created_at`, `updated_at`) VALUES
(2, 'British Council Agent', 'وكيل المجلس الثقافي البريطاني', 'Global Recognition', NULL, 'certifications/JQgu1GWOT1GvrbSMMLcuKTlrLpVfQItxwP551KrN.png', 'https://example.com', '2026-01-29 07:07:55', '2026-02-14 17:21:43'),
(3, 'ICEF Agent', 'وكيل آي سي إف', 'Global Recognition', NULL, 'certifications/eTJy5539iGDxhOSGRxpwCEywIDrNN0KdbS92i8Oq.png', 'https://example.com', '2026-01-29 07:07:55', '2026-02-14 17:22:15'),
(5, 'English UK Partner', 'شريك اللغة الإنجليزية في المملكة المتحدة', 'Global Recognition', NULL, 'certifications/SwUPOkWRG8f9R22IsDhCj2XfFU8BhXvzrOnQAh9w.png', 'https://example.com', '2026-01-29 07:07:55', '2026-02-14 17:22:37');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `slug`, `ar_name`, `description`, `ar_description`, `country_id`, `latitude`, `longitude`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'London', 'london', 'لندن', 'London is one of the most popular cities in the United Kingdom.', 'لندن من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 36, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(2, 'Manchester', 'manchester', 'مانشستر', 'Manchester is one of the most popular cities in the United Kingdom.', 'مانشستر من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 38, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(3, 'Birmingham', 'birmingham', 'برمنغهام', 'Birmingham is one of the most popular cities in the United Kingdom.', 'برمنغهام من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 7, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(4, 'Liverpool', 'liverpool', 'ليفربول', 'Liverpool is one of the most popular cities in the United Kingdom.', 'ليفربول من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 35, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(5, 'Leeds', 'leeds', 'ليدز', 'Leeds is one of the most popular cities in the United Kingdom.', 'ليدز من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 32, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(6, 'Glasgow', 'glasgow', 'غلاسكو', 'Glasgow is one of the most popular cities in the United Kingdom.', 'غلاسكو من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 24, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(7, 'Edinburgh', 'edinburgh', 'إدنبرة', 'Edinburgh is one of the most popular cities in the United Kingdom.', 'إدنبرة من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 21, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(8, 'Bristol', 'bristol', 'بريستول', 'Bristol is one of the most popular cities in the United Kingdom.', 'بريستول من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 11, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(9, 'Sheffield', 'sheffield', 'شيفيلد', 'Sheffield is one of the most popular cities in the United Kingdom.', 'شيفيلد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 53, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(10, 'Newcastle upon Tyne', 'newcastle-upon-tyne', 'نيوكاسل أبون تاين', 'Newcastle upon Tyne is one of the most popular cities in the United Kingdom.', 'نيوكاسل أبون تاين من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 41, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(11, 'Nottingham', 'nottingham', 'نوتنغهام', 'Nottingham is one of the most popular cities in the United Kingdom.', 'نوتنغهام من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 45, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(12, 'Leicester', 'leicester', 'ليستر', 'Leicester is one of the most popular cities in the United Kingdom.', 'ليستر من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 33, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(13, 'Coventry', 'coventry', 'كوفنتري', 'Coventry is one of the most popular cities in the United Kingdom.', 'كوفنتري من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 17, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(14, 'Bradford', 'bradford', 'برادفورد', 'Bradford is one of the most popular cities in the United Kingdom.', 'برادفورد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 9, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(15, 'Cardiff', 'cardiff', 'كارديف', 'Cardiff is one of the most popular cities in the United Kingdom.', 'كارديف من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 15, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(16, 'Belfast', 'belfast', 'بلفاست', 'Belfast is one of the most popular cities in the United Kingdom.', 'بلفاست من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 6, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(17, 'Southampton', 'southampton', 'ساوثهامبتون', 'Southampton is one of the most popular cities in the United Kingdom.', 'ساوثهامبتون من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 54, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(18, 'Portsmouth', 'portsmouth', 'بورتسموث', 'Portsmouth is one of the most popular cities in the United Kingdom.', 'بورتسموث من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 49, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(19, 'Brighton', 'brighton', 'برايتون', 'Brighton is one of the most popular cities in the United Kingdom.', 'برايتون من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 10, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(20, 'Cambridge', 'cambridge', 'كامبردج', 'Cambridge is one of the most popular cities in the United Kingdom.', 'كامبردج من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 12, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(21, 'Oxford', 'oxford', 'أكسفورد', 'Oxford is one of the most popular cities in the United Kingdom.', 'أكسفورد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 47, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(22, 'Bath', 'bath', 'باث', 'Bath is one of the most popular cities in the United Kingdom.', 'باث من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 4, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(23, 'York', 'york', 'يورك', 'York is one of the most popular cities in the United Kingdom.', 'يورك من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 60, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:44'),
(24, 'Bournemouth', 'bournemouth', 'بورنموث', 'Bournemouth is one of the most popular cities in the United Kingdom.', 'بورنموث من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 8, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(25, 'Exeter', 'exeter', 'إكستر', 'Exeter is one of the most popular cities in the United Kingdom.', 'إكستر من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 23, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(26, 'Plymouth', 'plymouth', 'بليموث', 'Plymouth is one of the most popular cities in the United Kingdom.', 'بليموث من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 48, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(27, 'Reading', 'reading', 'ريدينغ', 'Reading is one of the most popular cities in the United Kingdom.', 'ريدينغ من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 51, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(28, 'Milton Keynes', 'milton-keynes', 'ميلتون كينز', 'Milton Keynes is one of the most popular cities in the United Kingdom.', 'ميلتون كينز من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 40, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(29, 'Aberdeen', 'aberdeen', 'أبردين', 'Aberdeen is one of the most popular cities in the United Kingdom.', 'أبردين من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 1, 1, '2026-02-10 07:04:55', '2026-02-10 07:13:45'),
(30, 'Swansea', 'swansea', 'سوانزي', 'Swansea is one of the most popular cities in the United Kingdom.', 'سوانزي من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 57, 1, '2026-02-10 07:04:55', '2026-02-11 17:27:43'),
(31, 'Aberystwyth', 'aberystwyth', 'أبيريستويث', 'Aberystwyth is one of the most popular cities in the United Kingdom.', 'أبيريستويث من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 2, 1, '2026-02-10 07:13:45', '2026-02-10 07:13:45'),
(32, 'Bangor', 'bangor', 'بانغور', 'Bangor is one of the most popular cities in the United Kingdom.', 'بانغور من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 3, 1, '2026-02-10 07:13:45', '2026-02-10 07:13:45'),
(33, 'Bedfordshire', 'bedfordshire', 'بيدفوردشير', 'Bedfordshire is one of the most popular cities in the United Kingdom.', 'بيدفوردشير من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 5, 1, '2026-02-10 07:13:45', '2026-02-10 07:13:45'),
(34, 'Canterbury', 'canterbury', 'كانتربري', 'Canterbury is one of the most popular cities in the United Kingdom.', 'كانتربري من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 13, 1, '2026-02-10 07:13:45', '2026-02-10 07:13:45'),
(35, 'Colchester', 'colchester', 'كولتشستر', 'Colchester is one of the most popular cities in the United Kingdom.', 'كولتشستر من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 16, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(36, 'Derby', 'derby', 'ديربي', 'Derby is one of the most popular cities in the United Kingdom.', 'ديربي من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 18, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(37, 'Dundee', 'dundee', 'دندي', 'Dundee is one of the most popular cities in the United Kingdom.', 'دندي من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 19, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(38, 'Durham', 'durham', 'دورهام', 'Durham is one of the most popular cities in the United Kingdom.', 'دورهام من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 20, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(39, 'Egham', 'egham', 'إغهام', 'Egham is one of the most popular cities in the United Kingdom.', 'إغهام من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 22, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(40, 'Guildford', 'guildford', 'غيلدفورد', 'Guildford is one of the most popular cities in the United Kingdom.', 'غيلدفورد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 25, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(41, 'Hatfield', 'hatfield', 'هاتفيلد', 'Hatfield is one of the most popular cities in the United Kingdom.', 'هاتفيلد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 26, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(42, 'Huddersfield', 'huddersfield', 'هديرسفيلد', 'Huddersfield is one of the most popular cities in the United Kingdom.', 'هديرسفيلد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 27, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(43, 'Hull', 'hull', 'هَل', 'Hull is one of the most popular cities in the United Kingdom.', 'هَل من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 28, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(44, 'Keele', 'keele', 'كيل', 'Keele is one of the most popular cities in the United Kingdom.', 'كيل من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 29, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(45, 'Kingston upon Thames', 'kingston-upon-thames', 'كنغستون أبون تيمز', 'Kingston upon Thames is one of the most popular cities in the United Kingdom.', 'كنغستون أبون تيمز من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 30, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(46, 'Lancaster', 'lancaster', 'لانكستر', 'Lancaster is one of the most popular cities in the United Kingdom.', 'لانكستر من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 31, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(47, 'Lincoln', 'lincoln', 'لينكولن', 'Lincoln is one of the most popular cities in the United Kingdom.', 'لينكولن من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 34, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(48, 'Loughborough', 'loughborough', 'لافبرا', 'Loughborough is one of the most popular cities in the United Kingdom.', 'لافبرا من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 37, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(49, 'Middlesbrough', 'middlesbrough', 'ميدلزبره', 'Middlesbrough is one of the most popular cities in the United Kingdom.', 'ميدلزبره من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 39, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(50, 'Newport', 'newport', 'نيوبورت (شروبشاير)', 'Newport is one of the most popular cities in the United Kingdom.', 'نيوبورت (شروبشاير) من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 42, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(51, 'Norwich', 'norwich', 'نورويتش', 'Norwich is one of the most popular cities in the United Kingdom.', 'نورويتش من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 43, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(52, 'Northampton', 'northampton', 'نورثهامبتون', 'Northampton is one of the most popular cities in the United Kingdom.', 'نورثهامبتون من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 44, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(53, 'Ormskirk', 'ormskirk', 'أورمسكيرك', 'Ormskirk is one of the most popular cities in the United Kingdom.', 'أورمسكيرك من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 46, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(54, 'Preston', 'preston', 'برستون', 'Preston is one of the most popular cities in the United Kingdom.', 'برستون من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 50, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(55, 'Salford', 'salford', 'سالفورد', 'Salford is one of the most popular cities in the United Kingdom.', 'سالفورد من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 52, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(56, 'St Andrews', 'st-andrews', 'سانت أندروز', 'St Andrews is one of the most popular cities in the United Kingdom.', 'سانت أندروز من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 55, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(57, 'Stirling', 'stirling', 'ستيرلينغ', 'Stirling is one of the most popular cities in the United Kingdom.', 'ستيرلينغ من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 56, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(58, 'Uxbridge', 'uxbridge', 'أكسبريدج', 'Uxbridge is one of the most popular cities in the United Kingdom.', 'أكسبريدج من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 58, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(59, 'Wolverhampton', 'wolverhampton', 'وولفرهامبتون', 'Wolverhampton is one of the most popular cities in the United Kingdom.', 'وولفرهامبتون من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 59, 1, '2026-02-10 07:13:45', '2026-02-11 17:27:43'),
(60, 'Kent', 'kent', 'كنت', 'Kent is one of the most popular cities in the United Kingdom.', 'كنت من أشهر المدن في المملكة المتحدة.', 1, NULL, NULL, 14, 1, '2026-02-11 17:27:43', '2026-02-11 17:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `app` enum('courseenglish','university') NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `ar_content` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `app`, `slug`, `title`, `ar_title`, `content`, `ar_content`, `meta_title`, `meta_description`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'courseenglish', 'home', 'Home', 'الرئيسية', '{\"hero\":{\"promo_text\":\"Our offers are exclusive. We guarantee the best prices and services and will pay the difference if you find a better price or service.\",\"promo_icon\":\"/assets/icons/fire.svg\",\"headline\":\"Start your study journey now\",\"subheadline\":\"Discover the best institutes and accredited programs around the world, and choose the destination that fits your goals with ease.\",\"promo\":\"Our offers are exclusive. We guarantee the best prices and services and will pay the difference if you find a better price or service.\",\"services\":[\"Accommodation\",\"Pickup\",\"Insurance\"],\"button_text\":\"Search\",\"background_image\":\"\",\"figure_image\":\"/assets/fig.png\",\"search_placeholder\":\"Enter your preferred destination\",\"search_subtext\":\"Enter country, city, or institute\",\"course_label\":\"Course type\",\"course_placeholder\":\"Select course type\",\"weeks_label\":\"Number of weeks\",\"weeks_placeholder\":\"Select weeks\",\"start_label\":\"Start date\",\"start_placeholder\":\"Select start date\",\"search_button_text\":\"Search\"},\"stats\":{\"heading\":\"Our achievements in numbers\",\"body\":\"Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.\",\"items\":[{\"value\":\"+15,000\",\"label\":\"Students enrolled in accredited English programs abroad\"},{\"value\":\"+50\",\"label\":\"Partner universities offering accredited English study programs abroad\"}]},\"certificates_meta\":{\"heading\":\"We are accredited by many institutions\",\"subheading\":\"We are proud of our partnerships with leading English language institutes and accredited educational organizations around the world.\"},\"partners\":{\"heading\":\"Partner institutes around the world\",\"view_all_label\":\"View all schools\",\"view_all_url\":\"/language-institutes\",\"arrow_left_icon\":\"/assets/icons/arrow-left.svg\",\"arrow_right_icon\":\"/assets/icons/arrow-right.svg\"},\"summer\":{\"heading\":\"Summer Programs\",\"cta_text\":\"View all programs\",\"cta_url\":\"/summer-programs\"},\"online\":{\"heading\":\"Popular Online English Courses\",\"cta_text\":\"View all programs\",\"cta_url\":\"/online-courses\"},\"reviews\":{\"heading\":\"What our students say about their English courses\"},\"destinations\":{\"heading\":\"Best destinations to study languages\",\"links\":[{\"label\":\"Language institutes in China\",\"url\":\"/language-institutes/china\"},{\"label\":\"Language institutes in Japan\",\"url\":\"/language-institutes/japan\"},{\"label\":\"Language institutes in South Korea\",\"url\":\"/language-institutes/south-korea\"},{\"label\":\"Language institutes in Malaysia\",\"url\":\"/language-institutes/malaysia\"},{\"label\":\"Language institutes in Singapore\",\"url\":\"/language-institutes/singapore\"},{\"label\":\"Language institutes in Thailand\",\"url\":\"/language-institutes/thailand\"},{\"label\":\"Language institutes in India\",\"url\":\"/language-institutes/india\"},{\"label\":\"Language institutes in Indonesia\",\"url\":\"/language-institutes/indonesia\"},{\"label\":\"Language institutes in the Philippines\",\"url\":\"/language-institutes/philippines\"},{\"label\":\"Language institutes in Vietnam\",\"url\":\"/language-institutes/vietnam\"},{\"label\":\"Language institutes in the UK\",\"url\":\"/language-institutes/uk\"},{\"label\":\"Language institutes in France\",\"url\":\"/language-institutes/france\"},{\"label\":\"Language institutes in Ireland\",\"url\":\"/language-institutes/ireland\"},{\"label\":\"Language institutes in Germany\",\"url\":\"/language-institutes/germany\"},{\"label\":\"Language institutes in the Netherlands\",\"url\":\"/language-institutes/netherlands\"},{\"label\":\"Language institutes in Canada\",\"url\":\"/language-institutes/canada\"},{\"label\":\"Language institutes in USA\",\"url\":\"/language-institutes/usa\"},{\"label\":\"Language institutes in Australia\",\"url\":\"/language-institutes/australia\"},{\"label\":\"Language institutes in South Africa\",\"url\":\"/language-institutes/south-africa\"}]},\"faq\":{\"heading\":\"Questions & Answers\",\"subheading\":\"We help you decide with confidence. These are the most common questions prospective students ask us.\",\"cta_text\":\"Still have a question?\",\"cta_url\":\"/contact-us\",\"cta_icon\":\"/assets/icons/arrow-right.svg\"},\"blogs\":{\"heading\":\"Blogs & Latest News\",\"cta_text\":\"All articles\",\"cta_url\":\"/articles\"}}', '{\"hero\":{\"promo_text\":\"عروضنا حصرية. نضمن أفضل الأسعار والخدمات وندفع الفرق إذا وجدت سعرًا أو خدمة أفضل.\",\"promo_icon\":\"/assets/icons/fire.svg\",\"headline\":\"ابدأ رحلتك الدراسية الآن\",\"subheadline\":\"اكتشف أفضل المعاهد والبرامج المعتمدة حول العالم واختر الوجهة التي تناسب أهدافك بسهولة.\",\"promo\":\"عروضنا حصرية. نضمن أفضل الأسعار والخدمات وندفع الفرق إذا وجدت سعرًا أو خدمة أفضل.\",\"services\":[\"السكن\",\"الاستقبال\",\"التأمين\"],\"button_text\":\"ابحث\",\"background_image\":\"\",\"figure_image\":\"/assets/fig.png\",\"search_placeholder\":\"أدخل وجهتك المفضلة\",\"search_subtext\":\"أدخل الدولة أو المدينة أو المعهد\",\"course_label\":\"نوع الدورة\",\"course_placeholder\":\"اختر نوع الدورة\",\"weeks_label\":\"عدد الأسابيع\",\"weeks_placeholder\":\"اختر الأسابيع\",\"start_label\":\"تاريخ البدء\",\"start_placeholder\":\"اختر تاريخ البدء\",\"search_button_text\":\"ابحث\"},\"stats\":{\"heading\":\"إنجازاتنا بالأرقام\",\"body\":\"بفضل شركائنا الموثوقين والمؤسسات التعليمية الرائدة حول العالم ساعدنا آلاف الطلاب على تحقيق حلمهم في تعلم اللغة الإنجليزية في بيئات دولية معتمدة.\",\"items\":[{\"value\":\"+15,000\",\"label\":\"طلاب مسجلون في برامج إنجليزية معتمدة بالخارج\"},{\"value\":\"+50\",\"label\":\"جامعات شريكة تقدم برامج دراسة اللغة الإنجليزية بالخارج\"}]},\"certificates_meta\":{\"heading\":\"معتمدون من العديد من المؤسسات\",\"subheading\":\"نفخر بشراكاتنا مع معاهد اللغة الإنجليزية الرائدة والمنظمات التعليمية المعتمدة حول العالم.\"},\"partners\":{\"heading\":\"المعاهد الشريكة حول العالم\",\"view_all_label\":\"عرض جميع المعاهد\",\"view_all_url\":\"/language-institutes\",\"arrow_left_icon\":\"/assets/icons/arrow-left.svg\",\"arrow_right_icon\":\"/assets/icons/arrow-right.svg\"},\"summer\":{\"heading\":\"برامج الصيف\",\"cta_text\":\"عرض جميع البرامج\",\"cta_url\":\"/summer-programs\"},\"online\":{\"heading\":\"دورات اللغة الإنجليزية عبر الإنترنت\",\"cta_text\":\"عرض جميع البرامج\",\"cta_url\":\"/online-courses\"},\"reviews\":{\"heading\":\"آراء طلابنا حول دورات اللغة الإنجليزية\"},\"destinations\":{\"heading\":\"أفضل الوجهات لدراسة اللغات\",\"links\":[{\"label\":\"Language institutes in China\",\"url\":\"/language-institutes/china\"},{\"label\":\"Language institutes in Japan\",\"url\":\"/language-institutes/japan\"},{\"label\":\"Language institutes in South Korea\",\"url\":\"/language-institutes/south-korea\"},{\"label\":\"Language institutes in Malaysia\",\"url\":\"/language-institutes/malaysia\"},{\"label\":\"Language institutes in Singapore\",\"url\":\"/language-institutes/singapore\"},{\"label\":\"Language institutes in Thailand\",\"url\":\"/language-institutes/thailand\"},{\"label\":\"Language institutes in India\",\"url\":\"/language-institutes/india\"},{\"label\":\"Language institutes in Indonesia\",\"url\":\"/language-institutes/indonesia\"},{\"label\":\"Language institutes in the Philippines\",\"url\":\"/language-institutes/philippines\"},{\"label\":\"Language institutes in Vietnam\",\"url\":\"/language-institutes/vietnam\"},{\"label\":\"Language institutes in the UK\",\"url\":\"/language-institutes/uk\"},{\"label\":\"Language institutes in France\",\"url\":\"/language-institutes/france\"},{\"label\":\"Language institutes in Ireland\",\"url\":\"/language-institutes/ireland\"},{\"label\":\"Language institutes in Germany\",\"url\":\"/language-institutes/germany\"},{\"label\":\"Language institutes in the Netherlands\",\"url\":\"/language-institutes/netherlands\"},{\"label\":\"Language institutes in Canada\",\"url\":\"/language-institutes/canada\"},{\"label\":\"Language institutes in USA\",\"url\":\"/language-institutes/usa\"},{\"label\":\"Language institutes in Australia\",\"url\":\"/language-institutes/australia\"},{\"label\":\"Language institutes in South Africa\",\"url\":\"/language-institutes/south-africa\"}]},\"faq\":{\"heading\":\"الأسئلة الشائعة\",\"subheading\":\"نحن نساعدك على اتخاذ القرار بثقة. هذه هي الأسئلة الأكثر شيوعًا التي يطرحها علينا الطلاب المحتملون.\",\"cta_text\":\"ما زال لديك سؤال؟\",\"cta_url\":\"/contact-us\",\"cta_icon\":\"/assets/icons/arrow-right.svg\"},\"blogs\":{\"heading\":\"المدونة وآخر الأخبار\",\"cta_text\":\"جميع المقالات\",\"cta_url\":\"/articles\"}}', 'CourseEnglish | Home', 'English courses with live feedback, modern lessons, and clear results.', 1, 1, '2026-02-08 14:10:30', '2026-02-14 17:28:07'),
(2, 'courseenglish', 'offers', 'Offers', 'العروض', '{\"hero\":{\"badge\":\"Limited Time Offers\",\"headline\":\"Exclusive Education Deals\",\"subheadline\":\"Explore our handpicked selection of specialized courses, camps, and training programs at unbeatable prices.\"},\"sections\":[{\"title\":\"Language Courses\",\"subtitle\":\"Top-rated institutes with exclusive discounts.\",\"link\":\"/language-institutes\"},{\"title\":\"Summer Programs\",\"subtitle\":\"Unforgettable summer experiences for teens.\",\"link\":\"/summer-programs\"},{\"title\":\"Online Courses\",\"subtitle\":\"Learn from anywhere with flexible schedules.\",\"link\":\"/online-courses\"},{\"title\":\"Professional Training\",\"subtitle\":\"Boost your career with certified courses.\",\"link\":\"/training-and-professional-courses\"}]}', '{\"hero\":{\"badge\":\"عروض لفترة محدودة\",\"headline\":\"عروض تعليمية حصرية\",\"subheadline\":\"استكشف باقة من الدورات المتخصصة والبرامج الصيفية والتدريبية بأسعار لا تُنافس.\"},\"sections\":[{\"title\":\"دورات اللغة\",\"subtitle\":\"معاهد مميزة بخصومات حصرية.\",\"link\":\"/language-institutes\"},{\"title\":\"برامج الصيف\",\"subtitle\":\"تجارب صيفية لا تُنسى للمراهقين.\",\"link\":\"/summer-programs\"},{\"title\":\"الدورات الإلكترونية\",\"subtitle\":\"تعلم من أي مكان بجدول مرن.\",\"link\":\"/online-courses\"},{\"title\":\"التدريب المهني\",\"subtitle\":\"عزز مسيرتك المهنية بدورات معتمدة.\",\"link\":\"/training-and-professional-courses\"}]}', 'Special Offers | Pioneers Admissions', 'Browse exclusive offers on language courses, summer camps, online courses, and professional training.', 1, 2, '2026-02-08 14:10:30', '2026-02-08 16:25:43'),
(3, 'courseenglish', 'language-institutes', 'Language Institutes', 'معاهد اللغات', '{\"hero\":{\"heading\":\"Discover the Best Language Institutes Worldwide\",\"subheading\":\"A curated selection of the best accredited language institutes.\",\"destination_label\":\"Destination\",\"destination_placeholder\":\"Country, city, or institute\",\"course_label\":\"Course Type\",\"course_placeholder\":\"Select course type\",\"course_options\":[\"General Language\",\"Business Language\",\"Exam Preparation\"],\"duration_label\":\"Duration\",\"duration_placeholder\":\"Select duration\",\"start_label\":\"Start Date\",\"start_placeholder\":\"Select start date\",\"search_aria\":\"Search\"},\"results\":{\"count_label\":\"institutes available based on your choice\",\"step_label\":\"Step 1: Choose the right institute for you\",\"sort_label\":\"Sort by:\",\"sort_options\":[\"Most Popular\",\"Price: Low to High\",\"Price: High to Low\",\"Rating\"]},\"sidebar\":{\"sections\":[{\"title\":\"Accommodation\",\"options\":[\"With Accommodation\",\"Without Accommodation\"]},{\"title\":\"Airport Pickup\",\"options\":[\"With Pickup\",\"Without Pickup\"]},{\"title\":\"Insurance\",\"options\":[\"With Insurance\",\"Without Insurance\"]}]},\"load_more\":{\"text\":\"Load More\"}}', '{\"hero\":{\"heading\":\"اكتشف أفضل معاهد اللغات حول العالم\",\"subheading\":\"مجموعة مختارة من أفضل معاهد اللغات المعتمدة.\",\"destination_label\":\"الوجهة\",\"destination_placeholder\":\"الدولة أو المدينة أو المعهد\",\"course_label\":\"نوع الدورة\",\"course_placeholder\":\"اختر نوع الدورة\",\"course_options\":[\"لغة عامة\",\"لغة الأعمال\",\"التحضير للاختبارات\"],\"duration_label\":\"المدة\",\"duration_placeholder\":\"اختر المدة\",\"start_label\":\"تاريخ البدء\",\"start_placeholder\":\"اختر تاريخ البدء\",\"search_aria\":\"بحث\"},\"results\":{\"count_label\":\"معهدًا متاحًا بناءً على اختيارك\",\"step_label\":\"الخطوة 1: اختر المعهد المناسب لك\",\"sort_label\":\"ترتيب حسب:\",\"sort_options\":[\"الأكثر شعبية\",\"السعر: من الأقل إلى الأعلى\",\"السعر: من الأعلى إلى الأقل\",\"التقييم\"]},\"sidebar\":{\"sections\":[{\"title\":\"السكن\",\"options\":[\"مع سكن\",\"بدون سكن\"]},{\"title\":\"استقبال المطار\",\"options\":[\"مع استقبال\",\"بدون استقبال\"]},{\"title\":\"التأمين\",\"options\":[\"مع تأمين\",\"بدون تأمين\"]}]},\"load_more\":{\"text\":\"عرض المزيد\"}}', 'Language Institutes', 'Discover the best accredited language institutes worldwide.', 1, 4, '2026-02-08 14:10:30', '2026-02-09 08:53:16'),
(4, 'courseenglish', 'language-institute-detail', 'Language Institute Detail', 'تفاصيل معهد اللغة', '{\"top_nav\":{\"back_label\":\"Back to List\",\"compare_label\":\"Compare\",\"save_label\":\"Save\",\"share_label\":\"Share\",\"back_icon\":\"/assets/icons/arrow-left.svg\",\"compare_icon\":\"/assets/icons/compare.svg\",\"save_icon\":\"/assets/icons/heart-regular-black2.svg\"},\"course_step\":{\"title\":\"Step 1: Choose the Suitable Course\",\"lessons_label\":\"Lessons/week\",\"hours_label\":\"Hours\",\"age_label\":\"Age\",\"level_label\":\"Level\",\"price_suffix\":\"/ week\"},\"accommodation_step\":{\"title\":\"Choose Accommodation\",\"optional_label\":\"(Optional)\",\"no_accommodation_title\":\"No Accommodation\",\"no_accommodation_description\":\"Select this if you have arranged your own stay.\",\"price_suffix\":\"/ week\"},\"additional_options\":{\"heading\":\"Additional Options\"},\"sidebar_inquiry\":{\"title\":\"Have a Question?\",\"description\":\"Do you have questions or need more info about this course?\",\"button_text\":\"Chat via WhatsApp\"},\"booking_summary\":{\"total_label\":\"Total inclusive of all fees\",\"study_dates_label\":\"Study Dates\",\"duration_label\":\"Duration\",\"coupon_label\":\"Do you have a coupon?\",\"coupon_placeholder\":\"Coupon Code\",\"coupon_apply_text\":\"Apply\",\"course_summary_label\":\"General English (12 Weeks)\",\"accommodation_summary_label\":\"Homestay (12 Weeks)\",\"registration_fee_label\":\"Registration Fee\",\"course_discount_label\":\"Course Discount\",\"foundation_discount_label\":\"Foundation Discount\",\"total_discount_label\":\"Total Discount\",\"confirm_button_text\":\"Review & Confirm\"}}', '{\"top_nav\":{\"back_label\":\"العودة إلى القائمة\",\"compare_label\":\"مقارنة\",\"save_label\":\"حفظ\",\"share_label\":\"مشاركة\",\"back_icon\":\"/assets/icons/arrow-left.svg\",\"compare_icon\":\"/assets/icons/compare.svg\",\"save_icon\":\"/assets/icons/heart-regular-black2.svg\"},\"course_step\":{\"title\":\"الخطوة 1: اختر الدورة المناسبة\",\"lessons_label\":\"حصص/أسبوع\",\"hours_label\":\"ساعات\",\"age_label\":\"العمر\",\"level_label\":\"المستوى\",\"price_suffix\":\"/ week\"},\"accommodation_step\":{\"title\":\"اختر السكن\",\"optional_label\":\"(اختياري)\",\"no_accommodation_title\":\"بدون سكن\",\"no_accommodation_description\":\"اختر هذا الخيار إذا رتبت سكنك بنفسك.\",\"price_suffix\":\"/ week\"},\"additional_options\":{\"heading\":\"خيارات إضافية\"},\"sidebar_inquiry\":{\"title\":\"هل لديك سؤال؟\",\"description\":\"هل لديك أسئلة أو تحتاج لمزيد من المعلومات عن هذا البرنامج؟\",\"button_text\":\"تواصل عبر واتساب\"},\"booking_summary\":{\"total_label\":\"الإجمالي شامل جميع الرسوم\",\"study_dates_label\":\"تواريخ الدراسة\",\"duration_label\":\"المدة\",\"coupon_label\":\"هل لديك قسيمة خصم؟\",\"coupon_placeholder\":\"رمز القسيمة\",\"coupon_apply_text\":\"تطبيق\",\"course_summary_label\":\"General English (12 Weeks)\",\"accommodation_summary_label\":\"Homestay (12 Weeks)\",\"registration_fee_label\":\"رسوم التسجيل\",\"course_discount_label\":\"خصم الدورة\",\"foundation_discount_label\":\"خصم المؤسسة\",\"total_discount_label\":\"إجمالي الخصم\",\"confirm_button_text\":\"مراجعة وتأكيد\"}}', 'Institute Detail', 'Explore institute details, courses, accommodation, and booking summary.', 1, 5, '2026-02-08 14:10:30', '2026-02-09 10:06:12'),
(5, 'courseenglish', 'online-courses', 'Online Courses', 'الدورات عبر الإنترنت', '{\"hero\":{\"heading\":\"Master English Online from Anywhere\",\"subheading\":\"Flexible, accredited online courses designed for your success.\",\"level_label\":\"Course Level\",\"level_placeholder\":\"Select level\",\"level_options\":[\"Beginner (A1-A2)\",\"Intermediate (B1-B2)\",\"Advanced (C1-C2)\"],\"focus_label\":\"Focus Area\",\"focus_placeholder\":\"Select focus\",\"focus_options\":[\"General English\",\"Business English\",\"IELTS/TOEFL Prep\",\"English for Kids\"],\"schedule_label\":\"Schedule\",\"schedule_placeholder\":\"Select time\",\"schedule_options\":[\"Morning\",\"Afternoon\",\"Evening\",\"Weekend\"],\"start_label\":\"Start Date\",\"start_placeholder\":\"Select start date\",\"search_aria\":\"Search\"},\"results\":{\"count_label\":\"courses available based on your choice\",\"step_label\":\"Step 1: Choose the right online course for you\",\"sort_label\":\"Sort by:\",\"sort_options\":[\"Most Popular\",\"Price: Low to High\",\"Price: High to Low\",\"Rating\"]},\"load_more\":{\"text\":\"Load More\"}}', '{\"hero\":{\"heading\":\"أتقن الإنجليزية عبر الإنترنت من أي مكان\",\"subheading\":\"دورات مرنة ومعتمدة مصممة لنجاحك.\",\"level_label\":\"مستوى الدورة\",\"level_placeholder\":\"اختر المستوى\",\"level_options\":[\"مبتدئ (A1-A2)\",\"متوسط (B1-B2)\",\"متقدم (C1-C2)\"],\"focus_label\":\"مجال التركيز\",\"focus_placeholder\":\"اختر المجال\",\"focus_options\":[\"إنجليزي عام\",\"إنجليزي للأعمال\",\"تحضير IELTS/TOEFL\",\"إنجليزي للأطفال\"],\"schedule_label\":\"الجدول\",\"schedule_placeholder\":\"اختر الوقت\",\"schedule_options\":[\"صباحاً\",\"بعد الظهر\",\"مساءً\",\"عطلة نهاية الأسبوع\"],\"start_label\":\"تاريخ البدء\",\"start_placeholder\":\"اختر تاريخ البدء\",\"search_aria\":\"بحث\"},\"results\":{\"count_label\":\"دورات متاحة بناءً على اختيارك\",\"step_label\":\"الخطوة 1: اختر الدورة المناسبة عبر الإنترنت\",\"sort_label\":\"ترتيب حسب:\",\"sort_options\":[\"الأكثر شعبية\",\"السعر: من الأقل إلى الأعلى\",\"السعر: من الأعلى إلى الأقل\",\"التقييم\"]},\"load_more\":{\"text\":\"عرض المزيد\"}}', 'Online Courses', 'Browse accredited online English courses and flexible schedules.', 1, 12, '2026-02-08 14:10:30', '2026-02-09 12:20:23'),
(6, 'courseenglish', 'summer-programs', 'Summer Programs', 'برامج الصيف', '{\"hero\":{\"heading\":\"Unforgettable Summer Camps for Teens\",\"subheading\":\"Explore the world, learn English, and make lifelong friends.\",\"destination_label\":\"Destination\",\"destination_placeholder\":\"Country or city\",\"age_label\":\"Age Group\",\"age_placeholder\":\"Select age\",\"age_options\":[\"10-14 years\",\"14-17 years\",\"16-18 years\"],\"duration_label\":\"Duration\",\"duration_placeholder\":\"Select duration\",\"duration_options\":[\"1 Week\",\"2 Weeks\",\"3 Weeks\",\"4+ Weeks\"],\"start_label\":\"Start Date\",\"start_placeholder\":\"Select start date\",\"search_aria\":\"Search\"},\"results\":{\"count_label\":\"camps available based on your choice\",\"step_label\":\"Step 1: Choose the perfect summer camp\",\"sort_label\":\"Sort by:\",\"sort_options\":[\"Most Popular\",\"Price: Low to High\",\"Price: High to Low\",\"Rating\"]},\"load_more\":{\"text\":\"Load More\"}}', '{\"hero\":{\"heading\":\"مخيمات صيفية لا تُنسى للمراهقين\",\"subheading\":\"استكشف العالم وتعلم الإنجليزية وكون صداقات مدى الحياة.\",\"destination_label\":\"الوجهة\",\"destination_placeholder\":\"البلد أو المدينة\",\"age_label\":\"الفئة العمرية\",\"age_placeholder\":\"اختر العمر\",\"age_options\":[\"10-14 سنة\",\"14-17 سنة\",\"16-18 سنة\"],\"duration_label\":\"المدة\",\"duration_placeholder\":\"اختر المدة\",\"duration_options\":[\"أسبوع واحد\",\"أسبوعان\",\"3 أسابيع\",\"4 أسابيع أو أكثر\"],\"start_label\":\"تاريخ البدء\",\"start_placeholder\":\"اختر تاريخ البدء\",\"search_aria\":\"بحث\"},\"results\":{\"count_label\":\"مخيمات متاحة بناءً على اختيارك\",\"step_label\":\"الخطوة 1: اختر المخيم الصيفي المثالي\",\"sort_label\":\"ترتيب حسب:\",\"sort_options\":[\"الأكثر شعبية\",\"السعر: من الأقل إلى الأعلى\",\"السعر: من الأعلى إلى الأقل\",\"التقييم\"]},\"load_more\":{\"text\":\"عرض المزيد\"}}', 'Summer Programs', 'Explore summer camps and programs for teens.', 1, 13, '2026-02-08 14:10:30', '2026-02-09 12:29:26'),
(7, 'courseenglish', 'training-and-professional-courses', 'Training & Professional Courses', 'الدورات الاحترافية', '{\"hero\":{\"heading\":\"Advance Your Career with Professional Courses\",\"subheading\":\"Gain Accredited Certificates and Specialized Skills.\",\"subject_label\":\"Subject\",\"subject_placeholder\":\"Select subject\",\"subject_options\":[\"Management\",\"Marketing\",\"IT & Tech\",\"Finance\"],\"location_label\":\"Location\",\"location_placeholder\":\"City or Online\",\"duration_label\":\"Duration\",\"duration_placeholder\":\"Select duration\",\"duration_options\":[\"Short (1-5 days)\",\"Medium (1-4 weeks)\",\"Long (1+ month)\"],\"start_label\":\"Start Date\",\"start_placeholder\":\"Select start date\",\"search_aria\":\"Search\"},\"results\":{\"count_label\":\"professional courses available\",\"step_label\":\"Step 1: Choose the right course for your career\",\"sort_label\":\"Sort by:\",\"sort_options\":[\"Most Popular\",\"Price: Low to High\",\"Price: High to Low\",\"Rating\"]},\"load_more\":{\"text\":\"Load More\"}}', '{\"hero\":{\"heading\":\"ارتقِ بمسارك المهني عبر الدورات الاحترافية\",\"subheading\":\"احصل على شهادات معتمدة ومهارات متخصصة.\",\"subject_label\":\"المجال\",\"subject_placeholder\":\"اختر المجال\",\"subject_options\":[\"الإدارة\",\"التسويق\",\"تقنية المعلومات\",\"التمويل\"],\"location_label\":\"الموقع\",\"location_placeholder\":\"مدينة أو أونلاين\",\"duration_label\":\"المدة\",\"duration_placeholder\":\"اختر المدة\",\"duration_options\":[\"قصير (1-5 أيام)\",\"متوسط (1-4 أسابيع)\",\"طويل (أكثر من شهر)\"],\"start_label\":\"تاريخ البدء\",\"start_placeholder\":\"اختر تاريخ البدء\",\"search_aria\":\"بحث\"},\"results\":{\"count_label\":\"دورات احترافية متاحة\",\"step_label\":\"الخطوة 1: اختر الدورة المناسبة لمسارك المهني\",\"sort_label\":\"ترتيب حسب:\",\"sort_options\":[\"الأكثر شعبية\",\"السعر: من الأقل إلى الأعلى\",\"السعر: من الأعلى إلى الأقل\",\"التقييم\"]},\"load_more\":{\"text\":\"عرض المزيد\"}}', 'Training & Professional Courses', 'Explore professional courses and certifications.', 1, 14, '2026-02-08 14:10:30', '2026-02-09 12:37:39'),
(8, 'courseenglish', 'compare', 'Compare', 'المقارنة', '{\"hero\":{\"title\":\"Compare Institutes\",\"subtitle\":\"Compare features, prices, and ratings side by side.\"},\"table\":{\"criteria_label\":\"Criteria\",\"price_label\":\"Price\",\"action_button_text\":\"View Institute\"}}', '{\"hero\":{\"title\":\"مقارنة المعاهد\",\"subtitle\":\"قارن الميزات والأسعار والتقييمات جنبًا إلى جنب.\"},\"table\":{\"criteria_label\":\"المعيار\",\"price_label\":\"السعر\",\"action_button_text\":\"عرض المعهد\"}}', 'Compare Institutes', 'Compare institutes side by side to choose the best fit.', 1, 10, '2026-02-08 14:10:30', '2026-02-09 12:03:59'),
(9, 'courseenglish', 'booking', 'Booking Confirmation', 'تأكيد الحجز', '{\"headline\":\"Review and Confirm Booking\",\"subheadline\":\"Review booking details and contact info before sending request.\",\"institute\":{\"name\":\"CES School - Cork\",\"location\":\"United Kingdom, London\",\"image\":\"/assets/images/institute_london.png\",\"rating\":4.5,\"flag\":\"🇬🇧\"}}', '{\"headline\":\"Review and Confirm Booking\",\"subheadline\":\"Review booking details and contact info before sending request.\",\"institute\":{\"name\":\"CES School - Cork\",\"location\":\"United Kingdom, London\",\"image\":\"/assets/images/institute_london.png\",\"rating\":4.5,\"flag\":\"🇬🇧\"}}', 'Review and Confirm Booking', 'Review booking details and contact info before sending request.', 1, 9, '2026-02-08 14:10:30', '2026-02-08 14:10:30'),
(10, 'courseenglish', 'articles', 'Articles', 'المقالات', '{\"hero\":{\"heading\":\"Latest stories and guides\",\"subheading\":\"Browse insights, tips, and how-tos for your study journey.\"},\"empty_state\":{\"heading\":\"Articles\",\"message\":\"No posts available. Please cache data from the admin panel first.\"},\"card\":{\"read_more_label\":\"Read more\",\"category_fallback\":\"Article\"},\"sidebar\":{\"recent_title\":\"Recent posts\",\"recent_badge\":\"Latest\",\"categories_title\":\"Categories\"}}', '{\"hero\":{\"heading\":\"أحدث المقالات والأدلة\",\"subheading\":\"اطّلع على النصائح والإرشادات لرحلتك الدراسية.\"},\"empty_state\":{\"heading\":\"المقالات\",\"message\":\"لا توجد مقالات حالياً. يرجى تحديث البيانات من لوحة التحكم أولاً.\"},\"card\":{\"read_more_label\":\"اقرأ المزيد\",\"category_fallback\":\"مقال\"},\"sidebar\":{\"recent_title\":\"أحدث المقالات\",\"recent_badge\":\"الأحدث\",\"categories_title\":\"التصنيفات\"}}', 'Articles', 'Read the latest stories, tips, and study guides.', 1, 6, '2026-02-08 14:10:30', '2026-02-09 10:19:52'),
(11, 'courseenglish', 'about-us', 'About Us', 'من نحن', '{\"hero\":{\"badge\":\"Who We Are\",\"title\":\"About Pioneers\",\"description\":\"Transforming lives through international education since 2012.\"},\"director_message\":{\"image\":\"https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=800\",\"name\":\"Md Abdul Qaium\",\"role\":\"Director\",\"title\":\"Welcome to Pioneers EDU\",\"paragraphs\":[\"Dear Valued Partners, Students, and Stakeholders,\",\"Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education.\",\"Our team of highly experienced representatives provides expert guidance. This unique blend of expertise and empathy sets us apart.\",\"We are proud of our high visa success rates and the trust placed in us by students and partners.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Md Abdul Qaium\",\"position\":\"Director, Pioneers Educational Admission Consultancy Ltd\"}},\"ceo_message\":{\"image\":\"https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&w=800\",\"name\":\"Hanan Asiri\",\"role\":\"CEO\",\"title\":\"A Message from the CEO\",\"paragraphs\":[\"Dear Students, Parents, and Collaborators,\",\"As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success.\",\"At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity.\",\"Thank you for trusting us to be part of your journey.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Hanan Asiri\",\"position\":\"CEO, Pioneers Educational Admission Consultancy Ltd\"}},\"team\":{\"title\":\"Meet Our Team\",\"members\":[{\"name\":\"John Doe\",\"role\":\"Senior Consultant\",\"image\":\"https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Expert in UK and USA admissions with over 10 years of experience.\"},{\"name\":\"Jane Smith\",\"role\":\"Visa Specialist\",\"image\":\"https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Ensuring smooth visa processes for students worldwide.\"},{\"name\":\"Michael Brown\",\"role\":\"Student Counselor\",\"image\":\"https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Dedicated to finding the perfect course and university for every student.\"},{\"name\":\"Sarah Lee\",\"role\":\"Marketing Manager\",\"image\":\"https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Passionate about connecting students with educational opportunities.\"}]}}', '{\"hero\":{\"badge\":\"Who We Are\",\"title\":\"عن بايونيرز\",\"description\":\"نحوّل حياة الطلاب من خلال التعليم الدولي منذ 2012.\"},\"director_message\":{\"image\":\"https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=800\",\"name\":\"Md Abdul Qaium\",\"role\":\"Director\",\"title\":\"Welcome to Pioneers EDU\",\"paragraphs\":[\"Dear Valued Partners, Students, and Stakeholders,\",\"Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education.\",\"Our team of highly experienced representatives provides expert guidance. This unique blend of expertise and empathy sets us apart.\",\"We are proud of our high visa success rates and the trust placed in us by students and partners.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Md Abdul Qaium\",\"position\":\"Director, Pioneers Educational Admission Consultancy Ltd\"}},\"ceo_message\":{\"image\":\"https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&w=800\",\"name\":\"Hanan Asiri\",\"role\":\"CEO\",\"title\":\"A Message from the CEO\",\"paragraphs\":[\"Dear Students, Parents, and Collaborators,\",\"As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success.\",\"At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity.\",\"Thank you for trusting us to be part of your journey.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Hanan Asiri\",\"position\":\"CEO, Pioneers Educational Admission Consultancy Ltd\"}},\"team\":{\"title\":\"تعرف على فريقنا\",\"members\":[{\"name\":\"John Doe\",\"role\":\"Senior Consultant\",\"image\":\"https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Expert in UK and USA admissions with over 10 years of experience.\"},{\"name\":\"Jane Smith\",\"role\":\"Visa Specialist\",\"image\":\"https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Ensuring smooth visa processes for students worldwide.\"},{\"name\":\"Michael Brown\",\"role\":\"Student Counselor\",\"image\":\"https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Dedicated to finding the perfect course and university for every student.\"},{\"name\":\"Sarah Lee\",\"role\":\"Marketing Manager\",\"image\":\"https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=800\",\"desc\":\"Passionate about connecting students with educational opportunities.\"}]}}', 'About Pioneers', 'Welcome to Pioneers Educational Admission Consultancy.', 1, 11, '2026-02-08 14:10:30', '2026-02-08 16:34:03'),
(12, 'courseenglish', 'contact-us', 'Contact Us', 'تواصل معنا', '{\"hero\":{\"badge\":\"Contact Us\",\"title\":\"Get in touch with our team\",\"description\":\"Visit our offices worldwide or send us a message anytime.\"},\"offices\":{\"heading\":\"Our Global Offices\",\"subheading\":\"Visit us at one of our offices for a face-to-face consultation.\",\"button_text\":\"View Office Details\"},\"inquiries\":{\"eyebrow\":\"General Inquiries\",\"heading\":\"Get in Touch\",\"description\":\"We are here to help with questions about programs, admissions, and services.\"},\"contact_cards\":{\"email_title\":\"Email Us\",\"email_description\":\"For general questions and support:\",\"phone_title\":\"Call Us\",\"phone_description\":\"Mon-Fri from 9am to 6pm:\"},\"social\":{\"heading\":\"Follow Us\"},\"form\":{\"title\":\"Send us a Message\"}}', '{\"hero\":{\"badge\":\"تواصل معنا\",\"title\":\"تواصل مع فريقنا\",\"description\":\"قم بزيارة مكاتبنا حول العالم أو أرسل لنا رسالة في أي وقت.\"},\"offices\":{\"heading\":\"مكاتبنا حول العالم\",\"subheading\":\"زر أحد مكاتبنا للحصول على استشارة مباشرة.\",\"button_text\":\"عرض تفاصيل المكتب\"},\"inquiries\":{\"eyebrow\":\"استفسارات عامة\",\"heading\":\"تواصل معنا\",\"description\":\"نحن هنا لمساعدتك في البرامج والقبول والخدمات.\"},\"contact_cards\":{\"email_title\":\"راسلنا عبر البريد\",\"email_description\":\"للاستفسارات العامة والدعم:\",\"phone_title\":\"اتصل بنا\",\"phone_description\":\"من الإثنين إلى الجمعة من 9 صباحاً حتى 6 مساءً:\"},\"social\":{\"heading\":\"تابعنا\"},\"form\":{\"title\":\"أرسل لنا رسالة\"}}', 'Contact Us | Pioneers Admissions', 'Get in touch with our team. Visit our offices or send us a message.', 1, 7, '2026-02-08 14:10:30', '2026-02-09 10:34:38'),
(13, 'courseenglish', 'university-admissions', 'University Admissions', 'قبول الجامعات', '{\"hero\":{\"badge\":\"Admissions Portal\",\"title\":\"Find Your Future\",\"description\":\"Explore thousands of world-class universities and specialized courses. take the first step towards your global education journey today.\"},\"cards\":[{\"title\":\"Search Universities\",\"description\":\"Browse top-ranked institutions across the globe and find the perfect campus for you.\",\"button_text\":\"Explore Universities\",\"url\":\"https://pioneersedu.com/search/universities\",\"image\":\"https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=800\",\"icon\":\"faUniversity\"},{\"title\":\"Search Courses\",\"description\":\"Find the right degree or short course. Filter by subject, level, and location.\",\"button_text\":\"Find Your Course\",\"url\":\"https://pioneersedu.com/search/courses\",\"image\":\"https://images.pexels.com/photos/1205651/pexels-photo-1205651.jpeg?auto=compress&cs=tinysrgb&w=800\",\"icon\":\"faGraduationCap\"}],\"stats\":[{\"value\":\"500+\",\"label\":\"Partner Universities\"},{\"value\":\"10k+\",\"label\":\"Courses Available\"},{\"value\":\"50+\",\"label\":\"Destinations\"},{\"value\":\"100%\",\"label\":\"Free Consultation\"}]}', '{\"hero\":{\"badge\":\"بوابة القبول\",\"title\":\"اصنع مستقبلك\",\"description\":\"استكشف آلاف الجامعات العالمية والدورات المتخصصة. ابدأ رحلتك التعليمية العالمية اليوم.\"},\"cards\":[{\"title\":\"ابحث عن الجامعات\",\"description\":\"تصفح أفضل الجامعات حول العالم واختر الحرم المناسب لك.\",\"button_text\":\"استكشف الجامعات\",\"url\":\"https://pioneersedu.com/search/universities\",\"image\":\"https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=800\",\"icon\":\"faUniversity\"},{\"title\":\"ابحث عن الدورات\",\"description\":\"اختر الدرجة المناسبة أو الدورة القصيرة حسب التخصص والمستوى والموقع.\",\"button_text\":\"ابحث عن دورة\",\"url\":\"https://pioneersedu.com/search/courses\",\"image\":\"https://images.pexels.com/photos/1205651/pexels-photo-1205651.jpeg?auto=compress&cs=tinysrgb&w=800\",\"icon\":\"faGraduationCap\"}],\"stats\":[{\"value\":\"500+\",\"label\":\"جامعات شريكة\"},{\"value\":\"10k+\",\"label\":\"دورات متاحة\"},{\"value\":\"50+\",\"label\":\"وجهات\"},{\"value\":\"100%\",\"label\":\"استشارة مجانية\"}]}', 'University Admissions | Pioneers', 'Find your dream university or course. Search through thousands of programs worldwide.', 1, 9, '2026-02-08 14:10:30', '2026-02-09 11:55:53'),
(14, 'courseenglish', 'travel-and-tourism', 'Travel & Tourism', 'السفر والسياحة', '{\"hero\":{\"badge\":\"Discover the World\",\"title\":\"Plan Your Dream Journey\",\"description\":\"From flight bookings to visa assistance, we handle all the details so you can focus on making memories. Start your adventure today with Pioneers Travel.\",\"background_image\":\"https://images.pexels.com/photos/1271619/pexels-photo-1271619.jpeg?auto=compress&cs=tinysrgb&w=1600\",\"primary_cta_text\":\"Start Planning\",\"primary_cta_url\":\"#inquiry-form\",\"secondary_cta_text\":\"View Packages\",\"secondary_cta_url\":\"#destinations\"},\"cta_card\":{\"heading\":\"Ready to plan your trip?\",\"subheading\":\"Get a free consultation with our travel experts today.\",\"whatsapp_text\":\"WhatsApp\",\"whatsapp_url\":\"https://wa.me/1234567890\",\"call_text\":\"Call Us\",\"call_url\":\"tel:+1234567890\",\"inquire_text\":\"Inquire Now\",\"inquire_url\":\"#inquiry-form\"},\"features\":{\"items\":[{\"icon\":\"faHeadset\",\"title\":\"24/7 Support\",\"description\":\"Our team is available round the clock to assist you during your trip.\"},{\"icon\":\"faTags\",\"title\":\"Best Price Guarantee\",\"description\":\"We offer competitive prices and exclusive deals for all destinations.\"},{\"icon\":\"faGlobeAmericas\",\"title\":\"Global Coverage\",\"description\":\"Destinations across 6 continents with local partner support.\"},{\"icon\":\"faUserShield\",\"title\":\"100% Secure\",\"description\":\"Your bookings and payments are secure with our trusted platform.\"}]},\"destinations\":{\"eyebrow\":\"Top Destinations\",\"heading\":\"Trending Holiday Spots\",\"view_all_text\":\"View All Destinations\",\"view_all_url\":\"#\",\"items\":[{\"name\":\"London, UK\",\"image\":\"https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £599\",\"tag\":\"Popular\"},{\"name\":\"Paris, France\",\"image\":\"https://images.pexels.com/photos/1850619/pexels-photo-1850619.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £450\",\"tag\":\"Romance\"},{\"name\":\"Dubai, UAE\",\"image\":\"https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £799\",\"tag\":\"Luxury\"},{\"name\":\"Istanbul, Turkey\",\"image\":\"https://images.pexels.com/photos/3566139/pexels-photo-3566139.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £399\",\"tag\":\"Culture\"}]},\"services\":{\"eyebrow\":\"Our Services\",\"heading\":\"Everything You Need for a Perfect Trip\",\"description\":\"We offer a comprehensive range of travel services to ensure your experience is seamless, memorable, and hassle-free.\",\"items\":[{\"icon\":\"faPlaneDeparture\",\"title\":\"Flight Bookings\",\"description\":\"Best deals on domestic and international flights. We ensure a smooth journey from takeoff to landing.\"},{\"icon\":\"faHotel\",\"title\":\"Hotel Reservations\",\"description\":\"From luxury resorts to budget-friendly stays, find the perfect accommodation for your trip.\"},{\"icon\":\"faPassport\",\"title\":\"Visa Assistance\",\"description\":\"Expert guidance for tourist, student, and business visas. We handle the paperwork, you pack the bags.\"},{\"icon\":\"faMapMarkedAlt\",\"title\":\"Tour Packages\",\"description\":\"Curated holiday packages for families, couples, and solo travelers to the world\'s best destinations.\"},{\"icon\":\"faUmbrellaBeach\",\"title\":\"Beach Holidays\",\"description\":\"Relax on the most pristine beaches with our exclusive island getaway packages.\"},{\"icon\":\"faShip\",\"title\":\"Cruises\",\"description\":\"Set sail on a luxury cruise and explore multiple destinations with premium onboard amenities.\"}]},\"inquiry\":{\"title\":\"Plan Your Trip\",\"description\":\"Fill out the form and our travel experts will contact you to arrange the perfect trip tailored to your needs.\",\"bullets\":[\"Direct flight bookings worldwide\",\"Premium hotel reservations\",\"Hassle-free visa assistance\"],\"whatsapp_text\":\"Chat on WhatsApp\",\"whatsapp_url\":\"https://wa.me/1234567890\",\"form_title\":\"Travel Inquiry\",\"form_name_label\":\"Full Name\",\"form_name_placeholder\":\"Your Name\",\"form_phone_label\":\"Phone Number\",\"form_phone_placeholder\":\"+1 234 567 890\",\"form_destination_label\":\"Destination\",\"form_destination_placeholder\":\"e.g. London, Dubai, Paris\",\"form_date_label\":\"Travel Date (Optional)\",\"form_message_label\":\"Requirements / Message\",\"form_message_placeholder\":\"Tell us more about your trip...\",\"form_submit_text\":\"Send Request\"}}', '{\"hero\":{\"badge\":\"اكتشف العالم\",\"title\":\"خطط لرحلتك القادمة\",\"description\":\"من حجوزات الطيران إلى التأشيرات، نتولى التفاصيل لتستمتع برحلتك. ابدأ مغامرتك اليوم مع بايونيرز ترافل.\",\"background_image\":\"https://images.pexels.com/photos/1271619/pexels-photo-1271619.jpeg?auto=compress&cs=tinysrgb&w=1600\",\"primary_cta_text\":\"ابدأ التخطيط\",\"primary_cta_url\":\"#inquiry-form\",\"secondary_cta_text\":\"عرض الباقات\",\"secondary_cta_url\":\"#destinations\"},\"cta_card\":{\"heading\":\"جاهز لتخطيط رحلتك؟\",\"subheading\":\"احصل على استشارة مجانية مع خبرائنا اليوم.\",\"whatsapp_text\":\"واتساب\",\"whatsapp_url\":\"https://wa.me/1234567890\",\"call_text\":\"اتصل بنا\",\"call_url\":\"tel:+1234567890\",\"inquire_text\":\"استفسر الآن\",\"inquire_url\":\"#inquiry-form\"},\"features\":{\"items\":[{\"icon\":\"faHeadset\",\"title\":\"دعم 24/7\",\"description\":\"فريقنا متاح على مدار الساعة لمساعدتك أثناء الرحلة.\"},{\"icon\":\"faTags\",\"title\":\"أفضل سعر مضمون\",\"description\":\"نقدم أسعارًا تنافسية وعروضًا حصرية لجميع الوجهات.\"},{\"icon\":\"faGlobeAmericas\",\"title\":\"تغطية عالمية\",\"description\":\"وجهات عبر 6 قارات مع شركاء محليين.\"},{\"icon\":\"faUserShield\",\"title\":\"أمان 100%\",\"description\":\"حجوزاتك ومدفوعاتك آمنة عبر منصتنا الموثوقة.\"}]},\"destinations\":{\"eyebrow\":\"أفضل الوجهات\",\"heading\":\"وجهات سياحية رائجة\",\"view_all_text\":\"عرض جميع الوجهات\",\"view_all_url\":\"#\",\"items\":[{\"name\":\"London, UK\",\"image\":\"https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £599\",\"tag\":\"Popular\"},{\"name\":\"Paris, France\",\"image\":\"https://images.pexels.com/photos/1850619/pexels-photo-1850619.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £450\",\"tag\":\"Romance\"},{\"name\":\"Dubai, UAE\",\"image\":\"https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £799\",\"tag\":\"Luxury\"},{\"name\":\"Istanbul, Turkey\",\"image\":\"https://images.pexels.com/photos/3566139/pexels-photo-3566139.jpeg?auto=compress&cs=tinysrgb&w=800\",\"price\":\"From £399\",\"tag\":\"Culture\"}]},\"services\":{\"eyebrow\":\"خدماتنا\",\"heading\":\"كل ما تحتاجه لرحلة مثالية\",\"description\":\"نقدم مجموعة شاملة من خدمات السفر لضمان تجربة سلسة ومميزة.\",\"items\":[{\"icon\":\"faPlaneDeparture\",\"title\":\"Flight Bookings\",\"description\":\"Best deals on domestic and international flights. We ensure a smooth journey from takeoff to landing.\"},{\"icon\":\"faHotel\",\"title\":\"Hotel Reservations\",\"description\":\"From luxury resorts to budget-friendly stays, find the perfect accommodation for your trip.\"},{\"icon\":\"faPassport\",\"title\":\"Visa Assistance\",\"description\":\"Expert guidance for tourist, student, and business visas. We handle the paperwork, you pack the bags.\"},{\"icon\":\"faMapMarkedAlt\",\"title\":\"Tour Packages\",\"description\":\"Curated holiday packages for families, couples, and solo travelers to the world\'s best destinations.\"},{\"icon\":\"faUmbrellaBeach\",\"title\":\"Beach Holidays\",\"description\":\"Relax on the most pristine beaches with our exclusive island getaway packages.\"},{\"icon\":\"faShip\",\"title\":\"Cruises\",\"description\":\"Set sail on a luxury cruise and explore multiple destinations with premium onboard amenities.\"}]},\"inquiry\":{\"title\":\"خطط لرحلتك\",\"description\":\"املأ النموذج وسيتواصل معك خبراؤنا لترتيب الرحلة المثالية لك.\",\"bullets\":[\"حجوزات طيران مباشرة حول العالم\",\"حجوزات فنادق مميزة\",\"مساعدة في التأشيرات بدون تعقيدات\"],\"whatsapp_text\":\"تواصل عبر واتساب\",\"whatsapp_url\":\"https://wa.me/1234567890\",\"form_title\":\"طلب سفر\",\"form_name_label\":\"الاسم الكامل\",\"form_name_placeholder\":\"اسمك\",\"form_phone_label\":\"رقم الهاتف\",\"form_phone_placeholder\":\"+1 234 567 890\",\"form_destination_label\":\"الوجهة\",\"form_destination_placeholder\":\"مثل لندن، دبي، باريس\",\"form_date_label\":\"تاريخ السفر (اختياري)\",\"form_message_label\":\"المتطلبات / الرسالة\",\"form_message_placeholder\":\"أخبرنا المزيد عن رحلتك...\",\"form_submit_text\":\"إرسال الطلب\"}}', 'Travel & Tourism', 'Plan your next trip with our travel experts and curated packages.', 1, 8, '2026-02-08 14:10:30', '2026-02-09 11:45:22'),
(15, 'courseenglish', 'wishlist', 'Wishlist', 'قائمة الرغبات', '{\"hero\":{\"title\":\"Your Wishlist\",\"subtitle\":\"View and manage your saved courses and institutes.\"},\"card\":{\"type_suffix\":\"/ week\",\"view_details_text\":\"View Details\"},\"empty_state\":{\"title\":\"Your wishlist is empty\",\"subtitle\":\"Start exploring to find your perfect course.\",\"cta_text\":\"Explore Courses\",\"cta_url\":\"/\"}}', '{\"hero\":{\"title\":\"قائمة رغباتك\",\"subtitle\":\"اعرض وأدر الدورات والمعاهد التي حفظتها.\"},\"card\":{\"type_suffix\":\"/ أسبوع\",\"view_details_text\":\"عرض التفاصيل\"},\"empty_state\":{\"title\":\"قائمة رغباتك فارغة\",\"subtitle\":\"ابدأ الاستكشاف للعثور على الدورة المناسبة لك.\",\"cta_text\":\"استكشف الدورات\",\"cta_url\":\"/\"}}', 'Wishlist', 'View and manage your saved courses and institutes.', 1, 11, '2026-02-08 14:10:30', '2026-02-09 12:11:09');
INSERT INTO `cms_pages` (`id`, `app`, `slug`, `title`, `ar_title`, `content`, `ar_content`, `meta_title`, `meta_description`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(16, 'university', 'about', 'About Pioneers', NULL, '{\"hero\":{\"badge\":\"Who We Are\",\"title\":\"About Pioneers\",\"description\":\"Transforming lives through international education since 2012.\",\"image\":\"\"},\"director_message\":{\"image\":\"https:\\/\\/placehold.co\\/600x800?text=Director\",\"name\":\"Md Abdul Qaium\",\"role\":\"Director\",\"title\":\"Welcome to Pioneers EDU\",\"paragraphs\":[\"Dear Valued Partners, Students, and Stakeholders,\",\"Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education. With offices across multiple countries, we guide students to prestigious institutions worldwide, ensuring their success and satisfaction.\",\"Our team of highly experienced representatives, educated at renowned universities, provides expert, culturally sensitive guidance. This unique blend of expertise and empathy sets us apart.\",\"We are proud of our high visa success rates and the trust placed in us by students and partners. Our commitment to innovation and personalized support continues to drive our growth and impact.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Md Abdul Qaium\",\"position\":\"Director, Pioneers Educational Admission Consultancy Ltd\"}},\"ceo_message\":{\"image\":\"https:\\/\\/placehold.co\\/600x800?text=CEO\",\"name\":\"Hanan Asiri\",\"role\":\"CEO\",\"title\":\"A Message from the CEO\",\"paragraphs\":[\"Dear Students, Parents, and Collaborators,\",\"As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success. Our mission is to empower students by providing access to world-class education and resources that shape their futures.\",\"At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity. With our global reach and experienced team, we have successfully guided thousands of students toward achieving their academic dreams.\",\"Thank you for trusting us to be part of your journey. Together, we will continue to break barriers and build brighter futures for generations to come.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Hanan Asiri\",\"position\":\"CEO, Pioneers Educational Admission Consultancy Ltd\"}},\"team\":{\"badge\":\"Our Experts\",\"title\":\"Meet Our Team\",\"members\":[{\"name\":\"Tasnim Zarin\",\"role\":\"Admission Consultation Leader\",\"desc\":\"Guiding and managing admissions to ensure a smooth enrollment process.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Tasnim\"},{\"name\":\"Umme Habiba\",\"role\":\"Sales Manager\",\"desc\":\"Driving sales growth and leading teams to achieve business targets.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Umme\"},{\"name\":\"Nazrul Islam\",\"role\":\"System Administrator\",\"desc\":\"Managing and securing IT systems to ensure seamless operations.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Nazrul\"},{\"name\":\"Shohidul Hasan Mitu\",\"role\":\"BD Office Manager\",\"desc\":\"Overseeing operations and ensuring efficiency in BD office management.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Shohidul\"}]}}', '{\"hero\":{\"badge\":\"Who We Are\",\"title\":\"About Pioneers\",\"description\":\"Transforming lives through international education since 2012.\",\"image\":\"\"},\"director_message\":{\"image\":\"https:\\/\\/placehold.co\\/600x800?text=Director\",\"name\":\"Md Abdul Qaium\",\"role\":\"Director\",\"title\":\"Welcome to Pioneers EDU\",\"paragraphs\":[\"Dear Valued Partners, Students, and Stakeholders,\",\"Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education. With offices across multiple countries, we guide students to prestigious institutions worldwide, ensuring their success and satisfaction.\",\"Our team of highly experienced representatives, educated at renowned universities, provides expert, culturally sensitive guidance. This unique blend of expertise and empathy sets us apart.\",\"We are proud of our high visa success rates and the trust placed in us by students and partners. Our commitment to innovation and personalized support continues to drive our growth and impact.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Md Abdul Qaium\",\"position\":\"Director, Pioneers Educational Admission Consultancy Ltd\"}},\"ceo_message\":{\"image\":\"https:\\/\\/placehold.co\\/600x800?text=CEO\",\"name\":\"Hanan Asiri\",\"role\":\"CEO\",\"title\":\"A Message from the CEO\",\"paragraphs\":[\"Dear Students, Parents, and Collaborators,\",\"As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success. Our mission is to empower students by providing access to world-class education and resources that shape their futures.\",\"At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity. With our global reach and experienced team, we have successfully guided thousands of students toward achieving their academic dreams.\",\"Thank you for trusting us to be part of your journey. Together, we will continue to break barriers and build brighter futures for generations to come.\"],\"closing\":{\"text\":\"Warm regards,\",\"name\":\"Hanan Asiri\",\"position\":\"CEO, Pioneers Educational Admission Consultancy Ltd\"}},\"team\":{\"badge\":\"Our Experts\",\"title\":\"Meet Our Team\",\"members\":[{\"name\":\"Tasnim Zarin\",\"role\":\"Admission Consultation Leader\",\"desc\":\"Guiding and managing admissions to ensure a smooth enrollment process.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Tasnim\"},{\"name\":\"Umme Habiba\",\"role\":\"Sales Manager\",\"desc\":\"Driving sales growth and leading teams to achieve business targets.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Umme\"},{\"name\":\"Nazrul Islam\",\"role\":\"System Administrator\",\"desc\":\"Managing and securing IT systems to ensure seamless operations.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Nazrul\"},{\"name\":\"Shohidul Hasan Mitu\",\"role\":\"BD Office Manager\",\"desc\":\"Overseeing operations and ensuring efficiency in BD office management.\",\"image\":\"https:\\/\\/placehold.co\\/400x500?text=Shohidul\"}]}}', NULL, NULL, 1, 0, '2026-02-11 06:00:37', '2026-02-11 06:41:14'),
(17, 'university', 'contact', 'Contact Us', NULL, '{\"hero\":{\"badge\":\"We\'re Here for You\",\"title\":\"Contact Us\",\"description\":\"Whether you have a question about universities, visas, or just want to say hello, we\'re ready to answer all your questions.\"},\"contact_info\":{\"title\":\"Get in Touch\",\"description\":\"Can\'t make it to an office? No problem. Fill out the form or reach out to us directly through our general channels.\",\"items\":[{\"title\":\"Email\",\"value\":\"info@pioneers.edu.sa\",\"icon\":\"faEnvelope\"},{\"title\":\"Phone\",\"value\":\"+966 50 123 4567\",\"icon\":\"faPhone\"},{\"title\":\"Facebook\",\"value\":\"#\",\"icon\":\"faFacebook\"},{\"title\":\"Instagram\",\"value\":\"#\",\"icon\":\"faInstagram\"}]},\"offices\":{\"title\":\"Our Offices\",\"description\":\"Visit our offices in major cities worldwide.\",\"items\":[{\"city\":\"London\",\"address\":\"123 Oxford Street, London, W1D 1LP\",\"phone\":\"+44 20 7123 4567\",\"email\":\"london@pioneers.edu\"},{\"city\":\"Dubai\",\"address\":\"Office 101, Business Bay, Dubai\",\"phone\":\"+971 4 123 4567\",\"email\":\"dubai@pioneers.edu\"},{\"city\":\"New Delhi\",\"address\":\"Connaught Place, New Delhi\",\"phone\":\"+91 11 1234 5678\",\"email\":\"delhi@pioneers.edu\"},{\"city\":\"New York\",\"address\":\"5th Avenue, New York, NY\",\"phone\":\"+1 212 123 4567\",\"email\":\"ny@pioneers.edu\"}]}}', '{\"hero\":{\"badge\":\"We\'re Here for You\",\"title\":\"Contact Us\",\"description\":\"Whether you have a question about universities, visas, or just want to say hello, we\'re ready to answer all your questions.\"},\"contact_info\":{\"title\":\"Get in Touch\",\"description\":\"Can\'t make it to an office? No problem. Fill out the form or reach out to us directly through our general channels.\",\"items\":[{\"title\":\"Email\",\"value\":\"info@pioneers.edu.sa\",\"icon\":\"faEnvelope\"},{\"title\":\"Phone\",\"value\":\"+966 50 123 4567\",\"icon\":\"faPhone\"},{\"title\":\"Facebook\",\"value\":\"#\",\"icon\":\"faFacebook\"},{\"title\":\"Instagram\",\"value\":\"#\",\"icon\":\"faInstagram\"}]},\"offices\":{\"title\":\"Our Offices\",\"description\":\"Visit our offices in major cities worldwide.\",\"items\":[{\"city\":\"London\",\"address\":\"123 Oxford Street, London, W1D 1LP\",\"phone\":\"+44 20 7123 4567\",\"email\":\"london@pioneers.edu\"},{\"city\":\"Dubai\",\"address\":\"Office 101, Business Bay, Dubai\",\"phone\":\"+971 4 123 4567\",\"email\":\"dubai@pioneers.edu\"},{\"city\":\"New Delhi\",\"address\":\"Connaught Place, New Delhi\",\"phone\":\"+91 11 1234 5678\",\"email\":\"delhi@pioneers.edu\"},{\"city\":\"New York\",\"address\":\"5th Avenue, New York, NY\",\"phone\":\"+1 212 123 4567\",\"email\":\"ny@pioneers.edu\"}]}}', NULL, NULL, 1, 0, '2026-02-11 06:00:37', '2026-02-11 06:41:14'),
(18, 'university', 'services', 'Our Services', NULL, '{\"hero\":{\"badge\":\"World-Class Support\",\"title\":\"Comprehensive Services for Your Global Journey.\",\"description\":\"From your first counseling session to your first day on campus, we provide the tools, guidance, and support you need to succeed.\",\"image\":\"\"},\"what_we_offer\":{\"title\":\"What We Offer\",\"description\":\"Tailored solutions designed to make your study abroad experience seamless and stress-free.\",\"items\":[{\"title\":\"University Admissions\",\"description\":\"Expert guidance on selecting courses and universities that align with your career goals. We handle the entire application process.\",\"icon\":\"faUniversity\",\"link\":\"\\/services\\/application\"},{\"title\":\"Visa Assistance\",\"description\":\"Comprehensive support for student visa applications, including document checklists, interview preparation, and filing.\",\"icon\":\"faPassport\",\"link\":\"\\/services\\/visa\"},{\"title\":\"Accommodation\",\"description\":\"Find your home away from home. We help you book safe and affordable student housing near your university.\",\"icon\":\"faHome\",\"link\":\"\\/services\\/accommodation\"},{\"title\":\"Scholarships\",\"description\":\"Discover funding opportunities. We track thousands of scholarships to help you finance your education.\",\"icon\":\"faGraduationCap\",\"link\":\"\\/scholarships\"},{\"title\":\"Partner Network\",\"description\":\"For agents and institutions. Join our global network to expand your reach and help more students succeed.\",\"icon\":\"faHandshake\",\"link\":\"\\/services\\/agents\"},{\"title\":\"Student Guides\",\"description\":\"Essential resources, checklists, and how-to guides for every step of your study abroad journey.\",\"icon\":\"faBookOpen\",\"link\":\"\\/services\\/guides\"}]},\"our_process\":{\"title\":\"Simple Steps to Success\",\"description\":\"We\'ve simplified the complex study abroad process into a clear, manageable roadmap. Our experts are with you at every milestone.\",\"steps\":[{\"number\":\"01\",\"title\":\"Profile Evaluation\",\"description\":\"We analyze your academic background and career goals.\"},{\"number\":\"02\",\"title\":\"University Selection\",\"description\":\"Curated list of universities that match your profile.\"},{\"number\":\"03\",\"title\":\"Application & Visa\",\"description\":\"End-to-end support with documentation and filing.\"},{\"number\":\"04\",\"title\":\"Pre-Departure\",\"description\":\"Accommodation, flights, and briefing for your new life.\"}]},\"partner_section\":{\"badge\":\"For Partners\",\"title\":\"Grow with Pioneers Admissions\",\"description\":\"Are you an education agent or institution? Join our global network to access exclusive resources, streamlined processing, and dedicated support to help your students succeed.\",\"button_text\":\"Become a Partner\",\"button_link\":\"\\/services\\/agents\",\"secondary_button_text\":\"Contact Our B2B Team\",\"secondary_button_link\":\"\\/contact\"},\"cta\":{\"title\":\"Ready to start your journey?\",\"description\":\"Book a free consultation with our experts today and take the first step towards your global education.\",\"button_text\":\"Get Started Now\",\"button_link\":\"\\/consultation\"}}', '{\"hero\":{\"badge\":\"World-Class Support\",\"title\":\"Comprehensive Services for Your Global Journey.\",\"description\":\"From your first counseling session to your first day on campus, we provide the tools, guidance, and support you need to succeed.\",\"image\":\"\"},\"what_we_offer\":{\"title\":\"What We Offer\",\"description\":\"Tailored solutions designed to make your study abroad experience seamless and stress-free.\",\"items\":[{\"title\":\"University Admissions\",\"description\":\"Expert guidance on selecting courses and universities that align with your career goals. We handle the entire application process.\",\"icon\":\"faUniversity\",\"link\":\"\\/services\\/application\"},{\"title\":\"Visa Assistance\",\"description\":\"Comprehensive support for student visa applications, including document checklists, interview preparation, and filing.\",\"icon\":\"faPassport\",\"link\":\"\\/services\\/visa\"},{\"title\":\"Accommodation\",\"description\":\"Find your home away from home. We help you book safe and affordable student housing near your university.\",\"icon\":\"faHome\",\"link\":\"\\/services\\/accommodation\"},{\"title\":\"Scholarships\",\"description\":\"Discover funding opportunities. We track thousands of scholarships to help you finance your education.\",\"icon\":\"faGraduationCap\",\"link\":\"\\/scholarships\"},{\"title\":\"Partner Network\",\"description\":\"For agents and institutions. Join our global network to expand your reach and help more students succeed.\",\"icon\":\"faHandshake\",\"link\":\"\\/services\\/agents\"},{\"title\":\"Student Guides\",\"description\":\"Essential resources, checklists, and how-to guides for every step of your study abroad journey.\",\"icon\":\"faBookOpen\",\"link\":\"\\/services\\/guides\"}]},\"our_process\":{\"title\":\"Simple Steps to Success\",\"description\":\"We\'ve simplified the complex study abroad process into a clear, manageable roadmap. Our experts are with you at every milestone.\",\"steps\":[{\"number\":\"01\",\"title\":\"Profile Evaluation\",\"description\":\"We analyze your academic background and career goals.\"},{\"number\":\"02\",\"title\":\"University Selection\",\"description\":\"Curated list of universities that match your profile.\"},{\"number\":\"03\",\"title\":\"Application & Visa\",\"description\":\"End-to-end support with documentation and filing.\"},{\"number\":\"04\",\"title\":\"Pre-Departure\",\"description\":\"Accommodation, flights, and briefing for your new life.\"}]},\"partner_section\":{\"badge\":\"For Partners\",\"title\":\"Grow with Pioneers Admissions\",\"description\":\"Are you an education agent or institution? Join our global network to access exclusive resources, streamlined processing, and dedicated support to help your students succeed.\",\"button_text\":\"Become a Partner\",\"button_link\":\"\\/services\\/agents\",\"secondary_button_text\":\"Contact Our B2B Team\",\"secondary_button_link\":\"\\/contact\"},\"cta\":{\"title\":\"Ready to start your journey?\",\"description\":\"Book a free consultation with our experts today and take the first step towards your global education.\",\"button_text\":\"Get Started Now\",\"button_link\":\"\\/consultation\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(19, 'university', 'visa-support', 'Visa Support', NULL, '{\"hero\":{\"badge\":\"Visa Support Services\",\"title\":\"Secure Your Student Visa\",\"description\":\"Expert guidance for UK, USA, Canada, and Australia student visas. We minimize the risk of rejection with our proven methodology.\"},\"services_section\":{\"title\":\"Comprehensive Visa Support\",\"subtitle\":\"Detailed Assistance\",\"description\":\"From document checklist to interview preparation, we cover every aspect of your application.\",\"items\":[{\"title\":\"Document Verification\",\"description\":\"Reviewing your financial proofs, academic records, and sponsorship letters to meet embassy standards.\",\"icon\":\"faCheckDouble\"},{\"title\":\"Mock Interviews\",\"description\":\"One-on-one sessions simulating real visa interviews to boost your confidence and readiness.\",\"icon\":\"faUserTie\"},{\"title\":\"Application Strategy\",\"description\":\"Structuring your application to highlight your strong ties to your home country and genuine intent to study.\",\"icon\":\"faScaleBalanced\"},{\"title\":\"Financial Guidance\",\"description\":\"Expert advice on presenting funds, sponsorships, and scholarships correctly.\",\"icon\":\"faFileShield\"},{\"title\":\"Slot Booking\",\"description\":\"Assistance with booking biometric and interview slots at the earliest availability.\",\"icon\":\"faTimeline\"},{\"title\":\"Post-Visa Support\",\"description\":\"Pre-departure briefings and guidance on travel insurance and accommodation.\",\"icon\":\"faPlaneDeparture\"}]},\"process_section\":{\"title\":\"Your Roadmap to Approval\",\"subtitle\":\"Step-by-Step\",\"description\":\"A clear, structured timeline to ensure zero errors and maximum preparedness.\",\"steps\":[{\"number\":\"01\",\"title\":\"Consultation\",\"description\":\"We assess your profile and funding to determine the best visa strategy.\"},{\"number\":\"02\",\"title\":\"Documentation\",\"description\":\"Collecting and organizing every required document flawlessly.\"},{\"number\":\"03\",\"title\":\"Application\",\"description\":\"Filling out visa forms (DS-160, etc.) with precision.\"},{\"number\":\"04\",\"title\":\"Interview Prep\",\"description\":\"Intensive training for your embassy interview.\"}]},\"cta_section\":{\"title\":\"Don\'t Risk Your Visa Application\",\"description\":\"Get it right the first time with our expert guidance. Book a consultation today.\",\"button_text\":\"Book Visa Consultation\",\"button_link\":\"\\/apply-now\"}}', '{\"hero\":{\"badge\":\"Visa Support Services\",\"title\":\"Secure Your Student Visa\",\"description\":\"Expert guidance for UK, USA, Canada, and Australia student visas. We minimize the risk of rejection with our proven methodology.\"},\"services_section\":{\"title\":\"Comprehensive Visa Support\",\"subtitle\":\"Detailed Assistance\",\"description\":\"From document checklist to interview preparation, we cover every aspect of your application.\",\"items\":[{\"title\":\"Document Verification\",\"description\":\"Reviewing your financial proofs, academic records, and sponsorship letters to meet embassy standards.\",\"icon\":\"faCheckDouble\"},{\"title\":\"Mock Interviews\",\"description\":\"One-on-one sessions simulating real visa interviews to boost your confidence and readiness.\",\"icon\":\"faUserTie\"},{\"title\":\"Application Strategy\",\"description\":\"Structuring your application to highlight your strong ties to your home country and genuine intent to study.\",\"icon\":\"faScaleBalanced\"},{\"title\":\"Financial Guidance\",\"description\":\"Expert advice on presenting funds, sponsorships, and scholarships correctly.\",\"icon\":\"faFileShield\"},{\"title\":\"Slot Booking\",\"description\":\"Assistance with booking biometric and interview slots at the earliest availability.\",\"icon\":\"faTimeline\"},{\"title\":\"Post-Visa Support\",\"description\":\"Pre-departure briefings and guidance on travel insurance and accommodation.\",\"icon\":\"faPlaneDeparture\"}]},\"process_section\":{\"title\":\"Your Roadmap to Approval\",\"subtitle\":\"Step-by-Step\",\"description\":\"A clear, structured timeline to ensure zero errors and maximum preparedness.\",\"steps\":[{\"number\":\"01\",\"title\":\"Consultation\",\"description\":\"We assess your profile and funding to determine the best visa strategy.\"},{\"number\":\"02\",\"title\":\"Documentation\",\"description\":\"Collecting and organizing every required document flawlessly.\"},{\"number\":\"03\",\"title\":\"Application\",\"description\":\"Filling out visa forms (DS-160, etc.) with precision.\"},{\"number\":\"04\",\"title\":\"Interview Prep\",\"description\":\"Intensive training for your embassy interview.\"}]},\"cta_section\":{\"title\":\"Don\'t Risk Your Visa Application\",\"description\":\"Get it right the first time with our expert guidance. Book a consultation today.\",\"button_text\":\"Book Visa Consultation\",\"button_link\":\"\\/apply-now\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(20, 'university', 'agents', 'Agents & Partners', NULL, '{\"hero\":{\"badge\":\"B2B Partnership Program\",\"title\":\"Grow Your Business with Pioneers\",\"description\":\"Join our global network of recruitment partners. We empower agents with the tools, technology, and university connections needed to succeed.\",\"cta_primary\":\"Become a Partner\",\"cta_primary_link\":\"\\/contact\",\"cta_secondary\":\"Learn More\",\"cta_secondary_link\":\"#benefits\",\"bg_image\":\"\\/hero.png\"},\"stats\":[{\"value\":\"150+\",\"label\":\"Global Universities\"},{\"value\":\"10k+\",\"label\":\"Students Placed\"},{\"value\":\"50+\",\"label\":\"Countries\"},{\"value\":\"99%\",\"label\":\"Visa Success\"}],\"services_section\":{\"title\":\"Empowering Your Growth\",\"subtitle\":\"Why Choose Us\",\"description\":\"We provide everything you need to scale your student recruitment business efficiently.\",\"items\":[{\"title\":\"Global Network\",\"description\":\"Access our extensive network of 150+ top universities across the UK, USA, Canada, and Australia.\",\"icon\":\"faGlobe\"},{\"title\":\"High Commissions\",\"description\":\"Earn competitive commissions with timely payouts and transparent tracking systems.\",\"icon\":\"faPercent\"},{\"title\":\"Marketing Support\",\"description\":\"Get access to branded marketing materials, brochures, and digital assets to attract more students.\",\"icon\":\"faChartLine\"},{\"title\":\"Dedicated Account Manager\",\"description\":\"Work with a dedicated expert who will guide you through admissions and updates.\",\"icon\":\"faUserGroup\"},{\"title\":\"Priority Training\",\"description\":\"Regular training sessions on university courses, visa updates, and application processes.\",\"icon\":\"faCheckCircle\"},{\"title\":\"24\\/7 Support\",\"description\":\"Our support team is always available to resolve queries and assist with urgent applications.\",\"icon\":\"faHeadset\"}]},\"process_section\":{\"title\":\"Simple Steps to Start\",\"description\":\"Partnership Process\",\"steps\":[{\"number\":\"01\",\"title\":\"Register\",\"description\":\"Fill out our partner registration form with your business details.\"},{\"number\":\"02\",\"title\":\"Verify\",\"description\":\"Our team will review your application and conduct a quick validation call.\"},{\"number\":\"03\",\"title\":\"Start Recruiting\",\"description\":\"Get access to our portal and start submitting student applications.\"}]},\"cta_section\":{\"title\":\"Ready to grow with us?\",\"button_text\":\"Register as a Partner Now\",\"description\":\"Join over 500+ active agents today.\"}}', '{\"hero\":{\"badge\":\"B2B Partnership Program\",\"title\":\"Grow Your Business with Pioneers\",\"description\":\"Join our global network of recruitment partners. We empower agents with the tools, technology, and university connections needed to succeed.\",\"cta_primary\":\"Become a Partner\",\"cta_primary_link\":\"\\/contact\",\"cta_secondary\":\"Learn More\",\"cta_secondary_link\":\"#benefits\",\"bg_image\":\"\\/hero.png\"},\"stats\":[{\"value\":\"150+\",\"label\":\"Global Universities\"},{\"value\":\"10k+\",\"label\":\"Students Placed\"},{\"value\":\"50+\",\"label\":\"Countries\"},{\"value\":\"99%\",\"label\":\"Visa Success\"}],\"services_section\":{\"title\":\"Empowering Your Growth\",\"subtitle\":\"Why Choose Us\",\"description\":\"We provide everything you need to scale your student recruitment business efficiently.\",\"items\":[{\"title\":\"Global Network\",\"description\":\"Access our extensive network of 150+ top universities across the UK, USA, Canada, and Australia.\",\"icon\":\"faGlobe\"},{\"title\":\"High Commissions\",\"description\":\"Earn competitive commissions with timely payouts and transparent tracking systems.\",\"icon\":\"faPercent\"},{\"title\":\"Marketing Support\",\"description\":\"Get access to branded marketing materials, brochures, and digital assets to attract more students.\",\"icon\":\"faChartLine\"},{\"title\":\"Dedicated Account Manager\",\"description\":\"Work with a dedicated expert who will guide you through admissions and updates.\",\"icon\":\"faUserGroup\"},{\"title\":\"Priority Training\",\"description\":\"Regular training sessions on university courses, visa updates, and application processes.\",\"icon\":\"faCheckCircle\"},{\"title\":\"24\\/7 Support\",\"description\":\"Our support team is always available to resolve queries and assist with urgent applications.\",\"icon\":\"faHeadset\"}]},\"process_section\":{\"title\":\"Simple Steps to Start\",\"description\":\"Partnership Process\",\"steps\":[{\"number\":\"01\",\"title\":\"Register\",\"description\":\"Fill out our partner registration form with your business details.\"},{\"number\":\"02\",\"title\":\"Verify\",\"description\":\"Our team will review your application and conduct a quick validation call.\"},{\"number\":\"03\",\"title\":\"Start Recruiting\",\"description\":\"Get access to our portal and start submitting student applications.\"}]},\"cta_section\":{\"title\":\"Ready to grow with us?\",\"button_text\":\"Register as a Partner Now\",\"description\":\"Join over 500+ active agents today.\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(21, 'university', 'accommodation', 'Student Accommodation', NULL, '{\"hero\":{\"badge\":\"Housing\",\"title\":\"Find Your Perfect Home\",\"description\":\"Browse our verified student accommodation options. Safe, comfortable, and affordable.\",\"image\":\"\"},\"why_choose_us\":{\"title\":\"Why Our Housing?\",\"items\":[{\"title\":\"Verified Listings\",\"description\":\"All properties are personally checked.\"},{\"title\":\"No Hidden Fees\",\"description\":\"Transparent pricing policies.\"},{\"title\":\"Student Centric\",\"description\":\"Locations near universities and transit.\"},{\"title\":\"24\\/7 Support\",\"description\":\"Assistance whenever you need it.\"}]},\"booking_process\":{\"title\":\"Easy Booking\",\"steps\":[{\"number\":\"1\",\"title\":\"Search\",\"description\":\"Filter by city and university.\"},{\"number\":\"2\",\"title\":\"Select\",\"description\":\"Choose your preferred room type.\"},{\"number\":\"3\",\"title\":\"Reserve\",\"description\":\"Pay a deposit to secure your room.\"}]},\"cta\":{\"title\":\"Need Help?\",\"description\":\"Our accommodation team can help you find the right place.\",\"button_text\":\"Contact Housing Team\",\"button_link\":\"\\/contact\"}}', '{\"hero\":{\"badge\":\"Housing\",\"title\":\"Find Your Perfect Home\",\"description\":\"Browse our verified student accommodation options. Safe, comfortable, and affordable.\",\"image\":\"\"},\"why_choose_us\":{\"title\":\"Why Our Housing?\",\"items\":[{\"title\":\"Verified Listings\",\"description\":\"All properties are personally checked.\"},{\"title\":\"No Hidden Fees\",\"description\":\"Transparent pricing policies.\"},{\"title\":\"Student Centric\",\"description\":\"Locations near universities and transit.\"},{\"title\":\"24\\/7 Support\",\"description\":\"Assistance whenever you need it.\"}]},\"booking_process\":{\"title\":\"Easy Booking\",\"steps\":[{\"number\":\"1\",\"title\":\"Search\",\"description\":\"Filter by city and university.\"},{\"number\":\"2\",\"title\":\"Select\",\"description\":\"Choose your preferred room type.\"},{\"number\":\"3\",\"title\":\"Reserve\",\"description\":\"Pay a deposit to secure your room.\"}]},\"cta\":{\"title\":\"Need Help?\",\"description\":\"Our accommodation team can help you find the right place.\",\"button_text\":\"Contact Housing Team\",\"button_link\":\"\\/contact\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(22, 'university', 'applications', 'Applications', NULL, '{\"hero\":{\"badge\":\"Apply Now\",\"title\":\"Start Your Application\",\"description\":\"Your journey to a top university starts here. We guide you through every step.\",\"image\":\"\"},\"requirements\":{\"title\":\"Admission Requirements\",\"items\":[{\"title\":\"Academic Transcripts\",\"description\":\"High school or university records.\"},{\"title\":\"Language Profits\",\"description\":\"IELTS, TOEFL, or PTE scores.\"},{\"title\":\"Passport Copy\",\"description\":\"Valid passport for travel.\"},{\"title\":\"Statement of Purpose\",\"description\":\"A personal essay explaining your goals.\"}]},\"process_steps\":{\"title\":\"Application Timeline\",\"steps\":[{\"number\":\"1\",\"title\":\"Document Check\",\"description\":\"We verify all your documents.\"},{\"number\":\"2\",\"title\":\"Submission\",\"description\":\"We submit to universities on your behalf.\"},{\"number\":\"3\",\"title\":\"Offer Letter\",\"description\":\"Receive conditional or unconditional offers.\"}]},\"cta\":{\"title\":\"Apply Online\",\"description\":\"You can start your application process directly through our portal.\",\"button_text\":\"Apply Now\",\"button_link\":\"\\/portal\\/apply\"}}', '{\"hero\":{\"badge\":\"Apply Now\",\"title\":\"Start Your Application\",\"description\":\"Your journey to a top university starts here. We guide you through every step.\",\"image\":\"\"},\"requirements\":{\"title\":\"Admission Requirements\",\"items\":[{\"title\":\"Academic Transcripts\",\"description\":\"High school or university records.\"},{\"title\":\"Language Profits\",\"description\":\"IELTS, TOEFL, or PTE scores.\"},{\"title\":\"Passport Copy\",\"description\":\"Valid passport for travel.\"},{\"title\":\"Statement of Purpose\",\"description\":\"A personal essay explaining your goals.\"}]},\"process_steps\":{\"title\":\"Application Timeline\",\"steps\":[{\"number\":\"1\",\"title\":\"Document Check\",\"description\":\"We verify all your documents.\"},{\"number\":\"2\",\"title\":\"Submission\",\"description\":\"We submit to universities on your behalf.\"},{\"number\":\"3\",\"title\":\"Offer Letter\",\"description\":\"Receive conditional or unconditional offers.\"}]},\"cta\":{\"title\":\"Apply Online\",\"description\":\"You can start your application process directly through our portal.\",\"button_text\":\"Apply Now\",\"button_link\":\"\\/portal\\/apply\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(23, 'university', 'application', 'Application Support', NULL, '{\"hero\":{\"title\":\"Expert Application Services\",\"description\":\"Navigate the complex university application process with confidence. Our team of experts is with you every step of the way.\",\"btn1_text\":\"Start Your Application\",\"btn1_link\":\"\\/apply-now\",\"btn2_text\":\"How It Works\",\"btn2_link\":\"#process\"},\"services\":{\"subtitle\":\"Our Expertise\",\"title\":\"Comprehensive Application Support\",\"description\":\"We provide end-to-end services to maximize your chances of acceptance.\",\"items\":[{\"title\":\"University Selection\",\"description\":\"We help you identify the best universities based on your academic profile, career goals, and budget.\",\"icon\":\"faUniversity\"},{\"title\":\"SOP & LOR Editing\",\"description\":\"Our experts refine your Statement of Purpose and Letters of Recommendation to make a compelling case.\",\"icon\":\"faFilePen\"},{\"title\":\"Application Management\",\"description\":\"We handle the entire application process, ensuring every form is filled correctly and submitted on time.\",\"icon\":\"faCheckDouble\"},{\"title\":\"Interview Preparation\",\"description\":\"Mock interviews and personalized coaching to help you ace your university or visa interviews.\",\"icon\":\"faUserTie\"},{\"title\":\"Visa Assistance\",\"description\":\"Complete guidance on visa documentation, financial proof, and interview strategies.\",\"icon\":\"faPassport\"},{\"title\":\"Timeline Planning\",\"description\":\"We create a customized timeline to ensure you meet all deadlines without stress.\",\"icon\":\"faCalendarCheck\"}]},\"process\":{\"subtitle\":\"The Process\",\"title\":\"Your Journey to Acceptance\",\"description\":\"A structured approach designed to keep you organized and ahead of deadlines.\",\"steps\":[{\"number\":\"01\",\"title\":\"Profile Evaluation\",\"description\":\"We analyze your academic background and career aspirations.\"},{\"number\":\"02\",\"title\":\"University Shortlisting\",\"description\":\"Selecting the right mix of ambitious, target, and safe universities.\"},{\"number\":\"03\",\"title\":\"Document Preparation\",\"description\":\"Drafting and refining your SOPs, LORs, and CVs.\"},{\"number\":\"04\",\"title\":\"Application Submission\",\"description\":\"Timely submission of applications to your chosen universities.\"}],\"stat\":{\"value\":\"98%\",\"label\":\"Success Rate\",\"description\":\"Our students consistently secure offers from their top 3 university choices thanks to our strategic approach.\"}},\"cta\":{\"title\":\"Ready to Start Your Journey?\",\"description\":\"Book a free consultation with our experts and take the first step toward your dream university.\",\"btn_text\":\"Book Free Consultation\",\"btn_link\":\"\\/apply-now\"}}', '{\"hero\":{\"title\":\"Expert Application Services\",\"description\":\"Navigate the complex university application process with confidence. Our team of experts is with you every step of the way.\",\"btn1_text\":\"Start Your Application\",\"btn1_link\":\"\\/apply-now\",\"btn2_text\":\"How It Works\",\"btn2_link\":\"#process\"},\"services\":{\"subtitle\":\"Our Expertise\",\"title\":\"Comprehensive Application Support\",\"description\":\"We provide end-to-end services to maximize your chances of acceptance.\",\"items\":[{\"title\":\"University Selection\",\"description\":\"We help you identify the best universities based on your academic profile, career goals, and budget.\",\"icon\":\"faUniversity\"},{\"title\":\"SOP & LOR Editing\",\"description\":\"Our experts refine your Statement of Purpose and Letters of Recommendation to make a compelling case.\",\"icon\":\"faFilePen\"},{\"title\":\"Application Management\",\"description\":\"We handle the entire application process, ensuring every form is filled correctly and submitted on time.\",\"icon\":\"faCheckDouble\"},{\"title\":\"Interview Preparation\",\"description\":\"Mock interviews and personalized coaching to help you ace your university or visa interviews.\",\"icon\":\"faUserTie\"},{\"title\":\"Visa Assistance\",\"description\":\"Complete guidance on visa documentation, financial proof, and interview strategies.\",\"icon\":\"faPassport\"},{\"title\":\"Timeline Planning\",\"description\":\"We create a customized timeline to ensure you meet all deadlines without stress.\",\"icon\":\"faCalendarCheck\"}]},\"process\":{\"subtitle\":\"The Process\",\"title\":\"Your Journey to Acceptance\",\"description\":\"A structured approach designed to keep you organized and ahead of deadlines.\",\"steps\":[{\"number\":\"01\",\"title\":\"Profile Evaluation\",\"description\":\"We analyze your academic background and career aspirations.\"},{\"number\":\"02\",\"title\":\"University Shortlisting\",\"description\":\"Selecting the right mix of ambitious, target, and safe universities.\"},{\"number\":\"03\",\"title\":\"Document Preparation\",\"description\":\"Drafting and refining your SOPs, LORs, and CVs.\"},{\"number\":\"04\",\"title\":\"Application Submission\",\"description\":\"Timely submission of applications to your chosen universities.\"}],\"stat\":{\"value\":\"98%\",\"label\":\"Success Rate\",\"description\":\"Our students consistently secure offers from their top 3 university choices thanks to our strategic approach.\"}},\"cta\":{\"title\":\"Ready to Start Your Journey?\",\"description\":\"Book a free consultation with our experts and take the first step toward your dream university.\",\"btn_text\":\"Book Free Consultation\",\"btn_link\":\"\\/apply-now\"}}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14'),
(24, 'university', 'student-guide', 'Student Guide', NULL, '{\"hero\":{\"badge\":\"Knowledge Hub\",\"title\":\"Essential Student Guides\",\"description\":\"Expert advice, insider tips, and comprehensive resources to help you thrive in your international education journey.\",\"image\":\"\"},\"categories\":[{\"title\":\"Pre-Departure\",\"description\":\"Packing lists, flight tips, and essential checklists before you leave home.\",\"icon\":\"faPlane\",\"color\":\"bg-blue-50 text-blue-600\"},{\"title\":\"Academic Success\",\"description\":\"Study tips, understanding grading systems, and how to ace your assignments.\",\"icon\":\"faGraduationCap\",\"color\":\"bg-green-50 text-green-600\"},{\"title\":\"Cost of Living\",\"description\":\"Budgeting advice, part-time work rules, and banking guides for students.\",\"icon\":\"faMoneyBillWave\",\"color\":\"bg-orange-50 text-orange-600\"},{\"title\":\"City Guides\",\"description\":\"Deep dives into student life in London, New York, Toronto, and more.\",\"icon\":\"faCity\",\"color\":\"bg-purple-50 text-purple-600\"}],\"trust_section\":{\"title\":\"Trusted by 10,000+ Students\",\"description\":\"Our guides are written by experienced education counselors and alumni who have been through the process themselves. We ensure every piece of advice is accurate, up-to-date, and actionable.\",\"cta_text\":\"Speak to an Expert\",\"cta_link\":\"\\/contact\"},\"tools_resources\":{\"title\":\"Tools & Resources\",\"subtitle\":\"Free Downloads\",\"description\":\"Essential templates and checklists to simplify your application process.\",\"items\":[]},\"faq\":{\"title\":\"Frequently Asked Questions\",\"subtitle\":\"Common Questions\",\"description\":\"Have questions? We have answers. If you can\'t find what you\'re looking for, feel free to contact our expert team.\",\"cta\":{\"title\":\"Still have questions?\",\"description\":\"Our counselors are ready to help you with your specific study abroad queries.\",\"btn_text\":\"Contact Us\",\"btn_link\":\"\\/contact\"},\"items\":[{\"question\":\"How long does the study abroad application process take?\",\"answer\":\"Typically, it takes 6-12 months. This includes researching universities, preparing for standardized tests (IELTS\\/TOEFL), gathering documents, applying for admission, and finally the visa process. We recommend starting at least a year in advance.\"},{\"question\":\"Can I work while studying abroad?\",\"answer\":\"Yes, most countries allow international students to work part-time (usually 20 hours per week) during term time and full-time during breaks. Countries like the UK, Canada, Australia, and Germany have specific regulations that we can guide you through.\"},{\"question\":\"What are the English language requirements?\",\"answer\":\"Requirements vary by country and university. Generally, a minimum IELTS score of 6.0-6.5 or a TOEFL iBT score of 80-90 is required for undergraduate and postgraduate courses. Some universities may offer waivers based on your academic background.\"},{\"question\":\"Are scholarship opportunities available for international students?\",\"answer\":\"Absolutely! There are merit-based, need-based, and country-specific scholarships available. We help you identify and apply for scholarships that match your profile to reduce your financial burden.\"},{\"question\":\"Do you help with student accommodation?\",\"answer\":\"Yes, we assist with finding suitable accommodation, whether it\'s on-campus university housing or private off-campus apartments. Check out our Accommodation section for options.\"}]},\"featured_guides_category_slug\":\"visa\"}', '{\"hero\":{\"badge\":\"Knowledge Hub\",\"title\":\"Essential Student Guides\",\"description\":\"Expert advice, insider tips, and comprehensive resources to help you thrive in your international education journey.\",\"image\":\"\"},\"categories\":[{\"title\":\"Pre-Departure\",\"description\":\"Packing lists, flight tips, and essential checklists before you leave home.\",\"icon\":\"faPlane\",\"color\":\"bg-blue-50 text-blue-600\"},{\"title\":\"Academic Success\",\"description\":\"Study tips, understanding grading systems, and how to ace your assignments.\",\"icon\":\"faGraduationCap\",\"color\":\"bg-green-50 text-green-600\"},{\"title\":\"Cost of Living\",\"description\":\"Budgeting advice, part-time work rules, and banking guides for students.\",\"icon\":\"faMoneyBillWave\",\"color\":\"bg-orange-50 text-orange-600\"},{\"title\":\"City Guides\",\"description\":\"Deep dives into student life in London, New York, Toronto, and more.\",\"icon\":\"faCity\",\"color\":\"bg-purple-50 text-purple-600\"}],\"trust_section\":{\"title\":\"Trusted by 10,000+ Students\",\"description\":\"Our guides are written by experienced education counselors and alumni who have been through the process themselves. We ensure every piece of advice is accurate, up-to-date, and actionable.\",\"cta_text\":\"Speak to an Expert\",\"cta_link\":\"\\/contact\"},\"tools_resources\":{\"title\":\"Tools & Resources\",\"subtitle\":\"Free Downloads\",\"description\":\"Essential templates and checklists to simplify your application process.\",\"items\":[]},\"faq\":{\"title\":\"Frequently Asked Questions\",\"subtitle\":\"Common Questions\",\"description\":\"Have questions? We have answers. If you can\'t find what you\'re looking for, feel free to contact our expert team.\",\"cta\":{\"title\":\"Still have questions?\",\"description\":\"Our counselors are ready to help you with your specific study abroad queries.\",\"btn_text\":\"Contact Us\",\"btn_link\":\"\\/contact\"},\"items\":[{\"question\":\"How long does the study abroad application process take?\",\"answer\":\"Typically, it takes 6-12 months. This includes researching universities, preparing for standardized tests (IELTS\\/TOEFL), gathering documents, applying for admission, and finally the visa process. We recommend starting at least a year in advance.\"},{\"question\":\"Can I work while studying abroad?\",\"answer\":\"Yes, most countries allow international students to work part-time (usually 20 hours per week) during term time and full-time during breaks. Countries like the UK, Canada, Australia, and Germany have specific regulations that we can guide you through.\"},{\"question\":\"What are the English language requirements?\",\"answer\":\"Requirements vary by country and university. Generally, a minimum IELTS score of 6.0-6.5 or a TOEFL iBT score of 80-90 is required for undergraduate and postgraduate courses. Some universities may offer waivers based on your academic background.\"},{\"question\":\"Are scholarship opportunities available for international students?\",\"answer\":\"Absolutely! There are merit-based, need-based, and country-specific scholarships available. We help you identify and apply for scholarships that match your profile to reduce your financial burden.\"},{\"question\":\"Do you help with student accommodation?\",\"answer\":\"Yes, we assist with finding suitable accommodation, whether it\'s on-campus university housing or private off-campus apartments. Check out our Accommodation section for options.\"}]},\"featured_guides_category_slug\":\"visa\"}', NULL, NULL, 1, 0, '2026-02-11 06:00:38', '2026-02-11 06:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversion_fees`
--

CREATE TABLE `conversion_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `base_currency` char(3) NOT NULL,
  `target_currency` char(3) NOT NULL,
  `fee` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversion_fees`
--

INSERT INTO `conversion_fees` (`id`, `base_currency`, `target_currency`, `fee`, `created_at`, `updated_at`) VALUES
(1, 'GBP', 'SAR', 2.00, '2026-02-08 00:48:52', '2026-02-08 00:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `ar_name` varchar(255) NOT NULL,
  `flag` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) NOT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `currency_code` varchar(255) NOT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `continent` varchar(255) DEFAULT NULL,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `slug`, `ar_name`, `flag`, `country_code`, `is_popular`, `currency_code`, `phone_code`, `description`, `ar_description`, `capital`, `continent`, `display_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'United Kingdom', 'united-kingdom', 'المملكة المتحدة', 'flags/Ax0QbNK09T8u00nLKR7ASpfAsICkQPrH5ottGJNb.svg', 'GB', 1, 'GBP', '+44', 'The UK is a leading destination for English language studies with a rich academic heritage.', 'المملكة المتحدة من أبرز الوجهات لدراسة اللغة الإنجليزية وتتميز بتاريخ أكاديمي عريق.', 'London', 'Europe', 1, 1, '2026-02-10 06:56:03', '2026-02-12 05:00:42', NULL),
(2, 'United States', 'united-states', 'الولايات المتحدة', NULL, 'US', 1, 'USD', '+1', 'The USA offers diverse English programs across world-class institutions.', 'الولايات المتحدة تقدم برامج إنجليزية متنوعة عبر مؤسسات عالمية المستوى.', 'Washington, D.C.', 'North America', 2, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(3, 'Australia', 'australia', 'أستراليا', NULL, 'AU', 1, 'AUD', '+61', 'Australia combines high-quality education with a vibrant lifestyle.', 'أستراليا تجمع بين التعليم عالي الجودة ونمط حياة حيوي.', 'Canberra', 'Oceania', 3, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(4, 'New Zealand', 'new-zealand', 'نيوزيلندا', NULL, 'NZ', 1, 'NZD', '+64', 'New Zealand is known for safe cities and excellent English programs.', 'نيوزيلندا معروفة بمدن آمنة وبرامج إنجليزية ممتازة.', 'Wellington', 'Oceania', 4, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(5, 'Canada', 'canada', 'كندا', NULL, 'CA', 1, 'CAD', '+1', 'Canada is a popular choice for English studies with friendly multicultural cities.', 'كندا خيار شائع لدراسة الإنجليزية بمدن متعددة الثقافات.', 'Ottawa', 'North America', 5, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(6, 'Cyprus', 'cyprus', 'قبرص', NULL, 'CY', 1, 'EUR', '+357', 'Cyprus offers a warm climate and affordable language programs.', 'قبرص توفر مناخا دافئا وبرامج لغوية ميسورة.', 'Nicosia', 'Europe', 6, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(7, 'Hungary', 'hungary', 'المجر', NULL, 'HU', 1, 'HUF', '+36', 'Hungary is an affordable destination with growing English programs.', 'المجر وجهة ميسورة مع برامج إنجليزية متنامية.', 'Budapest', 'Europe', 7, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(8, 'Germany', 'germany', 'ألمانيا', NULL, 'DE', 1, 'EUR', '+49', 'Germany offers high-quality education and international study options.', 'ألمانيا تقدم تعليما عالي الجودة وخيارات دراسة دولية.', 'Berlin', 'Europe', 8, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(9, 'Ireland', 'ireland', 'إيرلندا', NULL, 'IE', 1, 'EUR', '+353', 'Ireland is a leading English-speaking destination with welcoming communities.', 'إيرلندا وجهة ناطقة بالإنجليزية مع مجتمع مرحب.', 'Dublin', 'Europe', 9, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(10, 'Malaysia', 'malaysia', 'ماليزيا', NULL, 'MY', 1, 'MYR', '+60', 'Malaysia is a budget-friendly destination with modern education hubs.', 'ماليزيا وجهة مناسبة من حيث التكلفة مع مراكز تعليم حديثة.', 'Kuala Lumpur', 'Asia', 10, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(11, 'Saudi Arabia', 'saudi-arabia', 'المملكة العربية السعودية', NULL, 'SA', 1, 'SAR', '+966', 'Saudi Arabia is a key market with growing demand for English education.', 'السعودية سوق مهم مع طلب متزايد على تعليم الإنجليزية.', 'Riyadh', 'Asia', 11, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(12, 'Bangladesh', 'bangladesh', 'بنغلاديش', NULL, 'BD', 1, 'BDT', '+880', 'Bangladesh offers a large student base seeking international education.', 'بنغلاديش تضم قاعدة طلابية كبيرة تهتم بالتعليم الدولي.', 'Dhaka', 'Asia', 12, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(13, 'India', 'india', 'الهند', NULL, 'IN', 1, 'INR', '+91', 'India is a major source market for English studies abroad.', 'الهند سوق رئيسي لدراسة الإنجليزية في الخارج.', 'New Delhi', 'Asia', 13, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(14, 'Pakistan', 'pakistan', 'باكستان', NULL, 'PK', 1, 'PKR', '+92', 'Pakistan has strong interest in international English programs.', 'باكستان لديها اهتمام قوي ببرامج الإنجليزية الدولية.', 'Islamabad', 'Asia', 14, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(15, 'United Arab Emirates', 'united-arab-emirates', 'الإمارات العربية المتحدة', NULL, 'AE', 1, 'AED', '+971', 'The UAE is a regional hub with strong demand for education services.', 'الإمارات مركز إقليمي مع طلب قوي على خدمات التعليم.', 'Abu Dhabi', 'Asia', 15, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(16, 'China', 'china', 'الصين', NULL, 'CN', 1, 'CNY', '+86', 'China is a major market for study abroad and English programs.', 'الصين سوق كبير للدراسة بالخارج وبرامج الإنجليزية.', 'Beijing', 'Asia', 16, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(17, 'Bahrain', 'bahrain', 'البحرين', NULL, 'BH', 1, 'BHD', '+973', 'Bahrain offers a growing market for international education.', 'البحرين سوق متنامٍ للتعليم الدولي.', 'Manama', 'Asia', 17, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(18, 'Kuwait', 'kuwait', 'الكويت', NULL, 'KW', 1, 'KWD', '+965', 'Kuwait is a key Gulf market with interest in English education.', 'الكويت سوق خليجي مهم يهتم بتعليم الإنجليزية.', 'Kuwait City', 'Asia', 18, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(19, 'South Africa', 'south-africa', 'جنوب أفريقيا', NULL, 'ZA', 0, 'ZAR', '+27', 'South Africa offers affordable English programs and diverse culture.', 'جنوب أفريقيا تقدم برامج إنجليزية ميسورة وثقافة متنوعة.', 'Pretoria', 'Africa', 30, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(20, 'Egypt', 'egypt', 'مصر', NULL, 'EG', 0, 'EGP', '+20', 'Egypt is a key African market with a large student population.', 'مصر سوق أفريقي مهم مع قاعدة طلابية كبيرة.', 'Cairo', 'Africa', 31, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(21, 'Nigeria', 'nigeria', 'نيجيريا', NULL, 'NG', 0, 'NGN', '+234', 'Nigeria has a strong demand for international education opportunities.', 'نيجيريا لديها طلب قوي على فرص التعليم الدولي.', 'Abuja', 'Africa', 32, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(22, 'Kenya', 'kenya', 'كينيا', NULL, 'KE', 0, 'KES', '+254', 'Kenya is an emerging market for study abroad.', 'كينيا سوق ناشئ للدراسة بالخارج.', 'Nairobi', 'Africa', 33, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(23, 'Brazil', 'brazil', 'البرازيل', NULL, 'BR', 0, 'BRL', '+55', 'Brazil is a large Latin American market for English education.', 'البرازيل سوق كبير في أمريكا اللاتينية لتعليم الإنجليزية.', 'Brasilia', 'South America', 34, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL),
(24, 'Mexico', 'mexico', 'المكسيك', NULL, 'MX', 0, 'MXN', '+52', 'Mexico provides a growing base of students seeking English programs.', 'المكسيك توفر قاعدة متنامية من الطلاب الراغبين في برامج الإنجليزية.', 'Mexico City', 'North America', 35, 1, '2026-02-10 06:56:03', '2026-02-10 06:56:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `ar_region` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `ar_description` longtext DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `short_pitch` text DEFAULT NULL,
  `ar_short_pitch` text DEFAULT NULL,
  `tuition_range` varchar(255) DEFAULT NULL,
  `ar_tuition_range` varchar(255) DEFAULT NULL,
  `visa_timeline` varchar(255) DEFAULT NULL,
  `ar_visa_timeline` varchar(255) DEFAULT NULL,
  `work_rights` varchar(255) DEFAULT NULL,
  `ar_work_rights` varchar(255) DEFAULT NULL,
  `scholarships_summary` varchar(255) DEFAULT NULL,
  `ar_scholarships_summary` varchar(255) DEFAULT NULL,
  `entry_req_gpa` text DEFAULT NULL,
  `ar_entry_req_gpa` text DEFAULT NULL,
  `entry_req_language` text DEFAULT NULL,
  `ar_entry_req_language` text DEFAULT NULL,
  `university_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `country_id`, `slug`, `name`, `ar_name`, `region`, `ar_region`, `description`, `ar_description`, `image_url`, `short_pitch`, `ar_short_pitch`, `tuition_range`, `ar_tuition_range`, `visa_timeline`, `ar_visa_timeline`, `work_rights`, `ar_work_rights`, `scholarships_summary`, `ar_scholarships_summary`, `entry_req_gpa`, `ar_entry_req_gpa`, `entry_req_language`, `ar_entry_req_language`, `university_count`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'united-kingdom', 'United Kingdom', 'المملكة المتحدة', 'Europe', 'أوروبا', 'Study in United Kingdom and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في المملكة المتحدة وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=United Kingdom', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'GBP 15,000 - 35,000 / year', 'GBP 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(2, 2, 'united-states', 'United States', 'الولايات المتحدة', 'North America', 'أمريكا الشمالية', 'Study in United States and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في الولايات المتحدة وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=United States', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'USD 15,000 - 35,000 / year', 'USD 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(3, 5, 'canada', 'Canada', 'كندا', 'North America', 'أمريكا الشمالية', 'Study in Canada and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في كندا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Canada', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'CAD 15,000 - 35,000 / year', 'CAD 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(4, 3, 'australia', 'Australia', 'أستراليا', 'Oceania', 'أوقيانوسيا', 'Study in Australia and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في أستراليا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Australia', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'AUD 15,000 - 35,000 / year', 'AUD 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(5, 8, 'germany', 'Germany', 'ألمانيا', 'Europe', 'أوروبا', 'Study in Germany and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في ألمانيا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Germany', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'EUR 15,000 - 35,000 / year', 'EUR 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(6, 9, 'ireland', 'Ireland', 'أيرلندا', 'Europe', 'أوروبا', 'Study in Ireland and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في أيرلندا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Ireland', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'EUR 15,000 - 35,000 / year', 'EUR 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(7, NULL, 'netherlands', 'Netherlands', 'هولندا', 'Europe', 'أوروبا', 'Study in Netherlands and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في هولندا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Netherlands', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'EUR 15,000 - 35,000 / year', 'EUR 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL),
(8, 10, 'malaysia', 'Malaysia', 'ماليزيا', 'Asia', 'آسيا', 'Study in Malaysia and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.', 'ادرس في ماليزيا وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.', 'https://placehold.co/800x600?text=Malaysia', 'World-renowned degrees & post-study work rights.', 'شهادات عالمية وفرص عمل بعد التخرج.', 'MYR 15,000 - 35,000 / year', 'MYR 15,000 - 35,000 / سنة', '4-6 weeks', '4-6 أسابيع', 'Up to 3 years post-study', 'حتى 3 سنوات بعد التخرج', 'Government & University mandated', 'منح حكومية وجامعية', NULL, NULL, NULL, NULL, 0, 1, '2026-02-10 09:55:31', '2026-02-10 09:55:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `destination_disciplines`
--

CREATE TABLE `destination_disciplines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `discipline` varchar(255) NOT NULL,
  `ar_discipline` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_disciplines`
--

INSERT INTO `destination_disciplines` (`id`, `destination_id`, `discipline`, `ar_discipline`, `created_at`, `updated_at`) VALUES
(1, 1, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 1, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 1, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 2, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 2, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 2, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 2, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 3, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 3, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 3, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 3, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 4, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 4, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 4, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 4, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(17, 5, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(18, 5, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(19, 5, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(20, 5, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(21, 6, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(22, 6, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(23, 6, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(24, 6, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(25, 7, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(26, 7, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(27, 7, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(28, 7, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(29, 8, 'Business', 'إدارة الأعمال', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(30, 8, 'Engineering', 'الهندسة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(31, 8, 'Health', 'العلوم الصحية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(32, 8, 'IT', 'تقنية المعلومات', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `destination_faqs`
--

CREATE TABLE `destination_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `ar_question` text DEFAULT NULL,
  `answer` text NOT NULL,
  `ar_answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_faqs`
--

INSERT INTO `destination_faqs` (`id`, `destination_id`, `question`, `ar_question`, `answer`, `ar_answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 2, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 2, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 3, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 3, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 4, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 4, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 5, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 5, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 6, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 6, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 7, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 7, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 8, 'Can I work while studying?', 'هل يمكنني العمل أثناء الدراسة؟', 'Yes, usually 20 hours per week during term time.', 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 8, 'Are scholarships available?', 'هل تتوفر منح دراسية؟', 'Yes, many universities offer merit-based scholarships.', 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `destination_features`
--

CREATE TABLE `destination_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `feature` varchar(255) NOT NULL,
  `ar_feature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_features`
--

INSERT INTO `destination_features` (`id`, `destination_id`, `feature`, `ar_feature`, `created_at`, `updated_at`) VALUES
(1, 1, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 1, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 2, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 2, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 2, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 3, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 3, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 3, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 4, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 4, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 4, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 5, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 5, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 5, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 6, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(17, 6, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(18, 6, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(19, 7, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(20, 7, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(21, 7, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(22, 8, 'High Quality Education', 'تعليم عالي الجودة', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(23, 8, 'Multicultural Society', 'مجتمع متعدد الثقافات', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(24, 8, 'Global Recognition', 'اعتراف عالمي', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `destination_guides`
--

CREATE TABLE `destination_guides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destination_intakes`
--

CREATE TABLE `destination_intakes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `ar_month` varchar(255) DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `ar_event` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_intakes`
--

INSERT INTO `destination_intakes` (`id`, `destination_id`, `month`, `ar_month`, `event`, `ar_event`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 1, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 2, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 2, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 2, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 3, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 3, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 3, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 4, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 4, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 4, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 5, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 5, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 5, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 6, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(17, 6, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(18, 6, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(19, 7, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(20, 7, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(21, 7, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(22, 8, 'Jan', 'يناير', 'Winter Intake', 'القبول الشتوي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(23, 8, 'May', 'مايو', 'Spring Intake', 'القبول الربيعي', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(24, 8, 'Sep', 'سبتمبر', 'Fall Intake (Main)', 'قبول الخريف (الرئيسي)', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `destination_requirements`
--

CREATE TABLE `destination_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `requirement` varchar(255) NOT NULL,
  `ar_requirement` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_requirements`
--

INSERT INTO `destination_requirements` (`id`, `destination_id`, `requirement`, `ar_requirement`, `created_at`, `updated_at`) VALUES
(1, 1, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 1, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 1, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 2, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 2, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 2, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 2, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 3, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 3, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 3, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 3, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 4, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 4, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 4, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 4, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(17, 5, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(18, 5, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(19, 5, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(20, 5, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(21, 6, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(22, 6, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(23, 6, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(24, 6, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(25, 7, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(26, 7, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(27, 7, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(28, 7, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(29, 8, 'Academic Transcripts', 'السجلات الأكاديمية', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(30, 8, 'English Proficiency (IELTS/TOEFL)', 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(31, 8, 'Statement of Purpose', 'بيان الغرض', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(32, 8, 'Financial Proof', 'إثبات القدرة المالية', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `destination_stats`
--

CREATE TABLE `destination_stats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `ar_label` varchar(255) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `ar_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destination_stats`
--

INSERT INTO `destination_stats` (`id`, `destination_id`, `label`, `ar_label`, `value`, `ar_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(2, 1, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(3, 1, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(4, 2, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(5, 2, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(6, 2, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(7, 3, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(8, 3, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(9, 3, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(10, 4, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(11, 4, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(12, 4, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(13, 5, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(14, 5, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(15, 5, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(16, 6, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(17, 6, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(18, 6, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(19, 7, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(20, 7, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(21, 7, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(22, 8, 'Universities', 'الجامعات', '20+', '20+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(23, 8, 'Intl. Students', 'الطلاب الدوليون', '500k+', '500k+', '2026-02-10 09:55:31', '2026-02-10 09:55:31'),
(24, 8, 'Post-Study Work', 'العمل بعد التخرج', 'Yes', 'نعم', '2026-02-10 09:55:31', '2026-02-10 09:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `base_currency` char(3) NOT NULL,
  `target_currency` char(3) NOT NULL,
  `rate` decimal(16,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `base_currency`, `target_currency`, `rate`, `created_at`, `updated_at`) VALUES
(1, 'GBP', 'INR', 123.04450000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(2, 'GBP', 'CNY', 9.45100000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(3, 'GBP', 'JPY', 213.48390000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(4, 'GBP', 'PKR', 380.93620000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(5, 'GBP', 'BDT', 166.34160000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(6, 'GBP', 'ZAR', 21.85740000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(7, 'GBP', 'SGD', 1.72930000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(8, 'GBP', 'SAR', 5.09770000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(9, 'GBP', 'KWD', 0.41590000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(10, 'GBP', 'OMR', 0.52270000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(11, 'GBP', 'BHD', 0.51110000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(12, 'GBP', 'QAR', 4.94820000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(13, 'GBP', 'EGP', 63.70190000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(14, 'GBP', 'LYD', 8.60940000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(15, 'GBP', 'IRR', 1599824.56140000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(16, 'GBP', 'MYR', 5.36100000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(17, 'GBP', 'THB', 42.93010000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(18, 'GBP', 'IDR', 22927.68140000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(19, 'GBP', 'PHP', 79.60750000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(20, 'GBP', 'VND', 35204.78660000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(21, 'GBP', 'TWD', 42.99810000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(22, 'GBP', 'AUD', 1.94420000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(23, 'GBP', 'NZD', 2.26380000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(24, 'GBP', 'AFN', 88.79600000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(25, 'GBP', 'LKR', 419.95990000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(26, 'GBP', 'USD', 1.35940000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(27, 'GBP', 'EUR', 1.15110000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(28, 'GBP', 'CAD', 1.85750000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(29, 'GBP', 'CHF', 1.05560000, '2026-02-08 00:46:35', '2026-02-08 00:46:35'),
(30, 'GBP', 'AED', 4.99240000, '2026-02-08 00:46:35', '2026-02-08 00:46:35');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `ar_category` varchar(255) DEFAULT NULL,
  `question` text NOT NULL,
  `ar_question` text DEFAULT NULL,
  `answer` text NOT NULL,
  `ar_answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category`, `ar_category`, `question`, `ar_question`, `answer`, `ar_answer`, `created_at`, `updated_at`) VALUES
(1, 'Getting Started', 'البدء', 'How do I sign up for a Pioneers Educational account?', 'كيف يمكنني التسجيل للحصول على حساب في Pioneers Educational؟', 'It\'s easy! Visit our website, click on \"Sign Up,\" and follow the prompts. You can register with an email or a social media account.', 'الأمر سهل! قم بزيارة موقعنا الإلكتروني، وانقر على \"Sign Up\"، واتبع التعليمات. يمكنك التسجيل باستخدام بريد إلكتروني أو حساب من وسائل التواصل الاجتماعي.', NULL, NULL),
(2, 'Getting Started', 'البدء', 'Can I access the site from multiple devices?', 'هل يمكنني الوصول إلى الموقع من عدة أجهزة؟', 'Yes, you can log in from multiple devices. Just make sure you have your login details handy, and ensure a stable internet connection for best results.', 'نعم، يمكنك تسجيل الدخول من عدة أجهزة. فقط تأكد من أن تفاصيل تسجيل الدخول الخاصة بك في متناول يدك، وتأكد من وجود اتصال إنترنت مستقر للحصول على أفضل النتائج.', NULL, NULL),
(3, 'Getting Started', 'البدء', 'How do I find and compare different schools or programs?', 'كيف أجد وأقارن بين المدارس أو البرامج المختلفة؟', 'After creating an account, head to the Schools/Programs section. You can filter by location, language, and other preferences to compare your best matches.', 'بعد إنشاء حساب، انتقل إلى قسم المدارس/البرامج. يمكنك التصفية حسب الموقع، اللغة، وغيرها من التفضيلات لمقارنة أفضل الخيارات المتاحة لك.', NULL, NULL),
(4, 'Getting Started', 'البدء', 'Is there a free consultation available?', 'هل يوجد استشارة مجانية متاحة؟', 'We offer a free consultation to help you understand the process. Simply contact our support team to schedule an appointment.', 'نحن نقدم استشارة مجانية لمساعدتك على فهم العملية. فقط اتصل بفريق الدعم لدينا لتحديد موعد.', NULL, NULL),
(5, 'Pricing', 'التسعير', 'How long does the entire admission process usually take?', 'كم من الوقت يستغرق عادةً عملية القبول بأكملها؟', 'This varies by school and program, but typically you’ll receive a response within a few days to a few weeks. We’ll keep you updated throughout the process.', 'يختلف ذلك حسب المدرسة والبرنامج، ولكن عادةً ما تتلقى ردًا خلال بضعة أيام إلى بضعة أسابيع. سنبقيك على اطلاع طوال العملية.', NULL, NULL),
(6, 'Pricing', 'التسعير', 'How does the referral system work?', 'كيف يعمل نظام الإحالة؟', 'Earn rewards when friends or family sign up using your unique referral link and complete a booking. You’ll receive bonuses or discounts toward future courses.', 'اكسب مكافآت عندما يقوم الأصدقاء أو أفراد العائلة بالتسجيل باستخدام رابط الإحالة الفريد الخاص بك وإتمام الحجز. ستحصل على مكافآت أو خصومات على الدورات المستقبلية.', NULL, NULL),
(7, 'Pricing', 'التسعير', 'Are there any hidden fees when booking through Pioneers?', 'هل توجد أي رسوم خفية عند الحجز من خلال Pioneers؟', 'No. We maintain transparency, and all charges will be clearly stated before you confirm your booking.', 'لا. نحن نحافظ على الشفافية، وسيتم توضيح جميع الرسوم بوضوح قبل تأكيد الحجز.', NULL, NULL),
(8, 'Pricing', 'التسعير', 'Do I need to pay upfront for the entire course fee?', 'هل أحتاج إلى الدفع مقدمًا لكامل رسوم الدورة؟', 'Payment policies vary by school. In some cases, a deposit is required, while others may request full payment. We’ll outline the details during checkout.', 'تختلف سياسات الدفع حسب المدرسة. في بعض الحالات، يُطلب وديعة، بينما قد تطلب مدارس أخرى الدفع الكامل. سنوضح التفاصيل أثناء عملية الدفع.', NULL, NULL),
(9, 'Pricing', 'التسعير', 'Is there a discount for group or family bookings?', 'هل يوجد خصم للحجوزات الجماعية أو العائلية؟', 'Yes! We often have group or family discounts. Check the \"Promotions\" section or contact support for the most up-to-date offers.', 'نعم! غالبًا ما نقدم خصومات للمجموعات أو العائلات. تحقق من قسم \"العروض\" أو اتصل بالدعم للحصول على أحدث العروض.', NULL, NULL),
(10, 'Features', 'الميزات', 'What is your refund or cancellation policy?', 'ما هي سياسة الاسترداد أو الإلغاء لديكم؟', 'Refunds and cancellations depend on each school’s policy. We recommend reviewing the terms before finalizing payment. If you have questions, reach out to support.', 'تعتمد سياسات الاسترداد والإلغاء على سياسة كل مدرسة. نوصي بمراجعة الشروط قبل تأكيد الدفع. إذا كانت لديك أي أسئلة، فاتصل بالدعم.', NULL, NULL),
(11, 'Features', 'الميزات', 'How do I request a specific language course?', 'كيف يمكنني طلب دورة لغة معينة؟', 'Under \"Language Courses,\" you can filter by language level and duration. Select the one that suits you best and follow the booking instructions.', 'ضمن قسم \"دورات اللغات\"، يمكنك التصفية حسب مستوى اللغة والمدة. اختر الدورة التي تناسبك واتبع تعليمات الحجز.', NULL, NULL),
(12, 'Features', 'الميزات', 'Can I suggest a course that is not listed?', 'هل يمكنني اقتراح دورة غير مدرجة؟', 'We’re open to expanding our offerings! Use the \"Suggest a Course\" form, and our team will explore adding it to our catalog.', 'نحن منفتحون لتوسيع عروضنا! استخدم نموذج \"اقتراح دورة\"، وسيتحقق فريقنا من إمكانية إضافتها إلى كتالوجنا.', NULL, NULL),
(13, 'Features', 'الميزات', 'How do I know if a recommended course is right for me?', 'كيف أعرف إذا كانت الدورة الموصى بها مناسبة لي؟', 'Each course listing includes details such as prerequisites, instructor qualifications, and student reviews to help you make an informed decision.', 'تتضمن كل دورة تفاصيل مثل المتطلبات المسبقة، مؤهلات المدرب، وتقييمات الطلاب لمساعدتك في اتخاذ قرار مستنير.', NULL, NULL),
(14, 'Features', 'الميزات', 'Can I request a course extension or additional materials?', 'هل يمكنني طلب تمديد الدورة أو مواد إضافية؟', 'Yes. Contact your course provider or open a support ticket to discuss extending your current program or acquiring supplementary materials.', 'نعم. اتصل بمزود الدورة الخاص بك أو افتح تذكرة دعم لمناقشة تمديد البرنامج الحالي أو الحصول على مواد إضافية.', NULL, NULL),
(15, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'How do I see recommended courses based on my interests?', 'كيف يمكنني رؤية الدورات الموصى بها بناءً على اهتماماتي؟', 'After you complete your profile, our platform will suggest courses aligned with your academic background and personal preferences.', 'بعد إكمال ملفك الشخصي، ستقترح منصتنا دورات تتماشى مع خلفيتك الأكاديمية وتفضيلاتك الشخصية.', NULL, NULL),
(16, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'I forgot my password—how do I reset it?', 'نسيت كلمة المرور—كيف يمكنني إعادة تعيينها؟', 'Click the \"Forgot Password\" link on the login page and follow the instructions to reset your password. Check your email for a reset link.', 'انقر على رابط \"نسيت كلمة المرور\" في صفحة تسجيل الدخول واتبع التعليمات لإعادة تعيين كلمة المرور. تحقق من بريدك الإلكتروني للحصول على رابط إعادة التعيين.', NULL, NULL),
(17, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'My referral link doesn’t seem to work. What should I do?', 'رابط الإحالة الخاص بي لا يعمل. ماذا يجب أن أفعل؟', 'Try clearing your browser cache or using a different device. If issues persist, contact support so we can generate a new link.', 'حاول مسح ذاكرة التخزين المؤقتة للمتصفح أو استخدام جهاز مختلف. إذا استمرت المشكلة، فاتصل بالدعم حتى نتمكن من إنشاء رابط جديد.', NULL, NULL),
(18, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'Is there a mobile app I can use?', 'هل يوجد تطبيق جوال يمكنني استخدامه؟', 'Our website is mobile-responsive, and we’re developing a dedicated app for iOS and Android. Stay tuned for updates in the coming months!', 'موقعنا متجاوب مع الجوال، ونحن بصدد تطوير تطبيق مخصص لنظامي iOS وAndroid. ترقب التحديثات في الأشهر القادمة!', NULL, NULL),
(19, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'How do I report a bug or system error?', 'كيف يمكنني الإبلاغ عن خلل أو خطأ في النظام؟', 'Use the \"Report an Issue\" form under your account settings or contact support directly. Our tech team will address it as soon as possible.', 'استخدم نموذج \"الإبلاغ عن مشكلة\" في إعدادات حسابك أو اتصل بالدعم مباشرة. سيتعامل فريقنا التقني مع الأمر في أسرع وقت ممكن.', NULL, NULL),
(20, 'Getting Started', 'البدء', 'How do I sign up for a Pioneers Educational account?', 'كيف يمكنني التسجيل للحصول على حساب في Pioneers Educational؟', 'It\'s easy! Visit our website, click on \"Sign Up,\" and follow the prompts. You can register with an email or a social media account.', 'الأمر سهل! قم بزيارة موقعنا الإلكتروني، وانقر على \"Sign Up\"، واتبع التعليمات. يمكنك التسجيل باستخدام بريد إلكتروني أو حساب من وسائل التواصل الاجتماعي.', NULL, NULL),
(21, 'Getting Started', 'البدء', 'Can I access the site from multiple devices?', 'هل يمكنني الوصول إلى الموقع من عدة أجهزة؟', 'Yes, you can log in from multiple devices. Just make sure you have your login details handy, and ensure a stable internet connection for best results.', 'نعم، يمكنك تسجيل الدخول من عدة أجهزة. فقط تأكد من أن تفاصيل تسجيل الدخول الخاصة بك في متناول يدك، وتأكد من وجود اتصال إنترنت مستقر للحصول على أفضل النتائج.', NULL, NULL),
(22, 'Getting Started', 'البدء', 'How do I find and compare different schools or programs?', 'كيف أجد وأقارن بين المدارس أو البرامج المختلفة؟', 'After creating an account, head to the Schools/Programs section. You can filter by location, language, and other preferences to compare your best matches.', 'بعد إنشاء حساب، انتقل إلى قسم المدارس/البرامج. يمكنك التصفية حسب الموقع، اللغة، وغيرها من التفضيلات لمقارنة أفضل الخيارات المتاحة لك.', NULL, NULL),
(23, 'Getting Started', 'البدء', 'Is there a free consultation available?', 'هل يوجد استشارة مجانية متاحة؟', 'We offer a free consultation to help you understand the process. Simply contact our support team to schedule an appointment.', 'نحن نقدم استشارة مجانية لمساعدتك على فهم العملية. فقط اتصل بفريق الدعم لدينا لتحديد موعد.', NULL, NULL),
(24, 'Pricing', 'التسعير', 'How long does the entire admission process usually take?', 'كم من الوقت يستغرق عادةً عملية القبول بأكملها؟', 'This varies by school and program, but typically you’ll receive a response within a few days to a few weeks. We’ll keep you updated throughout the process.', 'يختلف ذلك حسب المدرسة والبرنامج، ولكن عادةً ما تتلقى ردًا خلال بضعة أيام إلى بضعة أسابيع. سنبقيك على اطلاع طوال العملية.', NULL, NULL),
(25, 'Pricing', 'التسعير', 'How does the referral system work?', 'كيف يعمل نظام الإحالة؟', 'Earn rewards when friends or family sign up using your unique referral link and complete a booking. You’ll receive bonuses or discounts toward future courses.', 'اكسب مكافآت عندما يقوم الأصدقاء أو أفراد العائلة بالتسجيل باستخدام رابط الإحالة الفريد الخاص بك وإتمام الحجز. ستحصل على مكافآت أو خصومات على الدورات المستقبلية.', NULL, NULL),
(26, 'Pricing', 'التسعير', 'Are there any hidden fees when booking through Pioneers?', 'هل توجد أي رسوم خفية عند الحجز من خلال Pioneers؟', 'No. We maintain transparency, and all charges will be clearly stated before you confirm your booking.', 'لا. نحن نحافظ على الشفافية، وسيتم توضيح جميع الرسوم بوضوح قبل تأكيد الحجز.', NULL, NULL),
(27, 'Pricing', 'التسعير', 'Do I need to pay upfront for the entire course fee?', 'هل أحتاج إلى الدفع مقدمًا لكامل رسوم الدورة؟', 'Payment policies vary by school. In some cases, a deposit is required, while others may request full payment. We’ll outline the details during checkout.', 'تختلف سياسات الدفع حسب المدرسة. في بعض الحالات، يُطلب وديعة، بينما قد تطلب مدارس أخرى الدفع الكامل. سنوضح التفاصيل أثناء عملية الدفع.', NULL, NULL),
(28, 'Pricing', 'التسعير', 'Is there a discount for group or family bookings?', 'هل يوجد خصم للحجوزات الجماعية أو العائلية؟', 'Yes! We often have group or family discounts. Check the \"Promotions\" section or contact support for the most up-to-date offers.', 'نعم! غالبًا ما نقدم خصومات للمجموعات أو العائلات. تحقق من قسم \"العروض\" أو اتصل بالدعم للحصول على أحدث العروض.', NULL, NULL),
(29, 'Features', 'الميزات', 'What is your refund or cancellation policy?', 'ما هي سياسة الاسترداد أو الإلغاء لديكم؟', 'Refunds and cancellations depend on each school’s policy. We recommend reviewing the terms before finalizing payment. If you have questions, reach out to support.', 'تعتمد سياسات الاسترداد والإلغاء على سياسة كل مدرسة. نوصي بمراجعة الشروط قبل تأكيد الدفع. إذا كانت لديك أي أسئلة، فاتصل بالدعم.', NULL, NULL),
(30, 'Features', 'الميزات', 'How do I request a specific language course?', 'كيف يمكنني طلب دورة لغة معينة؟', 'Under \"Language Courses,\" you can filter by language level and duration. Select the one that suits you best and follow the booking instructions.', 'ضمن قسم \"دورات اللغات\"، يمكنك التصفية حسب مستوى اللغة والمدة. اختر الدورة التي تناسبك واتبع تعليمات الحجز.', NULL, NULL),
(31, 'Features', 'الميزات', 'Can I suggest a course that is not listed?', 'هل يمكنني اقتراح دورة غير مدرجة؟', 'We’re open to expanding our offerings! Use the \"Suggest a Course\" form, and our team will explore adding it to our catalog.', 'نحن منفتحون لتوسيع عروضنا! استخدم نموذج \"اقتراح دورة\"، وسيتحقق فريقنا من إمكانية إضافتها إلى كتالوجنا.', NULL, NULL),
(32, 'Features', 'الميزات', 'How do I know if a recommended course is right for me?', 'كيف أعرف إذا كانت الدورة الموصى بها مناسبة لي؟', 'Each course listing includes details such as prerequisites, instructor qualifications, and student reviews to help you make an informed decision.', 'تتضمن كل دورة تفاصيل مثل المتطلبات المسبقة، مؤهلات المدرب، وتقييمات الطلاب لمساعدتك في اتخاذ قرار مستنير.', NULL, NULL),
(33, 'Features', 'الميزات', 'Can I request a course extension or additional materials?', 'هل يمكنني طلب تمديد الدورة أو مواد إضافية؟', 'Yes. Contact your course provider or open a support ticket to discuss extending your current program or acquiring supplementary materials.', 'نعم. اتصل بمزود الدورة الخاص بك أو افتح تذكرة دعم لمناقشة تمديد البرنامج الحالي أو الحصول على مواد إضافية.', NULL, NULL),
(34, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'How do I see recommended courses based on my interests?', 'كيف يمكنني رؤية الدورات الموصى بها بناءً على اهتماماتي؟', 'After you complete your profile, our platform will suggest courses aligned with your academic background and personal preferences.', 'بعد إكمال ملفك الشخصي، ستقترح منصتنا دورات تتماشى مع خلفيتك الأكاديمية وتفضيلاتك الشخصية.', NULL, NULL),
(35, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'I forgot my password—how do I reset it?', 'نسيت كلمة المرور—كيف يمكنني إعادة تعيينها؟', 'Click the \"Forgot Password\" link on the login page and follow the instructions to reset your password. Check your email for a reset link.', 'انقر على رابط \"نسيت كلمة المرور\" في صفحة تسجيل الدخول واتبع التعليمات لإعادة تعيين كلمة المرور. تحقق من بريدك الإلكتروني للحصول على رابط إعادة التعيين.', NULL, NULL),
(36, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'My referral link doesn’t seem to work. What should I do?', 'رابط الإحالة الخاص بي لا يعمل. ماذا يجب أن أفعل؟', 'Try clearing your browser cache or using a different device. If issues persist, contact support so we can generate a new link.', 'حاول مسح ذاكرة التخزين المؤقتة للمتصفح أو استخدام جهاز مختلف. إذا استمرت المشكلة، فاتصل بالدعم حتى نتمكن من إنشاء رابط جديد.', NULL, NULL),
(37, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'Is there a mobile app I can use?', 'هل يوجد تطبيق جوال يمكنني استخدامه؟', 'Our website is mobile-responsive, and we’re developing a dedicated app for iOS and Android. Stay tuned for updates in the coming months!', 'موقعنا متجاوب مع الجوال، ونحن بصدد تطوير تطبيق مخصص لنظامي iOS وAndroid. ترقب التحديثات في الأشهر القادمة!', NULL, NULL),
(38, 'Account & Technical Issues', 'المشاكل التقنية والحساب', 'How do I report a bug or system error?', 'كيف يمكنني الإبلاغ عن خلل أو خطأ في النظام؟', 'Use the \"Report an Issue\" form under your account settings or contact support directly. Our tech team will address it as soon as possible.', 'استخدم نموذج \"الإبلاغ عن مشكلة\" في إعدادات حسابك أو اتصل بالدعم مباشرة. سيتعامل فريقنا التقني مع الأمر في أسرع وقت ممكن.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `featured_lists`
--

CREATE TABLE `featured_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(80) NOT NULL,
  `name` varchar(120) NOT NULL,
  `ar_name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `use_case` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `use_case`, `image_path`, `alt_text`, `created_at`, `updated_at`) VALUES
(1, 'LSI London', 'branch_image', 'gallery/bbsoYdiESM2UY4geNvyW6JrxdtWvl3tgqgQREJOT.jpg', 'lsi education, london', '2026-02-07 05:05:57', '2026-02-07 05:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `intake_terms`
--

CREATE TABLE `intake_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ar_name` varchar(80) DEFAULT NULL,
  `month_num` tinyint(4) DEFAULT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intake_terms`
--

INSERT INTO `intake_terms` (`id`, `key`, `name`, `ar_name`, `month_num`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'january', 'January', 'يناير', 1, 1, 1, '2026-02-11 01:51:43', '2026-02-11 01:51:43'),
(2, 'september', 'September', 'سبتمبر', 9, 2, 1, '2026-02-11 01:51:43', '2026-02-11 01:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_course_compares`
--

CREATE TABLE `language_course_compares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_type` varchar(50) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_compares`
--

INSERT INTO `language_course_compares` (`id`, `user_id`, `course_type`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 8, 'language_courses', 48, '2026-02-14 18:09:58', '2026-02-14 18:09:58'),
(2, 8, 'language_courses', 37, '2026-02-14 18:10:03', '2026-02-14 18:10:03'),
(3, 8, 'language_courses', 19, '2026-02-14 18:10:29', '2026-02-14 18:10:29'),
(4, 8, 'online_courses', 3, '2026-02-14 18:25:33', '2026-02-14 18:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `language_course_online_courses`
--

CREATE TABLE `language_course_online_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `language_school_id` bigint(20) UNSIGNED NOT NULL,
  `course_type_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `ar_name` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `ar_description` mediumtext DEFAULT NULL,
  `required_level` varchar(10) DEFAULT NULL,
  `study_time` varchar(10) DEFAULT NULL,
  `lessons_per_week` smallint(6) DEFAULT NULL,
  `min_age` tinyint(4) DEFAULT NULL,
  `start_date` varchar(10) DEFAULT NULL,
  `fee_type` enum('flat','weekly') NOT NULL,
  `fee_amount` decimal(12,2) NOT NULL,
  `currency_code` char(3) NOT NULL DEFAULT 'USD',
  `registration_fee` decimal(12,2) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('draft','published','suspended') NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_online_courses`
--

INSERT INTO `language_course_online_courses` (`id`, `slug`, `language_school_id`, `course_type_id`, `tag_id`, `name`, `ar_name`, `description`, `ar_description`, `required_level`, `study_time`, `lessons_per_week`, `min_age`, `start_date`, `fee_type`, `fee_amount`, `currency_code`, `registration_fee`, `thumbnail`, `visible`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'general-english-online-1', 5, 1, 5, 'General English Online', 'اللغة الإنجليزية العامة أونلاين', 'Build speaking confidence with flexible online lessons.', 'طوّر مهارات التحدث بثقة عبر دروس أونلاين مرنة.', 'A1', '10', 5, 16, 'Anytime', 'weekly', 85.00, 'USD', 20.00, 'language-online-courses/e7kOcLxQZdFzMhXEyaflvZM47IMkGha7y3O8dcqw.png', 1, 'published', '2026-02-12 07:40:19', '2026-02-12 23:53:51', NULL),
(2, 'intensive-english-online-2', 7, 2, 1, 'Intensive English Online', 'اللغة الإنجليزية المكثفة أونلاين', 'Accelerate your progress with intensive online classes.', 'سرّع تقدمك بدروس أونلاين مكثفة.', 'A2', '15', 8, 16, 'Weekly', 'weekly', 120.00, 'USD', 30.00, 'online_courses/intensive-english.jpg', 1, 'published', '2026-02-12 07:40:19', '2026-02-12 07:40:19', NULL),
(3, 'ielts-preparation-online-3', 8, 4, 2, 'IELTS Preparation Online', 'التحضير لاختبار IELTS أونلاين', 'Target all skills with expert IELTS guidance.', 'طوّر جميع المهارات مع إرشاد متخصص لاختبار IELTS.', 'B1', '12', 6, 16, 'Monthly', 'weekly', 135.00, 'USD', 25.00, 'online_courses/ielts.jpg', 1, 'published', '2026-02-12 07:40:19', '2026-02-12 07:40:19', NULL),
(4, 'semi-intensive-english-online-4', 9, 3, 6, 'Semi-Intensive English Online', 'اللغة الإنجليزية شبه المكثفة أونلاين', 'Balanced study plan for steady progress.', 'خطة دراسة متوازنة لتقدم ثابت.', 'A2', '12', 6, 16, 'Weekly', 'weekly', 105.00, 'USD', 20.00, 'online_courses/semi-intensive.jpg', 1, 'published', '2026-02-12 07:40:19', '2026-02-12 07:40:19', NULL),
(5, 'conversation-club-online-5', 10, 1, 7, 'Conversation Club Online', 'نادي المحادثة أونلاين', 'Weekly conversation practice with native tutors.', 'تمارين محادثة أسبوعية مع مدرسين ناطقين أصليين.', 'A1', '8', 4, 16, 'Anytime', 'weekly', 70.00, 'USD', 15.00, 'online_courses/conversation.jpg', 1, 'published', '2026-02-12 07:40:19', '2026-02-12 07:40:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language_course_summer_camps`
--

CREATE TABLE `language_course_summer_camps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `course_type_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `ar_name` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `ar_description` mediumtext DEFAULT NULL,
  `required_level` varchar(10) DEFAULT NULL,
  `study_time` varchar(10) DEFAULT NULL,
  `lessons_per_week` smallint(6) DEFAULT NULL,
  `age_range` varchar(50) DEFAULT NULL,
  `start_date` varchar(10) DEFAULT NULL,
  `payment_deadline` date DEFAULT NULL,
  `fee_type` enum('flat','weekly') NOT NULL,
  `fee_amount` decimal(12,2) NOT NULL,
  `registration_fee` decimal(12,2) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('draft','published','suspended') NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_summer_camps`
--

INSERT INTO `language_course_summer_camps` (`id`, `slug`, `branch_id`, `course_type_id`, `tag_id`, `name`, `ar_name`, `description`, `ar_description`, `required_level`, `study_time`, `lessons_per_week`, `age_range`, `start_date`, `payment_deadline`, `fee_type`, `fee_amount`, `registration_fee`, `thumbnail`, `visible`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'coastal-english-camp-brighton-1', 6, 1, 5, 'Coastal English Camp – Brighton', 'مخيم إنجليزي ساحلي – برايتون', 'Morning classes with afternoon beach activities.', 'دروس صباحية مع أنشطة شاطئية بعد الظهر.', 'A1', '15', 20, '13-16', '2026-06-15', '2026-05-15', 'weekly', 295.00, 50.00, 'language-summer-camps/3nyfeJpoSPjaXzBXkJjMKBJ93DLawPEK2h0At3NC.png', 1, 'published', '2026-02-12 07:40:20', '2026-02-14 18:15:47', NULL),
(2, 'cambridge-university-experience-2', 7, 1, 1, 'Cambridge University Experience', 'تجربة جامعة كامبريدج', 'Study English while exploring Cambridge.', 'تعلّم الإنجليزية واستكشف مدينة كامبريدج.', 'A2', '18', 22, '16-18', '2026-07-01', '2026-06-01', 'weekly', 350.00, 60.00, 'summer_camps/cambridge.jpg', 1, 'published', '2026-02-12 07:40:20', '2026-02-14 18:15:58', NULL),
(3, 'oxford-summer-english-3', 8, 1, 2, 'Oxford Summer English', 'الإنجليزية الصيفية في أكسفورد', 'Cultural trips and immersive lessons.', 'رحلات ثقافية ودروس غامرة.', 'A2', '16', 20, '15-18', '2026-07-10', '2026-06-10', 'weekly', 340.00, 55.00, 'summer_camps/oxford.jpg', 1, 'published', '2026-02-12 07:40:20', '2026-02-14 18:16:08', NULL),
(4, 'london-city-discovery-camp-4', 9, 1, 6, 'London City Discovery Camp', 'مخيم اكتشاف لندن', 'English classes and city tours.', 'دروس إنجليزية مع جولات في المدينة.', 'A1', '14', 18, '12-16', '2026-08-01', '2026-07-01', 'weekly', 320.00, 50.00, 'summer_camps/london.jpg', 1, 'published', '2026-02-12 07:40:20', '2026-02-14 18:16:17', NULL),
(5, 'manchester-english-adventure-5', 10, 1, 7, 'Manchester English Adventure', 'مغامرة الإنجليزية في مانشستر', 'Sports, culture, and English learning.', 'رياضة وثقافة وتعلّم الإنجليزية.', 'A1', '15', 20, '13-17', '2026-08-10', '2026-07-10', 'weekly', 330.00, 50.00, 'summer_camps/manchester.jpg', 1, 'published', '2026-02-12 07:40:20', '2026-02-14 18:16:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language_course_summer_camp_details`
--

CREATE TABLE `language_course_summer_camp_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `camp_id` bigint(20) UNSIGNED NOT NULL,
  `overview` mediumtext DEFAULT NULL,
  `ar_overview` mediumtext DEFAULT NULL,
  `academics` mediumtext DEFAULT NULL,
  `ar_academics` mediumtext DEFAULT NULL,
  `activities` mediumtext DEFAULT NULL,
  `ar_activities` mediumtext DEFAULT NULL,
  `accommodation` mediumtext DEFAULT NULL,
  `ar_accommodation` mediumtext DEFAULT NULL,
  `safeguarding` mediumtext DEFAULT NULL,
  `ar_safeguarding` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_summer_camp_details`
--

INSERT INTO `language_course_summer_camp_details` (`id`, `camp_id`, `overview`, `ar_overview`, `academics`, `ar_academics`, `activities`, `ar_activities`, `accommodation`, `ar_accommodation`, `safeguarding`, `ar_safeguarding`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 23:56:56', '2026-02-12 23:56:56'),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-14 18:15:58', '2026-02-14 18:15:58'),
(3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-14 18:16:08', '2026-02-14 18:16:08'),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-14 18:16:17', '2026-02-14 18:16:17'),
(5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-14 18:16:25', '2026-02-14 18:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `language_course_tags`
--

CREATE TABLE `language_course_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_tags`
--

INSERT INTO `language_course_tags` (`id`, `tag_code`, `name`, `ar_name`, `description`, `ar_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TOP_RATED', 'Top Rated', 'أعلى تقييم', NULL, NULL, '2025-01-26 21:09:31', '2025-01-26 21:09:31', NULL),
(2, 'MOST_REQUESTED', 'Most Requested', 'الأكثر طلبًا', NULL, NULL, '2026-02-11 18:08:12', '2026-02-11 18:08:12', NULL),
(5, 'BEST_OFFER', 'Best Offer', 'أفضل عرض', NULL, NULL, '2026-02-11 18:08:12', '2026-02-11 18:08:12', NULL),
(6, 'BEAUTIFUL_LOCATION', 'Beautiful Location', 'موقع جميل', NULL, NULL, '2026-02-11 18:08:12', '2026-02-11 18:08:12', NULL),
(7, 'BEST_TEACHING', 'Best Teaching', 'أفضل تعليم', NULL, NULL, '2026-02-11 18:08:12', '2026-02-11 18:08:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language_course_training_courses`
--

CREATE TABLE `language_course_training_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `language_school_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_type_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `ar_name` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `ar_description` mediumtext DEFAULT NULL,
  `required_level` varchar(10) DEFAULT NULL,
  `study_time` varchar(10) DEFAULT NULL,
  `lessons_per_week` smallint(6) DEFAULT NULL,
  `min_age` tinyint(4) DEFAULT NULL,
  `start_date` varchar(10) DEFAULT NULL,
  `fee_type` enum('flat','weekly') NOT NULL,
  `fee_amount` decimal(12,2) NOT NULL,
  `currency_code` char(3) NOT NULL DEFAULT 'USD',
  `registration_fee` decimal(12,2) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('draft','published','suspended') NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_training_courses`
--

INSERT INTO `language_course_training_courses` (`id`, `slug`, `language_school_id`, `branch_id`, `course_type_id`, `tag_id`, `name`, `ar_name`, `description`, `ar_description`, `required_level`, `study_time`, `lessons_per_week`, `min_age`, `start_date`, `fee_type`, `fee_amount`, `currency_code`, `registration_fee`, `thumbnail`, `visible`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'business-english-training-1', 5, 6, 2, 5, 'Business English Training', 'تدريب الإنجليزية للأعمال', 'Professional English for meetings and presentations.', 'إنجليزية احترافية للاجتماعات والعروض التقديمية.', 'B1', '20', 20, 18, 'Monthly', 'weekly', 260.00, 'USD', 60.00, 'training_courses/business.jpg', 1, 'published', '2026-02-12 07:40:21', '2026-02-12 07:40:21', NULL),
(2, 'exam-skills-workshop-2', 7, 7, 2, 1, 'Exam Skills Workshop', 'ورشة مهارات الاختبارات', 'Focused training for IELTS and exam techniques.', 'تدريب مركّز لمهارات IELTS وتقنيات الاختبارات.', 'B1', '15', 15, 18, 'Monthly', 'weekly', 220.00, 'USD', 50.00, 'training_courses/exam-skills.jpg', 1, 'published', '2026-02-12 07:40:21', '2026-02-12 07:40:21', NULL),
(3, 'academic-writing-bootcamp-3', 8, 8, 1, 2, 'Academic Writing Bootcamp', 'معسكر الكتابة الأكاديمية', 'Improve writing structure and clarity.', 'تحسين بنية الكتابة ووضوحها.', 'B2', '12', 12, 18, 'Weekly', 'weekly', 190.00, 'USD', 40.00, 'training_courses/writing.jpg', 1, 'published', '2026-02-12 07:40:21', '2026-02-12 07:40:21', NULL),
(4, 'teacher-development-course-4', 9, NULL, 1, 6, 'Teacher Development Course', 'دورة تطوير المعلمين', 'Practical strategies for ESL teachers.', 'استراتيجيات عملية لمعلمي اللغة الإنجليزية.', 'B2', '10', 10, 21, 'Quarterly', 'flat', 650.00, 'USD', 0.00, 'training_courses/teacher.jpg', 1, 'published', '2026-02-12 07:40:21', '2026-02-12 07:40:21', NULL),
(5, 'professional-communication-skills-5', 10, 10, 1, 7, 'Professional Communication Skills', 'مهارات التواصل المهني', 'Speak confidently in professional settings.', 'التحدث بثقة في بيئات العمل.', 'B1', '16', 16, 18, 'Monthly', 'weekly', 210.00, 'USD', 45.00, 'training_courses/communication.jpg', 1, 'published', '2026-02-12 07:40:21', '2026-02-12 07:40:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language_course_types`
--

CREATE TABLE `language_course_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_types`
--

INSERT INTO `language_course_types` (`id`, `type_code`, `name`, `ar_name`, `description`, `ar_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GENERAL_ENGLISH_COURSE', 'General English Course', 'دورة اللغة الإنجليزية العامة', NULL, NULL, '2025-01-26 13:49:46', '2025-01-26 13:49:46', NULL),
(2, 'INTENSIVE_ENGLISH_COURSE', 'Intensive English Course', 'دورة اللغة الإنجليزية المكثفة', NULL, NULL, '2025-01-26 13:49:52', '2025-01-26 13:49:52', NULL),
(3, 'SEMI-_INTENSIVE_ENGLISH', 'Semi-Intensive English', 'دورة اللغة الإنجليزية شبه المكثفة', NULL, NULL, '2025-01-26 21:13:22', '2025-01-26 21:13:22', NULL),
(4, 'I_E_L_T_S_EXAM_PREPARATION', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, '2025-03-25 16:11:59', '2025-03-25 16:11:59', NULL),
(5, 'SUPER-_INTENSIVE_ENGLISH', 'Super-Intensive English', 'دورة لغة إنجليزية عالية الكثافة', NULL, NULL, '2025-03-25 16:12:23', '2025-03-25 16:12:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language_course_wishlists`
--

CREATE TABLE `language_course_wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_type` varchar(50) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_course_wishlists`
--

INSERT INTO `language_course_wishlists` (`id`, `user_id`, `course_type`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 8, 'language_courses', 45, '2026-02-14 18:09:53', '2026-02-14 18:09:53'),
(2, 8, 'language_courses', 37, '2026-02-14 18:10:02', '2026-02-14 18:10:02'),
(3, 8, 'language_courses', 18, '2026-02-14 18:10:25', '2026-02-14 18:10:25'),
(4, 8, 'online_courses', 3, '2026-02-14 18:25:22', '2026-02-14 18:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `language_schools`
--

CREATE TABLE `language_schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `accreditation_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`accreditation_ids`)),
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `is_preferred` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_schools`
--

INSERT INTO `language_schools` (`id`, `name`, `ar_name`, `slug`, `description`, `ar_description`, `logo`, `accreditation_ids`, `rating`, `is_preferred`, `created_at`, `updated_at`) VALUES
(5, 'LSI Education', 'معهد ال اس اي', 'lsi-education', 'The institute is based in London, the capital city of the United Kingdom. It also has branches in countries like the UK, the United States, Canada, New Zealand, and Australia, among others. Students benefit from a supportive learning environment, with qualified instructors, engaging activities that encourage language use, and organized trips to popular tourist destinations around London.', 'يقع المعهد في مدينة لندن، عاصمة المملكة المتحدة. وله فروع في دول مثل المملكة المتحدة، الولايات المتحدة، كندا، نيوزيلندا وأستراليا، وغيرها. يستفيد الطلاب من بيئة تعليمية داعمة، مع مدرسين مؤهلين، وأنشطة تفاعلية تشجع على استخدام اللغة، ورحلات منظمة إلى وجهات سياحية شهيرة في لندن.', 'school_logos/4KU1iLftzeqp4xDzfIkKMW0CP0dEW4qNyB8wBFPU.png', '[1,2,7,13]', 5.00, 0, '2025-03-26 01:52:08', '2026-02-12 04:39:11'),
(7, 'Bath Academy of English', 'معهد اكاديمية باث', 'bath-academy-of-english', 'Bath Academy of English, established in 1997, is a language school in Bath, England, offering a variety of English courses for international students. These include General English, Business English, and exam preparation programs like IELTS and Cambridge exams. The academy emphasizes personalized teaching with small class sizes, ensuring students receive individual attention. It also provides accommodation options, including homestays, to enhance students’ cultural and educational experience.', 'أكاديمية بات الإنجليزية، التي تأسست في عام 1997، هي مدرسة لغة تقع في مدينة بات، إنجلترا، وتقدم مجموعة متنوعة من الدورات التعليمية للغة الإنجليزية للطلاب الدوليين. تشمل هذه الدورات الإنجليزية العامة، والإنجليزية للأعمال، وبرامج التحضير للامتحانات مثل IELTS و Cambridge. تركز الأكاديمية على التدريس الشخصي مع أحجام فصول صغيرة، مما يضمن حصول الطلاب على اهتمام فردي. كما توفر الأكاديمية خيارات إقامة، بما في ذلك الإقامة مع العائلات، لتعزيز تجربة الطلاب الثقافية والتعليمية.', 'school_logos/JFqykqHk8seQytYLGBin71fjBydTNt1IVlrhoweD.png', '[]', 5.00, 0, '2025-04-01 20:43:42', '2026-02-12 04:32:57'),
(8, 'Concorde International', 'معهد كونكورد العالمي', 'concorde-international', 'Concorde International is a language school that offers English courses for international students. The school provides a range of programs, including General English, Business English, exam preparation, and specialized courses. With a focus on high-quality education, Concorde International offers personalized teaching in small class sizes. Located in the UK, the school also provides various accommodation options to help students immerse themselves in the language and culture.', 'كونكورد إنترناشونال هي مدرسة لغات تقدم دورات في اللغة الإنجليزية للطلاب الدوليين. توفر المدرسة مجموعة من البرامج، بما في ذلك الإنجليزية العامة، الإنجليزية للأعمال، التحضير للامتحانات، والدورات المتخصصة. مع التركيز على التعليم عالي الجودة، تقدم كونكورد إنترناشونال تدريسًا شخصيًا في فصول صغيرة. تقع المدرسة في المملكة المتحدة، كما توفر خيارات إقامة متنوعة لمساعدة الطلاب على الاندماج في اللغة والثقافة.', 'school_logos/7sEfdSKSFbCM3QYBVviplBgHyaWYLxx7ZB4wRH9a.png', '[\"1\",\"2\",\"3\",\"6\",\"7\"]', 0.00, 0, '2025-04-01 21:05:50', '2025-04-01 21:05:50'),
(9, 'Islington Centre for English', 'مركز إزلنجتون للغة الإنجليزية', 'islington-centre-for-english', 'The Islington Centre for English is located in central London and offers a variety of courses suitable for all levels. It is equipped with modern learning tools that make learning easy and simple. The center\'s teachers are all native English speakers who encourage students to engage in class and practice speaking English as much as possible.', 'يتموقع معهد The Islington Centre for English في قلب لندن ويقدم مجموعة من الدورات المناسبة لجميع المستويات. ويتميز المعهد بتوفر أدوات تعليمية حديثة تجعل عملية التعلم سهلة وبسيطة. جميع المدرسين في المعهد من المتحدثين الأصليين للغة الإنجليزية ويشجعون الطلاب على التفاعل داخل الفصول الدراسية والتحدث باللغة الإنجليزية بأقصى قدر ممكن.', 'school_logos/JybRxwWmPXZJDfAztQhjmHFwhjSrZR6z6nFouHau.png', '[\"1\",\"2\",\"3\"]', 0.00, 0, '2025-04-01 21:09:05', '2025-04-01 21:09:05'),
(10, 'The London School of English', 'معهد لندن للغة الإنجليزية', 'the-london-school-of-english', 'The London School of English is just 15 minutes away from central London and offers a great experience in learning English. Established in 1912, it provides students with innovative and modern tools through reports, practical training, and tracking students\' goals to learn the language in the best possible ways. The school has certified teachers with many years of experience. It is worth noting that the institute welcomes students from over 70 different nationalities each year.', 'معهد The London School of English يقع على بعد 15 دقيقة فقط من وسط لندن ويعتبر تجربة مميزة لدراسة اللغة الإنجليزية. تأسس المعهد في عام 1912م ويقدم لطلابها أدوات مبتكرة وحديثة من خلال التقارير والتدريب العملي وتتبع أهداف الطلاب لتعلم اللغة بأفضل الطرق. يمتلك المعهد مدرسين معتمدين ذوي سنوات عديدة من الخبرة. ومن الجدير بالذكر أن المعهد يستقبل طلابًا من أكثر من 70 جنسية مختلفة حول العالم كل عام.', 'school_logos/9CQTlvef2rP6VgnzWZqXqIXaKxKcwWbDROQw9IhA.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 21:13:16', '2025-04-01 21:13:16'),
(11, 'Beet Language Centre', 'معهد بيت لانقويج سنتر', 'beet-language-centre', 'The \"BEET English Language Center\" is an educational institution specializing in teaching English, established in 1979 in Bournemouth, United Kingdom. The center offers a variety of programs, including General English, Business English, and exam preparation for IELTS and Cambridge exams. It is known for providing high-quality education with a focus on personalized teaching in small class sizes, ensuring individual attention for each student. Additionally, the center offers various accommodation options to enhance the cultural and educational experience for students.', 'معهد \"بيت إنجلش سنتر\" (BEET English Language Center) هو مؤسسة تعليمية متخصصة في تدريس اللغة الإنجليزية، تأسس عام 1979 في مدينة بورنموث، المملكة المتحدة. يقدم المعهد برامج متنوعة مثل الإنجليزية العامة، الإنجليزية للأعمال، والتحضير لامتحانات IELTS وكامبريدج. يتميز بتقديم تعليم عالي الجودة مع التركيز على التدريس الشخصي في فصول صغيرة، مما يضمن اهتمامًا فرديًا لكل طالب. بالإضافة إلى ذلك، يوفر المعهد خيارات إقامة متنوعة لتعزيز تجربة الطلاب الثقافية والتعليمية.', 'school_logos/Mu7eYf0XvQIMGEm8ZPmBOw3e9iWRkH0jow7JOV1v.png', '[\"1\",\"2\",\"8\",\"9\",\"10\",\"11\",\"12\"]', 0.00, 0, '2025-04-01 21:19:26', '2025-04-01 21:19:26'),
(12, 'Berlitz Manchester', 'معهد بيرلتز', 'berlitz-manchester', 'Berlitz Institute stands out with its fantastic location in the heart of Manchester, which many consider the second capital of the UK. This allows students to enjoy the city, practice the language naturally, and gain cultural experiences while forming friendships. The institute was founded in 1878, making it one of the oldest and most prestigious institutions. Since its establishment, it has maintained a high level of educational quality and value by offering a wide range of courses and selecting highly qualified teachers.', 'يتميز معهد بيرلتز بموقعه الرائع في قلب مدينة مانشستر، التي يعتبرها الكثيرون العاصمة الثانية لبريطانيا. مما يتيح لطلابه الاستمتاع بالخروج في المدينة وممارسة اللغة بشكل طبيعي واكتساب الثقافة مع تكوين الصداقات. تأسس المعهد في عام 1878، مما يجعله من أعرق وأقدم المعاهد. ومنذ تأسيسه، حافظ المعهد على تقديم جودة وقيمة تعليمية عالية لطلابه من خلال تقديم دورات متنوعة واختيار مدرسين ذوي مستوى عالٍ.', 'school_logos/Tl7UYKZd2CcnmFd9mQ5hGmgPPTbDS1iERx8JoH5d.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 21:23:24', '2025-04-01 21:23:24'),
(13, 'Britannia English Academy', 'معهد بريطانيا انجلش اكاديمي', 'britannia-english-academy', 'Britannia English Academy is an independent language school located in the heart of Manchester, UK. Established in 2012, it offers a range of English courses, including General English, Conversation Lessons, Exam Preparation for Cambridge FCE, CAE, and IELTS, as well as Business English and One-to-One sessions. With a maximum class size of 10 students, the academy ensures personalized attention from qualified teachers. Beyond academics, students can participate in social activities like conversation clubs and trips, enhancing their cultural experience. Accommodation options include homestays and student residences.', 'أكاديمية بريتانيا الإنجليزية هي مدرسة لغات مستقلة تقع في قلب مانشستر، المملكة المتحدة. تأسست في عام 2012، وتقدم مجموعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، دروس المحادثة، التحضير للامتحانات مثل FCE، CAE، IELTS، بالإضافة إلى الإنجليزية للأعمال والدروس الفردية. مع حد أقصى لعدد الطلاب في الفصول يبلغ 10 طلاب، تضمن الأكاديمية الاهتمام الشخصي من قبل المعلمين المؤهلين. بالإضافة إلى الدروس الأكاديمية، يمكن للطلاب المشاركة في الأنشطة الاجتماعية مثل أندية المحادثة والرحلات، مما يعزز تجربتهم الثقافية. تشمل خيارات الإقامة الإقامة مع العائلات والإقامات الطلابية.', 'school_logos/gQqyhxjtTEy6aBNcmcqy2EjJa5LSEwkFvrfAZ3Zr.png', '[\"1\",\"2\",\"3\",\"10\"]', 0.00, 0, '2025-04-01 21:32:01', '2025-04-01 21:32:01'),
(14, 'Oxford International Study Centre', 'معهد أكسفورد ستودي سنتر الدولي', 'oxford-international-study-centre', 'The Oxford International Study Centre (OISC) is a private tutorial college and language school located in the heart of Oxford, UK. Established over four hundred years ago, the institution offers a diverse range of programs, including English language courses, GCSE and A-Level studies, university preparation, and professional training. OISC is renowned for its personalized teaching approach, maintaining small class sizes to ensure individual attention. The center also provides various accommodation options, including residential stays in historic university colleges during the summer months. Accredited by the Independent Schools Inspectorate, OISC is committed to delivering high-quality education and fostering a supportive learning environment.', 'مركز أكسفورد الدولي للدراسات هو كلية تعليم خاص ومدرسة لغات تقع في قلب مدينة أكسفورد، المملكة المتحدة. تأسست منذ أكثر من أربعمائة عام، وتقدم المؤسسة مجموعة متنوعة من البرامج، بما في ذلك دورات اللغة الإنجليزية، ودروس GCSE وA-Level، والتحضير للجامعات، والتدريب المهني. يشتهر مركز أكسفورد الدولي بأسلوبه التعليمي الشخصي، حيث يحافظ على أحجام فصول صغيرة لضمان الاهتمام الفردي. كما يوفر المركز خيارات إقامة متنوعة، بما في ذلك الإقامة في الكليات الجامعية التاريخية خلال أشهر الصيف. معتمدة من هيئة تفتيش المدارس المستقلة، يلتزم OISC بتقديم تعليم عالي الجودة وتوفير بيئة تعليمية داعمة.', 'school_logos/zQrnx4IzkBtzmFIKN25zePy0DlQB6MHaWlK4VpaU.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 21:34:08', '2025-04-01 21:34:08'),
(15, 'Preston Academy of English', 'معهد بريستون أكاديمي', 'preston-academy-of-english', 'Preston Academy of English (PAE) is a private language school located in the heart of Preston, Lancashire, UK. Established in 2012, PAE offers a variety of English language courses, including General English, IELTS preparation, and English conversation classes. The academy emphasizes personalized teaching with small class sizes, ensuring individual attention for each student. Students also have access to a self-learning center and a student lounge equipped with amenities like free Wi-Fi. PAE is accredited by the British Council and is an authorized exam center for Trinity College London and LanguageCert.', 'أكاديمية بريستون للغة الإنجليزية (PAE) هي مدرسة لغات خاصة تقع في قلب مدينة بريستون، لانكشاير، المملكة المتحدة. تأسست في عام 2012، وتقدم مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS، ودروس المحادثة. تركز الأكاديمية على التعليم الشخصي من خلال فصول صغيرة، مما يضمن اهتمامًا فرديًا بكل طالب. بالإضافة إلى ذلك، توفر PAE مرافق مثل مركز تعلم ذاتي وصالة للطلاب مزودة بخدمات الإنترنت اللاسلكي المجانية. الأكاديمية معتمدة من قبل المجلس الثقافي البريطاني، ومركز اختبار معتمد لامتحانات', 'school_logos/HMkvfayNXeYWuFbjkZWZQTwTeq0g2TpxSZdglQbe.png', '[\"1\",\"2\",\"13\"]', 0.00, 0, '2025-04-01 23:31:35', '2025-04-01 23:31:35'),
(16, 'Select English Cambridge', 'معهد سليكت انجليش', 'select-english-cambridge', 'Select English is an independent language school located in Cambridge, UK, established in 1991. The school offers a variety of English language courses, including General English, Intensive English, IELTS preparation, and summer programs for young learners aged 10 and above. Select English prides itself on providing high-quality teaching with small class sizes, ensuring individual attention from experienced instructors. The facilities include bright classrooms, a computer room, a pleasant garden, a common room, and free wireless internet access. The school is accredited by the British Council and is a member of English UK. Cambridge, known for its prestigious university, offers a rich cultural and academic environment for students.', 'تُعدّ Select English مدرسة لغات مستقلة تقع في مدينة كامبريدج، المملكة المتحدة، وقد تأسست عام 1991. تقدم المدرسة مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات IELTS، وبرامج صيفية للمراهقين بدءًا من سن 10 فما فوق. تتميز Select English بتقديم تعليم عالي الجودة مع التركيز على الفصول الصغيرة لضمان الاهتمام الفردي بكل طالب. تتضمن المرافق صفوفًا دراسية مشرقة، وغرفة حاسوب، وحديقة جميلة، وغرفة مشتركة، واتصال مجاني بشبكة الإنترنت اللاسلكية. المدرسة معتمدة من قبل المجلس الثقافي البريطاني وعضو في English UK.', 'school_logos/0C1zzyhiKdHs9oVi0SVhUWH3KhBpwMFhzVq31J79.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:33:31', '2025-04-01 23:33:31'),
(17, 'UK College of English', 'معهد اللغة الإنجليزية في المملكة المتحدة', 'uk-college-of-english', 'UK College of English (UKCE) is an independent language school situated in the heart of London, UK. Established in 2001, UKCE offers a variety of English language courses, including General English, IELTS preparation, Occupational English Test (OET) preparation, and private lessons. The college emphasizes personalized teaching with small class sizes, ensuring individual attention for each student. Facilities include air-conditioned classrooms, free Wi-Fi, and modern student amenities. UKCE is accredited by the British Council and is a member of English UK. The college is conveniently located near Liverpool Street Station, making it easily accessible for students.', 'هو معهد لغات مستقل يقع في قلب مدينة لندن، المملكة المتحدة. يقدم مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS وOET، والدروس الخصوصية. يتميز المعهد بتقديم تعليم مخصص مع أحجام فصول صغيرة، مما يضمن اهتمامًا فرديًا بكل طالب. تتضمن المرافق صفوفًا دراسية مجهزة تجهيزًا حديثًا، وغرفة حاسوب، وصالة للطلاب، واتصال مجاني بشبكة الإنترنت اللاسلكية. المعهد معتمد من قبل المجلس الثقافي البريطاني وعضو في English UK.', 'school_logos/NVh1EKiWKNU0XluwFTZOaEAyWLMhWCpM7Iwd64TW.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:36:50', '2025-04-01 23:36:50'),
(18, 'Westbourne Academy', 'معهد ويستبورن اكاديمى', 'westbourne-academy', 'Westbourne Academy in Bournemouth, Dorset, is a well-known English language school that offers a variety of English courses, including General English, IELTS preparation, and specialized programs. Located in the heart of Bournemouth, the academy is close to the beach, making it an ideal location for students to enjoy a vibrant coastal city while learning. Westbourne Academy prides itself on a supportive learning environment with small class sizes, providing personalized attention to each student. It also offers modern facilities, including a student lounge and access to free Wi-Fi. The school aims to deliver high-quality language education and cultural exchange.', 'أكاديمية ويستبورن في بورنموث، دورست، هي مدرسة معروفة لتعليم اللغة الإنجليزية تقدم مجموعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS، وبرامج متخصصة. تقع الأكاديمية في قلب مدينة بورنموث، بالقرب من الشاطئ، مما يجعلها مكانًا مثاليًا للطلاب للاستمتاع بمدينة ساحلية حيوية أثناء تعلم اللغة. تفتخر أكاديمية ويستبورن ببيئة تعليمية داعمة مع فصول دراسية صغيرة، مما يوفر اهتمامًا شخصيًا لكل طالب. كما توفر الأكاديمية مرافق حديثة، بما في ذلك صالة للطلاب وإمكانية الوصول إلى الإنترنت اللاسلكي المجاني. تهدف الأكاديمية إلى تقديم تعليم لغوي عالي الجودة وتعزيز التبادل الثقافي.', 'school_logos/F8wmvrqZnYABzZkHj7R7Yv6kFzjJShNnQ2w2E6mO.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:40:52', '2025-04-01 23:40:52'),
(20, 'Bright School of English', 'مدرسة برايت للغة الإنجليزية', 'bright-school-of-english', 'Southbourne School of English, established in 1966, is a family-run language school located in Bournemouth, UK. It offers a variety of English courses, including General English, Intensive English, and preparation for Cambridge and IELTS exams. The school provides modern classrooms, free Wi-Fi, and a student café. Accommodation options include homestays close to the school. Southbourne School emphasizes a friendly, supportive learning environment for students of all ages.', 'تأسست مدرسة ساوثبورن للغة الإنجليزية في عام 1966، وهي مدرسة لغات عائلية تقع في بورنموث، المملكة المتحدة. تقدم المدرسة مجموعة متنوعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات كامبريدج وIELTS. تشمل المرافق صفوفًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومقهى للطلاب. تشمل خيارات الإقامة الإقامة مع العائلات المضيفة القريبة من المدرسة. تركّز المدرسة على بيئة تعليمية ودية وداعمة.', 'school_logos/ppeb09Zt8ysBs5wuDo5eOVS6MpRx6qHsT7FkfZic.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:46:55', '2025-04-01 23:46:55'),
(21, 'Southbourne School of English', 'معهد ساوثبورن للغة الإنجليزية', 'southbourne-school-of-english', 'Southbourne School of English, established in 1966, is a family-run language school located in a peaceful area of Bournemouth, UK. The school offers various English courses, including General English, Intensive English, and exam preparation for Cambridge and IELTS. Facilities include modern classrooms, free Wi-Fi, a café, and a student lounge. Accommodation is provided with local homestays. The school ensures a friendly, supportive environment for students of all ages.', 'تأسست مدرسة ساوثبورن للغة الإنجليزية في عام 1966، وهي مدرسة لغات عائلية تقع في منطقة هادئة بمدينة بورنموث، المملكة المتحدة. تقدم المدرسة دورات متنوعة في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات كامبريدج وIELTS. تشمل المرافق صفوفًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومقهى، وصالة للطلاب. يتم توفير الإقامة مع العائلات المضيفة المحلية. تضمن المدرسة بيئة تعليمية وداعمة للطلاب من جميع الأعمار.', 'school_logos/OyZVsK4zOHKE02ffiYZ7ISVdKm5WZTojtV5bQLDv.png', '[\"1\",\"2\",\"11\"]', 0.00, 0, '2025-04-01 23:50:06', '2025-04-01 23:50:06'),
(22, 'Twin English Centre', 'معهد توين انجلش سنتر', 'twin-english-centre', 'Twin English Centres, established in 1993, operate campuses in London, Eastbourne, and Dublin. Accredited by the British Council in the UK and by ACELS in Ireland, they offer courses such as General English, Intensive English, IELTS Preparation, and Teacher Development. Facilities include modern classrooms, free Wi-Fi, and social areas. Accommodation options range from homestays to residential housing. Twin emphasizes employability and university progression support.', 'تأسست مراكز توين الإنجليزية في عام 1993، وتدير فروعًا في لندن، إيستبورن، ودبلن. المعتمدة من المجلس الثقافي البريطاني في المملكة المتحدة وACELS في أيرلندا، تقدم المدرسة دورات مثل الإنجليزية العامة، الإنجليزية المكثفة، التحضير لامتحانات IELTS، وتطوير المعلمين. تشمل المرافق فصولًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومناطق اجتماعية. خيارات الإقامة تشمل الإقامة مع العائلات المضيفة والمساكن الطلابية. تركّز توين على دعم التوظيف والتقدم الجامعي.', 'school_logos/GcWGTqDaN0AqANmrdU5VzZrGGr3HFiKqi1JpRTXk.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:53:32', '2025-04-01 23:53:32'),
(23, 'Burlington School', 'معهد بيرلينغتون للغة الإنجليزية', 'burlington-school', 'Burlington School, established in 1990, is a family-run English language school located in Balham, South London. The school offers a variety of courses, including General English, Business English, exam preparation, and programs for young learners and groups. Facilities include well-equipped classrooms, a library, computer access with free internet, and a cafeteria serving freshly cooked meals. Accommodation options are available on-site and in nearby homestays. Burlington School emphasizes a communicative approach to language learning, aiming to provide quality courses that meet diverse student needs.', 'تأسست مدرسة بيرلينغتون في عام 1990، وهي مدرسة لغات عائلية تقع في منطقة بالهام بجنوب لندن. تقدم المدرسة مجموعة من الدورات، بما في ذلك الإنجليزية العامة، الإنجليزية للأعمال، التحضير للامتحانات، وبرامج للشباب والمجموعات. تشمل المرافق صفوفًا مجهزة تجهيزًا جيدًا، مكتبة، حواسيب مع إمكانية الوصول إلى الإنترنت المجاني، ومقهى يقدم وجبات مطهية طازجة. تتوفر خيارات الإقامة داخل المدرسة أو مع العائلات المضيفة القريبة. تركز مدرسة بيرلينغتون على نهج تواصلي في تعلم اللغة، وتهدف إلى تقديم دورات ذات جودة تلبي احتياجات الطلاب المتنوعة.', 'school_logos/It7Hh5Ekj9cf3hoYqyb9wV9Y3MdDQxzT9JnDwDZT.png', '[\"1\",\"2\"]', 0.00, 0, '2025-04-01 23:59:48', '2025-04-01 23:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_accommodations`
--

CREATE TABLE `language_school_accommodations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `language_course_tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bedroom_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bathroom_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meal_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `required_age` int(10) UNSIGNED DEFAULT NULL,
  `fee_per_week` decimal(12,2) DEFAULT NULL,
  `admin_charge` decimal(12,2) DEFAULT NULL,
  `under18_supplement_per_week` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `image` varchar(255) DEFAULT NULL,
  `details` longtext DEFAULT NULL,
  `ar_details` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_accommodations`
--

INSERT INTO `language_school_accommodations` (`id`, `school_branch_id`, `language_course_tag_id`, `bedroom_type_id`, `bathroom_type_id`, `meal_plan_id`, `required_age`, `fee_per_week`, `admin_charge`, `under18_supplement_per_week`, `notes`, `branch_id`, `title`, `ar_title`, `slug`, `description`, `ar_description`, `price`, `currency`, `features`, `image`, `details`, `ar_details`, `created_at`, `updated_at`) VALUES
(11, 6, 1, 4, 2, 2, 18, 255.00, NULL, 20.00, NULL, 6, 'Homestay', 'إقامة مع عائلة', 'homestay-6-11', NULL, NULL, 255.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"1\",\"required_age\":\"18\",\"admin_charge\":null,\"under_sup\":\"20\"}', NULL, NULL, NULL, '2025-03-25 20:45:49', '2025-03-25 20:45:49'),
(12, 7, 2, 4, 2, 2, 18, 215.00, NULL, 20.00, NULL, 7, 'Homestay', 'إقامة مع عائلة', 'homestay-7-12', NULL, NULL, 215.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":null,\"under_sup\":\"20\"}', NULL, NULL, NULL, '2025-03-25 20:46:30', '2025-03-25 20:46:30'),
(13, 8, 2, 4, 2, 2, 18, 240.00, NULL, 20.00, NULL, 8, 'Homestay', 'إقامة مع عائلة', 'homestay-8-13', NULL, NULL, 240.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":null,\"under_sup\":\"20\"}', NULL, NULL, NULL, '2025-03-25 20:47:05', '2025-03-25 20:47:05'),
(14, 21, 2, 4, 2, 2, 17, 195.00, NULL, NULL, NULL, 21, 'Homestay', 'إقامة مع عائلة', 'homestay-21-14', NULL, NULL, 195.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"17\",\"admin_charge\":null,\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 17:35:29', '2025-04-02 17:35:29'),
(15, 12, 2, 4, 2, 2, 18, 310.00, 55.00, NULL, NULL, 12, 'Homestay', 'إقامة مع عائلة', 'homestay-12-15', NULL, NULL, 310.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"55.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 17:39:10', '2025-04-02 17:39:10'),
(16, 22, 2, 4, 2, 2, 16, 190.00, 50.00, NULL, NULL, 22, 'Homestay', 'إقامة مع عائلة', 'homestay-22-16', NULL, NULL, 190.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"16\",\"admin_charge\":\"50.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 17:50:45', '2025-04-02 17:50:45'),
(17, 13, 2, 4, 2, 2, 17, 230.00, 70.00, NULL, NULL, 13, 'Homestay', 'إقامة مع عائلة', 'homestay-13-17', NULL, NULL, 230.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"17\",\"admin_charge\":\"70.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:03:49', '2025-04-02 18:03:49'),
(18, 23, 2, 4, 2, 2, NULL, 175.00, 10.00, NULL, NULL, 23, 'Homestay', 'إقامة مع عائلة', 'homestay-23-18', NULL, NULL, 175.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":null,\"admin_charge\":\"10.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:04:49', '2025-04-02 18:04:49'),
(19, 20, 2, 4, 2, 2, 18, 390.00, 50.00, NULL, NULL, 20, 'Homestay', 'إقامة مع عائلة', 'homestay-20-19', NULL, NULL, 390.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"50.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:05:54', '2025-04-02 18:05:54'),
(20, 19, 2, 4, 2, 2, 16, 270.00, NULL, NULL, NULL, 19, 'Homestay', 'إقامة مع عائلة', 'homestay-19-20', NULL, NULL, 270.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"16\",\"admin_charge\":null,\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:07:06', '2025-04-02 18:07:06'),
(21, 18, 2, 4, 3, 3, 18, 160.00, 50.00, NULL, NULL, 18, 'Residence', 'سكن طلاب', 'residence-18-21', NULL, NULL, 160.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"3\",\"meal_id\":\"3\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"50.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:10:51', '2025-04-02 18:10:51'),
(22, 9, 2, 4, 2, 2, 16, 255.00, 100.00, NULL, NULL, 9, 'Homestay', 'إقامة مع عائلة', 'homestay-9-22', NULL, NULL, 255.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"16\",\"admin_charge\":\"100.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:12:53', '2025-04-02 18:12:53'),
(23, 24, 2, 4, 2, 2, 18, 260.00, 55.00, NULL, NULL, 24, 'Homestay', 'إقامة مع عائلة', 'homestay-24-23', NULL, NULL, 260.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"55.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:16:02', '2025-04-02 18:16:02'),
(24, 17, 2, 4, 2, 2, NULL, 275.00, NULL, NULL, NULL, 17, 'Homestay', 'إقامة مع عائلة', 'homestay-17-24', NULL, NULL, 275.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":null,\"admin_charge\":null,\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:23:53', '2025-04-02 18:23:53'),
(25, 10, 2, 4, 2, 2, 18, 203.00, NULL, NULL, NULL, 10, 'Homestay', 'إقامة مع عائلة', 'homestay-10-25', NULL, NULL, 203.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":null,\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:24:33', '2025-04-02 18:24:33'),
(26, 16, 2, 4, 2, 2, 18, 220.00, 65.00, 35.00, NULL, 16, 'Homestay', 'إقامة مع عائلة', 'homestay-16-26', NULL, NULL, 220.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"65.00\",\"under_sup\":\"35\"}', NULL, NULL, NULL, '2025-04-02 18:25:34', '2025-04-02 18:25:34'),
(27, 14, 2, 4, 2, 2, 18, 195.00, NULL, 17.00, NULL, 14, 'Homestay', 'إقامة مع عائلة', 'homestay-14-27', NULL, NULL, 195.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":null,\"under_sup\":\"17\"}', NULL, NULL, NULL, '2025-04-02 18:26:30', '2025-04-02 18:26:30'),
(28, 15, 2, 4, 2, 2, 18, 220.00, 75.00, NULL, NULL, 15, 'Homestay', 'إقامة مع عائلة', 'homestay-15-28', NULL, NULL, 220.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":\"2\",\"required_age\":\"18\",\"admin_charge\":\"75.00\",\"under_sup\":null}', NULL, NULL, NULL, '2025-04-02 18:27:10', '2025-04-02 18:27:10'),
(29, 25, NULL, 4, 2, 2, 18, 280.00, 50.00, 25.00, NULL, 25, 'Homestay', 'إقامة مع عائلة', 'homestay-25-29', NULL, NULL, 280.00, 'GBP', '{\"bedroom_id\":\"4\",\"bathroom_id\":\"2\",\"meal_id\":\"2\",\"tag_id\":null,\"required_age\":\"18\",\"admin_charge\":\"50.00\",\"under_sup\":\"25\"}', NULL, NULL, NULL, '2025-04-02 18:28:16', '2025-04-02 18:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_branches`
--

CREATE TABLE `language_school_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_school_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `ar_description` mediumtext DEFAULT NULL,
  `gallery_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_urls`)),
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_branches`
--

INSERT INTO `language_school_branches` (`id`, `language_school_id`, `city_id`, `slug`, `description`, `ar_description`, `gallery_urls`, `video_url`, `created_at`, `updated_at`) VALUES
(6, 5, 1, 'lsi-education-london', 'The institute is based in London...', 'يقع المعهد في مدينة لندن...', '[\"branch_images\\/zXbauucJHKBgUYQTNKeUWEdXHclcB759F6OOHSTP.jpg\"]', NULL, '2025-03-25 15:55:13', '2025-03-25 15:55:13'),
(7, 5, 19, 'lsi-education-brighton', 'The institute is based in London...', 'يقع المعهد في مدينة لندن...', '[\"branch_images\\/saZliSlic8lfIQOFdCAM7hAipVZZ7dXQnOWqE7t3.jpg\"]', NULL, '2025-03-25 16:03:21', '2025-03-25 16:03:21'),
(8, 5, 20, 'lsi-education-cambridge', 'The institute is based in London...', 'يقع المعهد في مدينة لندن...', '[\"branch_images\\/xtQEm27paxGTQUIfbwWvvnNUhQrWbzWXcXfhKjyB.jpg\"]', NULL, '2025-03-25 16:04:58', '2025-03-25 16:04:58'),
(9, 7, 22, 'bath-academy-of-english-bath', 'Bath Academy of English...', 'أكاديمية بات الإنجليزية...', '[\"branch_images\\/AzCHWDovmV2Q7u9fumat3lhEaA5EDPUwn8I9N3cr.jpg\"]', NULL, '2025-04-01 14:10:16', '2025-04-01 14:10:16'),
(10, 8, 34, 'concorde-international-canterbury', 'Concorde International...', 'كونكورد إنترناشونال...', '[\"branch_images\\/9OjxRxqrgXLyXDAnqIGJMQ4eaQ5WemF0aoLcy7bB.jpg\"]', NULL, '2025-04-01 14:12:11', '2025-04-01 14:12:11'),
(12, 9, 1, 'islington-centre-for-english-london', 'The Islington Centre for English...', 'يتموقع معهد The Islington Centre...', '[\"branch_images\\/CBidsV0PH3X2qKPhxQz9FJeRDVHMOyTmf9lMcxV3.jpg\"]', NULL, '2025-04-01 14:15:17', '2025-04-01 14:15:17'),
(13, 10, 1, 'the-london-school-of-english-london', 'The London School of English...', 'معهد The London School of English...', '[\"branch_images\\/qcWZAKG61C0LMNPuA8BDUDctL1UnEEmIzR5FBUb6.jpg\"]', NULL, '2025-04-01 14:17:04', '2025-04-01 14:17:04'),
(14, 11, 24, 'beet-language-centre-bournemouth', 'BEET English Language Center...', 'معهد بيت إنجلش سنتر...', '[\"branch_images\\/5zdbZ33NfEsO08uSMbjr6ny5ATvSDOH1bHYlpBnc.jpg\"]', NULL, '2025-04-01 14:18:42', '2025-04-01 14:18:42'),
(15, 12, 2, 'berlitz-manchester-manchester', 'Berlitz Institute...', 'معهد بيرلتز...', '[\"branch_images\\/Qil6LjbxJrZyqjXfURX8NHAQuggniHzscVChC13p.jpg\"]', NULL, '2025-04-01 14:19:22', '2025-04-01 14:19:22'),
(16, 13, 2, 'britannia-english-academy-manchester', 'Britannia English Academy...', 'أكاديمية بريتانيا الإنجليزية...', '[\"branch_images\\/BOzeLRnwplHO1ToNbdA3IqtIAMRKeMt9uIX1qxz0.jpg\"]', NULL, '2025-04-01 14:20:11', '2025-04-01 14:20:11'),
(17, 14, 21, 'oxford-international-study-centre-oxford', 'Oxford International Study Centre...', 'مركز أكسفورد الدولي للدراسات...', '[\"branch_images\\/IuFQLQuFdJYl5KMnNr8AwyF2ajUEmJ6iUpegNKL5.jpg\"]', NULL, '2025-04-01 14:20:47', '2025-04-01 14:20:47'),
(18, 15, 54, 'preston-academy-of-english-preston', 'Preston Academy of English...', 'أكاديمية بريستون...', '[\"branch_images\\/rbkowcEL6dsVVOt0XKtvcRhM9EL06TUlERhKH6oc.jpg\"]', NULL, '2025-04-01 14:22:19', '2025-04-01 14:22:19'),
(19, 16, 20, 'select-english-cambridge-cambridge', 'Select English...', 'تُعدّ Select English...', '[\"branch_images\\/9uVCx1MlDYKSWdeqCxh2t3uq96HksK2x9J9ETsQH.jpg\"]', NULL, '2025-04-01 14:23:04', '2025-04-01 14:23:04'),
(20, 17, 1, 'uk-college-of-english-london', 'UK College of English...', 'هو معهد لغات مستقل...', '[\"branch_images\\/UjFJTA0YaaJLUplYWfLFNYRFS9eEZsIayjxu8xjv.jpg\"]', NULL, '2025-04-01 14:23:46', '2025-04-01 14:23:46'),
(21, 18, 24, 'westbourne-academy-bournemouth', 'Westbourne Academy...', 'أكاديمية ويستبورن...', '[\"branch_images\\/S7tHUf6BkAqdpFNUgJPac9TEZzBoEHMIqudzZZa9.jpg\"]', NULL, '2025-04-01 14:24:33', '2025-04-01 14:24:33'),
(22, 20, 24, 'bright-school-of-english-bournemouth', 'Bright School of English...', 'مدرسة برايت...', '[\"branch_images\\/rj2KUaNpg6GxN0DwiWwJ3BzV7hfBlFs6zsQXcw6j.jpg\"]', NULL, '2025-04-01 14:25:13', '2025-04-01 14:25:13'),
(23, 21, 24, 'southbourne-school-of-english-bournemouth', 'Southbourne School of English...', 'تأسست مدرسة ساوثبورن...', '[\"branch_images\\/ZnsacDUD6tChnp7FxiTn7vYXUkB4DZlrhdmnBpzt.jpg\"]', NULL, '2025-04-01 14:26:06', '2025-04-01 14:26:06'),
(24, 22, 1, 'twin-english-centre-london', 'Twin English Centres...', 'تأسست مراكز توين...', '[\"branch_images\\/RuZBn3eGEDfBLP9M1UrBsz7VvsuxVHnITZ0B3DJv.jpg\"]', NULL, '2025-04-01 14:26:45', '2025-04-01 14:26:45'),
(25, 23, 1, 'burlington-school-london', 'Burlington School...', 'تأسست مدرسة بيرلينغتون...', '[\"branch_images\\/yBfQTzwDF0BlcenPvRz7amxEXcHqbWJfWAuWg5IT.jpg\"]', NULL, '2025-04-01 14:29:13', '2025-04-01 14:29:13');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_branch_high_season_fees`
--

CREATE TABLE `language_school_branch_high_season_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `week_start` smallint(5) UNSIGNED NOT NULL,
  `week_end` smallint(5) UNSIGNED DEFAULT NULL,
  `fee` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_branch_high_season_fees`
--

INSERT INTO `language_school_branch_high_season_fees` (`id`, `branch_id`, `week_start`, `week_end`, `fee`, `created_at`, `updated_at`) VALUES
(2, 15, 24, 35, 35.00, '2025-04-02 16:38:42', '2025-04-02 16:38:42'),
(3, 15, 24, 35, 35.00, '2025-04-02 16:38:46', '2025-04-02 16:38:46'),
(4, 15, 24, 35, 35.00, '2025-04-02 16:38:51', '2025-04-02 16:38:51');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_branch_registration_fees`
--

CREATE TABLE `language_school_branch_registration_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_branch_registration_fees`
--

INSERT INTO `language_school_branch_registration_fees` (`id`, `branch_id`, `amount`, `created_at`, `updated_at`) VALUES
(5, 6, 95.00, '2025-03-25 20:53:10', '2025-03-25 20:53:10'),
(6, 7, 95.00, '2025-03-25 20:53:12', '2025-03-25 20:53:12'),
(7, 8, 95.00, '2025-03-25 20:53:16', '2025-03-25 20:53:16'),
(8, 9, 65.00, '2025-04-02 16:40:00', '2025-04-02 16:40:00'),
(9, 21, 100.00, '2025-04-02 16:40:55', '2025-04-02 16:40:55'),
(10, 12, 80.00, '2025-04-02 16:41:19', '2025-04-02 16:41:19'),
(11, 22, 50.00, '2025-04-02 16:45:01', '2025-04-02 16:45:01'),
(12, 13, 100.00, '2025-04-02 16:45:16', '2025-04-02 16:45:16'),
(13, 23, 120.00, '2025-04-02 16:45:38', '2025-04-02 16:45:38'),
(14, 20, 80.00, '2025-04-02 16:46:35', '2025-04-02 16:46:35'),
(15, 19, 50.00, '2025-04-02 16:47:40', '2025-04-02 16:47:40'),
(16, 18, 50.00, '2025-04-02 16:47:55', '2025-04-02 16:47:55'),
(17, 24, 55.00, '2025-04-02 16:48:40', '2025-04-02 16:48:40'),
(18, 17, 90.00, '2025-04-02 16:48:52', '2025-04-02 16:48:52'),
(19, 10, 98.00, '2025-04-02 16:49:06', '2025-04-02 16:49:06'),
(20, 16, 65.00, '2025-04-02 16:49:27', '2025-04-02 16:49:27'),
(21, 14, 115.00, '2025-04-02 16:49:51', '2025-04-02 16:49:51'),
(22, 15, 75.00, '2025-04-02 16:50:16', '2025-04-02 16:50:16'),
(23, 25, 50.00, '2025-04-02 16:51:01', '2025-04-02 16:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_coupons`
--

CREATE TABLE `language_school_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount_type` enum('percent','flat') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `usage_limit` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `expiration_date` date DEFAULT NULL,
  `minimum_purchase_amount` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_coupons`
--

INSERT INTO `language_school_coupons` (`id`, `code`, `name`, `discount_type`, `discount_value`, `usage_limit`, `used_count`, `expiration_date`, `minimum_purchase_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '827754', 'test', 'percent', 10.00, 100, 0, NULL, 1000.00, 1, '2026-02-07 08:14:06', '2026-02-07 08:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_courses`
--

CREATE TABLE `language_school_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `language_course_type_id` bigint(20) UNSIGNED NOT NULL,
  `language_course_tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(160) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `ar_name` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `ar_description` mediumtext DEFAULT NULL,
  `start_day` varchar(32) DEFAULT NULL,
  `required_level` varchar(32) DEFAULT NULL,
  `study_time` varchar(30) DEFAULT NULL,
  `lessons_per_week` varchar(30) DEFAULT NULL,
  `min_age` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_courses`
--

INSERT INTO `language_school_courses` (`id`, `branch_id`, `language_course_type_id`, `language_course_tag_id`, `slug`, `name`, `ar_name`, `description`, `ar_description`, `start_day`, `required_level`, `study_time`, `lessons_per_week`, `min_age`, `created_at`, `updated_at`) VALUES
(18, 6, 1, 5, 'general-english-6-18', 'General English', 'دورة اللغة الإنجليزية العامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-03-25 15:57:25', '2025-03-25 15:57:25'),
(19, 6, 3, 2, 'semi-intensive-english-6-19', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '24', '16', '2025-03-25 16:00:01', '2025-03-25 16:00:01'),
(20, 6, 2, 1, 'intensive-english-6-20', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-03-25 16:01:19', '2025-03-25 16:01:19'),
(21, 7, 1, 5, 'general-english-7-21', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-03-25 16:08:37', '2025-03-25 16:08:37'),
(22, 7, 3, 2, 'semi-intensive-english-7-22', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '24', '16', '2025-03-25 16:10:03', '2025-03-25 16:10:03'),
(23, 7, 2, 1, 'intensive-english-7-23', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-03-25 16:11:08', '2025-03-25 16:11:08'),
(24, 7, 4, 1, 'ielts-exam-preparation-7-24', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', NULL, '30', '16', '2025-03-25 16:13:49', '2025-03-25 16:13:49'),
(25, 8, 1, 5, 'general-english-8-25', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-03-25 16:15:25', '2025-03-25 16:15:25'),
(26, 8, 3, 2, 'semi-intensive-english-8-26', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '24', '16', '2025-03-25 16:16:17', '2025-03-25 16:16:17'),
(27, 8, 2, 1, 'intensive-english-8-27', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-03-25 16:17:44', '2025-03-25 16:17:44'),
(28, 8, 4, 2, 'ielts-exam-preparation-8-28', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', NULL, '30', '16', '2025-03-25 16:18:34', '2025-03-25 16:18:34'),
(29, 9, 1, 5, 'general-english-9-29', 'General English', 'دورة اللغة الإنجليزية العامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-01 14:33:13', '2025-04-01 14:33:13'),
(31, 9, 2, 7, 'intensive-english-9-31', 'Intensive English', 'دورة اللغة الإنجليزية المكثفة', NULL, NULL, 'Every Monday', 'A1', '23', '30', '16', '2025-04-01 14:37:45', '2025-04-01 14:37:45'),
(32, 9, 4, 7, 'ielts-exam-preparation-9-32', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '15', '20', '16', '2025-04-01 14:38:52', '2025-04-01 14:38:52'),
(33, 10, 1, 6, 'general-english-10-33', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-01 14:53:56', '2025-04-01 14:53:56'),
(34, 10, 3, 7, 'semi-intensive-english-10-34', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A1', '21', '30', '16', '2025-04-01 14:54:44', '2025-04-01 14:54:44'),
(35, 10, 2, 6, 'intensive-english-10-35', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-04-01 14:55:53', '2025-04-01 14:55:53'),
(36, 10, 4, 6, 'ielts-exam-preparation-10-36', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '10', '10', '16', '2025-04-01 14:56:42', '2025-04-01 14:56:42'),
(37, 12, 1, 6, 'general-english-12-37', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-01 14:59:49', '2025-04-01 14:59:49'),
(38, 12, 5, 7, 'super-intensive-english-12-38', 'Super-Intensive English', 'دورة لغة إنجليزية عالية الكثافة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-04-01 15:00:58', '2025-04-01 15:00:58'),
(39, 12, 4, 6, 'ielts-exam-preparation-12-39', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '15', NULL, '16', '2025-04-01 15:01:57', '2025-04-01 15:01:57'),
(40, 13, 1, 5, 'general-english-13-40', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A2', '12', '15', '20', '2025-04-01 15:06:39', '2025-04-01 15:06:39'),
(41, 13, 2, 6, 'intensive-english-13-41', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A2', '24', '30', '20', '2025-04-01 15:07:50', '2025-04-01 15:07:50'),
(42, 14, 1, 5, 'general-english-14-42', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 10:00:25', '2025-04-02 10:00:25'),
(43, 14, 2, 6, 'intensive-english-14-43', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '18', '24', '16', '2025-04-02 10:01:22', '2025-04-02 10:01:22'),
(44, 14, 5, 2, 'super-intensive-english-14-44', 'Super-Intensive English', 'دورة لغة إنجليزية عالية الكثافة', NULL, NULL, 'Every Monday', 'A1', '21', '28', '16', '2025-04-02 10:02:16', '2025-04-02 10:02:16'),
(45, 15, 1, 5, 'general-english-15-45', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 10:03:10', '2025-04-02 10:03:10'),
(46, 15, 2, 7, 'intensive-english-15-46', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '25', '16', '2025-04-02 10:03:54', '2025-04-02 10:03:54'),
(47, 15, 5, 2, 'super-intensive-english-15-47', 'Super-Intensive English', 'دورة لغة إنجليزية عالية الكثافة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-04-02 10:05:04', '2025-04-02 10:05:04'),
(48, 16, 1, 5, 'general-english-16-48', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 10:10:25', '2025-04-02 10:10:25'),
(49, 16, 3, 2, 'semi-intensive-english-16-49', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '25', '16', '2025-04-02 10:11:15', '2025-04-02 10:11:15'),
(50, 16, 2, 6, 'intensive-english-16-50', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '25', '30', '16', '2025-04-02 10:12:05', '2025-04-02 10:12:05'),
(51, 17, 1, 5, 'general-english-17-51', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 10:13:05', '2025-04-02 10:13:05'),
(52, 17, 2, 2, 'intensive-english-17-52', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '22.5', '30', '16', '2025-04-02 10:14:21', '2025-04-02 10:14:21'),
(53, 18, 1, 5, 'general-english-18-53', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 11:30:36', '2025-04-02 11:30:36'),
(54, 18, 2, 2, 'intensive-english-18-54', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '30', '16', '2025-04-02 11:31:29', '2025-04-02 11:31:29'),
(55, 18, 4, 7, 'ielts-exam-preparation-18-55', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '25', '30', '16', '2025-04-02 11:32:13', '2025-04-02 11:32:13'),
(56, 20, 1, 5, 'general-english-20-56', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 11:33:25', '2025-04-02 11:33:25'),
(57, 20, 5, 2, 'super-intensive-english-20-57', 'Super-Intensive English', 'دورة لغة إنجليزية عالية الكثافة', NULL, NULL, 'Every Monday', 'A1', '27', '30', '16', '2025-04-02 11:34:07', '2025-04-02 11:34:07'),
(58, 20, 4, 1, 'ielts-exam-preparation-20-58', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '15', '20', '16', '2025-04-02 11:35:14', '2025-04-02 11:35:14'),
(59, 19, 1, 5, 'general-english-19-59', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 11:36:13', '2025-04-02 11:36:13'),
(60, 19, 2, 2, 'intensive-english-19-60', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '21', '28', '16', '2025-04-02 11:36:49', '2025-04-02 11:36:49'),
(61, 19, 4, 7, 'ielts-exam-preparation-19-61', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '24', '32', '16', '2025-04-02 11:37:39', '2025-04-02 11:37:39'),
(62, 21, 1, 6, 'general-english-21-62', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 11:41:14', '2025-04-02 11:41:14'),
(63, 21, 3, 2, 'semi-intensive-english-21-63', 'Semi-Intensive English', 'دورة لغة إنجليزية شبه مكثفة', NULL, NULL, 'Every Monday', 'A2', '20', '30', '16', '2025-04-02 11:43:08', '2025-04-02 11:43:08'),
(64, 21, 2, 7, 'intensive-english-21-64', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A2', '26', '40', '16', '2025-04-02 11:44:39', '2025-04-02 11:44:39'),
(65, 21, 4, 2, 'ielts-exam-preparation-21-65', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '15', '20', '16', '2025-04-02 11:45:39', '2025-04-02 11:45:39'),
(66, 22, 1, 5, 'general-english-22-66', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 11:50:17', '2025-04-02 11:50:17'),
(67, 22, 1, 2, 'general-english-22-67', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '20', '25', '16', '2025-04-02 11:56:47', '2025-04-02 11:56:47'),
(68, 22, 4, 1, 'ielts-exam-preparation-22-68', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '20', '25', '16', '2025-04-02 11:58:20', '2025-04-02 11:58:20'),
(69, 23, 1, 5, 'general-english-23-69', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 12:50:05', '2025-04-02 12:50:05'),
(70, 23, 2, 6, 'intensive-english-23-70', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '20', '25', '16', '2025-04-02 12:50:41', '2025-04-02 12:50:41'),
(71, 24, 1, 5, 'general-english-24-71', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 12:52:54', '2025-04-02 12:52:54'),
(72, 24, 4, 6, 'ielts-exam-preparation-24-72', 'IELTS Exam Preparation', 'دورة تحضير الآيلتس IELTS', NULL, NULL, 'Every Monday', 'B1', '21', '28', '16', '2025-04-02 12:53:35', '2025-04-02 12:53:35'),
(73, 25, 1, 5, 'general-english-25-73', 'General English', 'دورة لغة إنجليزية عامة', NULL, NULL, 'Every Monday', 'A1', '15', '20', '16', '2025-04-02 12:54:24', '2025-04-02 12:54:24'),
(74, 25, 2, 2, 'intensive-english-25-74', 'Intensive English', 'دورة لغة إنجليزية مكثفة', NULL, NULL, 'Every Monday', 'A1', '22.5', '30', '16', '2025-04-02 12:55:19', '2025-04-02 12:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_course_fees`
--

CREATE TABLE `language_school_course_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_school_course_id` bigint(20) UNSIGNED NOT NULL,
  `week_number` int(10) UNSIGNED NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `price_split` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_course_fees`
--

INSERT INTO `language_school_course_fees` (`id`, `language_school_course_id`, `week_number`, `fee`, `valid_from`, `valid_to`, `price_split`, `created_at`, `updated_at`) VALUES
(1, 18, 1, 345.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(2, 18, 2, 345.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(3, 18, 3, 345.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(4, 18, 4, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(5, 18, 5, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(6, 18, 6, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(7, 18, 7, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(8, 18, 8, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(9, 18, 9, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(10, 18, 10, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(11, 18, 11, 335.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(12, 18, 12, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(13, 18, 13, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(14, 18, 14, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(15, 18, 15, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(16, 18, 16, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(17, 18, 17, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(18, 18, 18, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(19, 18, 19, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(20, 18, 20, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(21, 18, 21, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(22, 18, 22, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(23, 18, 23, 325.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(24, 18, 24, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(25, 18, 25, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(26, 18, 26, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(27, 18, 27, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(28, 18, 28, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(29, 18, 29, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(30, 18, 30, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(31, 18, 31, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(32, 18, 32, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(33, 18, 33, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(34, 18, 34, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(35, 18, 35, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(36, 18, 36, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(37, 18, 37, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(38, 18, 38, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(39, 18, 39, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(40, 18, 40, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(41, 18, 41, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(42, 18, 42, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(43, 18, 43, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(44, 18, 44, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(45, 18, 45, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(46, 18, 46, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(47, 18, 47, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(48, 18, 48, 295.00, NULL, NULL, 'yes', '2025-03-25 16:20:53', '2025-03-25 16:20:53'),
(49, 19, 1, 395.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(50, 19, 2, 395.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(51, 19, 3, 395.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(52, 19, 4, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(53, 19, 5, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(54, 19, 6, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(55, 19, 7, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(56, 19, 8, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(57, 19, 9, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(58, 19, 10, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(59, 19, 11, 380.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(60, 19, 12, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(61, 19, 13, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(62, 19, 14, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(63, 19, 15, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(64, 19, 16, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(65, 19, 17, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(66, 19, 18, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(67, 19, 19, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(68, 19, 20, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(69, 19, 21, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(70, 19, 22, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(71, 19, 23, 360.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(72, 19, 24, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(73, 19, 25, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(74, 19, 26, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(75, 19, 27, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(76, 19, 28, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(77, 19, 29, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(78, 19, 30, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(79, 19, 31, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(80, 19, 32, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(81, 19, 33, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(82, 19, 34, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(83, 19, 35, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(84, 19, 36, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(85, 19, 37, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(86, 19, 38, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(87, 19, 39, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(88, 19, 40, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(89, 19, 41, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(90, 19, 42, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(91, 19, 43, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(92, 19, 44, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(93, 19, 45, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(94, 19, 46, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(95, 19, 47, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(96, 19, 48, 330.00, NULL, NULL, 'yes', '2025-03-25 16:23:07', '2025-03-25 16:23:07'),
(97, 20, 1, 455.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(98, 20, 2, 455.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(99, 20, 3, 455.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(100, 20, 4, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(101, 20, 5, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(102, 20, 6, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(103, 20, 7, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(104, 20, 8, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(105, 20, 9, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(106, 20, 10, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(107, 20, 11, 410.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(108, 20, 12, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(109, 20, 13, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(110, 20, 14, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(111, 20, 15, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(112, 20, 16, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(113, 20, 17, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(114, 20, 18, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(115, 20, 19, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(116, 20, 20, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(117, 20, 21, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(118, 20, 22, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(119, 20, 23, 390.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(120, 20, 24, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(121, 20, 25, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(122, 20, 26, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(123, 20, 27, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(124, 20, 28, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(125, 20, 29, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(126, 20, 30, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(127, 20, 31, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(128, 20, 32, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(129, 20, 33, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(130, 20, 34, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(131, 20, 35, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(132, 20, 36, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(133, 20, 37, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(134, 20, 38, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(135, 20, 39, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(136, 20, 40, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(137, 20, 41, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(138, 20, 42, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(139, 20, 43, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(140, 20, 44, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(141, 20, 45, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(142, 20, 46, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(143, 20, 47, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(144, 20, 48, 350.00, NULL, NULL, 'yes', '2025-03-25 16:24:54', '2025-03-25 16:24:54'),
(145, 21, 1, 345.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(146, 21, 2, 345.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(147, 21, 3, 345.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(148, 21, 4, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(149, 21, 5, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(150, 21, 6, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(151, 21, 7, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(152, 21, 8, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(153, 21, 9, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(154, 21, 10, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(155, 21, 11, 335.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(156, 21, 12, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(157, 21, 13, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(158, 21, 14, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(159, 21, 15, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(160, 21, 16, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(161, 21, 17, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(162, 21, 18, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(163, 21, 19, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(164, 21, 20, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(165, 21, 21, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(166, 21, 22, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(167, 21, 23, 325.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(168, 21, 24, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(169, 21, 25, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(170, 21, 26, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(171, 21, 27, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(172, 21, 28, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(173, 21, 29, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(174, 21, 30, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(175, 21, 31, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(176, 21, 32, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(177, 21, 33, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(178, 21, 34, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(179, 21, 35, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(180, 21, 36, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(181, 21, 37, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(182, 21, 38, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(183, 21, 39, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(184, 21, 40, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(185, 21, 41, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(186, 21, 42, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(187, 21, 43, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(188, 21, 44, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(189, 21, 45, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(190, 21, 46, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(191, 21, 47, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(192, 21, 48, 295.00, NULL, NULL, 'yes', '2025-03-25 16:35:57', '2025-03-25 16:35:57'),
(193, 22, 1, 395.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(194, 22, 2, 395.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(195, 22, 3, 395.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(196, 22, 4, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(197, 22, 5, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(198, 22, 6, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(199, 22, 7, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(200, 22, 8, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(201, 22, 9, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(202, 22, 10, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(203, 22, 11, 380.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(204, 22, 12, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(205, 22, 13, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(206, 22, 14, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(207, 22, 15, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(208, 22, 16, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(209, 22, 17, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(210, 22, 18, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(211, 22, 19, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(212, 22, 20, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(213, 22, 21, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(214, 22, 22, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(215, 22, 23, 360.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(216, 22, 24, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(217, 22, 25, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(218, 22, 26, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(219, 22, 27, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(220, 22, 28, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(221, 22, 29, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(222, 22, 30, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(223, 22, 31, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(224, 22, 32, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(225, 22, 33, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(226, 22, 34, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(227, 22, 35, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(228, 22, 36, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(229, 22, 37, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(230, 22, 38, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(231, 22, 39, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(232, 22, 40, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(233, 22, 41, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(234, 22, 42, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(235, 22, 43, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(236, 22, 44, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(237, 22, 45, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(238, 22, 46, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(239, 22, 47, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(240, 22, 48, 330.00, NULL, NULL, 'yes', '2025-03-25 16:37:44', '2025-03-25 16:37:44'),
(241, 23, 1, 455.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(242, 23, 2, 455.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(243, 23, 3, 455.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(244, 23, 4, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(245, 23, 5, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(246, 23, 6, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(247, 23, 7, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(248, 23, 8, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(249, 23, 9, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(250, 23, 10, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(251, 23, 11, 410.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(252, 23, 12, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(253, 23, 13, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(254, 23, 14, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(255, 23, 15, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(256, 23, 16, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(257, 23, 17, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(258, 23, 18, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(259, 23, 19, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(260, 23, 20, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(261, 23, 21, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(262, 23, 22, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(263, 23, 23, 390.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(264, 23, 24, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(265, 23, 25, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(266, 23, 26, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(267, 23, 27, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(268, 23, 28, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(269, 23, 29, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(270, 23, 30, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(271, 23, 31, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(272, 23, 32, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(273, 23, 33, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(274, 23, 34, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(275, 23, 35, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(276, 23, 36, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(277, 23, 37, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(278, 23, 38, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(279, 23, 39, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(280, 23, 40, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(281, 23, 41, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(282, 23, 42, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(283, 23, 43, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(284, 23, 44, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(285, 23, 45, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(286, 23, 46, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(287, 23, 47, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(288, 23, 48, 350.00, NULL, NULL, 'yes', '2025-03-25 16:42:14', '2025-03-25 16:42:14'),
(289, 24, 1, 455.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(290, 24, 2, 455.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(291, 24, 3, 455.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(292, 24, 4, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(293, 24, 5, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(294, 24, 6, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(295, 24, 7, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(296, 24, 8, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(297, 24, 9, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(298, 24, 10, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(299, 24, 11, 410.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(300, 24, 12, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(301, 24, 13, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(302, 24, 14, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(303, 24, 15, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(304, 24, 16, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(305, 24, 17, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(306, 24, 18, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(307, 24, 19, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(308, 24, 20, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(309, 24, 21, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(310, 24, 22, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(311, 24, 23, 390.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(312, 24, 24, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(313, 24, 25, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(314, 24, 26, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(315, 24, 27, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(316, 24, 28, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(317, 24, 29, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(318, 24, 30, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(319, 24, 31, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(320, 24, 32, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(321, 24, 33, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(322, 24, 34, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(323, 24, 35, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(324, 24, 36, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(325, 24, 37, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(326, 24, 38, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(327, 24, 39, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(328, 24, 40, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(329, 24, 41, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(330, 24, 42, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(331, 24, 43, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(332, 24, 44, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(333, 24, 45, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(334, 24, 46, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(335, 24, 47, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(336, 24, 48, 350.00, NULL, NULL, 'yes', '2025-03-25 16:44:50', '2025-03-25 16:44:50'),
(337, 25, 1, 345.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(338, 25, 2, 345.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(339, 25, 3, 345.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(340, 25, 4, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(341, 25, 5, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(342, 25, 6, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(343, 25, 7, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(344, 25, 8, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(345, 25, 9, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(346, 25, 10, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(347, 25, 11, 335.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(348, 25, 12, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(349, 25, 13, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(350, 25, 14, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(351, 25, 15, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(352, 25, 16, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(353, 25, 17, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(354, 25, 18, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(355, 25, 19, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(356, 25, 20, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(357, 25, 21, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(358, 25, 22, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(359, 25, 23, 325.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(360, 25, 24, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(361, 25, 25, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(362, 25, 26, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(363, 25, 27, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(364, 25, 28, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(365, 25, 29, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(366, 25, 30, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(367, 25, 31, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(368, 25, 32, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(369, 25, 33, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(370, 25, 34, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(371, 25, 35, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(372, 25, 36, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(373, 25, 37, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(374, 25, 38, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(375, 25, 39, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(376, 25, 40, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(377, 25, 41, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(378, 25, 42, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(379, 25, 43, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(380, 25, 44, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(381, 25, 45, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(382, 25, 46, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(383, 25, 47, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(384, 25, 48, 295.00, NULL, NULL, 'yes', '2025-03-25 16:48:30', '2025-03-25 16:48:30'),
(385, 26, 1, 395.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(386, 26, 2, 395.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(387, 26, 3, 395.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(388, 26, 4, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(389, 26, 5, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(390, 26, 6, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(391, 26, 7, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(392, 26, 8, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(393, 26, 9, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(394, 26, 10, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(395, 26, 11, 380.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(396, 26, 12, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(397, 26, 13, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(398, 26, 14, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(399, 26, 15, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(400, 26, 16, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(401, 26, 17, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(402, 26, 18, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(403, 26, 19, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(404, 26, 20, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(405, 26, 21, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(406, 26, 22, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(407, 26, 23, 360.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(408, 26, 24, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(409, 26, 25, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(410, 26, 26, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(411, 26, 27, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(412, 26, 28, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(413, 26, 29, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(414, 26, 30, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(415, 26, 31, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(416, 26, 32, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(417, 26, 33, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(418, 26, 34, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(419, 26, 35, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(420, 26, 36, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(421, 26, 37, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(422, 26, 38, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(423, 26, 39, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(424, 26, 40, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(425, 26, 41, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(426, 26, 42, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(427, 26, 43, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(428, 26, 44, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(429, 26, 45, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(430, 26, 46, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(431, 26, 47, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(432, 26, 48, 330.00, NULL, NULL, 'yes', '2025-03-25 16:50:28', '2025-03-25 16:50:28'),
(433, 27, 1, 455.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(434, 27, 2, 455.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(435, 27, 3, 455.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(436, 27, 4, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(437, 27, 5, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(438, 27, 6, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(439, 27, 7, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(440, 27, 8, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(441, 27, 9, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(442, 27, 10, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(443, 27, 11, 410.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(444, 27, 12, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(445, 27, 13, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(446, 27, 14, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(447, 27, 15, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(448, 27, 16, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(449, 27, 17, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(450, 27, 18, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(451, 27, 19, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(452, 27, 20, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(453, 27, 21, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(454, 27, 22, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(455, 27, 23, 390.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(456, 27, 24, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(457, 27, 25, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(458, 27, 26, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(459, 27, 27, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(460, 27, 28, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(461, 27, 29, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(462, 27, 30, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(463, 27, 31, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(464, 27, 32, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(465, 27, 33, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(466, 27, 34, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(467, 27, 35, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(468, 27, 36, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(469, 27, 37, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(470, 27, 38, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(471, 27, 39, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(472, 27, 40, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(473, 27, 41, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(474, 27, 42, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(475, 27, 43, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(476, 27, 44, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(477, 27, 45, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(478, 27, 46, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(479, 27, 47, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(480, 27, 48, 350.00, NULL, NULL, 'yes', '2025-03-25 16:52:14', '2025-03-25 16:52:14'),
(481, 28, 1, 455.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(482, 28, 2, 455.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(483, 28, 3, 455.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(484, 28, 4, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(485, 28, 5, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(486, 28, 6, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(487, 28, 7, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(488, 28, 8, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(489, 28, 9, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(490, 28, 10, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(491, 28, 11, 410.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(492, 28, 12, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(493, 28, 13, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(494, 28, 14, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(495, 28, 15, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(496, 28, 16, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(497, 28, 17, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(498, 28, 18, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(499, 28, 19, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(500, 28, 20, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(501, 28, 21, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(502, 28, 22, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(503, 28, 23, 390.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(504, 28, 24, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(505, 28, 25, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(506, 28, 26, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(507, 28, 27, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(508, 28, 28, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(509, 28, 29, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(510, 28, 30, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(511, 28, 31, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(512, 28, 32, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(513, 28, 33, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(514, 28, 34, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(515, 28, 35, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(516, 28, 36, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(517, 28, 37, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(518, 28, 38, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(519, 28, 39, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(520, 28, 40, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(521, 28, 41, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(522, 28, 42, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(523, 28, 43, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(524, 28, 44, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(525, 28, 45, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(526, 28, 46, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(527, 28, 47, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(528, 28, 48, 350.00, NULL, NULL, 'yes', '2025-03-25 17:00:16', '2025-03-25 17:00:16'),
(529, 29, 1, 245.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(530, 29, 2, 245.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(531, 29, 3, 245.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(532, 29, 4, 245.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(533, 29, 5, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(534, 29, 6, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(535, 29, 7, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(536, 29, 8, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(537, 29, 9, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(538, 29, 10, 240.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(539, 29, 11, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(540, 29, 12, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(541, 29, 13, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(542, 29, 14, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(543, 29, 15, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(544, 29, 16, 235.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(545, 29, 17, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(546, 29, 18, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(547, 29, 19, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(548, 29, 20, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(549, 29, 21, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(550, 29, 22, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(551, 29, 23, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(552, 29, 24, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(553, 29, 25, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(554, 29, 26, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(555, 29, 27, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(556, 29, 28, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(557, 29, 29, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(558, 29, 30, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(559, 29, 31, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(560, 29, 32, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(561, 29, 33, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(562, 29, 34, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(563, 29, 35, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(564, 29, 36, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(565, 29, 37, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(566, 29, 38, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(567, 29, 39, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(568, 29, 40, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(569, 29, 41, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(570, 29, 42, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(571, 29, 43, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(572, 29, 44, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(573, 29, 45, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(574, 29, 46, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(575, 29, 47, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(576, 29, 48, 230.00, NULL, NULL, 'yes', '2025-04-01 15:15:32', '2025-04-01 15:15:32'),
(577, 31, 1, 330.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(578, 31, 2, 330.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(579, 31, 3, 330.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(580, 31, 4, 330.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(581, 31, 5, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02');
INSERT INTO `language_school_course_fees` (`id`, `language_school_course_id`, `week_number`, `fee`, `valid_from`, `valid_to`, `price_split`, `created_at`, `updated_at`) VALUES
(582, 31, 6, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(583, 31, 7, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(584, 31, 8, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(585, 31, 9, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(586, 31, 10, 320.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(587, 31, 11, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(588, 31, 12, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(589, 31, 13, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(590, 31, 14, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(591, 31, 15, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(592, 31, 16, 310.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(593, 31, 17, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(594, 31, 18, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(595, 31, 19, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(596, 31, 20, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(597, 31, 21, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(598, 31, 22, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(599, 31, 23, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(600, 31, 24, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(601, 31, 25, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(602, 31, 26, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(603, 31, 27, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(604, 31, 28, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(605, 31, 29, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(606, 31, 30, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(607, 31, 31, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(608, 31, 32, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:02', '2025-04-01 15:17:02'),
(609, 31, 33, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(610, 31, 34, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(611, 31, 35, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(612, 31, 36, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(613, 31, 37, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(614, 31, 38, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(615, 31, 39, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(616, 31, 40, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(617, 31, 41, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(618, 31, 42, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(619, 31, 43, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(620, 31, 44, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(621, 31, 45, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(622, 31, 46, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(623, 31, 47, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(624, 31, 48, 300.00, NULL, NULL, 'yes', '2025-04-01 15:17:03', '2025-04-01 15:17:03'),
(625, 32, 1, 245.00, NULL, NULL, 'yes', '2025-04-01 15:19:08', '2025-04-01 15:19:08'),
(626, 32, 2, 245.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(627, 32, 3, 245.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(628, 32, 4, 245.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(629, 32, 5, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(630, 32, 6, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(631, 32, 7, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(632, 32, 8, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(633, 32, 9, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(634, 32, 10, 240.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(635, 32, 11, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(636, 32, 12, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(637, 32, 13, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(638, 32, 14, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(639, 32, 15, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(640, 32, 16, 235.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(641, 32, 17, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(642, 32, 18, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(643, 32, 19, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(644, 32, 20, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(645, 32, 21, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(646, 32, 22, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(647, 32, 23, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(648, 32, 24, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(649, 32, 25, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(650, 32, 26, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(651, 32, 27, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(652, 32, 28, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(653, 32, 29, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(654, 32, 30, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(655, 32, 31, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(656, 32, 32, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(657, 32, 33, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(658, 32, 34, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(659, 32, 35, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(660, 32, 36, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(661, 32, 37, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(662, 32, 38, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(663, 32, 39, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(664, 32, 40, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(665, 32, 41, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(666, 32, 42, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(667, 32, 43, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(668, 32, 44, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(669, 32, 45, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(670, 32, 46, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(671, 32, 47, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(672, 32, 48, 230.00, NULL, NULL, 'yes', '2025-04-01 15:19:09', '2025-04-01 15:19:09'),
(673, 33, 1, 286.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(674, 33, 2, 286.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(675, 33, 3, 286.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(676, 33, 4, 286.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(677, 33, 5, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(678, 33, 6, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(679, 33, 7, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(680, 33, 8, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(681, 33, 9, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(682, 33, 10, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(683, 33, 11, 273.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(684, 33, 12, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(685, 33, 13, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(686, 33, 14, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(687, 33, 15, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(688, 33, 16, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(689, 33, 17, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(690, 33, 18, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(691, 33, 19, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(692, 33, 20, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(693, 33, 21, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(694, 33, 22, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(695, 33, 23, 260.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(696, 33, 24, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(697, 33, 25, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(698, 33, 26, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(699, 33, 27, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(700, 33, 28, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(701, 33, 29, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(702, 33, 30, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(703, 33, 31, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(704, 33, 32, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(705, 33, 33, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(706, 33, 34, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(707, 33, 35, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(708, 33, 36, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(709, 33, 37, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(710, 33, 38, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(711, 33, 39, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(712, 33, 40, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(713, 33, 41, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(714, 33, 42, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(715, 33, 43, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(716, 33, 44, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(717, 33, 45, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(718, 33, 46, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(719, 33, 47, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(720, 33, 48, 248.00, NULL, NULL, 'yes', '2025-04-01 15:23:12', '2025-04-01 15:23:12'),
(721, 34, 1, 353.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(722, 34, 2, 353.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(723, 34, 3, 353.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(724, 34, 4, 353.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(725, 34, 5, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(726, 34, 6, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(727, 34, 7, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(728, 34, 8, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(729, 34, 9, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(730, 34, 10, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(731, 34, 11, 338.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(732, 34, 12, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(733, 34, 13, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(734, 34, 14, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(735, 34, 15, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(736, 34, 16, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(737, 34, 17, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(738, 34, 18, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(739, 34, 19, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(740, 34, 20, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(741, 34, 21, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:55', '2025-04-01 15:24:55'),
(742, 34, 22, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(743, 34, 23, 323.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(744, 34, 24, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(745, 34, 25, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(746, 34, 26, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(747, 34, 27, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(748, 34, 28, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(749, 34, 29, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(750, 34, 30, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(751, 34, 31, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(752, 34, 32, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(753, 34, 33, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(754, 34, 34, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(755, 34, 35, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(756, 34, 36, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(757, 34, 37, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(758, 34, 38, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(759, 34, 39, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(760, 34, 40, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(761, 34, 41, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(762, 34, 42, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(763, 34, 43, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(764, 34, 44, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(765, 34, 45, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(766, 34, 46, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(767, 34, 47, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(768, 34, 48, 309.00, NULL, NULL, 'yes', '2025-04-01 15:24:56', '2025-04-01 15:24:56'),
(769, 35, 1, 374.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(770, 35, 2, 374.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(771, 35, 3, 374.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(772, 35, 4, 374.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(773, 35, 5, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(774, 35, 6, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(775, 35, 7, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(776, 35, 8, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(777, 35, 9, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(778, 35, 10, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(779, 35, 11, 357.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(780, 35, 12, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(781, 35, 13, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(782, 35, 14, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(783, 35, 15, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(784, 35, 16, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(785, 35, 17, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(786, 35, 18, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(787, 35, 19, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(788, 35, 20, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(789, 35, 21, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(790, 35, 22, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(791, 35, 23, 340.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(792, 35, 24, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(793, 35, 25, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(794, 35, 26, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(795, 35, 27, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(796, 35, 28, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(797, 35, 29, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(798, 35, 30, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(799, 35, 31, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(800, 35, 32, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(801, 35, 33, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(802, 35, 34, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(803, 35, 35, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(804, 35, 36, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(805, 35, 37, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(806, 35, 38, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(807, 35, 39, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(808, 35, 40, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(809, 35, 41, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:34', '2025-04-01 15:26:34'),
(810, 35, 42, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(811, 35, 43, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(812, 35, 44, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(813, 35, 45, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(814, 35, 46, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(815, 35, 47, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(816, 35, 48, 323.00, NULL, NULL, 'yes', '2025-04-01 15:26:35', '2025-04-01 15:26:35'),
(817, 36, 1, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(818, 36, 2, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(819, 36, 3, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(820, 36, 4, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(821, 36, 5, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(822, 36, 6, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(823, 36, 7, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(824, 36, 8, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(825, 36, 9, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(826, 36, 10, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(827, 36, 11, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(828, 36, 12, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(829, 36, 13, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(830, 36, 14, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(831, 36, 15, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(832, 36, 16, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(833, 36, 17, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:27', '2025-04-01 15:27:27'),
(834, 36, 18, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(835, 36, 19, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(836, 36, 20, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(837, 36, 21, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(838, 36, 22, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(839, 36, 23, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(840, 36, 24, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(841, 36, 25, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(842, 36, 26, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(843, 36, 27, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(844, 36, 28, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(845, 36, 29, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(846, 36, 30, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(847, 36, 31, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(848, 36, 32, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(849, 36, 33, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(850, 36, 34, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(851, 36, 35, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(852, 36, 36, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(853, 36, 37, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(854, 36, 38, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(855, 36, 39, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(856, 36, 40, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(857, 36, 41, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(858, 36, 42, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(859, 36, 43, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(860, 36, 44, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(861, 36, 45, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(862, 36, 46, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(863, 36, 47, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(864, 36, 48, 176.00, NULL, NULL, 'yes', '2025-04-01 15:27:28', '2025-04-01 15:27:28'),
(865, 37, 1, 285.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(866, 37, 2, 285.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(867, 37, 3, 285.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(868, 37, 4, 285.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(869, 37, 5, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(870, 37, 6, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(871, 37, 7, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(872, 37, 8, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(873, 37, 9, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(874, 37, 10, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(875, 37, 11, 225.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(876, 37, 12, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(877, 37, 13, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(878, 37, 14, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(879, 37, 15, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(880, 37, 16, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(881, 37, 17, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(882, 37, 18, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(883, 37, 19, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(884, 37, 20, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(885, 37, 21, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(886, 37, 22, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(887, 37, 23, 195.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(888, 37, 24, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(889, 37, 25, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(890, 37, 26, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(891, 37, 27, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(892, 37, 28, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(893, 37, 29, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(894, 37, 30, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(895, 37, 31, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(896, 37, 32, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(897, 37, 33, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(898, 37, 34, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(899, 37, 35, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(900, 37, 36, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(901, 37, 37, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(902, 37, 38, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(903, 37, 39, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(904, 37, 40, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(905, 37, 41, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(906, 37, 42, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(907, 37, 43, 175.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(908, 37, 44, 135.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(909, 37, 45, 135.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(910, 37, 46, 135.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(911, 37, 47, 135.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(912, 37, 48, 135.00, NULL, NULL, 'yes', '2025-04-01 15:32:44', '2025-04-01 15:32:44'),
(913, 38, 1, 314.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(914, 38, 2, 314.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(915, 38, 3, 314.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(916, 38, 4, 314.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(917, 38, 5, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(918, 38, 6, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(919, 38, 7, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(920, 38, 8, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(921, 38, 9, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(922, 38, 10, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(923, 38, 11, 265.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(924, 38, 12, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(925, 38, 13, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(926, 38, 14, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(927, 38, 15, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(928, 38, 16, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(929, 38, 17, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(930, 38, 18, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(931, 38, 19, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(932, 38, 20, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(933, 38, 21, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(934, 38, 22, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(935, 38, 23, 240.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(936, 38, 24, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(937, 38, 25, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(938, 38, 26, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(939, 38, 27, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(940, 38, 28, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(941, 38, 29, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(942, 38, 30, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(943, 38, 31, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(944, 38, 32, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(945, 38, 33, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(946, 38, 34, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(947, 38, 35, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(948, 38, 36, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(949, 38, 37, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(950, 38, 38, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(951, 38, 39, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(952, 38, 40, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(953, 38, 41, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(954, 38, 42, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(955, 38, 43, 195.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(956, 38, 44, 145.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(957, 38, 45, 145.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(958, 38, 46, 145.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(959, 38, 47, 145.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(960, 38, 48, 145.00, NULL, NULL, 'yes', '2025-04-01 15:34:22', '2025-04-01 15:34:22'),
(961, 39, 1, 265.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(962, 39, 2, 265.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(963, 39, 3, 265.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(964, 39, 4, 265.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(965, 39, 5, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(966, 39, 6, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(967, 39, 7, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(968, 39, 8, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(969, 39, 9, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(970, 39, 10, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(971, 39, 11, 215.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(972, 39, 12, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(973, 39, 13, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(974, 39, 14, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(975, 39, 15, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(976, 39, 16, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(977, 39, 17, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(978, 39, 18, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(979, 39, 19, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(980, 39, 20, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(981, 39, 21, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(982, 39, 22, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(983, 39, 23, 205.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(984, 39, 24, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(985, 39, 25, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(986, 39, 26, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(987, 39, 27, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(988, 39, 28, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(989, 39, 29, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(990, 39, 30, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(991, 39, 31, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(992, 39, 32, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(993, 39, 33, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(994, 39, 34, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(995, 39, 35, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(996, 39, 36, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(997, 39, 37, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(998, 39, 38, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(999, 39, 39, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1000, 39, 40, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1001, 39, 41, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1002, 39, 42, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1003, 39, 43, 165.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1004, 39, 44, 150.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1005, 39, 45, 150.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1006, 39, 46, 150.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1007, 39, 47, 150.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1008, 39, 48, 150.00, NULL, NULL, 'yes', '2025-04-01 15:35:49', '2025-04-01 15:35:49'),
(1009, 40, 1, 440.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1010, 40, 2, 440.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1011, 40, 3, 440.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1012, 40, 4, 440.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1013, 40, 5, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1014, 40, 6, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1015, 40, 7, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1016, 40, 8, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1017, 40, 9, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1018, 40, 10, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1019, 40, 11, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1020, 40, 12, 405.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1021, 40, 13, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1022, 40, 14, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1023, 40, 15, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1024, 40, 16, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1025, 40, 17, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1026, 40, 18, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1027, 40, 19, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1028, 40, 20, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1029, 40, 21, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1030, 40, 22, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1031, 40, 23, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1032, 40, 24, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1033, 40, 25, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1034, 40, 26, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1035, 40, 27, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1036, 40, 28, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1037, 40, 29, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1038, 40, 30, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1039, 40, 31, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1040, 40, 32, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1041, 40, 33, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1042, 40, 34, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1043, 40, 35, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1044, 40, 36, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1045, 40, 37, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1046, 40, 38, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1047, 40, 39, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1048, 40, 40, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1049, 40, 41, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1050, 40, 42, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1051, 40, 43, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1052, 40, 44, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1053, 40, 45, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1054, 40, 46, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1055, 40, 47, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1056, 40, 48, 373.00, NULL, NULL, 'yes', '2025-04-01 15:41:27', '2025-04-01 15:41:27'),
(1057, 41, 1, 675.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1058, 41, 2, 675.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1059, 41, 3, 675.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1060, 41, 4, 675.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1061, 41, 5, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1062, 41, 6, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:30', '2025-04-01 15:42:30'),
(1063, 41, 7, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1064, 41, 8, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1065, 41, 9, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1066, 41, 10, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1067, 41, 11, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1068, 41, 12, 625.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1069, 41, 13, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1070, 41, 14, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1071, 41, 15, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1072, 41, 16, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1073, 41, 17, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1074, 41, 18, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1075, 41, 19, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1076, 41, 20, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1077, 41, 21, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1078, 41, 22, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1079, 41, 23, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1080, 41, 24, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1081, 41, 25, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1082, 41, 26, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1083, 41, 27, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1084, 41, 28, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1085, 41, 29, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1086, 41, 30, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1087, 41, 31, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1088, 41, 32, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1089, 41, 33, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1090, 41, 34, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1091, 41, 35, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1092, 41, 36, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1093, 41, 37, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1094, 41, 38, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1095, 41, 39, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1096, 41, 40, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1097, 41, 41, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1098, 41, 42, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1099, 41, 43, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1100, 41, 44, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1101, 41, 45, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1102, 41, 46, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1103, 41, 47, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1104, 41, 48, 595.00, NULL, NULL, 'yes', '2025-04-01 15:42:31', '2025-04-01 15:42:31'),
(1105, 42, 1, 345.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1106, 42, 2, 345.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1107, 42, 3, 345.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1108, 42, 4, 345.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1109, 42, 5, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1110, 42, 6, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1111, 42, 7, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1112, 42, 8, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1113, 42, 9, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1114, 42, 10, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1115, 42, 11, 339.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1116, 42, 12, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1117, 42, 13, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1118, 42, 14, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1119, 42, 15, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1120, 42, 16, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1121, 42, 17, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1122, 42, 18, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1123, 42, 19, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1124, 42, 20, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1125, 42, 21, 326.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1126, 42, 22, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1127, 42, 23, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1128, 42, 24, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1129, 42, 25, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1130, 42, 26, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1131, 42, 27, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1132, 42, 28, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1133, 42, 29, 297.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1134, 42, 30, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1135, 42, 31, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1136, 42, 32, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1137, 42, 33, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1138, 42, 34, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1139, 42, 35, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1140, 42, 36, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1141, 42, 37, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1142, 42, 38, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1143, 42, 39, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1144, 42, 40, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1145, 42, 41, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1146, 42, 42, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1147, 42, 43, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1148, 42, 44, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1149, 42, 45, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1150, 42, 46, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1151, 42, 47, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1152, 42, 48, 281.00, NULL, NULL, 'yes', '2025-04-02 13:10:58', '2025-04-02 13:10:58'),
(1153, 43, 1, 389.00, NULL, NULL, 'yes', '2025-04-02 13:13:38', '2025-04-02 13:13:38'),
(1154, 43, 2, 389.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1155, 43, 3, 389.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1156, 43, 4, 389.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1157, 43, 5, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1158, 43, 6, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1159, 43, 7, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39');
INSERT INTO `language_school_course_fees` (`id`, `language_school_course_id`, `week_number`, `fee`, `valid_from`, `valid_to`, `price_split`, `created_at`, `updated_at`) VALUES
(1160, 43, 8, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1161, 43, 9, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1162, 43, 10, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1163, 43, 11, 384.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1164, 43, 12, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1165, 43, 13, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1166, 43, 14, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1167, 43, 15, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1168, 43, 16, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1169, 43, 17, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1170, 43, 18, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1171, 43, 19, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1172, 43, 20, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1173, 43, 21, 368.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1174, 43, 22, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1175, 43, 23, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1176, 43, 24, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1177, 43, 25, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1178, 43, 26, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1179, 43, 27, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1180, 43, 28, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1181, 43, 29, 334.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1182, 43, 30, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1183, 43, 31, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1184, 43, 32, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1185, 43, 33, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1186, 43, 34, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1187, 43, 35, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1188, 43, 36, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1189, 43, 37, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1190, 43, 38, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1191, 43, 39, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1192, 43, 40, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1193, 43, 41, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1194, 43, 42, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1195, 43, 43, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1196, 43, 44, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1197, 43, 45, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1198, 43, 46, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1199, 43, 47, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1200, 43, 48, 317.00, NULL, NULL, 'yes', '2025-04-02 13:13:39', '2025-04-02 13:13:39'),
(1201, 44, 1, 420.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1202, 44, 2, 420.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1203, 44, 3, 420.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1204, 44, 4, 420.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1205, 44, 5, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1206, 44, 6, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1207, 44, 7, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1208, 44, 8, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1209, 44, 9, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1210, 44, 10, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1211, 44, 11, 413.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1212, 44, 12, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1213, 44, 13, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1214, 44, 14, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1215, 44, 15, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1216, 44, 16, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1217, 44, 17, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1218, 44, 18, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1219, 44, 19, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1220, 44, 20, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1221, 44, 21, 395.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1222, 44, 22, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1223, 44, 23, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1224, 44, 24, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1225, 44, 25, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1226, 44, 26, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1227, 44, 27, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1228, 44, 28, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1229, 44, 29, 350.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1230, 44, 30, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1231, 44, 31, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1232, 44, 32, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1233, 44, 33, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1234, 44, 34, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1235, 44, 35, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1236, 44, 36, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1237, 44, 37, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1238, 44, 38, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1239, 44, 39, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1240, 44, 40, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1241, 44, 41, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1242, 44, 42, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1243, 44, 43, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1244, 44, 44, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1245, 44, 45, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1246, 44, 46, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1247, 44, 47, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1248, 44, 48, 335.00, NULL, NULL, 'yes', '2025-04-02 13:15:01', '2025-04-02 13:15:01'),
(1249, 45, 1, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1250, 45, 2, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1251, 45, 3, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1252, 45, 4, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1253, 45, 5, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1254, 45, 6, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1255, 45, 7, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1256, 45, 8, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1257, 45, 9, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1258, 45, 10, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1259, 45, 11, 260.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1260, 45, 12, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1261, 45, 13, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1262, 45, 14, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1263, 45, 15, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1264, 45, 16, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1265, 45, 17, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1266, 45, 18, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1267, 45, 19, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1268, 45, 20, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1269, 45, 21, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:00', '2025-04-02 13:19:00'),
(1270, 45, 22, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1271, 45, 23, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1272, 45, 24, 250.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1273, 45, 25, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1274, 45, 26, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1275, 45, 27, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1276, 45, 28, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1277, 45, 29, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1278, 45, 30, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1279, 45, 31, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1280, 45, 32, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1281, 45, 33, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1282, 45, 34, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1283, 45, 35, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1284, 45, 36, 240.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1285, 45, 37, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1286, 45, 38, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1287, 45, 39, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1288, 45, 40, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1289, 45, 41, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1290, 45, 42, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1291, 45, 43, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1292, 45, 44, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1293, 45, 45, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1294, 45, 46, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1295, 45, 47, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1296, 45, 48, 230.00, NULL, NULL, 'yes', '2025-04-02 13:19:01', '2025-04-02 13:19:01'),
(1297, 46, 1, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1298, 46, 2, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1299, 46, 3, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1300, 46, 4, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1301, 46, 5, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1302, 46, 6, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1303, 46, 7, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1304, 46, 8, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1305, 46, 9, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1306, 46, 10, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1307, 46, 11, 310.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1308, 46, 12, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1309, 46, 13, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1310, 46, 14, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1311, 46, 15, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1312, 46, 16, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1313, 46, 17, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1314, 46, 18, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1315, 46, 19, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1316, 46, 20, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1317, 46, 21, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1318, 46, 22, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1319, 46, 23, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1320, 46, 24, 290.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1321, 46, 25, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1322, 46, 26, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1323, 46, 27, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1324, 46, 28, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1325, 46, 29, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1326, 46, 30, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1327, 46, 31, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1328, 46, 32, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1329, 46, 33, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1330, 46, 34, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1331, 46, 35, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1332, 46, 36, 280.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1333, 46, 37, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1334, 46, 38, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1335, 46, 39, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1336, 46, 40, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1337, 46, 41, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1338, 46, 42, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1339, 46, 43, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1340, 46, 44, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1341, 46, 45, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1342, 46, 46, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1343, 46, 47, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:09', '2025-04-02 13:21:09'),
(1344, 46, 48, 270.00, NULL, NULL, 'yes', '2025-04-02 13:21:10', '2025-04-02 13:21:10'),
(1345, 47, 1, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1346, 47, 2, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1347, 47, 3, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1348, 47, 4, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1349, 47, 5, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1350, 47, 6, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1351, 47, 7, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1352, 47, 8, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1353, 47, 9, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1354, 47, 10, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1355, 47, 11, 350.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1356, 47, 12, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1357, 47, 13, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1358, 47, 14, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1359, 47, 15, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1360, 47, 16, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1361, 47, 17, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1362, 47, 18, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1363, 47, 19, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1364, 47, 20, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1365, 47, 21, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1366, 47, 22, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1367, 47, 23, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1368, 47, 24, 340.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1369, 47, 25, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1370, 47, 26, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1371, 47, 27, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1372, 47, 28, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1373, 47, 29, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1374, 47, 30, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1375, 47, 31, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1376, 47, 32, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1377, 47, 33, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1378, 47, 34, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1379, 47, 35, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1380, 47, 36, 330.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1381, 47, 37, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1382, 47, 38, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1383, 47, 39, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1384, 47, 40, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1385, 47, 41, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1386, 47, 42, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1387, 47, 43, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1388, 47, 44, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1389, 47, 45, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1390, 47, 46, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1391, 47, 47, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1392, 47, 48, 320.00, NULL, NULL, 'yes', '2025-04-02 13:23:22', '2025-04-02 13:23:22'),
(1393, 48, 1, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1394, 48, 2, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1395, 48, 3, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1396, 48, 4, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1397, 48, 5, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1398, 48, 6, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1399, 48, 7, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1400, 48, 8, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1401, 48, 9, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1402, 48, 10, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1403, 48, 11, 254.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1404, 48, 12, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1405, 48, 13, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1406, 48, 14, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1407, 48, 15, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1408, 48, 16, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1409, 48, 17, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1410, 48, 18, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1411, 48, 19, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1412, 48, 20, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1413, 48, 21, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1414, 48, 22, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1415, 48, 23, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1416, 48, 24, 243.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1417, 48, 25, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1418, 48, 26, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1419, 48, 27, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1420, 48, 28, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1421, 48, 29, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1422, 48, 30, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1423, 48, 31, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1424, 48, 32, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1425, 48, 33, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1426, 48, 34, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1427, 48, 35, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1428, 48, 36, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1429, 48, 37, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1430, 48, 38, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1431, 48, 39, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1432, 48, 40, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1433, 48, 41, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1434, 48, 42, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1435, 48, 43, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1436, 48, 44, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1437, 48, 45, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1438, 48, 46, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1439, 48, 47, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1440, 48, 48, 233.00, NULL, NULL, 'yes', '2025-04-02 13:29:12', '2025-04-02 13:29:12'),
(1441, 49, 1, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1442, 49, 2, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1443, 49, 3, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1444, 49, 4, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1445, 49, 5, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1446, 49, 6, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1447, 49, 7, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1448, 49, 8, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1449, 49, 9, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1450, 49, 10, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1451, 49, 11, 335.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1452, 49, 12, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1453, 49, 13, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1454, 49, 14, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1455, 49, 15, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1456, 49, 16, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1457, 49, 17, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1458, 49, 18, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1459, 49, 19, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1460, 49, 20, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1461, 49, 21, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1462, 49, 22, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1463, 49, 23, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1464, 49, 24, 317.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1465, 49, 25, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1466, 49, 26, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1467, 49, 27, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1468, 49, 28, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1469, 49, 29, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1470, 49, 30, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1471, 49, 31, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1472, 49, 32, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1473, 49, 33, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1474, 49, 34, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1475, 49, 35, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1476, 49, 36, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1477, 49, 37, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1478, 49, 38, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1479, 49, 39, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1480, 49, 40, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1481, 49, 41, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1482, 49, 42, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1483, 49, 43, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1484, 49, 44, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1485, 49, 45, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1486, 49, 46, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1487, 49, 47, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1488, 49, 48, 306.00, NULL, NULL, 'yes', '2025-04-02 13:30:57', '2025-04-02 13:30:57'),
(1489, 50, 1, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1490, 50, 2, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1491, 50, 3, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1492, 50, 4, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1493, 50, 5, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1494, 50, 6, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1495, 50, 7, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1496, 50, 8, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1497, 50, 9, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1498, 50, 10, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1499, 50, 11, 381.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1500, 50, 12, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1501, 50, 13, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1502, 50, 14, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1503, 50, 15, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1504, 50, 16, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1505, 50, 17, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1506, 50, 18, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1507, 50, 19, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1508, 50, 20, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1509, 50, 21, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1510, 50, 22, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1511, 50, 23, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1512, 50, 24, 364.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1513, 50, 25, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1514, 50, 26, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1515, 50, 27, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1516, 50, 28, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1517, 50, 29, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1518, 50, 30, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1519, 50, 31, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1520, 50, 32, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1521, 50, 33, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1522, 50, 34, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1523, 50, 35, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1524, 50, 36, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1525, 50, 37, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1526, 50, 38, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1527, 50, 39, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1528, 50, 40, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1529, 50, 41, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1530, 50, 42, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1531, 50, 43, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1532, 50, 44, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1533, 50, 45, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1534, 50, 46, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1535, 50, 47, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1536, 50, 48, 351.00, NULL, NULL, 'yes', '2025-04-02 13:32:19', '2025-04-02 13:32:19'),
(1537, 51, 1, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1538, 51, 2, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1539, 51, 3, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1540, 51, 4, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1541, 51, 5, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1542, 51, 6, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1543, 51, 7, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1544, 51, 8, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1545, 51, 9, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1546, 51, 10, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1547, 51, 11, 300.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1548, 51, 12, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1549, 51, 13, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1550, 51, 14, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1551, 51, 15, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1552, 51, 16, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1553, 51, 17, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1554, 51, 18, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1555, 51, 19, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1556, 51, 20, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1557, 51, 21, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1558, 51, 22, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1559, 51, 23, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1560, 51, 24, 240.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1561, 51, 25, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1562, 51, 26, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1563, 51, 27, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1564, 51, 28, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1565, 51, 29, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1566, 51, 30, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1567, 51, 31, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1568, 51, 32, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1569, 51, 33, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1570, 51, 34, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1571, 51, 35, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1572, 51, 36, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1573, 51, 37, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1574, 51, 38, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1575, 51, 39, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1576, 51, 40, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1577, 51, 41, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1578, 51, 42, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1579, 51, 43, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1580, 51, 44, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1581, 51, 45, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1582, 51, 46, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1583, 51, 47, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1584, 51, 48, 220.00, NULL, NULL, 'yes', '2025-04-02 13:35:47', '2025-04-02 13:35:47'),
(1585, 52, 1, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1586, 52, 2, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1587, 52, 3, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1588, 52, 4, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1589, 52, 5, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1590, 52, 6, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1591, 52, 7, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1592, 52, 8, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1593, 52, 9, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1594, 52, 10, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1595, 52, 11, 400.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1596, 52, 12, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1597, 52, 13, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1598, 52, 14, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1599, 52, 15, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1600, 52, 16, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1601, 52, 17, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1602, 52, 18, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1603, 52, 19, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1604, 52, 20, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1605, 52, 21, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1606, 52, 22, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1607, 52, 23, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1608, 52, 24, 340.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1609, 52, 25, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1610, 52, 26, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1611, 52, 27, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1612, 52, 28, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1613, 52, 29, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1614, 52, 30, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1615, 52, 31, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1616, 52, 32, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1617, 52, 33, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1618, 52, 34, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1619, 52, 35, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1620, 52, 36, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1621, 52, 37, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1622, 52, 38, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1623, 52, 39, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1624, 52, 40, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1625, 52, 41, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1626, 52, 42, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1627, 52, 43, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1628, 52, 44, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1629, 52, 45, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1630, 52, 46, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1631, 52, 47, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1632, 52, 48, 320.00, NULL, NULL, 'yes', '2025-04-02 13:36:44', '2025-04-02 13:36:44'),
(1633, 53, 1, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1634, 53, 2, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1635, 53, 3, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1636, 53, 4, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1637, 53, 5, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1638, 53, 6, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1639, 53, 7, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1640, 53, 8, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1641, 53, 9, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1642, 53, 10, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1643, 53, 11, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1644, 53, 12, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1645, 53, 13, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1646, 53, 14, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1647, 53, 15, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1648, 53, 16, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1649, 53, 17, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1650, 53, 18, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1651, 53, 19, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1652, 53, 20, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1653, 53, 21, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1654, 53, 22, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1655, 53, 23, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1656, 53, 24, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1657, 53, 25, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1658, 53, 26, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1659, 53, 27, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1660, 53, 28, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1661, 53, 29, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1662, 53, 30, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1663, 53, 31, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1664, 53, 32, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1665, 53, 33, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1666, 53, 34, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1667, 53, 35, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1668, 53, 36, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1669, 53, 37, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1670, 53, 38, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1671, 53, 39, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1672, 53, 40, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1673, 53, 41, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1674, 53, 42, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1675, 53, 43, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1676, 53, 44, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1677, 53, 45, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1678, 53, 46, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1679, 53, 47, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1680, 53, 48, 200.00, NULL, NULL, 'yes', '2025-04-02 13:52:30', '2025-04-02 13:52:30'),
(1681, 54, 1, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1682, 54, 2, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1683, 54, 3, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1684, 54, 4, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1685, 54, 5, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1686, 54, 6, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1687, 54, 7, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1688, 54, 8, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1689, 54, 9, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1690, 54, 10, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1691, 54, 11, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1692, 54, 12, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1693, 54, 13, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1694, 54, 14, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1695, 54, 15, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1696, 54, 16, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1697, 54, 17, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1698, 54, 18, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1699, 54, 19, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1700, 54, 20, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1701, 54, 21, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1702, 54, 22, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1703, 54, 23, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1704, 54, 24, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1705, 54, 25, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1706, 54, 26, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1707, 54, 27, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1708, 54, 28, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1709, 54, 29, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1710, 54, 30, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1711, 54, 31, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1712, 54, 32, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1713, 54, 33, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1714, 54, 34, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1715, 54, 35, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1716, 54, 36, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1717, 54, 37, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1718, 54, 38, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1719, 54, 39, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1720, 54, 40, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1721, 54, 41, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1722, 54, 42, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1723, 54, 43, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1724, 54, 44, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1725, 54, 45, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1726, 54, 46, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1727, 54, 47, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1728, 54, 48, 280.00, NULL, NULL, 'yes', '2025-04-02 13:54:04', '2025-04-02 13:54:04'),
(1729, 55, 1, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1730, 55, 2, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1731, 55, 3, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1732, 55, 4, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13');
INSERT INTO `language_school_course_fees` (`id`, `language_school_course_id`, `week_number`, `fee`, `valid_from`, `valid_to`, `price_split`, `created_at`, `updated_at`) VALUES
(1733, 55, 5, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1734, 55, 6, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1735, 55, 7, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1736, 55, 8, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1737, 55, 9, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1738, 55, 10, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1739, 55, 11, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1740, 55, 12, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1741, 55, 13, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1742, 55, 14, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1743, 55, 15, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1744, 55, 16, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1745, 55, 17, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1746, 55, 18, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1747, 55, 19, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1748, 55, 20, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1749, 55, 21, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1750, 55, 22, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1751, 55, 23, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1752, 55, 24, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1753, 55, 25, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1754, 55, 26, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1755, 55, 27, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1756, 55, 28, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1757, 55, 29, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1758, 55, 30, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1759, 55, 31, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1760, 55, 32, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1761, 55, 33, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1762, 55, 34, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1763, 55, 35, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1764, 55, 36, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1765, 55, 37, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1766, 55, 38, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1767, 55, 39, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1768, 55, 40, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1769, 55, 41, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1770, 55, 42, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1771, 55, 43, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1772, 55, 44, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1773, 55, 45, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1774, 55, 46, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1775, 55, 47, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1776, 55, 48, 340.00, NULL, NULL, 'yes', '2025-04-02 13:55:13', '2025-04-02 13:55:13'),
(1777, 56, 1, 300.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1778, 56, 2, 300.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1779, 56, 3, 300.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1780, 56, 4, 300.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1781, 56, 5, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1782, 56, 6, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1783, 56, 7, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1784, 56, 8, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1785, 56, 9, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1786, 56, 10, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1787, 56, 11, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1788, 56, 12, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1789, 56, 13, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1790, 56, 14, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1791, 56, 15, 250.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1792, 56, 16, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1793, 56, 17, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1794, 56, 18, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1795, 56, 19, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1796, 56, 20, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1797, 56, 21, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1798, 56, 22, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1799, 56, 23, 215.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1800, 56, 24, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1801, 56, 25, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1802, 56, 26, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1803, 56, 27, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1804, 56, 28, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1805, 56, 29, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1806, 56, 30, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1807, 56, 31, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1808, 56, 32, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1809, 56, 33, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1810, 56, 34, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1811, 56, 35, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1812, 56, 36, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1813, 56, 37, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1814, 56, 38, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1815, 56, 39, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1816, 56, 40, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1817, 56, 41, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1818, 56, 42, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1819, 56, 43, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1820, 56, 44, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1821, 56, 45, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1822, 56, 46, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1823, 56, 47, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1824, 56, 48, 180.00, NULL, NULL, 'yes', '2025-04-02 13:59:19', '2025-04-02 13:59:19'),
(1825, 57, 1, 390.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1826, 57, 2, 390.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1827, 57, 3, 390.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1828, 57, 4, 390.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1829, 57, 5, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1830, 57, 6, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1831, 57, 7, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1832, 57, 8, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1833, 57, 9, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1834, 57, 10, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1835, 57, 11, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1836, 57, 12, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1837, 57, 13, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1838, 57, 14, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1839, 57, 15, 320.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1840, 57, 16, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1841, 57, 17, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1842, 57, 18, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1843, 57, 19, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1844, 57, 20, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1845, 57, 21, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1846, 57, 22, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1847, 57, 23, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1848, 57, 24, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1849, 57, 25, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1850, 57, 26, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1851, 57, 27, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1852, 57, 28, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1853, 57, 29, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1854, 57, 30, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1855, 57, 31, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1856, 57, 32, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1857, 57, 33, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1858, 57, 34, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1859, 57, 35, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1860, 57, 36, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1861, 57, 37, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1862, 57, 38, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1863, 57, 39, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1864, 57, 40, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1865, 57, 41, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1866, 57, 42, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1867, 57, 43, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1868, 57, 44, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1869, 57, 45, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1870, 57, 46, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1871, 57, 47, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1872, 57, 48, 270.00, NULL, NULL, 'yes', '2025-04-02 14:00:37', '2025-04-02 14:00:37'),
(1873, 58, 1, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1874, 58, 2, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1875, 58, 3, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1876, 58, 4, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1877, 58, 5, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1878, 58, 6, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1879, 58, 7, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1880, 58, 8, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1881, 58, 9, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1882, 58, 10, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1883, 58, 11, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1884, 58, 12, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1885, 58, 13, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:22', '2025-04-02 14:06:22'),
(1886, 58, 14, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1887, 58, 15, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1888, 58, 16, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1889, 58, 17, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1890, 58, 18, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1891, 58, 19, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1892, 58, 20, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1893, 58, 21, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1894, 58, 22, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1895, 58, 23, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1896, 58, 24, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1897, 58, 25, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1898, 58, 26, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1899, 58, 27, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1900, 58, 28, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1901, 58, 29, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1902, 58, 30, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1903, 58, 31, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1904, 58, 32, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1905, 58, 33, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1906, 58, 34, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1907, 58, 35, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1908, 58, 36, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1909, 58, 37, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1910, 58, 38, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1911, 58, 39, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1912, 58, 40, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1913, 58, 41, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1914, 58, 42, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1915, 58, 43, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1916, 58, 44, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1917, 58, 45, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1918, 58, 46, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1919, 58, 47, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1920, 58, 48, 300.00, NULL, NULL, 'yes', '2025-04-02 14:06:23', '2025-04-02 14:06:23'),
(1921, 59, 1, 290.00, NULL, NULL, 'yes', '2025-04-02 15:09:45', '2025-04-02 15:09:45'),
(1922, 59, 2, 290.00, NULL, NULL, 'yes', '2025-04-02 15:09:45', '2025-04-02 15:09:45'),
(1923, 59, 3, 290.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1924, 59, 4, 290.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1925, 59, 5, 285.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1926, 59, 6, 285.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1927, 59, 7, 285.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1928, 59, 8, 285.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1929, 59, 9, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1930, 59, 10, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1931, 59, 11, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1932, 59, 12, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1933, 59, 13, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1934, 59, 14, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1935, 59, 15, 275.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1936, 59, 16, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1937, 59, 17, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1938, 59, 18, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1939, 59, 19, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1940, 59, 20, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1941, 59, 21, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1942, 59, 22, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1943, 59, 23, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1944, 59, 24, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1945, 59, 25, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1946, 59, 26, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1947, 59, 27, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1948, 59, 28, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1949, 59, 29, 265.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1950, 59, 30, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1951, 59, 31, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1952, 59, 32, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1953, 59, 33, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1954, 59, 34, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1955, 59, 35, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1956, 59, 36, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1957, 59, 37, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1958, 59, 38, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1959, 59, 39, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1960, 59, 40, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1961, 59, 41, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1962, 59, 42, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1963, 59, 43, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1964, 59, 44, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1965, 59, 45, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1966, 59, 46, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1967, 59, 47, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1968, 59, 48, 250.00, NULL, NULL, 'yes', '2025-04-02 15:09:46', '2025-04-02 15:09:46'),
(1969, 60, 1, 400.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1970, 60, 2, 400.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1971, 60, 3, 400.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1972, 60, 4, 400.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1973, 60, 5, 385.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1974, 60, 6, 385.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1975, 60, 7, 385.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1976, 60, 8, 385.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1977, 60, 9, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1978, 60, 10, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1979, 60, 11, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1980, 60, 12, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1981, 60, 13, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1982, 60, 14, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1983, 60, 15, 365.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1984, 60, 16, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1985, 60, 17, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1986, 60, 18, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1987, 60, 19, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1988, 60, 20, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1989, 60, 21, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1990, 60, 22, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1991, 60, 23, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1992, 60, 24, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1993, 60, 25, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1994, 60, 26, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1995, 60, 27, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1996, 60, 28, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1997, 60, 29, 345.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1998, 60, 30, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(1999, 60, 31, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2000, 60, 32, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2001, 60, 33, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2002, 60, 34, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2003, 60, 35, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2004, 60, 36, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2005, 60, 37, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2006, 60, 38, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2007, 60, 39, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2008, 60, 40, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2009, 60, 41, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2010, 60, 42, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2011, 60, 43, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2012, 60, 44, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2013, 60, 45, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2014, 60, 46, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2015, 60, 47, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2016, 60, 48, 325.00, NULL, NULL, 'yes', '2025-04-02 15:11:18', '2025-04-02 15:11:18'),
(2017, 61, 1, 420.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2018, 61, 2, 420.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2019, 61, 3, 420.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2020, 61, 4, 420.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2021, 61, 5, 400.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2022, 61, 6, 400.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2023, 61, 7, 400.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2024, 61, 8, 400.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2025, 61, 9, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2026, 61, 10, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2027, 61, 11, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2028, 61, 12, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2029, 61, 13, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2030, 61, 14, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2031, 61, 15, 385.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2032, 61, 16, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2033, 61, 17, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2034, 61, 18, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2035, 61, 19, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2036, 61, 20, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2037, 61, 21, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2038, 61, 22, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2039, 61, 23, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2040, 61, 24, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2041, 61, 25, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2042, 61, 26, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2043, 61, 27, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2044, 61, 28, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2045, 61, 29, 370.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2046, 61, 30, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2047, 61, 31, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2048, 61, 32, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2049, 61, 33, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2050, 61, 34, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2051, 61, 35, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2052, 61, 36, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2053, 61, 37, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2054, 61, 38, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2055, 61, 39, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2056, 61, 40, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2057, 61, 41, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2058, 61, 42, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2059, 61, 43, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2060, 61, 44, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2061, 61, 45, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2062, 61, 46, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2063, 61, 47, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2064, 61, 48, 350.00, NULL, NULL, 'yes', '2025-04-02 15:12:49', '2025-04-02 15:12:49'),
(2065, 62, 1, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2066, 62, 2, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2067, 62, 3, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2068, 62, 4, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2069, 62, 5, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2070, 62, 6, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2071, 62, 7, 215.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2072, 62, 8, 201.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2073, 62, 9, 201.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2074, 62, 10, 201.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2075, 62, 11, 201.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2076, 62, 12, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2077, 62, 13, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2078, 62, 14, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2079, 62, 15, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2080, 62, 16, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2081, 62, 17, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2082, 62, 18, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2083, 62, 19, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2084, 62, 20, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2085, 62, 21, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2086, 62, 22, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2087, 62, 23, 187.00, NULL, NULL, 'yes', '2025-04-02 15:15:52', '2025-04-02 15:15:52'),
(2088, 62, 24, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2089, 62, 25, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2090, 62, 26, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2091, 62, 27, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2092, 62, 28, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2093, 62, 29, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2094, 62, 30, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2095, 62, 31, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2096, 62, 32, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2097, 62, 33, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2098, 62, 34, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2099, 62, 35, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2100, 62, 36, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2101, 62, 37, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2102, 62, 38, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2103, 62, 39, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2104, 62, 40, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2105, 62, 41, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2106, 62, 42, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2107, 62, 43, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2108, 62, 44, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2109, 62, 45, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2110, 62, 46, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2111, 62, 47, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2112, 62, 48, 170.00, NULL, NULL, 'yes', '2025-04-02 15:15:53', '2025-04-02 15:15:53'),
(2113, 63, 1, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2114, 63, 2, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2115, 63, 3, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2116, 63, 4, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2117, 63, 5, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2118, 63, 6, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2119, 63, 7, 262.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2120, 63, 8, 245.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2121, 63, 9, 245.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2122, 63, 10, 245.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2123, 63, 11, 245.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2124, 63, 12, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2125, 63, 13, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2126, 63, 14, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2127, 63, 15, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2128, 63, 16, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2129, 63, 17, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2130, 63, 18, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2131, 63, 19, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2132, 63, 20, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2133, 63, 21, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2134, 63, 22, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2135, 63, 23, 227.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2136, 63, 24, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2137, 63, 25, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2138, 63, 26, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2139, 63, 27, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2140, 63, 28, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2141, 63, 29, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2142, 63, 30, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2143, 63, 31, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2144, 63, 32, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2145, 63, 33, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2146, 63, 34, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2147, 63, 35, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2148, 63, 36, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2149, 63, 37, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2150, 63, 38, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2151, 63, 39, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2152, 63, 40, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2153, 63, 41, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2154, 63, 42, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:36', '2025-04-02 15:17:36'),
(2155, 63, 43, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2156, 63, 44, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2157, 63, 45, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2158, 63, 46, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2159, 63, 47, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2160, 63, 48, 199.00, NULL, NULL, 'yes', '2025-04-02 15:17:37', '2025-04-02 15:17:37'),
(2161, 66, 1, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2162, 66, 2, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2163, 66, 3, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2164, 66, 4, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2165, 66, 5, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2166, 66, 6, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:44', '2025-04-02 15:19:44'),
(2167, 66, 7, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2168, 66, 8, 200.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2169, 66, 9, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2170, 66, 10, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2171, 66, 11, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2172, 66, 12, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2173, 66, 13, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2174, 66, 14, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2175, 66, 15, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2176, 66, 16, 195.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2177, 66, 17, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2178, 66, 18, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2179, 66, 19, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2180, 66, 20, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2181, 66, 21, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2182, 66, 22, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2183, 66, 23, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2184, 66, 24, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2185, 66, 25, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2186, 66, 26, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2187, 66, 27, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2188, 66, 28, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2189, 66, 29, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2190, 66, 30, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2191, 66, 31, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2192, 66, 32, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2193, 66, 33, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2194, 66, 34, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2195, 66, 35, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2196, 66, 36, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2197, 66, 37, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2198, 66, 38, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2199, 66, 39, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2200, 66, 40, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2201, 66, 41, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2202, 66, 42, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2203, 66, 43, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2204, 66, 44, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2205, 66, 45, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2206, 66, 46, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2207, 66, 47, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2208, 66, 48, 190.00, NULL, NULL, 'yes', '2025-04-02 15:19:45', '2025-04-02 15:19:45'),
(2209, 67, 1, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2210, 67, 2, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2211, 67, 3, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2212, 67, 4, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2213, 67, 5, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2214, 67, 6, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2215, 67, 7, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2216, 67, 8, 260.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2217, 67, 9, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2218, 67, 10, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2219, 67, 11, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2220, 67, 12, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2221, 67, 13, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2222, 67, 14, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2223, 67, 15, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2224, 67, 16, 255.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2225, 67, 17, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2226, 67, 18, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2227, 67, 19, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2228, 67, 20, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2229, 67, 21, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2230, 67, 22, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2231, 67, 23, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2232, 67, 24, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2233, 67, 25, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2234, 67, 26, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2235, 67, 27, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2236, 67, 28, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2237, 67, 29, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2238, 67, 30, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2239, 67, 31, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2240, 67, 32, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2241, 67, 33, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2242, 67, 34, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2243, 67, 35, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2244, 67, 36, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2245, 67, 37, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2246, 67, 38, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2247, 67, 39, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2248, 67, 40, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2249, 67, 41, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2250, 67, 42, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2251, 67, 43, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2252, 67, 44, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2253, 67, 45, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2254, 67, 46, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2255, 67, 47, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2256, 67, 48, 250.00, NULL, NULL, 'yes', '2025-04-02 15:21:15', '2025-04-02 15:21:15'),
(2257, 68, 1, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2258, 68, 2, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2259, 68, 3, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2260, 68, 4, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2261, 68, 5, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2262, 68, 6, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2263, 68, 7, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2264, 68, 8, 270.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2265, 68, 9, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2266, 68, 10, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2267, 68, 11, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2268, 68, 12, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2269, 68, 13, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2270, 68, 14, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2271, 68, 15, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2272, 68, 16, 265.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2273, 68, 17, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2274, 68, 18, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2275, 68, 19, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2276, 68, 20, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2277, 68, 21, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2278, 68, 22, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2279, 68, 23, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2280, 68, 24, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2281, 68, 25, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2282, 68, 26, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2283, 68, 27, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2284, 68, 28, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2285, 68, 29, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2286, 68, 30, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2287, 68, 31, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2288, 68, 32, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2289, 68, 33, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2290, 68, 34, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2291, 68, 35, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2292, 68, 36, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2293, 68, 37, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2294, 68, 38, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2295, 68, 39, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2296, 68, 40, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2297, 68, 41, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2298, 68, 42, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2299, 68, 43, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2300, 68, 44, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2301, 68, 45, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2302, 68, 46, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2303, 68, 47, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2304, 68, 48, 260.00, NULL, NULL, 'yes', '2025-04-02 15:22:20', '2025-04-02 15:22:20'),
(2305, 71, 1, 340.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15');
INSERT INTO `language_school_course_fees` (`id`, `language_school_course_id`, `week_number`, `fee`, `valid_from`, `valid_to`, `price_split`, `created_at`, `updated_at`) VALUES
(2306, 71, 2, 340.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2307, 71, 3, 340.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2308, 71, 4, 340.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2309, 71, 5, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2310, 71, 6, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2311, 71, 7, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2312, 71, 8, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2313, 71, 9, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2314, 71, 10, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2315, 71, 11, 300.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2316, 71, 12, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2317, 71, 13, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2318, 71, 14, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2319, 71, 15, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2320, 71, 16, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2321, 71, 17, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2322, 71, 18, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2323, 71, 19, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2324, 71, 20, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2325, 71, 21, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2326, 71, 22, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2327, 71, 23, 271.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2328, 71, 24, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2329, 71, 25, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2330, 71, 26, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2331, 71, 27, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2332, 71, 28, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2333, 71, 29, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2334, 71, 30, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2335, 71, 31, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2336, 71, 32, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2337, 71, 33, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2338, 71, 34, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2339, 71, 35, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2340, 71, 36, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2341, 71, 37, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2342, 71, 38, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2343, 71, 39, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2344, 71, 40, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2345, 71, 41, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2346, 71, 42, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2347, 71, 43, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2348, 71, 44, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2349, 71, 45, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2350, 71, 46, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2351, 71, 47, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2352, 71, 48, 250.00, NULL, NULL, 'yes', '2025-04-02 15:25:15', '2025-04-02 15:25:15'),
(2353, 72, 1, 400.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2354, 72, 2, 400.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2355, 72, 3, 400.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2356, 72, 4, 400.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2357, 72, 5, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2358, 72, 6, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2359, 72, 7, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2360, 72, 8, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2361, 72, 9, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:25', '2025-04-02 15:26:25'),
(2362, 72, 10, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2363, 72, 11, 355.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2364, 72, 12, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2365, 72, 13, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2366, 72, 14, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2367, 72, 15, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2368, 72, 16, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2369, 72, 17, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2370, 72, 18, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2371, 72, 19, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2372, 72, 20, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2373, 72, 21, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2374, 72, 22, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2375, 72, 23, 320.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2376, 72, 24, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2377, 72, 25, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2378, 72, 26, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2379, 72, 27, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2380, 72, 28, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2381, 72, 29, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2382, 72, 30, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2383, 72, 31, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2384, 72, 32, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2385, 72, 33, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2386, 72, 34, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2387, 72, 35, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2388, 72, 36, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2389, 72, 37, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2390, 72, 38, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2391, 72, 39, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2392, 72, 40, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2393, 72, 41, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2394, 72, 42, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2395, 72, 43, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:26', '2025-04-02 15:26:26'),
(2396, 72, 44, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:27', '2025-04-02 15:26:27'),
(2397, 72, 45, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:27', '2025-04-02 15:26:27'),
(2398, 72, 46, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:27', '2025-04-02 15:26:27'),
(2399, 72, 47, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:27', '2025-04-02 15:26:27'),
(2400, 72, 48, 300.00, NULL, NULL, 'yes', '2025-04-02 15:26:27', '2025-04-02 15:26:27'),
(2401, 69, 1, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2402, 69, 2, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2403, 69, 3, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2404, 69, 4, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2405, 69, 5, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2406, 69, 6, 320.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2407, 69, 7, 305.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2408, 69, 8, 305.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2409, 69, 9, 305.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2410, 69, 10, 305.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2411, 69, 11, 305.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2412, 69, 12, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2413, 69, 13, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2414, 69, 14, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2415, 69, 15, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2416, 69, 16, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2417, 69, 17, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2418, 69, 18, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2419, 69, 19, 285.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2420, 69, 20, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2421, 69, 21, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2422, 69, 22, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2423, 69, 23, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2424, 69, 24, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2425, 69, 25, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2426, 69, 26, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2427, 69, 27, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2428, 69, 28, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:58', '2025-04-02 15:28:58'),
(2429, 69, 29, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2430, 69, 30, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2431, 69, 31, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2432, 69, 32, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2433, 69, 33, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2434, 69, 34, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2435, 69, 35, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2436, 69, 36, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2437, 69, 37, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2438, 69, 38, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2439, 69, 39, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2440, 69, 40, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2441, 69, 41, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2442, 69, 42, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2443, 69, 43, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2444, 69, 44, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2445, 69, 45, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2446, 69, 46, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2447, 69, 47, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2448, 69, 48, 270.00, NULL, NULL, 'yes', '2025-04-02 15:28:59', '2025-04-02 15:28:59'),
(2449, 70, 1, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2450, 70, 2, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2451, 70, 3, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2452, 70, 4, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2453, 70, 5, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2454, 70, 6, 365.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2455, 70, 7, 350.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2456, 70, 8, 350.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2457, 70, 9, 350.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2458, 70, 10, 350.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2459, 70, 11, 350.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2460, 70, 12, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2461, 70, 13, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2462, 70, 14, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2463, 70, 15, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2464, 70, 16, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2465, 70, 17, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2466, 70, 18, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2467, 70, 19, 330.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2468, 70, 20, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2469, 70, 21, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2470, 70, 22, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2471, 70, 23, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2472, 70, 24, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2473, 70, 25, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2474, 70, 26, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2475, 70, 27, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2476, 70, 28, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:27', '2025-04-02 15:30:27'),
(2477, 70, 29, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2478, 70, 30, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2479, 70, 31, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2480, 70, 32, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2481, 70, 33, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2482, 70, 34, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2483, 70, 35, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2484, 70, 36, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2485, 70, 37, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2486, 70, 38, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2487, 70, 39, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2488, 70, 40, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2489, 70, 41, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2490, 70, 42, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2491, 70, 43, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2492, 70, 44, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2493, 70, 45, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2494, 70, 46, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2495, 70, 47, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2496, 70, 48, 315.00, NULL, NULL, 'yes', '2025-04-02 15:30:28', '2025-04-02 15:30:28'),
(2497, 73, 1, 295.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2498, 73, 2, 295.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2499, 73, 3, 295.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2500, 73, 4, 295.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2501, 73, 5, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2502, 73, 6, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2503, 73, 7, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2504, 73, 8, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2505, 73, 9, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2506, 73, 10, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2507, 73, 11, 275.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2508, 73, 12, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2509, 73, 13, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2510, 73, 14, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2511, 73, 15, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2512, 73, 16, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2513, 73, 17, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2514, 73, 18, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2515, 73, 19, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2516, 73, 20, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2517, 73, 21, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2518, 73, 22, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2519, 73, 23, 255.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2520, 73, 24, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2521, 73, 25, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2522, 73, 26, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2523, 73, 27, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2524, 73, 28, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2525, 73, 29, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2526, 73, 30, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2527, 73, 31, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2528, 73, 32, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2529, 73, 33, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2530, 73, 34, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2531, 73, 35, 235.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2532, 73, 36, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2533, 73, 37, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2534, 73, 38, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2535, 73, 39, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2536, 73, 40, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2537, 73, 41, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2538, 73, 42, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2539, 73, 43, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2540, 73, 44, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2541, 73, 45, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2542, 73, 46, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2543, 73, 47, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2544, 73, 48, 215.00, NULL, NULL, 'yes', '2025-04-02 15:32:37', '2025-04-02 15:32:37'),
(2545, 74, 1, 375.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2546, 74, 2, 375.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2547, 74, 3, 375.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2548, 74, 4, 375.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2549, 74, 5, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2550, 74, 6, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2551, 74, 7, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2552, 74, 8, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2553, 74, 9, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2554, 74, 10, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2555, 74, 11, 355.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2556, 74, 12, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2557, 74, 13, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2558, 74, 14, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2559, 74, 15, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2560, 74, 16, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2561, 74, 17, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2562, 74, 18, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2563, 74, 19, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2564, 74, 20, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2565, 74, 21, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2566, 74, 22, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2567, 74, 23, 335.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2568, 74, 24, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2569, 74, 25, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2570, 74, 26, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2571, 74, 27, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2572, 74, 28, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2573, 74, 29, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2574, 74, 30, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2575, 74, 31, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2576, 74, 32, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2577, 74, 33, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2578, 74, 34, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2579, 74, 35, 315.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2580, 74, 36, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2581, 74, 37, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2582, 74, 38, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2583, 74, 39, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2584, 74, 40, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2585, 74, 41, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2586, 74, 42, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2587, 74, 43, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2588, 74, 44, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2589, 74, 45, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2590, 74, 46, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2591, 74, 47, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53'),
(2592, 74, 48, 395.00, NULL, NULL, 'yes', '2025-04-02 15:33:53', '2025-04-02 15:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_course_material_fees`
--

CREATE TABLE `language_school_course_material_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_school_course_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `billing_unit` enum('week','month','course') NOT NULL,
  `billing_count` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_course_material_fees`
--

INSERT INTO `language_school_course_material_fees` (`id`, `language_school_course_id`, `amount`, `billing_unit`, `billing_count`, `created_at`, `updated_at`) VALUES
(2, 66, 30.00, 'course', 1, '2025-04-02 16:54:44', '2025-04-02 16:54:44'),
(3, 67, 30.00, 'course', 1, '2025-04-02 16:54:47', '2025-04-02 16:54:47'),
(4, 68, 30.00, 'course', 1, '2025-04-02 16:54:51', '2025-04-02 16:54:51'),
(5, 56, 40.00, 'course', 1, '2025-04-02 16:55:14', '2025-04-02 16:55:14'),
(6, 57, 40.00, 'course', 1, '2025-04-02 16:55:17', '2025-04-02 16:55:17'),
(7, 58, 40.00, 'course', 1, '2025-04-02 16:55:20', '2025-04-02 16:55:20'),
(8, 53, 35.00, 'course', 1, '2025-04-02 16:55:42', '2025-04-02 16:55:42'),
(9, 54, 35.00, 'course', 1, '2025-04-02 16:55:45', '2025-04-02 16:55:45'),
(10, 55, 35.00, 'course', 1, '2025-04-02 16:55:48', '2025-04-02 16:55:48'),
(11, 45, 40.00, 'course', 1, '2025-04-02 16:56:09', '2025-04-02 16:56:09'),
(12, 46, 40.00, 'course', 1, '2025-04-02 16:56:12', '2025-04-02 16:56:12'),
(13, 47, 40.00, 'course', 1, '2025-04-02 16:56:15', '2025-04-02 16:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_discounts`
--

CREATE TABLE `language_school_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `applies_to_all_branches` tinyint(1) NOT NULL DEFAULT 1,
  `applies_to_all_countries` tinyint(1) NOT NULL DEFAULT 1,
  `school_branch_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`school_branch_ids`)),
  `country_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`country_ids`)),
  `applies_to_user_country` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_school_insurance_fees`
--

CREATE TABLE `language_school_insurance_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `billing_unit` enum('week','month','course') NOT NULL DEFAULT 'week',
  `billing_count` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_insurance_fees`
--

INSERT INTO `language_school_insurance_fees` (`id`, `branch_id`, `amount`, `currency`, `billing_unit`, `billing_count`, `created_at`, `updated_at`) VALUES
(5, 10, 7.50, 'USD', 'week', 1, '2025-04-02 19:16:40', '2025-04-02 19:16:40'),
(6, 16, 12.00, 'USD', 'week', 1, '2025-04-02 19:17:53', '2025-04-02 19:17:53'),
(7, 13, 11.12, 'USD', 'week', 1, '2025-04-02 19:18:06', '2025-04-02 19:18:06'),
(8, 15, 35.00, 'USD', 'week', 1, '2025-04-02 19:18:46', '2025-04-02 19:18:46'),
(9, 23, 7.00, 'USD', 'week', 1, '2025-04-02 19:19:09', '2025-04-02 19:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_pickups`
--

CREATE TABLE `language_school_pickups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_pickups`
--

INSERT INTO `language_school_pickups` (`id`, `branch_id`, `route`, `price`, `currency`, `notes`, `created_at`, `updated_at`) VALUES
(12, 6, 'Heathrow Airport', 120.00, 'USD', 'مطار هيثرو', '2025-03-25 20:54:10', '2025-03-25 20:54:10'),
(13, 6, 'Gatwick Airport', 150.00, 'USD', 'مطار جاتويك', '2025-03-25 20:54:44', '2025-03-25 20:54:44'),
(14, 6, 'London City Airport', 120.00, 'USD', 'مطار مدينة لندن', '2025-03-25 20:55:30', '2025-03-25 20:55:30'),
(15, 6, 'Stansted Airport', 150.00, 'USD', 'مطار ستانستيد', '2025-03-25 20:56:02', '2025-03-25 20:56:02'),
(16, 6, 'Luton Airport', 150.00, 'USD', 'مطار لوتون', '2025-03-25 20:56:23', '2025-03-25 20:56:23'),
(17, 7, 'London Heathrow Airport', 170.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:36:47', '2025-04-02 20:36:47'),
(18, 7, 'London Gatwick Airport', 95.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:37:26', '2025-04-02 20:37:26'),
(19, 7, 'London Stansted Airport', 240.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 20:37:41', '2025-04-02 20:37:41'),
(20, 7, 'London Luton Airport', 240.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:38:16', '2025-04-02 20:38:16'),
(21, 8, 'London Heathrow Airport', 200.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:38:56', '2025-04-02 20:38:56'),
(22, 8, 'London Gatwick Airport', 225.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:39:17', '2025-04-02 20:39:17'),
(23, 8, 'London Stansted Airport', 125.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 20:39:32', '2025-04-02 20:39:32'),
(24, 8, 'London Luton Airport', 140.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:39:53', '2025-04-02 20:39:53'),
(25, 22, 'London Heathrow Airport', 260.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:40:29', '2025-04-02 20:40:29'),
(26, 22, 'London Gatwick Airport', 280.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:40:42', '2025-04-02 20:40:42'),
(27, 22, 'London Luton Airport', 290.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:40:52', '2025-04-02 20:40:52'),
(28, 22, 'London Stansted Airport', 310.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 20:41:29', '2025-04-02 20:41:29'),
(29, 22, 'Southampton Airport', 70.00, 'USD', 'مطار ساوثهامبتون', '2025-04-02 20:41:42', '2025-04-02 20:41:42'),
(30, 22, 'Bournemouth Airport', 40.00, 'USD', 'مطار بورنموث', '2025-04-02 20:41:54', '2025-04-02 20:41:54'),
(31, 22, 'Poole Ferry Terminal', 35.00, 'USD', 'ميناء العبّارات في بول', '2025-04-02 20:42:12', '2025-04-02 20:42:12'),
(32, 22, 'Central London Airport', 280.00, 'USD', 'مطار لندن المركزي', '2025-04-02 20:42:31', '2025-04-02 20:42:31'),
(33, 13, 'London Heathrow Airport', 140.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:43:01', '2025-04-02 20:43:01'),
(34, 13, 'London Gatwick Airport', 195.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:43:45', '2025-04-02 20:43:45'),
(35, 13, 'London Luton Airport', 215.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:44:01', '2025-04-02 20:44:01'),
(36, 23, 'Bournemouth Airport', 60.00, 'USD', 'مطار بورنموث', '2025-04-02 20:45:09', '2025-04-02 20:45:09'),
(37, 23, 'Southampton Airport', 140.00, 'USD', 'مطار ساوثهامبتون', '2025-04-02 20:45:25', '2025-04-02 20:45:25'),
(38, 23, 'London Heathrow Airport', 270.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:45:44', '2025-04-02 20:45:44'),
(39, 23, 'London Gatwick Airport', 285.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:46:01', '2025-04-02 20:46:01'),
(40, 23, 'London Luton Airport', 285.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:46:27', '2025-04-02 20:46:27'),
(41, 23, 'Central London Airport', 315.00, 'USD', 'مطار لندن المركزي', '2025-04-02 20:46:43', '2025-04-02 20:46:43'),
(42, 23, 'London Stansted Airport', 330.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 20:47:00', '2025-04-02 20:47:00'),
(43, 19, 'London Stansted Airport', 100.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 20:53:08', '2025-04-02 20:53:08'),
(44, 19, 'London Heathrow Airport', 220.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:53:24', '2025-04-02 20:53:24'),
(45, 19, 'St Pancras Station', 225.00, 'USD', 'محطة سانت بانكراس', '2025-04-02 20:53:57', '2025-04-02 20:53:57'),
(46, 19, 'London Gatwick Airport', 225.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 20:54:12', '2025-04-02 20:54:12'),
(47, 19, 'London Luton Airport', 145.00, 'USD', 'مطار لندن لوتون', '2025-04-02 20:54:23', '2025-04-02 20:54:23'),
(48, 18, 'Manchester Airport', 125.00, 'USD', 'مطار مانشستر', '2025-04-02 20:58:02', '2025-04-02 20:58:02'),
(49, 9, 'London Heathrow Airport', 290.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 20:58:32', '2025-04-02 20:58:32'),
(50, 24, 'London Heathrow Airport', 140.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:03:54', '2025-04-02 21:03:54'),
(51, 24, 'London Gatwick Airport', 160.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 21:04:20', '2025-04-02 21:04:20'),
(52, 24, 'London Stansted Airport', 140.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 21:04:33', '2025-04-02 21:04:33'),
(53, 24, 'London Luton Airport', 165.00, 'USD', 'مطار لندن لوتون', '2025-04-02 21:04:46', '2025-04-02 21:04:46'),
(54, 17, 'London Heathrow Airport', 140.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:05:16', '2025-04-02 21:05:16'),
(55, 10, 'London Heathrow Airport', 179.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:05:48', '2025-04-02 21:05:48'),
(56, 10, 'London Gatwick Airport', 279.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 21:06:10', '2025-04-02 21:06:10'),
(57, 10, 'London Stansted Airport', 189.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 21:06:22', '2025-04-02 21:06:22'),
(58, 10, 'London Luton Airport', 205.00, 'USD', 'مطار لندن لوتون', '2025-04-02 21:06:38', '2025-04-02 21:06:38'),
(59, 16, 'Manchester Airport', 80.00, 'USD', 'مطار مانشستر', '2025-04-02 21:07:14', '2025-04-02 21:07:14'),
(60, 14, 'London Heathrow Airport', 263.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:07:44', '2025-04-02 21:07:44'),
(61, 14, 'London Gatwick Airport', 276.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 21:07:56', '2025-04-02 21:07:56'),
(62, 14, 'Bournemouth Airport', 100.00, 'USD', 'مطار بورنموث', '2025-04-02 21:08:08', '2025-04-02 21:08:08'),
(63, 14, 'Southampton Airport', 158.00, 'USD', 'مطار ساوثهامبتون', '2025-04-02 21:08:22', '2025-04-02 21:08:22'),
(64, 14, 'Central London Airport', 314.00, 'USD', 'مطار لندن المركزي', '2025-04-02 21:08:33', '2025-04-02 21:08:33'),
(65, 14, 'London Stansted Airport', 326.00, 'USD', 'مطار لندن ستانستيد', '2025-04-02 21:08:55', '2025-04-02 21:08:55'),
(66, 14, 'London Luton Airport', 276.00, 'USD', 'مطار لندن لوتون', '2025-04-02 21:09:13', '2025-04-02 21:09:13'),
(67, 14, 'Bristol', 327.00, 'USD', 'Bristol', '2025-04-02 21:09:59', '2025-04-02 21:09:59'),
(68, 15, 'London City Airport', 365.00, 'USD', 'مطار لندن سيتي', '2025-04-02 21:10:42', '2025-04-02 21:10:42'),
(69, 15, 'Manchester Airport', 85.00, 'USD', 'مطار مانشستر', '2025-04-02 21:10:54', '2025-04-02 21:10:54'),
(70, 15, 'Liverpool Airport', 115.00, 'USD', 'مطار ليفربول', '2025-04-02 21:11:07', '2025-04-02 21:11:07'),
(71, 12, 'London Heathrow Airport', 180.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:11:30', '2025-04-02 21:11:30'),
(72, 25, 'London Heathrow Airport', 130.00, 'USD', 'مطار لندن هيثرو', '2025-04-02 21:11:56', '2025-04-02 21:11:56'),
(73, 25, 'London Gatwick Airport', 140.00, 'USD', 'مطار لندن غاتويك', '2025-04-02 21:12:13', '2025-04-02 21:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_pioneers_discounts`
--

CREATE TABLE `language_school_pioneers_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `weeks` int(10) UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `discount_full_for` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language_school_pioneers_discounts`
--

INSERT INTO `language_school_pioneers_discounts` (`id`, `name`, `ar_name`, `weeks`, `discount_amount`, `discount_full_for`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', 10, 155.00, '5', 1, '2026-02-07 08:14:43', '2026-02-07 08:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `language_school_supplements`
--

CREATE TABLE `language_school_supplements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `billing_unit` varchar(50) DEFAULT NULL,
  `billing_count` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_tests`
--

CREATE TABLE `language_tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(30) NOT NULL,
  `name` varchar(60) NOT NULL,
  `ar_name` varchar(80) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `ar_name` varchar(120) DEFAULT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `key`, `name`, `ar_name`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'foundation', 'Foundation', 'البرنامج التأسيسي', 1, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(2, 'international-foundation', 'International Foundation', 'السنة التأسيسية الدولية', 2, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(3, 'bachelor', 'Bachelor', 'بكالوريوس', 3, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(4, 'bachelor-top-up', 'Bachelor Top-up', 'بكالوريوس (تكميلي)', 4, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(5, 'integrated-master', 'Integrated Master', 'ماجستير مدمج', 5, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(6, 'pre-masters', 'Pre-Masters', 'ما قبل الماجستير', 6, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(7, 'graduate-diploma', 'Graduate Diploma', 'دبلوم دراسات عليا', 7, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(8, 'postgraduate-certificate', 'Postgraduate Certificate (PGCert)', 'شهادة دراسات عليا', 8, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(9, 'postgraduate-diploma', 'Postgraduate Diploma (PGDip)', 'دبلوم دراسات عليا', 9, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(10, 'masters', 'Masters', 'ماجستير', 10, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(11, 'mba', 'MBA', 'ماجستير إدارة أعمال', 11, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(12, 'mres', 'MRes', 'ماجستير بحثي', 12, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(13, 'phd-doctorate', 'PhD / Doctorate', 'دكتوراه', 13, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(14, 'professional-doctorate', 'Professional Doctorate', 'دكتوراه مهنية', 14, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(15, 'short-course', 'Short Course', 'دورة قصيرة', 15, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21'),
(16, 'distance-learning', 'Distance Learning', 'التعليم عن بُعد', 16, 1, '2026-02-10 14:31:21', '2026-02-10 14:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meal_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `meal_code`, `name`, `ar_name`, `description`, `ar_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'HALFBOARD', 'Halfboard', 'نصف إقامة', '(Breakfast & Evening Meal)', NULL, '2026-02-11 23:40:53', '2026-02-11 23:40:53', NULL),
(3, 'FULLBOARD', 'Fullboard', 'إقامة كاملة', '(Breakfast, Lunch & Evening Meal)', NULL, '2026-02-11 23:40:53', '2026-02-11 23:40:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_26_000000_create_settings_table', 1),
(5, '2025_10_30_144000_create_user_profiles_table', 1),
(6, '2025_12_19_120000_create_user_otps_table', 1),
(7, '2026_01_27_000001_create_blog_categories_table', 1),
(8, '2026_01_27_000002_create_blogs_table', 1),
(9, '2026_01_27_000003_create_blog_tags_tables', 1),
(10, '2026_01_27_000004_create_countries_table', 1),
(11, '2026_01_27_000005_create_cities_table', 1),
(12, '2026_01_27_115658_create_oauth_auth_codes_table', 1),
(13, '2026_01_27_115659_create_oauth_access_tokens_table', 1),
(14, '2026_01_27_115700_create_oauth_refresh_tokens_table', 1),
(15, '2026_01_27_115701_create_oauth_clients_table', 1),
(16, '2026_01_27_115702_create_oauth_device_codes_table', 1),
(17, '2026_01_27_115901_create_personal_access_tokens_table', 1),
(18, '2026_01_27_121116_create_oauth_auth_codes_table', 1),
(19, '2026_01_27_121117_create_oauth_access_tokens_table', 1),
(20, '2026_01_27_121118_create_oauth_refresh_tokens_table', 1),
(21, '2026_01_27_121119_create_oauth_clients_table', 1),
(22, '2026_01_27_121120_create_oauth_device_codes_table', 1),
(23, '2026_01_30_170507_create_user_profiles_table', 1),
(24, '2026_02_01_171530_create_settings_table', 1),
(25, '2026_02_05_200000_create_applications_table', 1),
(26, '2026_02_05_210000_create_contact_submissions_table', 1),
(27, '2026_02_05_220000_create_reviews_table', 1),
(28, '2026_02_05_220100_create_faqs_table', 1),
(29, '2026_02_05_220200_create_certifications_table', 1),
(30, '2026_02_05_230000_create_course_attributes_tables', 1),
(31, '2026_02_05_240000_create_universities_table', 2),
(32, '2026_02_05_240100_create_university_campuses_table', 3),
(33, '2026_02_05_240200_create_university_courses_table', 4),
(34, '2026_02_05_240300_create_university_course_intake_term_table', 5),
(35, '2026_02_05_240400_create_university_course_intakes_table', 6),
(36, '2026_02_05_240500_create_university_course_fees_table', 7),
(37, '2026_02_05_250000_create_destinations_tables', 8),
(38, '2026_02_05_250500_create_destination_guides_table', 9),
(39, '2026_02_05_250000_create_scholarships_table', 10),
(40, '2026_02_05_250100_add_tags_to_scholarships_table', 10),
(41, '2026_02_05_250200_create_scholarship_applications_table', 10),
(42, '2026_02_05_250300_add_assignee_id_to_scholarship_applications_table', 10),
(43, '2026_02_05_260000_create_featured_lists_table', 11),
(44, '2026_02_05_260100_create_university_wishlists_table', 11),
(45, '2026_02_05_260200_create_uni_applications_table', 11),
(46, '2026_02_05_260300_create_offices_table', 11),
(47, '2026_02_05_260400_create_university_accommodation_rooms_table', 11),
(49, '2026_02_05_270000_update_university_courses_add_requirements', 12),
(50, '2026_02_05_280000_create_accreditations_table', 13),
(51, '2026_02_05_280100_create_language_course_tags_table', 13),
(52, '2026_02_05_280200_create_language_course_types_table', 13),
(53, '2026_02_05_280300_create_meal_plans_table', 13),
(54, '2026_02_05_280400_create_bedroom_types_table', 13),
(55, '2026_02_05_280500_create_bathroom_types_table', 13),
(56, '2026_02_05_290000_create_language_schools_table', 14),
(57, '2026_02_05_290100_create_language_school_branches_table', 14),
(58, '2026_02_05_290200_create_language_school_courses_table', 14),
(59, '2026_02_05_300000_create_galleries_table', 15),
(60, '2026_02_05_310000_create_language_school_course_fees_table', 16),
(61, '2026_02_05_310100_create_language_school_course_material_fees_table', 17),
(62, '2026_02_05_310200_create_language_school_branch_registration_fees_table', 17),
(63, '2026_02_05_310300_create_language_school_branch_high_season_fees_table', 17),
(64, '2026_02_05_310400_create_language_school_accommodations_table', 17),
(65, '2026_02_05_310500_create_language_school_supplements_table', 17),
(66, '2026_02_05_310600_create_language_school_pickups_table', 17),
(67, '2026_02_05_310700_create_language_school_insurance_fees_table', 17),
(68, '2026_02_05_320000_create_language_school_discounts_table', 18),
(69, '2026_02_05_320100_create_language_school_coupons_table', 18),
(70, '2026_02_05_320200_create_language_school_pioneers_discounts_table', 18),
(71, '2026_02_05_330000_create_language_course_online_courses_table', 19),
(72, '2026_02_05_330100_create_language_course_summer_camps_table', 19),
(73, '2026_02_05_330200_create_language_course_summer_camp_details_table', 19),
(74, '2026_02_05_330300_create_language_course_training_courses_table', 19),
(75, '2026_02_05_340000_create_exchange_rates_table', 20),
(76, '2026_02_05_340100_create_conversion_fees_table', 20),
(80, '2026_02_05_350000_update_language_school_courses_slug_nullable', 21),
(81, '2026_02_05_350100_update_status_enum_in_applications', 21),
(82, '2026_02_05_350200_update_established_year_to_integer', 21),
(83, '2026_02_05_360000_add_is_preferred_to_language_schools', 22),
(84, '2026_02_05_370000_create_agents_table', 23),
(85, '2026_02_05_370100_create_agent_students_table', 23),
(86, '2026_02_05_380000_create_cms_pages_table', 24),
(87, '2026_02_10_000001_update_universities_rankings', 25),
(88, '2026_02_10_000010_create_university_course_catalogs', 26),
(89, '2026_02_10_000011_update_university_courses_for_catalog', 26),
(90, '2026_02_12_120000_update_language_school_accommodations_table', 27),
(91, '2026_02_12_000000_add_thumbnails_to_language_course_home_tables', 28),
(92, '2026_02_14_100000_create_language_course_wishlists_table', 29),
(93, '2026_02_14_100100_create_language_course_compares_table', 29);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` char(80) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('c0e7569229239455e51a2485b0e27785a6dd24b36944da185062316514d9683207184ae688e6ebc9', 8, '019c5e93-5830-73ba-bea0-be4cf1969c8a', 'api', '[]', 0, '2026-02-14 18:07:52', '2026-02-14 18:07:52', '2027-02-15 00:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` char(80) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` char(36) NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) NOT NULL,
  `owner_type` varchar(255) DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect_uris` text NOT NULL,
  `grant_types` text NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `owner_type`, `owner_id`, `name`, `secret`, `provider`, `redirect_uris`, `grant_types`, `revoked`, `created_at`, `updated_at`) VALUES
('019c5e93-5830-73ba-bea0-be4cf1969c8a', NULL, NULL, 'Personal Access Client (users)', '$2y$12$B.FbSU9q9vYImAVUFPUdNuHVucuG2arJZ4.IS.IQX1wWwKOYDOf8.', 'users', '[]', '[\"personal_access\"]', 0, '2026-02-14 17:54:00', '2026-02-14 17:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_device_codes`
--

CREATE TABLE `oauth_device_codes` (
  `id` char(80) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` char(36) NOT NULL,
  `user_code` char(8) NOT NULL,
  `scopes` text NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `user_approved_at` datetime DEFAULT NULL,
  `last_polled_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` char(80) NOT NULL,
  `access_token_id` char(80) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `ar_city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `ar_country` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `ar_address` text DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Branch Office',
  `image` varchar(255) DEFAULT NULL,
  `map_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `hours` varchar(255) DEFAULT NULL,
  `ar_hours` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `university_name` varchar(255) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `institute_name` varchar(255) DEFAULT NULL,
  `ar_institute_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `ar_review_text` text DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_iframe` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `screenshots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`screenshots`)),
  `video` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `ar_name`, `university_name`, `course_name`, `country_name`, `photo`, `institute_name`, `ar_institute_name`, `title`, `ar_title`, `review_text`, `ar_review_text`, `gender`, `rating`, `video_url`, `video_iframe`, `thumbnail`, `facebook_link`, `twitter_link`, `instagram_link`, `linkedin_link`, `screenshots`, `video`, `is_approved`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(2, 'Sarah Smith', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(3, 'Ali Khan', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(4, 'Maria Garcia', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(5, 'Chen Wei', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(6, 'Ahmed Hassan', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(7, 'Emily Johnson', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(8, 'David Lee', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(9, 'Fatima Al-Sayed', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(10, 'James Wilson', NULL, NULL, NULL, NULL, NULL, 'University of London', NULL, 'Highly Recommended', NULL, 'An amazing experience! The support was fantastic and I love the campus.', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:07:55', '2026-01-29 07:07:55'),
(11, 'Michael Brown', 'مايكل براون', 'University of Manchester', 'MBA', 'UK', 'reviews/photos/eHUVpCNbwBM5NuGJqmOQdOo6cUT59183sgsCwT6Z.jpg', 'University of Manchester', NULL, 'My Journey', NULL, 'Watch my full review of the course.', NULL, NULL, 5, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, 'reviews/thumbnails/AFj2dsVKAYRQSjZ9nyZblTQVL9PtjNL7gg7ctbIT.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:14:29', '2026-02-14 17:24:19'),
(12, 'Linda Green', NULL, 'University of Manchester', 'MBA', 'UK', NULL, 'University of Manchester', NULL, 'My Journey', NULL, 'Watch my full review of the course.', NULL, NULL, 5, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:14:29', '2026-01-29 07:14:29'),
(13, 'Robert Taylor', NULL, 'University of Manchester', 'MBA', 'UK', NULL, 'University of Manchester', NULL, 'My Journey', NULL, 'Watch my full review of the course.', NULL, NULL, 5, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-29 07:14:29', '2026-01-29 07:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_id` bigint(20) UNSIGNED DEFAULT NULL,
  `provider_name` varchar(255) DEFAULT NULL,
  `ar_provider_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `ar_summary` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `ar_description` longtext DEFAULT NULL,
  `amount_type` enum('fixed','percentage','variable') NOT NULL DEFAULT 'variable',
  `amount_value` decimal(12,2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `min_amount` decimal(12,2) DEFAULT NULL,
  `max_amount` decimal(12,2) DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `eligible_nationalities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`eligible_nationalities`)),
  `eligibility_text` longtext DEFAULT NULL,
  `ar_eligibility_text` longtext DEFAULT NULL,
  `apply_link` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_applications`
--

CREATE TABLE `scholarship_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scholarship_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scholarship_title` varchar(255) DEFAULT NULL,
  `scholarship_slug` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `grade_average` varchar(255) DEFAULT NULL,
  `english_proficiency` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewing','approved','rejected') NOT NULL DEFAULT 'pending',
  `assignee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FYkKd2IkRmfJqywQZwd8kjGOGmIBsGfKZzottjl2', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic1lCcnlQdmkwTGhTa242NUtKR0Z2d0QzeHVRdXRXeEc5VUI4Z3FkOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jbXMvYnJhbmRpbmciO3M6NToicm91dGUiO3M6MjM6ImFkbWluLmNtcy5icmFuZGluZy5lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771115957);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', '\"Pioneers Admissions\"', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(2, 'site_email', '\"info@pioneers.edu.sa\"', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(3, 'site_phone', '\"+966 50 123 4567\"', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(4, 'site_address', '\"Riyadh, Saudi Arabia\"', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(5, 'social_links', '[{\"platform\":\"Facebook\",\"url\":\"#\"},{\"platform\":\"Twitter\",\"url\":\"#\"},{\"platform\":\"Instagram\",\"url\":\"#\"},{\"platform\":\"LinkedIn\",\"url\":\"#\"}]', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(6, 'contact_description', '\"Can\'t make it to an office? No problem. Fill out the form or reach out to us directly through our general channels.\"', '2026-02-07 01:19:35', '2026-02-07 01:19:35'),
(7, 'app.branding', '{\"app_name\":\"Laravel\",\"primary_color\":\"#6366f1\",\"logo\":\"branding\\/fEUJJwntLjx3czmWhKvZ5XC0qmBPotcmR9HXEOPI.png\",\"favicon\":null}', '2026-02-07 01:20:08', '2026-02-08 13:41:30'),
(8, 'branding', '{\"app\":\"courseenglish\",\"header\":{\"logo\":{\"main\":\"\\/logo.png\",\"ar\":\"\\/logo.png\"},\"top_nav\":[{\"label\":\"Language Institutes\",\"ar_label\":\"\\u0645\\u0639\\u0627\\u0647\\u062f \\u0627\\u0644\\u0644\\u063a\\u0629\",\"url\":\"\\/language-institutes\",\"href\":\"\\/language-institutes\"},{\"label\":\"Summer Programs\",\"ar_label\":\"\\u0628\\u0631\\u0627\\u0645\\u062c \\u0627\\u0644\\u0635\\u064a\\u0641\",\"url\":\"\\/summer-programs\",\"href\":\"\\/summer-programs\"},{\"label\":\"Online Courses\",\"ar_label\":\"\\u0627\\u0644\\u062f\\u0648\\u0631\\u0627\\u062a \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0629\",\"url\":\"\\/online-courses\",\"href\":\"\\/online-courses\"},{\"label\":\"University Admissions\",\"ar_label\":\"\\u0627\\u0644\\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u062c\\u0627\\u0645\\u0639\\u064a\",\"url\":\"\\/university-admissions\",\"href\":\"\\/university-admissions\"},{\"label\":\"Travel & Tourism\",\"ar_label\":\"\\u0627\\u0644\\u0633\\u0641\\u0631 \\u0648\\u0627\\u0644\\u0633\\u064a\\u0627\\u062d\\u0629\",\"url\":\"\\/travel-and-tourism\",\"href\":\"\\/travel-and-tourism\"},{\"label\":\"Training & Professional Courses\",\"ar_label\":\"\\u0627\\u0644\\u062a\\u062f\\u0631\\u064a\\u0628 \\u0648\\u0627\\u0644\\u062f\\u0648\\u0631\\u0627\\u062a \\u0627\\u0644\\u0645\\u0647\\u0646\\u064a\\u0629\",\"url\":\"\\/training-and-professional-courses\",\"href\":\"\\/training-and-professional-courses\"}],\"main_nav\":[{\"label\":\"Home\",\"ar_label\":\"\\u0627\\u0644\\u0631\\u0626\\u064a\\u0633\\u064a\\u0629\",\"url\":\"\\/\",\"href\":\"\\/\"},{\"label\":\"Offers\",\"ar_label\":\"\\u0627\\u0644\\u0639\\u0631\\u0648\\u0636\",\"url\":\"\\/offers\",\"href\":\"\\/offers\"},{\"label\":\"About Us\",\"ar_label\":\"\\u0645\\u0646 \\u0646\\u062d\\u0646\",\"url\":\"\\/about-us\",\"href\":\"\\/about-us\"},{\"label\":\"Contact Us\",\"ar_label\":\"\\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627\",\"url\":\"\\/contact-us\",\"href\":\"\\/contact-us\"},{\"label\":\"Articles\",\"ar_label\":\"\\u0627\\u0644\\u0645\\u0642\\u0627\\u0644\\u0627\\u062a\",\"url\":\"\\/articles\",\"href\":\"\\/articles\"}],\"currencies\":[{\"code\":\"SAR\",\"label\":\"Saudi Riyal\",\"symbol\":\"\\ufdfc\",\"icon\":\"\\/assets\\/sar.svg\"},{\"code\":\"GBP\",\"label\":\"British Pound\",\"symbol\":\"\\u00a3\",\"icon\":\"\\/assets\\/gbp.svg\"}],\"languages\":[{\"code\":\"en\",\"label\":\"English\",\"flag\":\"\\/assets\\/flags\\/gb.svg\"},{\"code\":\"ar\",\"label\":\"Arabic\",\"flag\":\"\\/assets\\/flags\\/sa.svg\"}],\"buttons\":{\"compare\":{\"icon\":\"compare\",\"url\":\"\\/compare\"},\"wishlist\":{\"icon\":\"heart\",\"url\":\"\\/wishlist\"},\"account\":{\"label\":\"My Account\",\"ar_label\":\"\\u062d\\u0633\\u0627\\u0628\\u064a\",\"url\":\"\\/student\\/dashboard\",\"icon\":\"user\"}}},\"footer\":{\"subscribe\":{\"heading\":\"Stay in the loop\",\"placeholder\":\"Enter your email\",\"button_text\":\"Subscribe\"},\"columns\":[{\"title\":\"Programs\",\"items\":[{\"label\":\"Language Institutes\",\"ar_label\":\"\\u0645\\u0639\\u0627\\u0647\\u062f \\u0627\\u0644\\u0644\\u063a\\u0629\",\"url\":\"\\/language-institutes\"},{\"label\":\"Summer Programs\",\"ar_label\":\"\\u0628\\u0631\\u0627\\u0645\\u062c \\u0627\\u0644\\u0635\\u064a\\u0641\",\"url\":\"\\/summer-programs\"},{\"label\":\"Distance Learning\",\"ar_label\":\"\\u0627\\u0644\\u062a\\u0639\\u0644\\u0645 \\u0639\\u0646 \\u0628\\u0639\\u062f\",\"url\":\"\\/online-courses\"},{\"label\":\"Travel & Tourism\",\"ar_label\":\"\\u0627\\u0644\\u0633\\u0641\\u0631 \\u0648\\u0627\\u0644\\u0633\\u064a\\u0627\\u062d\\u0629\",\"url\":\"\\/travel-and-tourism\"}]},{\"title\":\"Company\",\"items\":[{\"label\":\"About Us\",\"ar_label\":\"\\u0645\\u0646 \\u0646\\u062d\\u0646\",\"url\":\"\\/about-us\"},{\"label\":\"Careers\",\"ar_label\":\"\\u0648\\u0638\\u0627\\u0626\\u0641\",\"url\":\"\\/careers\"},{\"label\":\"Blog\",\"ar_label\":\"\\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629\",\"url\":\"\\/articles\"},{\"label\":\"Contact\",\"ar_label\":\"\\u0627\\u062a\\u0635\\u0644\",\"url\":\"\\/contact-us\"}]}],\"description\":\"CourseEnglish helps learners find the right English programs, compare options, and book with confidence.\",\"social\":[{\"platform\":\"linkedin\",\"url\":\"#\"},{\"platform\":\"facebook\",\"url\":\"#\"},{\"platform\":\"instagram\",\"url\":\"#\"},{\"platform\":\"twitter\",\"url\":\"#\"}],\"copyright\":\"All rights reserved \\u00a9 2026\",\"brand\":\"CourseEnglish\"},\"mobile\":{\"promo\":{\"text\":\"Our exclusive offers guarantee the best prices and services. If you find a better price or service, we\\u2019ll match it.\",\"icon\":\"\\/assets\\/icons\\/offer.png\"},\"logo\":\"\\/logo.png\",\"nav\":[{\"label\":\"Language Institutes\",\"ar_label\":\"\\u0645\\u0639\\u0627\\u0647\\u062f \\u0627\\u0644\\u0644\\u063a\\u0629\",\"url\":\"\\/language-institutes\"},{\"label\":\"Summer Programs\",\"ar_label\":\"\\u0628\\u0631\\u0627\\u0645\\u062c \\u0627\\u0644\\u0635\\u064a\\u0641\",\"url\":\"\\/summer-programs\"},{\"label\":\"Online Courses\",\"ar_label\":\"\\u0627\\u0644\\u062f\\u0648\\u0631\\u0627\\u062a \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0629\",\"url\":\"\\/online-courses\"},{\"label\":\"University Admissions\",\"ar_label\":\"\\u0627\\u0644\\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u062c\\u0627\\u0645\\u0639\\u064a\",\"url\":\"\\/university-admissions\"},{\"label\":\"Travel & Tourism\",\"ar_label\":\"\\u0627\\u0644\\u0633\\u0641\\u0631 \\u0648\\u0627\\u0644\\u0633\\u064a\\u0627\\u062d\\u0629\",\"url\":\"\\/travel-and-tourism\"},{\"label\":\"Training & Professional Courses\",\"ar_label\":\"\\u0627\\u0644\\u062a\\u062f\\u0631\\u064a\\u0628 \\u0648\\u0627\\u0644\\u062f\\u0648\\u0631\\u0627\\u062a \\u0627\\u0644\\u0645\\u0647\\u0646\\u064a\\u0629\",\"url\":\"\\/training-and-professional-courses\"}],\"quick_links\":[{\"label\":\"Home\",\"icon\":\"home\",\"url\":\"\\/\"},{\"label\":\"Contact Us\",\"icon\":\"phone\",\"url\":\"\\/contact-us\"},{\"label\":\"FAQ\",\"icon\":\"question\",\"url\":\"\\/articles\"}],\"actions\":[{\"label\":\"My Account\",\"icon\":\"user\",\"url\":\"\\/student\\/dashboard\"},{\"label\":\"Compare\",\"icon\":\"compare\",\"url\":\"\\/compare\"},{\"label\":\"Wishlist\",\"icon\":\"heart\",\"url\":\"\\/wishlist\",\"badge\":4},{\"label\":\"Institutes\",\"icon\":\"building\",\"url\":\"\\/language-institutes\"},{\"label\":\"Home\",\"icon\":\"home\",\"url\":\"\\/\"}],\"drawer_links\":[{\"label\":\"Offers\",\"url\":\"\\/offers\"},{\"label\":\"About Us\",\"url\":\"\\/about-us\"},{\"label\":\"Team\",\"url\":\"\\/team\"},{\"label\":\"Blog\",\"url\":\"\\/articles\"},{\"label\":\"English Language Schools\",\"url\":\"\\/language-institutes\"},{\"label\":\"Summer Program\",\"url\":\"\\/summer-programs\"},{\"label\":\"University Admission\",\"url\":\"\\/university-admissions\"}],\"drawer_social\":[{\"platform\":\"linkedin\",\"url\":\"#\"},{\"platform\":\"facebook\",\"url\":\"#\"},{\"platform\":\"instagram\",\"url\":\"#\"},{\"platform\":\"twitter\",\"url\":\"#\"}],\"drawer_legal\":[{\"label\":\"Privacy Policy\",\"url\":\"\\/privacy\"},{\"label\":\"Terms & Conditions\",\"url\":\"\\/terms\"}]}}', '2026-02-08 15:06:45', '2026-02-12 14:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `subject_areas`
--

CREATE TABLE `subject_areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(80) NOT NULL,
  `name` varchar(120) NOT NULL,
  `ar_name` varchar(160) DEFAULT NULL,
  `slug` varchar(140) NOT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subject_areas`
--

INSERT INTO `subject_areas` (`id`, `key`, `name`, `ar_name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'business-management', 'Business & Management', 'إدارة الأعمال', 'business-management', 1, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(2, 'economics-finance', 'Economics & Finance', 'الاقتصاد والتمويل', 'economics-finance', 2, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(3, 'accounting-banking', 'Accounting & Banking', 'المحاسبة والمصارف', 'accounting-banking', 3, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(4, 'sustainability-environment', 'Sustainability & Environment', 'الاستدامة والبيئة', 'sustainability-environment', 4, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(5, 'education', 'Education', 'التربية والتعليم', 'education', 5, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(6, 'law', 'Law', 'القانون', 'law', 6, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(7, 'politics-international-relations', 'Politics & International Relations', 'العلوم السياسية والعلاقات الدولية', 'politics-international-relations', 7, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(8, 'journalism-media', 'Journalism & Media', 'الصحافة والإعلام', 'journalism-media', 8, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(9, 'sociology-social-sciences', 'Sociology & Social Sciences', 'علم الاجتماع والعلوم الاجتماعية', 'sociology-social-sciences', 9, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(10, 'design-applied-arts', 'Design & Applied Arts', 'التصميم والفنون التطبيقية', 'design-applied-arts', 10, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(11, 'architecture-planning', 'Architecture & Planning', 'العمارة والتخطيط', 'architecture-planning', 11, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(12, 'arts-humanities', 'Arts & Humanities', 'الآداب والعلوم الإنسانية', 'arts-humanities', 12, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(13, 'english-linguistics', 'English & Linguistics', 'اللغة الإنجليزية واللغويات', 'english-linguistics', 13, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(14, 'history-philosophy', 'History & Philosophy', 'التاريخ والفلسفة', 'history-philosophy', 14, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(15, 'engineering-general', 'Engineering (General)', 'الهندسة العامة', 'engineering-general', 15, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(16, 'civil-structural-engineering', 'Civil & Structural Engineering', 'الهندسة المدنية والإنشائية', 'civil-structural-engineering', 16, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(17, 'mechanical-manufacturing-engineering', 'Mechanical & Manufacturing Engineering', 'الهندسة الميكانيكية والتصنيع', 'mechanical-manufacturing-engineering', 17, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(18, 'electrical-electronic-engineering', 'Electrical & Electronic Engineering', 'الهندسة الكهربائية والإلكترونية', 'electrical-electronic-engineering', 18, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(19, 'chemical-process-engineering', 'Chemical & Process Engineering', 'الهندسة الكيميائية وهندسة العمليات', 'chemical-process-engineering', 19, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(20, 'energy-environmental-engineering', 'Energy & Environmental Engineering', 'هندسة الطاقة والبيئة', 'energy-environmental-engineering', 20, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(21, 'computer-software-engineering', 'Computer & Software Engineering', 'هندسة الحاسوب والبرمجيات', 'computer-software-engineering', 21, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(22, 'aerospace-aviation-engineering', 'Aerospace & Aviation Engineering', 'هندسة الطيران والفضاء', 'aerospace-aviation-engineering', 22, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(23, 'robotics-mechatronics', 'Robotics & Mechatronics', 'الروبوتات والميكاترونكس', 'robotics-mechatronics', 23, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(24, 'materials-industrial-engineering', 'Materials & Industrial Engineering', 'هندسة المواد والصناعة', 'materials-industrial-engineering', 24, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(25, 'biotechnology-biomedical-engineering', 'Biotechnology & Biomedical Engineering', 'الهندسة الطبية الحيوية والتقنيات الحيوية', 'biotechnology-biomedical-engineering', 25, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(26, 'agriculture-forestry', 'Agriculture & Forestry', 'الزراعة والغابات', 'agriculture-forestry', 26, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(27, 'environmental-sciences', 'Environmental Sciences', 'العلوم البيئية', 'environmental-sciences', 27, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(28, 'marine-nautical-sciences', 'Marine & Nautical Sciences', 'العلوم البحرية والملاحة', 'marine-nautical-sciences', 28, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(29, 'veterinary-sciences', 'Veterinary Sciences', 'العلوم البيطرية', 'veterinary-sciences', 29, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(30, 'pharmacy-pharmacology', 'Pharmacy & Pharmacology', 'الصيدلة وعلم الأدوية', 'pharmacy-pharmacology', 30, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(31, 'natural-sciences', 'Natural Sciences', 'العلوم الطبيعية', 'natural-sciences', 31, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(32, 'biology-life-sciences', 'Biology & Life Sciences', 'علوم الحياة والأحياء', 'biology-life-sciences', 32, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(33, 'chemistry', 'Chemistry', 'الكيمياء', 'chemistry', 33, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(34, 'physics-astronomy', 'Physics & Astronomy', 'الفيزياء والفلك', 'physics-astronomy', 34, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(35, 'mathematics-statistics', 'Mathematics & Statistics', 'الرياضيات والإحصاء', 'mathematics-statistics', 35, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(36, 'earth-geological-sciences', 'Earth & Geological Sciences', 'علوم الأرض والجيولوجيا', 'earth-geological-sciences', 36, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(37, 'computer-science-it', 'Computer Science & IT', 'علوم الحاسوب وتقنية المعلومات', 'computer-science-it', 37, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(38, 'data-science-ai', 'Data Science & AI', 'علوم البيانات والذكاء الاصطناعي', 'data-science-ai', 38, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(39, 'cyber-security-information-systems', 'Cyber Security & Information Systems', 'الأمن السيبراني ونظم المعلومات', 'cyber-security-information-systems', 39, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(40, 'sports-science-physical-education', 'Sports Science & Physical Education', 'علوم الرياضة والتربية البدنية', 'sports-science-physical-education', 40, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(41, 'hospitality-tourism-leisure', 'Hospitality, Tourism & Leisure', 'الضيافة والسياحة', 'hospitality-tourism-leisure', 41, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(42, 'health-sciences', 'Health Sciences', 'العلوم الصحية', 'health-sciences', 42, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(43, 'nursing-healthcare', 'Nursing & Healthcare', 'التمريض والرعاية الصحية', 'nursing-healthcare', 43, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(44, 'criminology-forensic-studies', 'Criminology & Forensic Studies', 'علم الجريمة والعلوم الجنائية', 'criminology-forensic-studies', 44, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(45, 'genetics-microbiology', 'Genetics & Microbiology', 'الوراثة والأحياء الدقيقة', 'genetics-microbiology', 45, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(46, 'nutrition-food-sciences', 'Nutrition & Food Sciences', 'علوم التغذية والأغذية', 'nutrition-food-sciences', 46, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(47, 'urban-regional-studies', 'Urban & Regional Studies', 'الدراسات الحضرية والإقليمية', 'urban-regional-studies', 47, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(48, 'transportation-logistics', 'Transportation & Logistics', 'النقل واللوجستيات', 'transportation-logistics', 48, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(49, 'religion-theology', 'Religion & Theology', 'الدين واللاهوت', 'religion-theology', 49, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(50, 'archaeology-heritage-studies', 'Archaeology & Heritage Studies', 'الآثار وإدارة التراث', 'archaeology-heritage-studies', 50, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(51, 'geography', 'Geography', 'الجغرافيا', 'geography', 51, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(52, 'museum-cultural-studies', 'Museum & Cultural Studies', 'دراسات المتاحف والثقافة', 'museum-cultural-studies', 52, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(53, 'occupational-health-safety', 'Occupational Health & Safety', 'الصحة والسلامة المهنية', 'occupational-health-safety', 53, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05'),
(54, 'science-technology-society', 'Science, Technology & Society', 'العلوم والتكنولوجيا والمجتمع', 'science-technology-society', 54, 1, '2026-02-10 14:36:05', '2026-02-10 14:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'public',
  `established_year` int(11) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `qs_ranking` int(11) DEFAULT NULL,
  `the_ranking` int(11) DEFAULT NULL,
  `shanghai_ranking` int(11) DEFAULT NULL,
  `famous_for` text DEFAULT NULL,
  `ar_famous_for` text DEFAULT NULL,
  `fees` text DEFAULT NULL,
  `ar_fees` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `name`, `ar_name`, `slug`, `logo`, `cover_image`, `country_id`, `city_id`, `type`, `established_year`, `website`, `qs_ranking`, `the_ranking`, `shanghai_ranking`, `famous_for`, `ar_famous_for`, `fees`, `ar_fees`, `is_featured`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Imperial College London', NULL, 'imperial-college-london', NULL, NULL, 1, 1, 'public', 1907, 'https://www.imperial.ac.uk', 2, 8, 26, 'Engineering, Sciences, Medicine, Technology', 'الهندسة، العلوم، الطب، التكنولوجيا', '£40,000 - £55,000', 'من 40,000 إلى 55,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(2, 'University of Oxford', NULL, 'university-of-oxford', NULL, NULL, 1, 21, 'public', 1907, 'https://www.ox.ac.uk', 4, 1, 6, 'Law, Medicine, Humanities', 'القانون، الطب، العلوم الإنسانية', '£35,000 - £60,000', 'من 35,000 إلى 60,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(3, 'University of Cambridge', NULL, 'university-of-cambridge', NULL, NULL, 1, 20, 'public', 1096, 'https://www.cam.ac.uk', 6, 3, 4, 'Sciences, Engineering, Mathematics', 'العلوم، الهندسة، الرياضيات', '£27,000 - £67,000', 'من 27,000 إلى 67,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(4, 'University College London', NULL, 'university-college-london', NULL, NULL, 1, 1, 'public', 1209, 'https://www.ucl.ac.uk', 9, 22, 14, 'Medicine, Law, Architecture, Social Sciences', 'الطب، القانون، العمارة، العلوم الاجتماعية', '£27,000 - £45,000', 'من 27,000 إلى 45,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(5, 'King\'s College London', NULL, 'kings-college-london', NULL, NULL, 1, 1, 'public', 1836, 'https://www.kcl.ac.uk', 31, 38, 61, 'Medicine, Law, International Relations', 'الطب، القانون، العلاقات الدولية', '£25,000 - £50,000', 'من 25,000 إلى 50,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(6, 'University of Edinburgh', NULL, 'university-of-edinburgh', NULL, NULL, 1, 7, 'public', 1829, 'https://www.ed.ac.uk', 34, 29, 37, 'Medicine, Law, Sciences, Literature', 'الطب، القانون، العلوم، الأدب', '£24,000 - £52,000', 'من 24,000 إلى 52,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(7, 'University of Manchester', NULL, 'university-of-manchester', NULL, NULL, 1, 2, 'public', 1583, 'https://www.manchester.ac.uk', 35, 56, 46, 'Engineering, Business, Computer Science', 'الهندسة، إدارة الأعمال، علوم الحاسوب', '£25,000 - £52,000', 'من 25,000 إلى 52,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(8, 'University of Bristol', NULL, 'university-of-bristol', NULL, NULL, 1, 8, 'public', 2004, 'https://www.bristol.ac.uk', 51, 80, 98, 'Engineering, Law, Economics, Medicine, Computer Science', 'الهندسة، القانون، الاقتصاد، الطب، علوم الحاسوب', '£25,000 - £45,000', 'من 25,000 إلى 45,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(9, 'London School of Economics and Political Science (LSE)', NULL, 'london-school-of-economics-and-political-science', NULL, NULL, 1, 1, 'public', 1909, 'https://www.lse.ac.uk', 56, 52, 151, 'Economics, Politics, International Relations, Finance, Social Sciences', 'الاقتصاد، السياسة، العلاقات الدولية، المالية، العلوم الاجتماعية', '£26,000 - £32,000', 'من 26,000 إلى 32,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(10, 'University of Warwick', NULL, 'university-of-warwick', NULL, NULL, 1, 13, 'public', 1895, 'https://www.warwick.ac.uk', 74, 122, 101, 'Business, Management, Economics, Mathematics, Engineering, Computer Science', 'إدارة الأعمال، الإدارة، الاقتصاد، الرياضيات، الهندسة، علوم الحاسوب', '£25,000 - £38,000', 'من 25,000 إلى 38,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(11, 'University of Birmingham', NULL, 'university-of-birmingham', NULL, NULL, 1, 3, 'public', 1965, 'https://www.birmingham.ac.uk', 76, 98, 151, 'Medicine, Engineering, Business, Law, Education', 'الطب، الهندسة، إدارة الأعمال، القانون، التعليم', '£22,000 - £38,000', 'من 22,000 إلى 38,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(12, 'University of Glasgow', NULL, 'university-of-glasgow', NULL, NULL, 1, 6, 'public', 1900, 'https://www.gla.ac.uk', 79, 84, 101, 'Medicine, Veterinary Medicine, Engineering, Life Sciences', 'الطب، الطب البيطري، الهندسة، علوم الحياة', '£23,000 - £45,000', 'من 23,000 إلى 45,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(13, 'University of Leeds', NULL, 'university-of-leeds', NULL, NULL, 1, 5, 'public', 1451, 'https://www.leeds.ac.uk', 86, 118, 151, 'Engineering, Business, Media, Dentistry, Environmental Sciences', 'الهندسة، إدارة الأعمال، الإعلام، طب الأسنان، العلوم البيئية', '£22,000 - £40,000', 'من 22,000 إلى 40,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(14, 'University of Southampton', NULL, 'university-of-southampton', NULL, NULL, 1, 17, 'public', 1904, 'https://www.southampton.ac.uk', 87, 129, 151, 'Engineering, Computer Science, Marine Science, Business', 'الهندسة، علوم الحاسوب، العلوم البحرية، إدارة الأعمال', '£23,000 - £42,000', 'من 23,000 إلى 42,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(15, 'University of Sheffield', NULL, 'university-of-sheffield', NULL, NULL, 1, 9, 'public', 1952, 'https://www.sheffield.ac.uk', 92, 108, 151, 'Engineering, Architecture, Journalism, Computer Science', 'الهندسة، العمارة، الصحافة، علوم الحاسوب', '£22,000 - £40,000', 'من 22,000 إلى 40,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:41', '2026-02-10 14:05:41', NULL),
(16, 'Durham University', NULL, 'durham-university', NULL, NULL, 1, 38, 'public', 1905, 'https://www.durham.ac.uk', 94, 175, 201, 'Law, Theology, Politics, Business, Humanities', 'القانون، اللاهوت، السياسة، إدارة الأعمال، العلوم الإنسانية', '£22,000 - £37,000', 'من 22,000 إلى 37,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(17, 'University of Nottingham', NULL, 'university-of-nottingham', NULL, NULL, 1, 11, 'public', 1832, 'https://www.nottingham.ac.uk', 97, 145, 101, 'Pharmacy, Medicine, Engineering, Business, Agriculture', 'الصيدلة، الطب، الهندسة، إدارة الأعمال، الزراعة', '£22,000 - £38,000', 'من 22,000 إلى 38,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(18, 'Queen Mary University of London', NULL, 'queen-mary-university-of-london', NULL, NULL, 1, 1, 'public', 1948, 'https://www.qmul.ac.uk', 110, 134, 201, 'Law, Medicine, Dentistry, Engineering', 'القانون، الطب، طب الأسنان، الهندسة', '£21,000 - £38,000', 'من 21,000 إلى 38,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(19, 'University of St Andrews', NULL, 'university-of-st-andrews', NULL, NULL, 1, 56, 'public', 1887, 'https://www.st-andrews.ac.uk', 113, 162, 301, 'International Relations, Philosophy, Economics, Physics', 'العلاقات الدولية، الفلسفة، الاقتصاد، الفيزياء', '£23,000 - £36,000', 'من 23,000 إلى 36,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(20, 'University of Bath', NULL, 'university-of-bath', NULL, NULL, 1, 22, 'public', 1413, 'https://www.bath.ac.uk', 132, 251, 401, 'Engineering, Architecture, Management, Computer Science', 'الهندسة، العمارة، الإدارة، علوم الحاسوب', '£23,000 - £39,000', 'من 23,000 إلى 39,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(21, 'Newcastle University', NULL, 'newcastle-university', NULL, NULL, 1, 10, 'public', 1966, 'https://www.ncl.ac.uk', 137, 144, 201, 'Medicine, Architecture, Engineering, Business', 'الطب، العمارة، الهندسة، إدارة الأعمال', '£21,000 - £37,000', 'من 21,000 إلى 37,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(22, 'University of Liverpool', NULL, 'university-of-liverpool', NULL, NULL, 1, 4, 'public', 1963, 'https://www.liverpool.ac.uk', 147, 143, 101, 'Medicine, Veterinary Science, Engineering, Life Sciences', 'الطب، العلوم البيطرية، الهندسة، علوم الحياة', '£21,000 - £38,000', 'من 21,000 إلى 38,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(23, 'University of Exeter', NULL, 'university-of-exeter', NULL, NULL, 1, 25, 'public', 1903, 'https://www.exeter.ac.uk', 155, 170, 151, 'Business, Economics, Environmental Science, Psychology', 'إدارة الأعمال، الاقتصاد، العلوم البيئية، علم النفس', '£22,000 - £35,000', 'من 22,000 إلى 35,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(24, 'Lancaster University', NULL, 'lancaster-university', NULL, NULL, 1, 46, 'public', 1955, 'https://www.lancaster.ac.uk', 157, 184, 301, 'Management, Data Science, Economics, Linguistics', 'الإدارة، علم البيانات، الاقتصاد، اللغويات', '£21,000 - £34,000', 'من 21,000 إلى 34,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(25, 'University of York', NULL, 'university-of-york', NULL, NULL, 1, 23, 'public', 1964, 'https://www.york.ac.uk', 169, 154, 201, 'Computer Science, History, Archaeology, Psychology', 'علوم الحاسوب، التاريخ، علم الآثار، علم النفس', '£21,000 - £36,000', 'من 21,000 إلى 36,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(26, 'Cardiff University', NULL, 'cardiff-university', NULL, NULL, 1, 15, 'public', 1963, 'https://www.cardiff.ac.uk', 181, 201, 201, 'Journalism, Medicine, Architecture, Engineering', 'الصحافة، الطب، العمارة، الهندسة', '£21,000 - £35,000', 'من 21,000 إلى 35,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(27, 'University of Reading', NULL, 'university-of-reading', NULL, NULL, 1, 27, 'public', 1883, 'https://www.reading.ac.uk', 194, 201, 401, 'Real Estate, Agriculture, Business, Economics', 'العقارات، الزراعة، إدارة الأعمال، الاقتصاد', '£20,000 - £32,000', 'من 20,000 إلى 32,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(28, 'Queen\'s University Belfast', NULL, 'queens-university-belfast', NULL, NULL, 1, 16, 'public', 1926, 'https://www.qub.ac.uk', 199, 198, 301, 'Engineering, Medicine, Pharmacy, Architecture', 'الهندسة، الطب، الصيدلة، العمارة', '£20,000 - £34,000', 'من 20,000 إلى 34,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(29, 'Loughborough University', NULL, 'loughborough-university', NULL, NULL, 1, 48, 'public', 1907, 'https://www.lboro.ac.uk', 225, 301, 801, 'Engineering, Sports Science, Design, Business', 'الهندسة، علوم الرياضة، التصميم، إدارة الأعمال', '£22,000 - £36,000', 'من 22,000 إلى 36,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(30, 'University of Strathclyde', NULL, 'university-of-strathclyde', NULL, NULL, 1, 6, 'public', 1796, 'https://www.strath.ac.uk', 251, 351, 701, 'Engineering, Business, Finance, Architecture', 'الهندسة، إدارة الأعمال، المالية، العمارة', '£20,000 - £33,000', 'من 20,000 إلى 33,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(31, 'University of Aberdeen', NULL, 'university-of-aberdeen', NULL, NULL, 1, 29, 'public', 1495, 'https://www.abdn.ac.uk', 262, 201, 201, 'Medicine, Law, Engineering, Energy Studies', 'الطب، القانون، الهندسة، دراسات الطاقة', '£20,000 - £36,000', 'من 20,000 إلى 36,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(32, 'University of Surrey', NULL, 'university-of-surrey', NULL, NULL, 1, 40, 'public', 1966, 'https://www.surrey.ac.uk', 262, 201, 401, 'Hospitality, Tourism, Engineering, Business, Computer Science', 'الضيافة، السياحة، الهندسة، إدارة الأعمال، علوم الحاسوب', '£21,000 - £35,000', 'من 21,000 إلى 35,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(33, 'University of Sussex', NULL, 'university-of-sussex', NULL, NULL, 1, 19, 'public', 1961, 'https://www.sussex.ac.uk', 278, 201, 201, 'Development Studies, International Relations, Politics, Psychology', 'دراسات التنمية، العلاقات الدولية، السياسة، علم النفس', '£20,000 - £34,000', 'من 20,000 إلى 34,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(34, 'Heriot-Watt University', NULL, 'heriot-watt-university', NULL, NULL, 1, 7, 'public', 1821, 'https://www.hw.ac.uk', 287, 401, 701, 'Engineering, Architecture, Business, Actuarial Science', 'الهندسة، العمارة، إدارة الأعمال، العلوم الاكتوارية', '£20,000 - £34,000', 'من 20,000 إلى 34,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(35, 'Swansea University', NULL, 'swansea-university', NULL, NULL, 1, 30, 'public', 1920, 'https://www.swansea.ac.uk', 292, 301, 701, 'Engineering, Law, Computer Science, Materials Science', 'الهندسة، القانون، علوم الحاسوب، علم المواد', '£19,000 - £32,000', 'من 19,000 إلى 32,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(36, 'City St Georges, University of London', NULL, 'city-st-georges-university-of-london', NULL, NULL, 1, 1, 'public', 1894, 'https://www.city.ac.uk', 310, 351, 701, 'Business, Law, Journalism, Health Sciences', 'إدارة الأعمال، القانون، الصحافة، العلوم الصحية', '£20,000 - £36,000', 'من 20,000 إلى 36,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(37, 'University of Leicester', NULL, 'university-of-leicester', NULL, NULL, 1, 12, 'public', 1921, 'https://www.le.ac.uk', 326, 192, 301, 'Medicine, Genetics, Criminology, Law, Data Science', 'الطب، علم الوراثة، علم الجريمة، القانون، علم البيانات', '£20,000 - £33,000', 'من 20,000 إلى 33,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(38, 'Oxford Brookes University', NULL, 'oxford-brookes-university', NULL, NULL, 1, 21, 'public', 1992, 'https://www.brookes.ac.uk', 374, 801, NULL, 'Architecture, Business, Engineering, Hospitality, Education', 'العمارة، إدارة الأعمال، الهندسة، الضيافة، التعليم', '£16,000 - £22,000', 'من 16,000 إلى 22,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(39, 'University of East Anglia', NULL, 'university-of-east-anglia', NULL, NULL, 1, 51, 'public', 1963, 'https://www.uea.ac.uk', 381, 251, 301, 'Environmental Science, Development Studies, Creative Writing, Economics, Biology', 'العلوم البيئية، دراسات التنمية، الكتابة الإبداعية، الاقتصاد، الأحياء', '£19,000 - £33,000', 'من 19,000 إلى 33,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(40, 'Brunel University London', NULL, 'brunel-university-london', NULL, NULL, 1, 58, 'public', 1966, 'https://www.brunel.ac.uk', 385, 401, 601, 'Engineering, Design, Business, Sports Science', 'الهندسة، التصميم، إدارة الأعمال، علوم الرياضة', '£19,000 - £32,000', 'من 19,000 إلى 32,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(41, 'Birkbeck, University of London', NULL, 'birkbeck-university-of-london', NULL, NULL, 1, 1, 'public', 1823, 'https://www.bbk.ac.uk', 388, 301, 801, 'Law, Psychology, Business, Social Sciences', 'القانون، علم النفس، إدارة الأعمال، العلوم الاجتماعية', '£15,000 - £20,000', 'من 15,000 إلى 20,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(42, 'Aston University', NULL, 'aston-university', NULL, NULL, 1, 3, 'public', 1895, 'https://www.aston.ac.uk', 395, 401, NULL, 'Business, Finance, Economics, Pharmacy, Engineering', 'إدارة الأعمال، المالية، الاقتصاد، الصيدلة، الهندسة', '£20,000 - £33,000', 'من 20,000 إلى 33,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(43, 'University of Kent', NULL, 'university-of-kent', NULL, NULL, 1, 34, 'public', 1965, 'https://www.kent.ac.uk', 397, 401, 401, 'Law, Politics, Psychology, Business, International Relations', 'القانون، السياسة، علم النفس، إدارة الأعمال، العلاقات الدولية', '£18,000 - £32,000', 'من 18,000 إلى 32,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(44, 'University of Dundee', NULL, 'university-of-dundee', NULL, NULL, 1, 37, 'public', 1881, 'https://www.dundee.ac.uk', 428, 301, 301, 'Medicine, Dentistry, Law, Life Sciences, Architecture', 'الطب، طب الأسنان، القانون، علوم الحياة، العمارة', '£20,000 - £34,000', 'من 20,000 إلى 34,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(45, 'University of Essex', NULL, 'university-of-essex', NULL, NULL, 1, 35, 'public', 1964, 'https://www.essex.ac.uk', 456, 301, 401, 'Politics, Sociology, Economics, Law, Data Analytics', 'السياسة، علم الاجتماع، الاقتصاد، القانون، تحليل البيانات', '£18,000 - £31,000', 'من 18,000 إلى 31,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(46, 'Royal Holloway, University of London', NULL, 'royal-holloway-university-of-london', NULL, NULL, 1, 39, 'public', 1879, 'https://www.rhul.ac.uk', 461, 401, 801, 'International Relations, Computer Science, Business, Media Arts', 'العلاقات الدولية، علوم الحاسوب، إدارة الأعمال، الفنون الإعلامية', '£19,000 - £33,000', 'من 19,000 إلى 33,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(47, 'SOAS University of London', NULL, 'soas-university-of-london', NULL, NULL, 1, 1, 'public', 1916, 'https://www.soas.ac.uk', 511, 401, NULL, 'International Relations, Politics, Economics, Asian Studies, African Studies', 'العلاقات الدولية، السياسة، الاقتصاد، الدراسات الآسيوية، الدراسات الإفريقية', '£19,000 - £28,000', 'من 19,000 إلى 28,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(48, 'University of Bradford', NULL, 'university-of-bradford', NULL, NULL, 1, 14, 'public', 1966, 'https://www.bradford.ac.uk', 511, 501, NULL, 'Management, Peace Studies, Engineering, Health Sciences', 'الإدارة، دراسات السلام، الهندسة، العلوم الصحية', '£15,000 - £22,000', 'من 15,000 إلى 22,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(49, 'University of Stirling', NULL, 'university-of-stirling', NULL, NULL, 1, 57, 'public', 1967, 'https://www.stir.ac.uk', 517, 501, 701, 'Sports Science, Education, Aquaculture, Business, Psychology', 'علوم الرياضة، التعليم، الاستزراع المائي، إدارة الأعمال، علم النفس', '£18,000 - £30,000', 'من 18,000 إلى 30,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(50, 'University of Huddersfield', NULL, 'university-of-huddersfield', NULL, NULL, 1, 42, 'public', 1825, 'https://www.hud.ac.uk', 524, 501, NULL, 'Engineering, Music, Computing, Business', 'الهندسة، الموسيقى، الحوسبة، إدارة الأعمال', '£16,000 - £25,000', 'من 16,000 إلى 25,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(51, 'University of Hull', NULL, 'university-of-hull', NULL, NULL, 1, 43, 'public', 1927, 'https://www.hull.ac.uk', 526, 501, 801, 'Logistics, Engineering, Environmental Science, Business', 'الخدمات اللوجستية، الهندسة، العلوم البيئية، إدارة الأعمال', '£16,000 - £26,000', 'من 16,000 إلى 26,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(52, 'Northumbria University', NULL, 'northumbria-university', NULL, NULL, 1, 10, 'public', 1992, 'https://www.northumbria.ac.uk', 550, 401, 701, 'Design, Architecture, Business, Law', 'التصميم، العمارة، إدارة الأعمال، القانون', '£17,000 - £28,000', 'من 17,000 إلى 28,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(53, 'Coventry University', NULL, 'coventry-university', NULL, NULL, 1, 13, 'public', 1992, 'https://www.coventry.ac.uk', 558, 601, NULL, 'Automotive Engineering, Business, Design, Health Sciences', 'هندسة السيارات، إدارة الأعمال، التصميم، العلوم الصحية', '£16,000 - £27,000', 'من 16,000 إلى 27,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(54, 'Bangor University', NULL, 'bangor-university', NULL, NULL, 1, 32, 'public', 1884, 'https://www.bangor.ac.uk', 566, 501, 501, 'Psychology, Ocean Sciences, Environmental Science, Linguistics', 'علم النفس، علوم المحيطات، العلوم البيئية، اللغويات', '£16,000 - £26,000', 'من 16,000 إلى 26,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(55, 'Nottingham Trent University', NULL, 'nottingham-trent-university', NULL, NULL, 1, 11, 'public', 1992, 'https://www.ntu.ac.uk', 609, 601, 801, 'Fashion, Design, Business, Marketing', 'الأزياء، التصميم، إدارة الأعمال، التسويق', '£16,000 - £28,000', 'من 16,000 إلى 28,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(56, 'Ulster University', NULL, 'ulster-university', NULL, NULL, 1, 16, 'public', 1984, 'https://www.ulster.ac.uk', 609, 601, 801, 'Engineering, Nursing, Computing, Business', 'الهندسة، التمريض، الحوسبة، إدارة الأعمال', '£15,000 - £26,000', 'من 15,000 إلى 26,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(57, 'University of Plymouth', NULL, 'university-of-plymouth', NULL, NULL, 1, 26, 'public', 1862, 'https://www.plymouth.ac.uk', 613, 501, 701, 'Marine Science, Environmental Science, Nursing, Education', 'العلوم البحرية، العلوم البيئية، التمريض، التعليم', '£16,000 - £27,000', 'من 16,000 إلى 27,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(58, 'University of Portsmouth', NULL, 'university-of-portsmouth', NULL, NULL, 1, 18, 'public', 1870, 'https://www.port.ac.uk', 635, 401, 901, 'Engineering, Cyber Security, Business, Law', 'الهندسة، الأمن السيبراني، إدارة الأعمال، القانون', '£17,000 - £28,000', 'من 17,000 إلى 28,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(59, 'Manchester Metropolitan University', NULL, 'manchester-metropolitan-university', NULL, NULL, 1, 2, 'public', 1970, 'https://www.mmu.ac.uk', 643, 601, 601, 'Art, Design, Business, Education, Sports Science', 'الفنون، التصميم، إدارة الأعمال، التعليم، علوم الرياضة', '£17,000 - £28,000', 'من 17,000 إلى 28,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(60, 'Kingston University', NULL, 'kingston-university', NULL, NULL, 1, 45, 'public', 1899, 'https://www.kingston.ac.uk', 660, 801, NULL, 'Design, Fashion, Architecture, Business', 'التصميم، الأزياء، العمارة، إدارة الأعمال', '£16,000 - £26,000', 'من 16,000 إلى 26,000 جنيه إسترليني سنويًا', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(61, 'Goldsmiths, University of London', NULL, 'goldsmiths-university-of-london', NULL, NULL, 1, 1, 'public', 1891, 'https://www.gold.ac.uk', 711, 501, NULL, 'Media, Arts, Sociology, Education', 'الإعلام، الفنون، علم الاجتماع، التربية', '18000 GBP - 23000 GBP', '18000 جنيه إسترليني - 23000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(62, 'University of the West of England, Bristol', NULL, 'university-of-the-west-of-england-bristol', NULL, NULL, 1, 8, 'public', 1992, 'https://www.uwe.ac.uk', 721, 601, NULL, 'Engineering, Architecture, Business, Media', 'الهندسة، العمارة، الأعمال، الإعلام', '15000 GBP - 19000 GBP', '15000 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(63, 'Aberystwyth University', NULL, 'aberystwyth-university', NULL, NULL, 1, 31, 'public', 1872, 'https://www.aber.ac.uk', 741, 601, NULL, 'International Relations, Agriculture, Geography, History', 'العلاقات الدولية، الزراعة، الجغرافيا، التاريخ', '15000 GBP - 19000 GBP', '15000 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(64, 'Bournemouth University', NULL, 'bournemouth-university', NULL, NULL, 1, 24, 'public', 1992, 'https://www.bournemouth.ac.uk', 801, 401, NULL, 'Media, Film, Animation, Tourism, Marketing', 'الإعلام، السينما، الرسوم المتحركة، السياحة، التسويق', '16000 GBP - 20000 GBP', '16000 جنيه إسترليني - 20000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(65, 'De Montfort University', NULL, 'de-montfort-university', NULL, NULL, 1, 12, 'public', 1992, 'https://www.dmu.ac.uk', 801, 601, NULL, 'Fashion, Design, Business, Computing', 'الأزياء، التصميم، الأعمال، الحوسبة', '15000 GBP - 18000 GBP', '15000 جنيه إسترليني - 18000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(66, 'Keele University', NULL, 'keele-university', NULL, NULL, 1, 44, 'public', 1962, 'https://www.keele.ac.uk', 801, 501, NULL, 'Medicine, Psychology, International Relations, Biology', 'الطب، علم النفس، العلاقات الدولية، الأحياء', '17000 GBP - 22000 GBP', '17000 جنيه إسترليني - 22000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(67, 'Middlesex University London', NULL, 'middlesex-university-london', NULL, NULL, 1, 1, 'public', 1992, 'https://www.mdx.ac.uk', 801, 501, NULL, 'Business, Law, Computing, Psychology', 'الأعمال، القانون، الحوسبة، علم النفس', '15000 GBP - 19000 GBP', '15000 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(68, 'University of Brighton', NULL, 'university-of-brighton', NULL, NULL, 1, 19, 'public', 1992, 'https://www.brighton.ac.uk', 801, 601, NULL, 'Art, Design, Architecture, Business, Health', 'الفن، التصميم، العمارة، الأعمال، الصحة', '15000 GBP - 19000 GBP', '15000 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(69, 'University of Greenwich', NULL, 'university-of-greenwich', NULL, NULL, 1, 1, 'public', 1890, 'https://www.gre.ac.uk', 801, 601, 701, 'Engineering, Architecture, Business, Maritime Studies', 'الهندسة، العمارة، الأعمال، الدراسات البحرية', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(70, 'University of Lincoln', NULL, 'university-of-lincoln', NULL, NULL, 1, 47, 'public', 1996, 'https://www.lincoln.ac.uk', 801, 601, NULL, 'Agriculture, Business, Journalism, Engineering', 'الزراعة، الأعمال، الصحافة، الهندسة', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(71, 'University of Westminster', NULL, 'university-of-westminster', NULL, NULL, 1, 1, 'public', 1838, 'https://www.westminster.ac.uk', 801, 801, NULL, 'Media, Film, Fashion, Business', 'الإعلام، السينما، الأزياء، الأعمال', '16000 GBP - 21000 GBP', '16000 جنيه إسترليني - 21000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(72, 'Edinburgh Napier University', NULL, 'edinburgh-napier-university', NULL, NULL, 1, 7, 'public', 1992, 'https://www.napier.ac.uk', 851, 601, NULL, 'Engineering, Computing, Business, Creative Industries', 'الهندسة، الحوسبة، الأعمال، الصناعات الإبداعية', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(73, 'Liverpool John Moores University', NULL, 'liverpool-john-moores-university', NULL, NULL, 1, 4, 'public', 1992, 'https://www.ljmu.ac.uk', 851, 501, 601, 'Engineering, Sports Science, Maritime Studies, Business', 'الهندسة، علوم الرياضة، الدراسات البحرية، الأعمال', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(74, 'London South Bank University', NULL, 'london-south-bank-university', NULL, NULL, 1, 1, 'public', 1992, 'https://www.lsbu.ac.uk', 901, 601, NULL, 'Engineering, Construction, Business, Health Sciences', 'الهندسة، التشييد، الأعمال، العلوم الصحية', '15500 GBP - 19500 GBP', '15500 جنيه إسترليني - 19500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(75, 'University of Hertfordshire', NULL, 'university-of-hertfordshire', NULL, NULL, 1, 41, 'public', 1992, 'https://www.herts.ac.uk', 901, 601, 801, 'Engineering, Computer Science, Business, Animation', 'الهندسة، علوم الحاسوب، الأعمال، الرسوم المتحركة', '15500 GBP - 20000 GBP', '15500 جنيه إسترليني - 20000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(76, 'University of Salford', NULL, 'university-of-salford', NULL, NULL, 1, 55, 'public', 1967, 'https://www.salford.ac.uk', 901, 801, NULL, 'Media, Construction, Business, Health', 'الإعلام، البناء، الأعمال، الصحة', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(77, 'Robert Gordon University (RGU)', NULL, 'robert-gordon-university', NULL, NULL, 1, 29, 'public', 1992, 'https://www.rgu.ac.uk', 951, 801, NULL, 'Engineering, Business, Architecture, Health Sciences', 'الهندسة، الأعمال، العمارة، العلوم الصحية', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(78, 'Birmingham City University', NULL, 'birmingham-city-university', NULL, NULL, 1, 3, 'public', 1971, 'https://www.bcu.ac.uk', 1001, 801, NULL, 'Art & Design, Media, Business, Engineering', 'الفنون والتصميم، الإعلام، الأعمال، الهندسة', '15500 GBP - 19500 GBP', '15500 جنيه إسترليني - 19500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(79, 'Glasgow Caledonian University', NULL, 'glasgow-caledonian-university', NULL, NULL, 1, 6, 'public', 1993, 'https://www.gcu.ac.uk', 1001, 801, NULL, 'Business, Nursing, Engineering, Public Health', 'الأعمال، التمريض، الهندسة، الصحة العامة', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(80, 'Leeds Beckett University', NULL, 'leeds-beckett-university', NULL, NULL, 1, 5, 'public', 1992, 'https://www.leedsbeckett.ac.uk', 1001, 801, NULL, 'Sports Science, Business, Tourism, Media', 'علوم الرياضة، الأعمال، السياحة، الإعلام', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(81, 'London Metropolitan University', NULL, 'london-metropolitan-university', NULL, NULL, 1, 1, 'public', 2002, 'https://www.londonmet.ac.uk', 1001, 601, NULL, 'Business, Law, Media, Social Sciences', 'الأعمال، القانون، الإعلام، العلوم الاجتماعية', '15500 GBP - 20000 GBP', '15500 جنيه إسترليني - 20000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(82, 'Sheffield Hallam University', NULL, 'sheffield-hallam-university', NULL, NULL, 1, 9, 'public', 1843, 'https://www.shu.ac.uk', 1001, 801, NULL, 'Engineering, Business, Architecture, Health', 'الهندسة، الأعمال، العمارة، الصحة', '15500 GBP - 19500 GBP', '15500 جنيه إسترليني - 19500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(83, 'University of East London', NULL, 'university-of-east-london', NULL, NULL, 1, 1, 'public', 1992, 'https://www.uel.ac.uk', 1001, 1001, NULL, 'Sports Science, Psychology, Business, Public Health', 'علوم الرياضة، علم النفس، الأعمال، الصحة العامة', '15000 GBP - 19000 GBP', '15000 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(84, 'University of Lancashire', NULL, 'university-of-lancashire', NULL, NULL, 1, 54, 'public', 1828, 'https://www.uclan.ac.uk', 1001, 1001, 901, 'Forensic Science, Sports Science, Business, Engineering', 'العلوم الجنائية، علوم الرياضة، الأعمال، الهندسة', '15500 GBP - 19000 GBP', '15500 جنيه إسترليني - 19000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(85, 'University of Wolverhampton', NULL, 'university-of-wolverhampton', NULL, NULL, 1, 59, 'public', 1827, 'https://www.wlv.ac.uk', 1001, 801, NULL, 'Engineering, Business, Computing, Education', 'الهندسة، الأعمال، الحوسبة، التربية', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(86, 'Canterbury Christ Church University', NULL, 'canterbury-christ-church-university', NULL, NULL, 1, 34, 'public', 1962, 'https://www.canterbury.ac.uk', 1201, 1201, NULL, 'Education, Nursing, Psychology, Theology', 'التربية، التمريض، علم النفس، اللاهوت', '14000 GBP - 17500 GBP', '14000 جنيه إسترليني - 17500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(87, 'Queen Margaret University', NULL, 'queen-margaret-university', NULL, NULL, 1, 7, 'public', 1998, 'https://www.qmu.ac.uk', 1201, NULL, NULL, 'Health Sciences, Nursing, Drama, Business', 'العلوم الصحية، التمريض، الدراما، الأعمال', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(88, 'University of Derby', NULL, 'university-of-derby', NULL, NULL, 1, 36, 'public', 1992, 'https://www.derby.ac.uk', 1201, 601, NULL, 'Engineering, Education, Business, Health', 'الهندسة، التربية، الأعمال، الصحة', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(89, 'University of Northampton', NULL, 'university-of-northampton', NULL, NULL, 1, 52, 'public', 2005, 'https://www.northampton.ac.uk', 1201, 1501, NULL, 'Business, Education, Computing, Fashion', 'الأعمال، التربية، الحوسبة، الأزياء', '15000 GBP - 18000 GBP', '15000 جنيه إسترليني - 18000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(90, 'Harper Adams University', NULL, 'harper-adams-university', NULL, NULL, 1, 50, 'public', 1901, 'https://www.harper-adams.ac.uk', 1401, NULL, NULL, 'Agriculture, Agribusiness, Food Technology', 'الزراعة، الأعمال الزراعية، تقنيات الأغذية', '16000 GBP - 20000 GBP', '16000 جنيه إسترليني - 20000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(91, 'Anglia Ruskin University', NULL, 'anglia-ruskin-university', NULL, NULL, 1, 20, 'public', 1992, 'https://www.anglia.ac.uk', 1001, 601, NULL, 'Business, Nursing, Engineering, Psychology', 'الأعمال، التمريض، الهندسة، علم النفس', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(92, 'Cardiff Metropolitan University', NULL, 'cardiff-metropolitan-university', NULL, NULL, 1, 15, 'public', 1992, 'https://www.cardiffmet.ac.uk', 1001, 1001, NULL, 'Sports Science, Business, Education, Health', 'علوم الرياضة، الأعمال، التربية، الصحة', '14500 GBP - 18000 GBP', '14500 جنيه إسترليني - 18000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(93, 'Cranfield University', NULL, 'cranfield-university', NULL, NULL, 1, 33, 'public', 1946, 'https://www.cranfield.ac.uk', NULL, NULL, 801, 'Engineering, Aerospace, Manufacturing, Management', 'الهندسة، الطيران والفضاء، التصنيع، الإدارة', '20000 GBP - 35000 GBP', '20000 جنيه إسترليني - 35000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(94, 'Edge Hill University', NULL, 'edge-hill-university', NULL, NULL, 1, 53, 'public', 1885, 'https://www.edgehill.ac.uk', NULL, 1001, NULL, 'Education, Nursing, Psychology, Sports Science', 'التربية، التمريض، علم النفس، علوم الرياضة', '14000 GBP - 17500 GBP', '14000 جنيه إسترليني - 17500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(95, 'Liverpool Hope University', NULL, 'liverpool-hope-university', NULL, NULL, 1, 4, 'public', 2005, 'https://www.hope.ac.uk', NULL, 1501, NULL, 'Education, Psychology, Humanities, Social Sciences', 'التربية، علم النفس، العلوم الإنسانية، العلوم الاجتماعية', '13000 GBP - 16000 GBP', '13000 جنيه إسترليني - 16000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(96, 'London School of Hygiene & Tropical Medicine', NULL, 'london-school-of-hygiene-and-tropical-medicine', NULL, NULL, 1, 1, 'public', 1899, 'https://www.lshtm.ac.uk', NULL, NULL, 201, 'Public Health, Epidemiology, Global Health', 'الصحة العامة، علم الوبائيات، الصحة العالمية', '25000 GBP - 35000 GBP', '25000 جنيه إسترليني - 35000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(97, 'Royal Veterinary College', NULL, 'royal-veterinary-college', NULL, NULL, 1, 1, 'public', 1791, 'https://www.rvc.ac.uk', NULL, 501, 801, 'Veterinary Medicine, Animal Science', 'الطب البيطري، علوم الحيوان', '27000 GBP - 40000 GBP', '27000 جنيه إسترليني - 40000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(98, 'Scotlands Rural College (SRUC)', NULL, 'scotlands-rural-college-sruc', NULL, NULL, 1, 7, 'public', 1899, 'https://www.sruc.ac.uk', NULL, 601, NULL, 'Agriculture, Animal Science, Environmental Science', 'الزراعة، علوم الحيوان، علوم البيئة', '14000 GBP - 18000 GBP', '14000 جنيه إسترليني - 18000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(99, 'Teesside University', NULL, 'teesside-university', NULL, NULL, 1, 49, 'public', 1930, 'https://www.tees.ac.uk', NULL, 601, NULL, 'Computing, Digital, Business, Engineering', 'الحوسبة، المجالات الرقمية، الأعمال، الهندسة', '15000 GBP - 18500 GBP', '15000 جنيه إسترليني - 18500 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL),
(100, 'The Open University', NULL, 'the-open-university', NULL, NULL, 1, 28, 'public', 1969, 'https://www.open.ac.uk', NULL, 801, 901, 'Distance Learning, Flexible Study, Open Education', 'التعليم عن بُعد، الدراسة المرنة، التعليم المفتوح', '7000 GBP - 11000 GBP', '7000 جنيه إسترليني - 11000 جنيه إسترليني', 0, 1, '2026-02-10 14:05:42', '2026-02-10 14:05:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `university_accommodation_rooms`
--

CREATE TABLE `university_accommodation_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `ar_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ar_description` text DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `image` varchar(255) DEFAULT NULL,
  `details` longtext DEFAULT NULL,
  `ar_details` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_campuses`
--

CREATE TABLE `university_campuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `ar_name` varchar(200) DEFAULT NULL,
  `slug` varchar(180) NOT NULL,
  `address` text DEFAULT NULL,
  `ar_address` text DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_courses`
--

CREATE TABLE `university_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_id` bigint(20) UNSIGNED NOT NULL,
  `course_catalog_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `duration_value` smallint(5) UNSIGNED DEFAULT NULL,
  `duration_unit` varchar(20) DEFAULT NULL,
  `first_year_fee` decimal(12,2) DEFAULT NULL,
  `currency` char(3) NOT NULL DEFAULT 'USD',
  `overview` longtext DEFAULT NULL,
  `ar_overview` longtext DEFAULT NULL,
  `awarding_body` varchar(255) DEFAULT NULL,
  `ar_awarding_body` varchar(255) DEFAULT NULL,
  `degree_requirement` longtext DEFAULT NULL,
  `language_requirement` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_course_catalogs`
--

CREATE TABLE `university_course_catalogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(300) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `university_course_catalogs`
--

INSERT INTO `university_course_catalogs` (`id`, `subject_area_id`, `name`, `ar_name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Business & Management', 'الأعمال والإدارة', 'business-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(2, 1, 'Risk management', 'إدارة المخاطر', 'risk-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(3, 15, 'Engineering management', 'الإدارة الهندسية', 'engineering-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(4, 3, 'Banking', 'الأعمال المصرفية', 'banking', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(5, 4, 'Sustainability Management', 'إدارة الاستدامة', 'sustainability-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(6, 3, 'Accounting', 'المحاسبة', 'accounting', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(7, 2, 'Finance', 'المالية', 'finance', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(8, 2, 'Economics', 'الاقتصاد', 'economics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(9, 2, 'Macroeconomics', 'الاقتصاد الكلي', 'macroeconomics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(10, 1, 'Marketing', 'التسويق', 'marketing', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(11, 1, 'Master of Business Administration MBA', 'ماجستير إدارة الأعمال MBA', 'master-of-business-administration-mba', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(12, 4, 'Sustainability Studies', 'دراسات الاستدامة', 'sustainability-studies', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(13, 5, 'Education', 'التعليم', 'education', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(14, 6, 'Law', 'القانون', 'law', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(15, 7, 'Politics /Political science', 'السياسة / العلوم السياسية', 'politics-political-science', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(16, 8, 'Journalism', 'الصحافة', 'journalism', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(17, 9, 'Sociology', 'علم الاجتماع', 'sociology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(18, 10, 'Design and Applied Arts', 'التصميم والفنون التطبيقية', 'design-and-applied-arts', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(19, 11, 'Architecture', 'العماره', 'architecture', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(20, 10, 'Interior Design', 'التصميم الداخلي', 'interior-design', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(21, 12, 'Art & Humanities', 'الفنون والعلوم الإنسانية', 'art-humanities', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(22, 13, 'English Language & Literature / Linguistics', 'اللغة الإنجليزية وآدابها / و اللغويات', 'english-language-literature-linguistics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(23, 14, 'History', 'التاريخ', 'history', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(24, 14, 'Philosophy', 'الفلسفة', 'philosophy', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(25, 15, 'Engineering', 'الهندسة', 'engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(26, 15, 'General Engineering', 'الهندسة العامة', 'general-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(27, 16, 'Civil & Structural Engineering', 'الهندسة المدنية والإنشائية', 'civil-structural-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(28, 19, 'Chemical Engineering', 'الهندسة الكيميائية', 'chemical-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(29, 17, 'Mechanical Engineering', 'الهندسة الميكانيكية', 'mechanical-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(30, 18, 'Electrical Engineering', 'الهندسة الكهربائية', 'electrical-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(31, 18, 'Electronics Engineering', 'هندسة الإلكترونيات', 'electronics-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(32, 20, 'Energy engineering', 'هندسة الطاقة', 'energy-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(33, 18, 'Control, automation, and instrumentation systems engineering', 'هندسة أنظمة التحكم والأتمتة والقياس', 'control-automation-and-instrumentation-systems-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(34, 21, 'Computer & Software Engineering', 'هندسة الحاسوب والبرمجيات', 'computer-software-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(35, 22, 'Aerospace & Aviation Systems and Engineering', 'هندسة الطيران وأنظمة الطيران', 'aerospace-aviation-systems-and-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(36, 23, 'Mechatronics & Robotics engineering', 'هندسة الميكاترونكس / والروبوتات', 'mechatronics-robotics-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(37, 24, 'Material Engineering & Science', 'هندسة المواد / وعلم المواد', 'material-engineering-science', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(38, 24, 'Industrial & Production Engineering', 'الهندسة الصناعية والإنتاج', 'industrial-production-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(39, 25, 'Biotechnology', 'التكنولوجيا الحيوية', 'biotechnology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(40, 20, 'Environmental Engineering', 'الهندسة البيئية', 'environmental-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(41, 11, 'Architectural Engineering', 'الهندسة المعمارية', 'architectural-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(42, 25, 'Biomedical engineering', 'الهندسة الطبية الحيوية', 'biomedical-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(43, 24, 'Mining and Mineral engineering', 'هندسة التعدين والمعادن', 'mining-and-mineral-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(44, 28, 'Marine engineering', 'الهندسة البحرية', 'marine-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(45, 16, 'Surveying Engineering', 'هندسة المساحة', 'surveying-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(46, 47, 'Urban & Regional Planning', 'التخطيط الحضري والإقليمي', 'urban-regional-planning', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(47, 24, 'Manufacturing', 'التصنيع', 'manufacturing', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(48, 16, 'Construction / Construction management and engineering', 'البناء / إدارة وهندسة التشييد', 'construction-construction-management-and-engineering', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(49, 26, 'Agriculture & Forestry', 'الزراعة / والغابات', 'agriculture-forestry', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(50, 27, 'Environmental Sciences', 'العلوم البيئية', 'environmental-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(51, 28, 'Fisheries', 'الأسماك', 'fisheries', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(52, 29, 'Veterinary', 'الطب البيطري', 'veterinary', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(53, 30, 'Pharmacy & Pharmacology', 'الصيدلة وعلم الأدوية', 'pharmacy-pharmacology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(54, 31, 'Sciences & Natural sciences', 'العلوم والعلوم الطبيعية', 'sciences-natural-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(55, 32, 'Biology', 'الأحياء', 'biology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(56, 33, 'Chemistry', 'الكيمياء', 'chemistry', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(57, 34, 'Physics', 'الفيزياء', 'physics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(58, 35, 'Mathematics & Statistics', 'الرياضيات والإحصاء', 'mathematics-statistics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(59, 36, 'Earth Sciences & Geology', 'الجيولوجيا / و علوم الأرض', 'earth-sciences-geology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(60, 34, 'Astronomy', 'علم الفلك', 'astronomy', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(61, 34, 'Nanoscience and Nanotechnology', 'علم وتقنية النانو', 'nanoscience-and-nanotechnology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(62, 37, 'Computer science', 'علوم الحاسب', 'computer-science', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(63, 38, 'Data sciences', 'علوم البيانات', 'data-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(64, 37, 'Information and Communication Technologies (ICTs)', 'تكنولوجيا المعلومات والاتصالات', 'information-and-communication-technologies-icts', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(65, 39, 'Information security', 'أمن المعلومات', 'information-security', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(66, 39, 'Information systems', 'نظم المعلومات', 'information-systems', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(67, 37, 'Information technology', 'تكنولوجيا المعلومات', 'information-technology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(68, 1, 'Services', 'الخدمات', 'services', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(69, 40, 'Sports-Related Subjects', 'الرياضة وتخصصاتها', 'sports-related-subjects', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(70, 38, 'Artificial Intelligence & Machine Learning', 'الذكاء الاصطناعي وتعلم الآلة', 'artificial-intelligence-machine-learning', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(71, 41, 'Hospitality / Leisure', 'الضيافة / الترفيه', 'hospitality-leisure', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(72, 41, 'Tour guide / Tour Management', ' الإرشاد السياحي / إدارة السياحة', 'tour-guide-tour-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(73, 50, 'Heritage resource management', 'إدارة موارد التراث', 'heritage-resource-management', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(74, 28, 'Nautical sciences', 'العلوم البحرية', 'nautical-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(75, 53, 'Occupational health and safety', 'الصحة والسلامة المهنية', 'occupational-health-and-safety', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(76, 42, 'Life Sciences and Medicine', 'علوم الحياة والعلوم الطبية', 'life-sciences-and-medicine', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(77, 49, 'Religion', 'الديانات', 'religion', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(78, 50, 'Archaeology', 'علم الآثار', 'archaeology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(79, 51, 'Geography', 'الجغرافيا', 'geography', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(80, 52, 'Museum studies', 'دراسات المتاحف', 'museum-studies', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(81, 42, 'Health Sciences', 'العلوم الصحية', 'health-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(82, 43, 'Nursing', 'التمريض', 'nursing', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(83, 43, 'Healthcare', 'الرعاية الصحية', 'healthcare', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(84, 44, 'Criminology', 'علم الجريمة', 'criminology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(85, 45, 'Microbiology', 'علم الأحياء الدقيقة', 'microbiology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(86, 45, 'Genetics', 'علم الوراثة', 'genetics', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(87, 42, 'Toxicology', 'علم السموم', 'toxicology', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(88, 46, 'Nutrition Sciences', 'علوم التغذية', 'nutrition-sciences', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(89, 54, 'Science, Technology and Society', 'العلوم والتكنولوجيا والمجتمع', 'science-technology-and-society', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(90, 4, 'Natural Resources and Conservation', 'الموارد الطبيعية والحفظ', 'natural-resources-and-conservation', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(91, 47, 'Urban Studies / Affairs', 'الدراسات الحضرية / الشؤون الحضرية', 'urban-studies-affairs', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(92, 48, 'Transportation Studies', 'دراسات النقل', 'transportation-studies', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03'),
(93, 48, 'Air Transportation', 'النقل الجوي', 'air-transportation', 1, '2026-02-11 01:17:03', '2026-02-11 01:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `university_course_intakes`
--

CREATE TABLE `university_course_intakes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_course_id` bigint(20) UNSIGNED NOT NULL,
  `intake_term_id` bigint(20) UNSIGNED NOT NULL,
  `deadline_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_course_intake_term`
--

CREATE TABLE `university_course_intake_term` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_course_id` bigint(20) UNSIGNED NOT NULL,
  `intake_term_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_course_levels`
--

CREATE TABLE `university_course_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_catalog_id` bigint(20) UNSIGNED NOT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `university_course_levels`
--

INSERT INTO `university_course_levels` (`id`, `course_catalog_id`, `level_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(2, 1, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(3, 1, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(4, 1, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(5, 1, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(6, 1, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(7, 1, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(8, 1, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(9, 1, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(10, 1, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(11, 1, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(12, 1, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(13, 1, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(14, 1, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(15, 1, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(16, 1, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(17, 2, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(18, 2, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(19, 2, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(20, 2, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(21, 2, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(22, 2, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(23, 2, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(24, 2, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(25, 2, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(26, 2, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(27, 2, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(28, 2, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(29, 2, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(30, 2, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(31, 2, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(32, 2, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(33, 3, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(34, 3, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(35, 3, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(36, 3, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(37, 3, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(38, 3, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(39, 3, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(40, 3, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(41, 3, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(42, 3, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(43, 3, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(44, 3, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(45, 3, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(46, 3, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(47, 3, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(48, 3, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(49, 4, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(50, 4, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(51, 4, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(52, 4, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(53, 4, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(54, 4, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(55, 4, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(56, 4, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(57, 4, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(58, 4, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(59, 4, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(60, 4, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(61, 4, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(62, 4, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(63, 4, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(64, 4, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(65, 5, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(66, 5, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(67, 5, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(68, 5, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(69, 5, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(70, 5, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(71, 5, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(72, 5, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(73, 5, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(74, 5, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(75, 5, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(76, 5, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(77, 5, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(78, 5, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(79, 5, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(80, 5, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(81, 6, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(82, 6, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(83, 6, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(84, 6, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(85, 6, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(86, 6, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(87, 6, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(88, 6, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(89, 6, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(90, 6, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(91, 6, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(92, 6, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(93, 6, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(94, 6, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(95, 6, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(96, 6, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(97, 7, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(98, 7, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(99, 7, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(100, 7, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(101, 7, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(102, 7, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(103, 7, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(104, 7, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(105, 7, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(106, 7, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(107, 7, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(108, 7, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(109, 7, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(110, 7, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(111, 7, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(112, 7, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(113, 8, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(114, 8, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(115, 8, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(116, 8, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(117, 8, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(118, 8, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(119, 8, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(120, 8, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(121, 8, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(122, 8, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(123, 8, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(124, 8, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(125, 8, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(126, 8, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(127, 8, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(128, 8, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(129, 9, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(130, 9, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(131, 9, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(132, 9, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(133, 9, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(134, 9, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(135, 9, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(136, 9, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(137, 9, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(138, 9, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(139, 9, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(140, 9, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(141, 9, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(142, 9, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(143, 9, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(144, 9, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(145, 10, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(146, 10, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(147, 10, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(148, 10, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(149, 10, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(150, 10, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(151, 10, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(152, 10, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(153, 10, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(154, 10, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(155, 10, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(156, 10, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(157, 10, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(158, 10, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(159, 10, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(160, 10, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(161, 11, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(162, 11, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(163, 11, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(164, 11, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(165, 11, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(166, 11, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(167, 11, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(168, 11, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(169, 11, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(170, 11, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(171, 11, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(172, 11, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(173, 11, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(174, 11, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(175, 11, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(176, 11, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(177, 12, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(178, 12, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(179, 12, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(180, 12, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(181, 12, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(182, 12, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(183, 12, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(184, 12, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(185, 12, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(186, 12, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(187, 12, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(188, 12, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(189, 12, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(190, 12, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(191, 12, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(192, 12, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(193, 13, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(194, 13, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(195, 13, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(196, 13, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(197, 13, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(198, 13, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(199, 13, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(200, 13, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(201, 13, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(202, 13, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(203, 13, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(204, 13, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(205, 13, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(206, 13, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(207, 13, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(208, 13, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(209, 14, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(210, 14, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(211, 14, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(212, 14, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(213, 14, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(214, 14, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(215, 14, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(216, 14, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(217, 14, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(218, 14, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(219, 14, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(220, 14, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(221, 14, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(222, 14, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(223, 14, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(224, 14, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(225, 15, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(226, 15, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(227, 15, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(228, 15, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(229, 15, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(230, 15, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(231, 15, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(232, 15, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(233, 15, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(234, 15, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(235, 15, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(236, 15, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(237, 15, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(238, 15, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(239, 15, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(240, 15, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(241, 16, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(242, 16, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(243, 16, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(244, 16, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(245, 16, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(246, 16, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(247, 16, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(248, 16, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(249, 16, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(250, 16, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(251, 16, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(252, 16, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(253, 16, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(254, 16, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(255, 16, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(256, 16, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(257, 17, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(258, 17, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(259, 17, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(260, 17, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(261, 17, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(262, 17, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(263, 17, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(264, 17, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(265, 17, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(266, 17, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(267, 17, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(268, 17, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(269, 17, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(270, 17, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(271, 17, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(272, 17, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(273, 18, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(274, 18, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(275, 18, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(276, 18, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(277, 18, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(278, 18, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(279, 18, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(280, 18, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(281, 18, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(282, 18, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(283, 18, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(284, 18, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(285, 18, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(286, 18, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(287, 18, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(288, 18, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(289, 19, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(290, 19, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(291, 19, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(292, 19, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(293, 19, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(294, 19, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(295, 19, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(296, 19, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(297, 19, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(298, 19, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(299, 19, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(300, 19, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(301, 19, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(302, 19, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(303, 19, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(304, 19, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(305, 20, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(306, 20, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(307, 20, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(308, 20, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(309, 20, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(310, 20, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(311, 20, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(312, 20, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(313, 20, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(314, 20, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(315, 20, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(316, 20, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(317, 20, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(318, 20, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(319, 20, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(320, 20, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(321, 21, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(322, 21, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(323, 21, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(324, 21, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(325, 21, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(326, 21, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(327, 21, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(328, 21, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(329, 21, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(330, 21, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(331, 21, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(332, 21, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(333, 21, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(334, 21, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(335, 21, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(336, 21, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(337, 22, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(338, 22, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(339, 22, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(340, 22, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(341, 22, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(342, 22, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(343, 22, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(344, 22, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(345, 22, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(346, 22, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(347, 22, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(348, 22, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(349, 22, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(350, 22, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(351, 22, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(352, 22, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(353, 23, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(354, 23, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(355, 23, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(356, 23, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(357, 23, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(358, 23, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(359, 23, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(360, 23, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(361, 23, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(362, 23, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(363, 23, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(364, 23, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(365, 23, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(366, 23, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(367, 23, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(368, 23, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(369, 24, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(370, 24, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(371, 24, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(372, 24, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(373, 24, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(374, 24, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(375, 24, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(376, 24, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(377, 24, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(378, 24, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(379, 24, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(380, 24, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(381, 24, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(382, 24, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(383, 24, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(384, 24, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(385, 25, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(386, 25, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(387, 25, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(388, 25, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(389, 25, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(390, 25, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(391, 25, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(392, 25, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(393, 25, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(394, 25, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(395, 25, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(396, 25, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(397, 25, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(398, 25, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(399, 25, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(400, 25, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(401, 26, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(402, 26, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(403, 26, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(404, 26, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(405, 26, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(406, 26, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(407, 26, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(408, 26, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(409, 26, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(410, 26, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(411, 26, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(412, 26, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(413, 26, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(414, 26, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(415, 26, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(416, 26, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(417, 27, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(418, 27, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(419, 27, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(420, 27, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(421, 27, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(422, 27, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(423, 27, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(424, 27, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(425, 27, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(426, 27, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(427, 27, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(428, 27, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(429, 27, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(430, 27, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(431, 27, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(432, 27, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(433, 28, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(434, 28, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(435, 28, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(436, 28, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(437, 28, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(438, 28, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(439, 28, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(440, 28, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(441, 28, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(442, 28, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(443, 28, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(444, 28, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(445, 28, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(446, 28, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(447, 28, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(448, 28, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(449, 29, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(450, 29, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(451, 29, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(452, 29, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(453, 29, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(454, 29, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(455, 29, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(456, 29, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(457, 29, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(458, 29, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(459, 29, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(460, 29, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(461, 29, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(462, 29, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(463, 29, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(464, 29, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(465, 30, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(466, 30, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(467, 30, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(468, 30, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(469, 30, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(470, 30, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(471, 30, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(472, 30, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(473, 30, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(474, 30, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(475, 30, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(476, 30, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(477, 30, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(478, 30, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(479, 30, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(480, 30, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(481, 31, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(482, 31, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(483, 31, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(484, 31, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(485, 31, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(486, 31, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(487, 31, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(488, 31, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(489, 31, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(490, 31, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(491, 31, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(492, 31, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(493, 31, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(494, 31, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(495, 31, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(496, 31, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(497, 32, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(498, 32, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(499, 32, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(500, 32, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(501, 32, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(502, 32, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(503, 32, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(504, 32, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(505, 32, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(506, 32, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(507, 32, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(508, 32, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(509, 32, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(510, 32, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(511, 32, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(512, 32, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(513, 33, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(514, 33, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(515, 33, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(516, 33, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(517, 33, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(518, 33, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(519, 33, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(520, 33, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(521, 33, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(522, 33, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(523, 33, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(524, 33, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(525, 33, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(526, 33, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(527, 33, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(528, 33, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(529, 34, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(530, 34, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(531, 34, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(532, 34, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(533, 34, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(534, 34, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(535, 34, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(536, 34, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(537, 34, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(538, 34, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(539, 34, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(540, 34, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(541, 34, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(542, 34, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(543, 34, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(544, 34, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(545, 35, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(546, 35, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(547, 35, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(548, 35, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(549, 35, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(550, 35, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(551, 35, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(552, 35, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(553, 35, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(554, 35, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(555, 35, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(556, 35, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(557, 35, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(558, 35, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(559, 35, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(560, 35, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(561, 36, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(562, 36, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(563, 36, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(564, 36, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(565, 36, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(566, 36, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(567, 36, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(568, 36, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(569, 36, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(570, 36, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(571, 36, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(572, 36, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(573, 36, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(574, 36, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(575, 36, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(576, 36, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(577, 37, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(578, 37, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(579, 37, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(580, 37, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(581, 37, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(582, 37, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(583, 37, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(584, 37, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(585, 37, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(586, 37, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(587, 37, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(588, 37, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(589, 37, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(590, 37, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(591, 37, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(592, 37, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(593, 38, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(594, 38, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(595, 38, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(596, 38, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(597, 38, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(598, 38, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(599, 38, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(600, 38, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(601, 38, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(602, 38, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(603, 38, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(604, 38, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(605, 38, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(606, 38, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(607, 38, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(608, 38, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(609, 39, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(610, 39, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(611, 39, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(612, 39, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(613, 39, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(614, 39, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(615, 39, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(616, 39, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(617, 39, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(618, 39, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(619, 39, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(620, 39, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(621, 39, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(622, 39, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(623, 39, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(624, 39, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(625, 40, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(626, 40, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(627, 40, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(628, 40, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(629, 40, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(630, 40, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(631, 40, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(632, 40, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(633, 40, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(634, 40, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(635, 40, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(636, 40, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(637, 40, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(638, 40, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(639, 40, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(640, 40, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(641, 41, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(642, 41, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(643, 41, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(644, 41, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(645, 41, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(646, 41, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(647, 41, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(648, 41, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(649, 41, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(650, 41, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(651, 41, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(652, 41, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(653, 41, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(654, 41, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(655, 41, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(656, 41, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(657, 42, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(658, 42, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(659, 42, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(660, 42, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(661, 42, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(662, 42, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(663, 42, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(664, 42, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(665, 42, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(666, 42, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(667, 42, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(668, 42, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(669, 42, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(670, 42, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(671, 42, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(672, 42, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(673, 43, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(674, 43, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(675, 43, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(676, 43, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(677, 43, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(678, 43, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(679, 43, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(680, 43, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(681, 43, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(682, 43, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(683, 43, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(684, 43, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(685, 43, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(686, 43, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(687, 43, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(688, 43, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(689, 44, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(690, 44, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(691, 44, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(692, 44, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(693, 44, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(694, 44, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(695, 44, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(696, 44, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(697, 44, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(698, 44, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(699, 44, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(700, 44, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(701, 44, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(702, 44, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(703, 44, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(704, 44, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(705, 45, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(706, 45, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(707, 45, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(708, 45, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(709, 45, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(710, 45, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(711, 45, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(712, 45, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(713, 45, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(714, 45, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(715, 45, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(716, 45, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(717, 45, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(718, 45, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(719, 45, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(720, 45, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(721, 46, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(722, 46, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(723, 46, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(724, 46, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(725, 46, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(726, 46, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(727, 46, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(728, 46, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(729, 46, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(730, 46, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(731, 46, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(732, 46, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(733, 46, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(734, 46, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(735, 46, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(736, 46, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(737, 47, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(738, 47, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(739, 47, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(740, 47, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(741, 47, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(742, 47, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(743, 47, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(744, 47, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(745, 47, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(746, 47, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(747, 47, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(748, 47, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(749, 47, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(750, 47, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(751, 47, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(752, 47, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(753, 48, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(754, 48, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(755, 48, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(756, 48, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(757, 48, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(758, 48, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(759, 48, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(760, 48, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(761, 48, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(762, 48, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(763, 48, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(764, 48, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(765, 48, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(766, 48, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(767, 48, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(768, 48, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(769, 49, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(770, 49, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(771, 49, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(772, 49, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(773, 49, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(774, 49, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(775, 49, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(776, 49, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(777, 49, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(778, 49, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(779, 49, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(780, 49, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(781, 49, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(782, 49, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(783, 49, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(784, 49, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(785, 50, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(786, 50, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(787, 50, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(788, 50, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(789, 50, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(790, 50, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(791, 50, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(792, 50, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(793, 50, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(794, 50, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(795, 50, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(796, 50, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(797, 50, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(798, 50, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(799, 50, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(800, 50, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(801, 51, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(802, 51, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(803, 51, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(804, 51, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(805, 51, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(806, 51, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(807, 51, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(808, 51, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(809, 51, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(810, 51, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(811, 51, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(812, 51, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(813, 51, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(814, 51, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(815, 51, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(816, 51, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(817, 52, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(818, 52, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(819, 52, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(820, 52, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(821, 52, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(822, 52, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(823, 52, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(824, 52, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(825, 52, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(826, 52, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(827, 52, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(828, 52, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(829, 52, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(830, 52, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(831, 52, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(832, 52, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(833, 53, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(834, 53, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(835, 53, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(836, 53, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(837, 53, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(838, 53, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(839, 53, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(840, 53, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(841, 53, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(842, 53, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(843, 53, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(844, 53, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(845, 53, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(846, 53, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(847, 53, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(848, 53, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(849, 54, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(850, 54, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(851, 54, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(852, 54, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(853, 54, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(854, 54, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(855, 54, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(856, 54, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(857, 54, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(858, 54, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18');
INSERT INTO `university_course_levels` (`id`, `course_catalog_id`, `level_id`, `created_at`, `updated_at`) VALUES
(859, 54, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(860, 54, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(861, 54, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(862, 54, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(863, 54, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(864, 54, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(865, 55, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(866, 55, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(867, 55, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(868, 55, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(869, 55, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(870, 55, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(871, 55, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(872, 55, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(873, 55, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(874, 55, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(875, 55, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(876, 55, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(877, 55, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(878, 55, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(879, 55, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(880, 55, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(881, 56, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(882, 56, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(883, 56, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(884, 56, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(885, 56, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(886, 56, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(887, 56, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(888, 56, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(889, 56, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(890, 56, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(891, 56, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(892, 56, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(893, 56, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(894, 56, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(895, 56, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(896, 56, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(897, 57, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(898, 57, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(899, 57, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(900, 57, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(901, 57, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(902, 57, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(903, 57, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(904, 57, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(905, 57, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(906, 57, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(907, 57, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(908, 57, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(909, 57, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(910, 57, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(911, 57, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(912, 57, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(913, 58, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(914, 58, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(915, 58, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(916, 58, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(917, 58, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(918, 58, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(919, 58, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(920, 58, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(921, 58, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(922, 58, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(923, 58, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(924, 58, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(925, 58, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(926, 58, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(927, 58, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(928, 58, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(929, 59, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(930, 59, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(931, 59, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(932, 59, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(933, 59, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(934, 59, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(935, 59, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(936, 59, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(937, 59, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(938, 59, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(939, 59, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(940, 59, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(941, 59, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(942, 59, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(943, 59, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(944, 59, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(945, 60, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(946, 60, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(947, 60, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(948, 60, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(949, 60, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(950, 60, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(951, 60, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(952, 60, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(953, 60, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(954, 60, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(955, 60, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(956, 60, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(957, 60, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(958, 60, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(959, 60, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(960, 60, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(961, 61, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(962, 61, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(963, 61, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(964, 61, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(965, 61, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(966, 61, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(967, 61, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(968, 61, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(969, 61, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(970, 61, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(971, 61, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(972, 61, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(973, 61, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(974, 61, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(975, 61, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(976, 61, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(977, 62, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(978, 62, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(979, 62, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(980, 62, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(981, 62, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(982, 62, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(983, 62, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(984, 62, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(985, 62, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(986, 62, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(987, 62, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(988, 62, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(989, 62, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(990, 62, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(991, 62, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(992, 62, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(993, 63, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(994, 63, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(995, 63, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(996, 63, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(997, 63, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(998, 63, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(999, 63, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1000, 63, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1001, 63, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1002, 63, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1003, 63, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1004, 63, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1005, 63, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1006, 63, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1007, 63, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1008, 63, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1009, 64, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1010, 64, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1011, 64, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1012, 64, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1013, 64, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1014, 64, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1015, 64, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1016, 64, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1017, 64, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1018, 64, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1019, 64, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1020, 64, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1021, 64, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1022, 64, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1023, 64, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1024, 64, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1025, 65, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1026, 65, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1027, 65, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1028, 65, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1029, 65, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1030, 65, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1031, 65, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1032, 65, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1033, 65, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1034, 65, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1035, 65, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1036, 65, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1037, 65, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1038, 65, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1039, 65, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1040, 65, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1041, 66, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1042, 66, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1043, 66, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1044, 66, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1045, 66, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1046, 66, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1047, 66, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1048, 66, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1049, 66, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1050, 66, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1051, 66, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1052, 66, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1053, 66, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1054, 66, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1055, 66, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1056, 66, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1057, 67, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1058, 67, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1059, 67, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1060, 67, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1061, 67, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1062, 67, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1063, 67, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1064, 67, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1065, 67, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1066, 67, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1067, 67, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1068, 67, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1069, 67, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1070, 67, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1071, 67, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1072, 67, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1073, 68, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1074, 68, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1075, 68, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1076, 68, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1077, 68, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1078, 68, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1079, 68, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1080, 68, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1081, 68, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1082, 68, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1083, 68, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1084, 68, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1085, 68, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1086, 68, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1087, 68, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1088, 68, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1089, 69, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1090, 69, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1091, 69, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1092, 69, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1093, 69, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1094, 69, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1095, 69, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1096, 69, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1097, 69, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1098, 69, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1099, 69, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1100, 69, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1101, 69, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1102, 69, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1103, 69, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1104, 69, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1105, 70, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1106, 70, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1107, 70, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1108, 70, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1109, 70, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1110, 70, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1111, 70, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1112, 70, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1113, 70, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1114, 70, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1115, 70, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1116, 70, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1117, 70, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1118, 70, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1119, 70, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1120, 70, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1121, 71, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1122, 71, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1123, 71, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1124, 71, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1125, 71, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1126, 71, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1127, 71, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1128, 71, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1129, 71, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1130, 71, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1131, 71, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1132, 71, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1133, 71, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1134, 71, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1135, 71, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1136, 71, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1137, 72, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1138, 72, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1139, 72, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1140, 72, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1141, 72, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1142, 72, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1143, 72, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1144, 72, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1145, 72, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1146, 72, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1147, 72, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1148, 72, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1149, 72, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1150, 72, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1151, 72, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1152, 72, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1153, 73, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1154, 73, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1155, 73, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1156, 73, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1157, 73, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1158, 73, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1159, 73, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1160, 73, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1161, 73, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1162, 73, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1163, 73, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1164, 73, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1165, 73, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1166, 73, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1167, 73, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1168, 73, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1169, 74, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1170, 74, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1171, 74, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1172, 74, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1173, 74, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1174, 74, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1175, 74, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1176, 74, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1177, 74, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1178, 74, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1179, 74, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1180, 74, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1181, 74, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1182, 74, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1183, 74, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1184, 74, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1185, 75, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1186, 75, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1187, 75, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1188, 75, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1189, 75, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1190, 75, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1191, 75, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1192, 75, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1193, 75, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1194, 75, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1195, 75, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1196, 75, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1197, 75, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1198, 75, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1199, 75, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1200, 75, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1201, 76, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1202, 76, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1203, 76, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1204, 76, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1205, 76, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1206, 76, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1207, 76, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1208, 76, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1209, 76, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1210, 76, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1211, 76, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1212, 76, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1213, 76, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1214, 76, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1215, 76, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1216, 76, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1217, 77, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1218, 77, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1219, 77, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1220, 77, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1221, 77, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1222, 77, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1223, 77, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1224, 77, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1225, 77, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1226, 77, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1227, 77, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1228, 77, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1229, 77, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1230, 77, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1231, 77, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1232, 77, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1233, 78, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1234, 78, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1235, 78, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1236, 78, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1237, 78, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1238, 78, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1239, 78, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1240, 78, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1241, 78, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1242, 78, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1243, 78, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1244, 78, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1245, 78, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1246, 78, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1247, 78, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1248, 78, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1249, 79, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1250, 79, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1251, 79, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1252, 79, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1253, 79, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1254, 79, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1255, 79, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1256, 79, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1257, 79, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1258, 79, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1259, 79, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1260, 79, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1261, 79, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1262, 79, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1263, 79, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1264, 79, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1265, 80, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1266, 80, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1267, 80, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1268, 80, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1269, 80, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1270, 80, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1271, 80, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1272, 80, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1273, 80, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1274, 80, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1275, 80, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1276, 80, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1277, 80, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1278, 80, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1279, 80, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1280, 80, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1281, 81, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1282, 81, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1283, 81, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1284, 81, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1285, 81, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1286, 81, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1287, 81, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1288, 81, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1289, 81, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1290, 81, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1291, 81, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1292, 81, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1293, 81, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1294, 81, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1295, 81, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1296, 81, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1297, 82, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1298, 82, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1299, 82, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1300, 82, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1301, 82, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1302, 82, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1303, 82, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1304, 82, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1305, 82, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1306, 82, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1307, 82, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1308, 82, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1309, 82, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1310, 82, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1311, 82, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1312, 82, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1313, 83, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1314, 83, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1315, 83, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1316, 83, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1317, 83, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1318, 83, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1319, 83, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1320, 83, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1321, 83, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1322, 83, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1323, 83, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1324, 83, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1325, 83, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1326, 83, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1327, 83, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1328, 83, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1329, 84, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1330, 84, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1331, 84, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1332, 84, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1333, 84, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1334, 84, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1335, 84, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1336, 84, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1337, 84, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1338, 84, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1339, 84, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1340, 84, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1341, 84, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1342, 84, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1343, 84, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1344, 84, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1345, 85, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1346, 85, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1347, 85, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1348, 85, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1349, 85, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1350, 85, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1351, 85, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1352, 85, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1353, 85, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1354, 85, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1355, 85, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1356, 85, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1357, 85, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1358, 85, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1359, 85, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1360, 85, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1361, 86, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1362, 86, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1363, 86, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1364, 86, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1365, 86, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1366, 86, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1367, 86, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1368, 86, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1369, 86, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1370, 86, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1371, 86, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1372, 86, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1373, 86, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1374, 86, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1375, 86, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1376, 86, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1377, 87, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1378, 87, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1379, 87, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1380, 87, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1381, 87, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1382, 87, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1383, 87, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1384, 87, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1385, 87, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1386, 87, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1387, 87, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1388, 87, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1389, 87, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1390, 87, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1391, 87, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1392, 87, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1393, 88, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1394, 88, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1395, 88, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1396, 88, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1397, 88, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1398, 88, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1399, 88, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1400, 88, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1401, 88, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1402, 88, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1403, 88, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1404, 88, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1405, 88, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1406, 88, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1407, 88, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1408, 88, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1409, 89, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1410, 89, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1411, 89, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1412, 89, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1413, 89, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1414, 89, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1415, 89, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1416, 89, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1417, 89, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1418, 89, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1419, 89, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1420, 89, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1421, 89, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1422, 89, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1423, 89, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1424, 89, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1425, 90, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1426, 90, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1427, 90, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1428, 90, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1429, 90, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1430, 90, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1431, 90, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1432, 90, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1433, 90, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1434, 90, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1435, 90, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1436, 90, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1437, 90, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1438, 90, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1439, 90, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1440, 90, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1441, 91, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1442, 91, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1443, 91, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1444, 91, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1445, 91, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1446, 91, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1447, 91, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1448, 91, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1449, 91, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1450, 91, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1451, 91, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1452, 91, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1453, 91, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1454, 91, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1455, 91, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1456, 91, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1457, 92, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1458, 92, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1459, 92, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1460, 92, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1461, 92, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1462, 92, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1463, 92, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1464, 92, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1465, 92, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1466, 92, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1467, 92, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1468, 92, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1469, 92, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1470, 92, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1471, 92, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1472, 92, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1473, 93, 1, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1474, 93, 2, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1475, 93, 3, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1476, 93, 4, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1477, 93, 5, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1478, 93, 6, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1479, 93, 7, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1480, 93, 8, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1481, 93, 9, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1482, 93, 10, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1483, 93, 11, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1484, 93, 12, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1485, 93, 13, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1486, 93, 14, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1487, 93, 15, '2026-02-11 01:17:18', '2026-02-11 01:17:18'),
(1488, 93, 16, '2026-02-11 01:17:18', '2026-02-11 01:17:18');

-- --------------------------------------------------------

--
-- Table structure for table `university_course_tags`
--

CREATE TABLE `university_course_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(60) NOT NULL,
  `name` varchar(80) NOT NULL,
  `ar_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_wishlists`
--

CREATE TABLE `university_wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uni_applications`
--

CREATE TABLE `uni_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `intake` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','team','counsellor','uni_agent','agent','lg_agent','school','lg_student','uni_student') NOT NULL DEFAULT 'lg_student',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `role`, `phone`, `avatar`, `status`, `last_login_at`, `google_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@test.com', 'admin_user', '2026-02-07 01:19:33', 'admin', NULL, NULL, 'active', '2026-02-10 01:55:19', NULL, '$2y$12$0F8.48TWW5Fvgc.UyxoVmOvwFat0xopKCCUtVeMtAt9pzjDbbpBAK', 'c4nl5qb20Y', '2026-02-07 01:19:33', '2026-02-10 01:55:19'),
(2, 'Team User', 'team@test.com', 'team_user', '2026-02-07 01:19:33', 'team', NULL, NULL, 'active', NULL, NULL, '$2y$12$D3LN9kTQzIi03uhMckRPnOL9owz2ZcxFzo7qBgq57kqyE.us6dfVG', 'nC1Nzk8DId', '2026-02-07 01:19:33', '2026-02-07 01:19:33'),
(3, 'Counsellor User', 'counsellor@test.com', 'counsellor_user', '2026-02-07 01:19:33', 'counsellor', NULL, NULL, 'active', NULL, NULL, '$2y$12$LKdJT8lIi2CFQx2GC/zoWeDZdEHdz1eK9aKHZGOq7XWNVrkVpmr2a', 'wB03SCg51g', '2026-02-07 01:19:33', '2026-02-07 01:19:33'),
(4, 'Uni agent User', 'uni_agent@test.com', 'uni_agent_user', '2026-02-07 01:19:34', 'uni_agent', NULL, NULL, 'active', NULL, NULL, '$2y$12$BsmOLVjYo8poJxa4fDSrXuKymDQuvq9AhFKM2a/2XgFjO64E/akmm', 'hf9ynfHHM6', '2026-02-07 01:19:34', '2026-02-07 01:19:34'),
(5, 'Agent User', 'agent@test.com', 'agent_user', '2026-02-07 01:19:34', 'agent', NULL, NULL, 'active', NULL, NULL, '$2y$12$5svykZomI9dutNYYfoVBz.4mNfLUg/PmzECrqRUJOrDJblAaicTAe', '105bVMZ0ob', '2026-02-07 01:19:34', '2026-02-07 01:19:34'),
(6, 'Lg agent User', 'lg_agent@test.com', 'lg_agent_user', '2026-02-07 01:19:34', 'lg_agent', NULL, NULL, 'active', NULL, NULL, '$2y$12$xFy/jMOpP5dd5TQgP/PspO3FXq5Gdv52Rizb5XRQHU3G4YYJi9xya', 'KEHg7EYn8b', '2026-02-07 01:19:34', '2026-02-07 01:19:34'),
(7, 'School User', 'school@test.com', 'school_user', '2026-02-07 01:19:34', 'school', NULL, NULL, 'active', NULL, NULL, '$2y$12$XwkwHGB5DZZV5G3YEzAVxOaAwk8r/rV3URfuUOpv8e3rpLQ3K72US', '4hs7t6ZUi2', '2026-02-07 01:19:34', '2026-02-07 01:19:34'),
(8, 'Lg student User', 'lg_student@test.com', 'lg_student_user', '2026-02-07 01:19:35', 'lg_student', NULL, NULL, 'active', '2026-02-14 18:07:52', NULL, '$2y$12$zngVhZwwKbNYX985z/OGquBOaJ6cnbk47AN3vl2L6cFHhih1pOsn2', '67QmWPQjGz', '2026-02-07 01:19:35', '2026-02-14 18:07:52'),
(9, 'Uni student User', 'uni_student@test.com', 'uni_student_user', '2026-02-07 01:19:35', 'uni_student', NULL, NULL, 'active', NULL, NULL, '$2y$12$Wi/1nIdP/3avH2Zl2cNuAOzet2AvCjYopoKoGyiei0ZbOXIHfjg0y', 'tODnlxoAgT', '2026-02-07 01:19:35', '2026-02-07 01:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_otps`
--

CREATE TABLE `user_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `purpose` varchar(40) NOT NULL,
  `code` varchar(10) NOT NULL,
  `channel` varchar(20) NOT NULL DEFAULT 'email',
  `expires_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `nationality_country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address_line` varchar(191) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `secondary_email` varchar(191) DEFAULT NULL,
  `alt_phone_e164` varchar(32) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `study_level` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accreditations`
--
ALTER TABLE `accreditations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agents_referral_code_unique` (`referral_code`),
  ADD KEY `agents_user_id_foreign` (`user_id`);

--
-- Indexes for table `agent_students`
--
ALTER TABLE `agent_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_students_agent_id_foreign` (`agent_id`),
  ADD KEY `agent_students_student_user_id_foreign` (`student_user_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_application_id_unique` (`application_id`),
  ADD KEY `applications_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `bathroom_types`
--
ALTER TABLE `bathroom_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bathroom_types_bathroom_code_unique` (`bathroom_code`);

--
-- Indexes for table `bedroom_types`
--
ALTER TABLE `bedroom_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bedroom_types_bedroom_code_unique` (`bedroom_code`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`),
  ADD KEY `blogs_category_id_foreign` (`category_id`),
  ADD KEY `blogs_publisher_id_foreign` (`publisher_id`);

--
-- Indexes for table `blog_blog_tag`
--
ALTER TABLE `blog_blog_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_blog_tag_blog_id_foreign` (`blog_id`),
  ADD KEY `blog_blog_tag_blog_tag_id_foreign` (`blog_tag_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_slug_unique` (`slug`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_tags_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_country_id_foreign` (`country_id`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_pages_app_slug_unique` (`app`,`slug`),
  ADD KEY `cms_pages_app_display_order_index` (`app`,`display_order`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversion_fees`
--
ALTER TABLE `conversion_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_conversion_fees_base_target` (`base_currency`,`target_currency`),
  ADD KEY `idx_conversion_fees_base` (`base_currency`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_country_code_unique` (`country_code`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `destinations_slug_unique` (`slug`),
  ADD KEY `destinations_country_id_foreign` (`country_id`);

--
-- Indexes for table `destination_disciplines`
--
ALTER TABLE `destination_disciplines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_disciplines_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_faqs`
--
ALTER TABLE `destination_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_faqs_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_features`
--
ALTER TABLE `destination_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_features_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_guides`
--
ALTER TABLE `destination_guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_guides_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_intakes`
--
ALTER TABLE `destination_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_intakes_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_requirements`
--
ALTER TABLE `destination_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_requirements_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `destination_stats`
--
ALTER TABLE `destination_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_stats_destination_id_foreign` (`destination_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_exchange_rates_base_target` (`base_currency`,`target_currency`),
  ADD KEY `idx_exchange_rates_base` (`base_currency`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_lists`
--
ALTER TABLE `featured_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `featured_lists_key_unique` (`key`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_galleries_use_case` (`use_case`);

--
-- Indexes for table `intake_terms`
--
ALTER TABLE `intake_terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `intake_terms_key_unique` (`key`),
  ADD KEY `intake_terms_month_num_index` (`month_num`),
  ADD KEY `intake_terms_sort_order_index` (`sort_order`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_course_compares`
--
ALTER TABLE `language_course_compares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lc_compare_unique` (`user_id`,`course_type`,`course_id`),
  ADD KEY `lc_compare_course_idx` (`course_type`,`course_id`);

--
-- Indexes for table `language_course_online_courses`
--
ALTER TABLE `language_course_online_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_online_courses_slug_unique` (`slug`),
  ADD KEY `idx_lc_online_school` (`language_school_id`),
  ADD KEY `idx_lc_online_type` (`course_type_id`),
  ADD KEY `idx_lc_online_tag` (`tag_id`),
  ADD KEY `idx_lc_online_status` (`status`);

--
-- Indexes for table `language_course_summer_camps`
--
ALTER TABLE `language_course_summer_camps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_summer_camps_slug_unique` (`slug`),
  ADD KEY `idx_lc_camps_branch` (`branch_id`),
  ADD KEY `idx_lc_camps_type` (`course_type_id`),
  ADD KEY `idx_lc_camps_tag` (`tag_id`);

--
-- Indexes for table `language_course_summer_camp_details`
--
ALTER TABLE `language_course_summer_camp_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_summer_camp_details_camp_id_unique` (`camp_id`);

--
-- Indexes for table `language_course_tags`
--
ALTER TABLE `language_course_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_tags_tag_code_unique` (`tag_code`);

--
-- Indexes for table `language_course_training_courses`
--
ALTER TABLE `language_course_training_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_training_courses_slug_unique` (`slug`),
  ADD KEY `idx_lc_training_school` (`language_school_id`),
  ADD KEY `idx_lc_training_branch` (`branch_id`),
  ADD KEY `idx_lc_training_type` (`course_type_id`),
  ADD KEY `idx_lc_training_tag` (`tag_id`);

--
-- Indexes for table `language_course_types`
--
ALTER TABLE `language_course_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_course_types_type_code_unique` (`type_code`);

--
-- Indexes for table `language_course_wishlists`
--
ALTER TABLE `language_course_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lc_wishlist_unique` (`user_id`,`course_type`,`course_id`),
  ADD KEY `lc_wishlist_course_idx` (`course_type`,`course_id`);

--
-- Indexes for table `language_schools`
--
ALTER TABLE `language_schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_schools_slug_unique` (`slug`),
  ADD KEY `language_schools_is_preferred_index` (`is_preferred`);

--
-- Indexes for table `language_school_accommodations`
--
ALTER TABLE `language_school_accommodations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_school_accommodations_slug_unique` (`slug`),
  ADD KEY `language_school_accommodations_branch_id_foreign` (`branch_id`),
  ADD KEY `language_school_accommodations_school_branch_id_foreign` (`school_branch_id`),
  ADD KEY `language_school_accommodations_language_course_tag_id_foreign` (`language_course_tag_id`),
  ADD KEY `language_school_accommodations_bedroom_type_id_foreign` (`bedroom_type_id`),
  ADD KEY `language_school_accommodations_bathroom_type_id_foreign` (`bathroom_type_id`),
  ADD KEY `language_school_accommodations_meal_plan_id_foreign` (`meal_plan_id`);

--
-- Indexes for table `language_school_branches`
--
ALTER TABLE `language_school_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_school_branches_slug_unique` (`slug`),
  ADD KEY `language_school_branches_language_school_id_foreign` (`language_school_id`),
  ADD KEY `language_school_branches_city_id_foreign` (`city_id`);

--
-- Indexes for table `language_school_branch_high_season_fees`
--
ALTER TABLE `language_school_branch_high_season_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_branch_high_season_fees_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `language_school_branch_registration_fees`
--
ALTER TABLE `language_school_branch_registration_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_branch_registration_fees_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `language_school_coupons`
--
ALTER TABLE `language_school_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_school_coupons_code_unique` (`code`);

--
-- Indexes for table `language_school_courses`
--
ALTER TABLE `language_school_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_courses_branch_id_foreign` (`branch_id`),
  ADD KEY `language_school_courses_language_course_type_id_foreign` (`language_course_type_id`),
  ADD KEY `language_school_courses_language_course_tag_id_foreign` (`language_course_tag_id`);

--
-- Indexes for table `language_school_course_fees`
--
ALTER TABLE `language_school_course_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_course_fees_language_school_course_id_foreign` (`language_school_course_id`);

--
-- Indexes for table `language_school_course_material_fees`
--
ALTER TABLE `language_school_course_material_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ls_cm_fee_course_id_foreign` (`language_school_course_id`);

--
-- Indexes for table `language_school_discounts`
--
ALTER TABLE `language_school_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ls_discounts_active_dates` (`is_active`,`start_date`,`end_date`);

--
-- Indexes for table `language_school_insurance_fees`
--
ALTER TABLE `language_school_insurance_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_insurance_fees_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `language_school_pickups`
--
ALTER TABLE `language_school_pickups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_pickups_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `language_school_pioneers_discounts`
--
ALTER TABLE `language_school_pioneers_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_school_supplements`
--
ALTER TABLE `language_school_supplements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_school_supplements_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `language_tests`
--
ALTER TABLE `language_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_tests_key_unique` (`key`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `levels_key_unique` (`key`),
  ADD KEY `levels_sort_order_index` (`sort_order`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meal_plans_meal_code_unique` (`meal_code`);

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
  ADD KEY `oauth_clients_owner_type_owner_id_index` (`owner_type`,`owner_id`);

--
-- Indexes for table `oauth_device_codes`
--
ALTER TABLE `oauth_device_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `oauth_device_codes_user_code_unique` (`user_code`),
  ADD KEY `oauth_device_codes_user_id_index` (`user_id`),
  ADD KEY `oauth_device_codes_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `offices_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scholarships_university_id_slug_unique` (`university_id`,`slug`),
  ADD KEY `scholarships_university_id_is_active_index` (`university_id`,`is_active`),
  ADD KEY `scholarships_deadline_date_index` (`deadline_date`);

--
-- Indexes for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scholarship_applications_application_id_unique` (`application_id`),
  ADD KEY `scholarship_applications_user_id_foreign` (`user_id`),
  ADD KEY `scholarship_applications_assignee_id_foreign` (`assignee_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `subject_areas`
--
ALTER TABLE `subject_areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_areas_key_unique` (`key`),
  ADD UNIQUE KEY `subject_areas_slug_unique` (`slug`),
  ADD KEY `subject_areas_sort_order_index` (`sort_order`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `universities_slug_unique` (`slug`),
  ADD KEY `universities_country_id_foreign` (`country_id`),
  ADD KEY `universities_city_id_foreign` (`city_id`);

--
-- Indexes for table `university_accommodation_rooms`
--
ALTER TABLE `university_accommodation_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_accommodation_rooms_slug_unique` (`slug`);

--
-- Indexes for table `university_campuses`
--
ALTER TABLE `university_campuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_campuses_university_id_slug_unique` (`university_id`,`slug`),
  ADD KEY `university_campuses_city_id_foreign` (`city_id`),
  ADD KEY `university_campuses_is_online_is_active_index` (`is_online`,`is_active`);

--
-- Indexes for table `university_courses`
--
ALTER TABLE `university_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_courses_ucourse_level_unique` (`university_id`,`course_catalog_id`,`level_id`),
  ADD KEY `university_courses_university_id_is_active_index` (`university_id`,`is_active`),
  ADD KEY `university_courses_level_id_index` (`level_id`),
  ADD KEY `university_courses_course_catalog_id_foreign` (`course_catalog_id`);

--
-- Indexes for table `university_course_catalogs`
--
ALTER TABLE `university_course_catalogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_course_catalogs_slug_unique` (`slug`),
  ADD KEY `university_course_catalogs_subject_area_id_foreign` (`subject_area_id`);

--
-- Indexes for table `university_course_intakes`
--
ALTER TABLE `university_course_intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_intake_unique` (`university_course_id`,`intake_term_id`),
  ADD KEY `university_course_intakes_intake_term_id_foreign` (`intake_term_id`),
  ADD KEY `university_course_intakes_university_course_id_is_active_index` (`university_course_id`,`is_active`);

--
-- Indexes for table `university_course_intake_term`
--
ALTER TABLE `university_course_intake_term`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_intake_unique` (`university_course_id`,`intake_term_id`),
  ADD KEY `university_course_intake_term_intake_term_id_foreign` (`intake_term_id`);

--
-- Indexes for table `university_course_levels`
--
ALTER TABLE `university_course_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_course_levels_course_catalog_id_level_id_unique` (`course_catalog_id`,`level_id`),
  ADD KEY `university_course_levels_level_id_foreign` (`level_id`);

--
-- Indexes for table `university_course_tags`
--
ALTER TABLE `university_course_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_course_tags_key_unique` (`key`);

--
-- Indexes for table `university_wishlists`
--
ALTER TABLE `university_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `university_wishlists_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `university_wishlists_course_id_foreign` (`course_id`);

--
-- Indexes for table `uni_applications`
--
ALTER TABLE `uni_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uni_applications_course_id_foreign` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_last_login_at_index` (`last_login_at`);

--
-- Indexes for table `user_otps`
--
ALTER TABLE `user_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_otps_user_id_purpose_index` (`user_id`,`purpose`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_profiles_user_id_unique` (`user_id`),
  ADD KEY `user_profiles_current_country_id_current_city_id_index` (`current_country_id`,`current_city_id`),
  ADD KEY `user_profiles_nationality_country_id_index` (`nationality_country_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accreditations`
--
ALTER TABLE `accreditations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_students`
--
ALTER TABLE `agent_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bathroom_types`
--
ALTER TABLE `bathroom_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bedroom_types`
--
ALTER TABLE `bedroom_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_blog_tag`
--
ALTER TABLE `blog_blog_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_tags`
--
ALTER TABLE `blog_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversion_fees`
--
ALTER TABLE `conversion_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `destination_disciplines`
--
ALTER TABLE `destination_disciplines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `destination_faqs`
--
ALTER TABLE `destination_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `destination_features`
--
ALTER TABLE `destination_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `destination_guides`
--
ALTER TABLE `destination_guides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destination_intakes`
--
ALTER TABLE `destination_intakes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `destination_requirements`
--
ALTER TABLE `destination_requirements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `destination_stats`
--
ALTER TABLE `destination_stats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `featured_lists`
--
ALTER TABLE `featured_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `intake_terms`
--
ALTER TABLE `intake_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_course_compares`
--
ALTER TABLE `language_course_compares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `language_course_online_courses`
--
ALTER TABLE `language_course_online_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language_course_summer_camps`
--
ALTER TABLE `language_course_summer_camps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language_course_summer_camp_details`
--
ALTER TABLE `language_course_summer_camp_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language_course_tags`
--
ALTER TABLE `language_course_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `language_course_training_courses`
--
ALTER TABLE `language_course_training_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language_course_types`
--
ALTER TABLE `language_course_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language_course_wishlists`
--
ALTER TABLE `language_course_wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `language_schools`
--
ALTER TABLE `language_schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `language_school_accommodations`
--
ALTER TABLE `language_school_accommodations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `language_school_branches`
--
ALTER TABLE `language_school_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `language_school_branch_high_season_fees`
--
ALTER TABLE `language_school_branch_high_season_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `language_school_branch_registration_fees`
--
ALTER TABLE `language_school_branch_registration_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `language_school_coupons`
--
ALTER TABLE `language_school_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language_school_courses`
--
ALTER TABLE `language_school_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `language_school_course_fees`
--
ALTER TABLE `language_school_course_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2593;

--
-- AUTO_INCREMENT for table `language_school_course_material_fees`
--
ALTER TABLE `language_school_course_material_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `language_school_discounts`
--
ALTER TABLE `language_school_discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_school_insurance_fees`
--
ALTER TABLE `language_school_insurance_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `language_school_pickups`
--
ALTER TABLE `language_school_pickups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `language_school_pioneers_discounts`
--
ALTER TABLE `language_school_pioneers_discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language_school_supplements`
--
ALTER TABLE `language_school_supplements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_tests`
--
ALTER TABLE `language_tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subject_areas`
--
ALTER TABLE `subject_areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `university_accommodation_rooms`
--
ALTER TABLE `university_accommodation_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_campuses`
--
ALTER TABLE `university_campuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_courses`
--
ALTER TABLE `university_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_course_catalogs`
--
ALTER TABLE `university_course_catalogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `university_course_intakes`
--
ALTER TABLE `university_course_intakes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_course_intake_term`
--
ALTER TABLE `university_course_intake_term`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_course_levels`
--
ALTER TABLE `university_course_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1489;

--
-- AUTO_INCREMENT for table `university_course_tags`
--
ALTER TABLE `university_course_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_wishlists`
--
ALTER TABLE `university_wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uni_applications`
--
ALTER TABLE `uni_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_otps`
--
ALTER TABLE `user_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `agent_students`
--
ALTER TABLE `agent_students`
  ADD CONSTRAINT `agent_students_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agent_students_student_user_id_foreign` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blogs_publisher_id_foreign` FOREIGN KEY (`publisher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_blog_tag`
--
ALTER TABLE `blog_blog_tag`
  ADD CONSTRAINT `blog_blog_tag_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_blog_tag_blog_tag_id_foreign` FOREIGN KEY (`blog_tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `destination_disciplines`
--
ALTER TABLE `destination_disciplines`
  ADD CONSTRAINT `destination_disciplines_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_faqs`
--
ALTER TABLE `destination_faqs`
  ADD CONSTRAINT `destination_faqs_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_features`
--
ALTER TABLE `destination_features`
  ADD CONSTRAINT `destination_features_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_guides`
--
ALTER TABLE `destination_guides`
  ADD CONSTRAINT `destination_guides_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_intakes`
--
ALTER TABLE `destination_intakes`
  ADD CONSTRAINT `destination_intakes_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_requirements`
--
ALTER TABLE `destination_requirements`
  ADD CONSTRAINT `destination_requirements_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_stats`
--
ALTER TABLE `destination_stats`
  ADD CONSTRAINT `destination_stats_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_course_compares`
--
ALTER TABLE `language_course_compares`
  ADD CONSTRAINT `language_course_compares_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_course_online_courses`
--
ALTER TABLE `language_course_online_courses`
  ADD CONSTRAINT `language_course_online_courses_course_type_id_foreign` FOREIGN KEY (`course_type_id`) REFERENCES `language_course_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_online_courses_language_school_id_foreign` FOREIGN KEY (`language_school_id`) REFERENCES `language_schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_online_courses_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `language_course_tags` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `language_course_summer_camps`
--
ALTER TABLE `language_course_summer_camps`
  ADD CONSTRAINT `language_course_summer_camps_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_summer_camps_course_type_id_foreign` FOREIGN KEY (`course_type_id`) REFERENCES `language_course_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_summer_camps_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `language_course_tags` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `language_course_summer_camp_details`
--
ALTER TABLE `language_course_summer_camp_details`
  ADD CONSTRAINT `language_course_summer_camp_details_camp_id_foreign` FOREIGN KEY (`camp_id`) REFERENCES `language_course_summer_camps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_course_training_courses`
--
ALTER TABLE `language_course_training_courses`
  ADD CONSTRAINT `language_course_training_courses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_course_training_courses_course_type_id_foreign` FOREIGN KEY (`course_type_id`) REFERENCES `language_course_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_training_courses_language_school_id_foreign` FOREIGN KEY (`language_school_id`) REFERENCES `language_schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_course_training_courses_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `language_course_tags` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `language_course_wishlists`
--
ALTER TABLE `language_course_wishlists`
  ADD CONSTRAINT `language_course_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_accommodations`
--
ALTER TABLE `language_school_accommodations`
  ADD CONSTRAINT `language_school_accommodations_bathroom_type_id_foreign` FOREIGN KEY (`bathroom_type_id`) REFERENCES `bathroom_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_school_accommodations_bedroom_type_id_foreign` FOREIGN KEY (`bedroom_type_id`) REFERENCES `bedroom_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_school_accommodations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_school_accommodations_language_course_tag_id_foreign` FOREIGN KEY (`language_course_tag_id`) REFERENCES `language_course_tags` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_school_accommodations_meal_plan_id_foreign` FOREIGN KEY (`meal_plan_id`) REFERENCES `meal_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_school_accommodations_school_branch_id_foreign` FOREIGN KEY (`school_branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `language_school_branches`
--
ALTER TABLE `language_school_branches`
  ADD CONSTRAINT `language_school_branches_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_school_branches_language_school_id_foreign` FOREIGN KEY (`language_school_id`) REFERENCES `language_schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_branch_high_season_fees`
--
ALTER TABLE `language_school_branch_high_season_fees`
  ADD CONSTRAINT `language_school_branch_high_season_fees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_branch_registration_fees`
--
ALTER TABLE `language_school_branch_registration_fees`
  ADD CONSTRAINT `language_school_branch_registration_fees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_courses`
--
ALTER TABLE `language_school_courses`
  ADD CONSTRAINT `language_school_courses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_school_courses_language_course_tag_id_foreign` FOREIGN KEY (`language_course_tag_id`) REFERENCES `language_course_tags` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `language_school_courses_language_course_type_id_foreign` FOREIGN KEY (`language_course_type_id`) REFERENCES `language_course_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_course_fees`
--
ALTER TABLE `language_school_course_fees`
  ADD CONSTRAINT `language_school_course_fees_language_school_course_id_foreign` FOREIGN KEY (`language_school_course_id`) REFERENCES `language_school_courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_course_material_fees`
--
ALTER TABLE `language_school_course_material_fees`
  ADD CONSTRAINT `ls_cm_fee_course_id_foreign` FOREIGN KEY (`language_school_course_id`) REFERENCES `language_school_courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_insurance_fees`
--
ALTER TABLE `language_school_insurance_fees`
  ADD CONSTRAINT `language_school_insurance_fees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_pickups`
--
ALTER TABLE `language_school_pickups`
  ADD CONSTRAINT `language_school_pickups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_school_supplements`
--
ALTER TABLE `language_school_supplements`
  ADD CONSTRAINT `language_school_supplements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `language_school_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD CONSTRAINT `scholarships_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  ADD CONSTRAINT `scholarship_applications_assignee_id_foreign` FOREIGN KEY (`assignee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scholarship_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `universities`
--
ALTER TABLE `universities`
  ADD CONSTRAINT `universities_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `universities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_campuses`
--
ALTER TABLE `university_campuses`
  ADD CONSTRAINT `university_campuses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `university_campuses_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_courses`
--
ALTER TABLE `university_courses`
  ADD CONSTRAINT `university_courses_course_catalog_id_foreign` FOREIGN KEY (`course_catalog_id`) REFERENCES `university_course_catalogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `university_courses_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`),
  ADD CONSTRAINT `university_courses_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_course_catalogs`
--
ALTER TABLE `university_course_catalogs`
  ADD CONSTRAINT `university_course_catalogs_subject_area_id_foreign` FOREIGN KEY (`subject_area_id`) REFERENCES `subject_areas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `university_course_intakes`
--
ALTER TABLE `university_course_intakes`
  ADD CONSTRAINT `university_course_intakes_intake_term_id_foreign` FOREIGN KEY (`intake_term_id`) REFERENCES `intake_terms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `university_course_intakes_university_course_id_foreign` FOREIGN KEY (`university_course_id`) REFERENCES `university_courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_course_intake_term`
--
ALTER TABLE `university_course_intake_term`
  ADD CONSTRAINT `university_course_intake_term_intake_term_id_foreign` FOREIGN KEY (`intake_term_id`) REFERENCES `intake_terms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `university_course_intake_term_university_course_id_foreign` FOREIGN KEY (`university_course_id`) REFERENCES `university_courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_course_levels`
--
ALTER TABLE `university_course_levels`
  ADD CONSTRAINT `university_course_levels_course_catalog_id_foreign` FOREIGN KEY (`course_catalog_id`) REFERENCES `university_course_catalogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `university_course_levels_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_wishlists`
--
ALTER TABLE `university_wishlists`
  ADD CONSTRAINT `university_wishlists_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `university_courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `university_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uni_applications`
--
ALTER TABLE `uni_applications`
  ADD CONSTRAINT `uni_applications_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `university_courses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_otps`
--
ALTER TABLE `user_otps`
  ADD CONSTRAINT `user_otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
