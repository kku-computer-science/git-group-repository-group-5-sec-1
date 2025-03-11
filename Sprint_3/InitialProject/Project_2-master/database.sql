-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 11, 2025 at 11:19 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `example_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `research_projects`
--

CREATE TABLE `research_projects` (
  `id` bigint UNSIGNED NOT NULL,
  `project_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `project_year` year DEFAULT NULL,
  `budget` int DEFAULT NULL,
  `show_budget` tinyint(1) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `fund_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `research_projects`
--

INSERT INTO `research_projects` (`id`, `project_name`, `project_start`, `project_end`, `project_year`, `budget`, `show_budget`, `note`, `status`, `fund_id`, `created_at`, `updated_at`) VALUES
(62, 'dsfsdf', '2025-03-11', '2025-03-13', '2000', 20000, 0, NULL, 2, 36, '2025-03-11 10:21:08', '2025-03-11 10:21:08'),
(63, 'fdsfdsfsdfsd', '2025-03-11', '2025-03-13', '2000', 200000, 0, NULL, 3, 36, '2025-03-11 10:23:02', '2025-03-11 10:23:02'),
(64, 'sfdsfds', '2025-03-11', '2025-03-11', '2003', 12314324, 0, 'dsfdsf', 3, 36, '2025-03-11 10:26:53', '2025-03-11 10:26:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `research_projects`
--
ALTER TABLE `research_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_id` (`fund_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `research_projects`
--
ALTER TABLE `research_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
