-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2018 at 12:06 AM
-- Server version: 5.7.14
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warsha`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `message` text COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`, `updated_at`) VALUES
(1, 'asd', 'asd@asd.asd', 'asd-asd-asd-asd-asd-asd-asd', '2018-11-26 02:48:36', '2018-11-26 02:48:36');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2014_10_12_000000_create_users_table', 2),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_11_25_174831_create_services_table', 1),
(4, '2018_11_25_174904_create_orders_table', 1),
(5, '2018_11_25_175013_create_offers_table', 1),
(6, '2018_11_25_175027_create_abouts_table', 1),
(7, '2018_11_25_175044_create_contacts_table', 1),
(8, '2018_11_25_175113_create_user_services_table', 1),
(9, '2018_11_25_175134_create_order_images_table', 1),
(14, '2018_11_25_175150_create_order_services_table', 3),
(11, '2018_11_25_175217_create_workshop_rates_table', 1),
(12, '2018_11_25_232427_create_roles_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `user_id`, `order_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'السلام عليكم ورحمة الله وبركاته، لدى مشكلة فى محرك السيارة ولا أستطيع التحرك تمام. أرغب فى سطحة لحمل السيارة وصيانة كاملة للمحرك', 1, '2018-11-26 14:35:38', '2018-11-26 17:23:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `car_type` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `car_model` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `lat` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `lon` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `car_type`, `car_model`, `description`, `lat`, `lon`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mercedes Benz', '2018', 'السلام عليكم ورحمة الله وبركاته، لدى مشكلة فى محرك السيارة ولا أستطيع التحرك تمام. أرغب فى سطحة لحمل السيارة وصيانة كاملة للمحرك', NULL, NULL, 'بوليفارد الشيخ محمد بن راشد - دبى - الإمارات العربية المتحدة', 0, '2018-11-26 04:07:19', '2018-11-26 04:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_images`
--

CREATE TABLE `order_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `order_images`
--

INSERT INTO `order_images` (`id`, `order_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, '779953595bfb8d97e0645.jpg', '2018-11-26 04:07:19', '2018-11-26 04:07:19'),
(2, 1, '972677575bfb8d97e19cd.jpg', '2018-11-26 04:07:19', '2018-11-26 04:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_services`
--

CREATE TABLE `order_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `order_services`
--

INSERT INTO `order_services` (`id`, `order_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2018-11-26 04:07:19', '2018-11-26 04:07:19'),
(2, 1, 2, '2018-11-26 04:07:19', '2018-11-26 04:07:19'),
(3, 1, 3, '2018-11-26 04:07:19', '2018-11-26 04:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'customer', NULL, NULL),
(2, 'workshop', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'شحن البطاريات', NULL, NULL, NULL),
(2, 'إصلاح المحركات', NULL, NULL, NULL),
(3, 'إصلاح الإطارات', NULL, NULL, NULL),
(4, 'برمجة المفاتيح', NULL, NULL, NULL),
(5, 'سطحات نقل', NULL, NULL, NULL),
(6, 'فحص كمبيوتر', NULL, NULL, NULL),
(7, 'تغيير زيت', NULL, NULL, NULL),
(8, 'تعبئة وقود', NULL, NULL, NULL),
(9, 'غسيل السيارة', NULL, NULL, NULL),
(10, 'قطع غيار', NULL, NULL, NULL),
(11, 'دهانات السيارة', NULL, NULL, NULL),
(0, 'كل الفئات', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `whatsapp` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_bin NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `lat` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `lon` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `firebase` varchar(191) COLLATE utf8mb4_bin DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `phone`, `email`, `whatsapp`, `username`, `image`, `password`, `code`, `lat`, `lon`, `status`, `firebase`, `last_seen`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tarek Mahfouz', '01273280587', 'timahfouz262@gmail.com', '01273280587', 'mahfouz', '130613225bfb546d670b0.jpg', '$2y$10$uMgjT3C4bIC4oa1egaqtrOwTW7udovzHozps6rxdXD0ZDoFP9GcPS', '9197', '17.346232321', '43.344565624', 1, 'test firebase', '2018-11-26 03:49:19', NULL, '2018-11-26 00:03:25', '2018-11-26 03:49:19'),
(2, 2, 'Tarek Mahfouz', '01114254513', 't.mahfouz@yahoo.com', '01114254513', 'tarek', 'default.png', '$2y$10$gdQT.rG1uAbN8.AJsm4cQei2E1QHGaVWgGWxjBlb4hIUxorZEg9vW', '2623', '17.346232321', '43.344565624', 1, 'test', '2018-11-26 21:31:24', NULL, '2018-11-26 14:12:07', '2018-11-26 21:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_services`
--

CREATE TABLE `user_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `user_services`
--

INSERT INTO `user_services` (`id`, `user_id`, `service_id`, `created_at`, `updated_at`) VALUES
(6, 2, 3, '2018-11-26 21:31:24', '2018-11-26 21:31:24'),
(5, 2, 2, '2018-11-26 21:31:24', '2018-11-26 21:31:24'),
(4, 2, 1, '2018-11-26 21:31:24', '2018-11-26 21:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `workshop_rates`
--

CREATE TABLE `workshop_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `workshop_id` int(10) UNSIGNED NOT NULL,
  `rate` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `workshop_rates`
--

INSERT INTO `workshop_rates` (`id`, `user_id`, `workshop_id`, `rate`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3.00, '2018-11-26 21:22:21', '2018-11-26 21:22:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_user_id_foreign` (`user_id`),
  ADD KEY `offers_order_id_foreign` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_images`
--
ALTER TABLE `order_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_images_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_services`
--
ALTER TABLE `order_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_services_order_id_foreign` (`order_id`),
  ADD KEY `order_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_whatsapp_unique` (`whatsapp`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_services`
--
ALTER TABLE `user_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_services_user_id_foreign` (`user_id`),
  ADD KEY `user_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `workshop_rates`
--
ALTER TABLE `workshop_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workshop_rates_user_id_foreign` (`user_id`),
  ADD KEY `workshop_rates_workshop_id_foreign` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `order_images`
--
ALTER TABLE `order_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `order_services`
--
ALTER TABLE `order_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_services`
--
ALTER TABLE `user_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `workshop_rates`
--
ALTER TABLE `workshop_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
