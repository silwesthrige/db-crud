-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 12:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lara_crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `event1`
--

CREATE TABLE `event1` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(50) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `event_date` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event1`
--

INSERT INTO `event1` (`id`, `user_id`, `name`, `description`, `priority`, `event_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(21, NULL, 'coursework', 'DMW', 'High', '2025-09-04', '2025-08-31 11:36:43', '2025-09-06 09:12:08', NULL),
(24, NULL, 'Mahela Dissanayaka', 'ASDFG', 'Low', '2025-08-09', '2025-08-31 12:48:57', '2025-09-06 07:50:39', NULL),
(25, NULL, 'EAD', 'EAD Course work submission', 'High', '2026-06-25', '2025-09-06 07:50:12', '2025-09-07 05:52:24', NULL),
(31, NULL, 'Test Event', 'Testing', 'Medium', '2025-09-10', '2025-09-07 13:50:46', '2025-09-07 14:20:14', '2025-09-07 14:20:14'),
(32, NULL, 'Submission', 'DMW', 'High', '2025-09-11', '2025-09-07 14:00:56', '2025-09-07 14:00:56', NULL),
(34, NULL, 'Test Event 1', 'Testing', 'Medium', '2025-09-10', '2025-09-07 14:21:32', '2025-09-07 14:21:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `int` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `priority` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, '2025_09_07_194048_add_deleted_at_to_event1_table', 2),
(6, '2025_09_07_140223_create_notifications_table', 3),
(7, '2025_09_07_200223_add_user_approval_fields_to_users_table', 4),
(8, '2025_09_07_204801_add_inactive_status_to_users_table', 5),
(9, '2025_09_07_203458_add_rejection_reason_to_users_table', 6),
(10, '2025_09_07_105452_create_event1_table', 2),
(11, '2025_09_07_220227_add_user_id_to_event1_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT 'fas fa-bell',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `action_url` varchar(255) DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `title`, `message`, `icon`, `user_id`, `is_read`, `read_at`, `data`, `action_url`, `priority`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'success', 'Welcome!', 'Welcome to the Event Management System', 'fas fa-bell', NULL, 1, '2025-09-07 14:19:26', NULL, NULL, 'medium', NULL, '2025-09-07 14:18:52', '2025-09-07 14:19:26'),
(3, 'warning', 'Maintenance', 'System maintenance scheduled for tomorrow', 'fas fa-bell', NULL, 1, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 14:18:53', '2025-09-07 14:18:53'),
(4, 'info', 'Event Updated', 'Event \'Test Event\' has been updated successfully.', 'edit', 1, 0, NULL, '{\"name\":\"Test Event\",\"description\":\"Testing\",\"priority\":\"Medium\",\"event_date\":\"2025-09-10\",\"event_id\":\"31\",\"operation\":\"update\",\"success\":true,\"timestamp\":\"2025-09-07T19:49:47.937538Z\"}', 'http://127.0.0.1:8000/events', 'medium', '2025-09-14 14:19:47', '2025-09-07 14:19:47', '2025-09-07 14:19:47'),
(5, 'warning', 'Event Deleted', 'Event \'Test Event\' has been deleted successfully.', 'trash', 1, 0, NULL, '{\"event_id\":\"31\",\"event_name\":\"Test Event\",\"operation\":\"delete\",\"success\":true,\"timestamp\":\"2025-09-07T19:50:14.761600Z\"}', 'http://127.0.0.1:8000/events', 'medium', '2025-09-14 14:20:14', '2025-09-07 14:20:14', '2025-09-07 14:20:14'),
(6, 'danger', 'Event Creation Failed', 'Failed to create event \'Test Event\'. Please try again.', 'exclamation-triangle', 1, 0, NULL, '{\"name\":\"Test Event\",\"description\":\"Testing\",\"priority\":\"Medium\",\"event_date\":\"2025-09-10\",\"operation\":\"create\",\"success\":false,\"timestamp\":\"2025-09-07T19:51:10.804773Z\"}', 'http://127.0.0.1:8000/events/create', 'high', '2025-09-14 14:21:10', '2025-09-07 14:21:10', '2025-09-07 14:21:10'),
(7, 'success', 'Event Created', 'Event \'Test Event 1\' has been created successfully.', 'calendar-plus', 1, 0, NULL, '{\"name\":\"Test Event 1\",\"description\":\"Testing\",\"priority\":\"Medium\",\"event_date\":\"2025-09-11\",\"operation\":\"create\",\"success\":true,\"timestamp\":\"2025-09-07T19:51:32.155701Z\"}', 'http://127.0.0.1:8000/events', 'medium', '2025-09-14 14:21:32', '2025-09-07 14:21:32', '2025-09-07 14:21:32'),
(8, 'info', 'Event Updated', 'Event \'Test Event 1\' has been updated successfully.', 'edit', 1, 0, NULL, '{\"name\":\"Test Event 1\",\"description\":\"Testing\",\"priority\":\"Medium\",\"event_date\":\"2025-09-10\",\"event_id\":\"34\",\"operation\":\"update\",\"success\":true,\"timestamp\":\"2025-09-07T19:51:50.476745Z\"}', 'http://127.0.0.1:8000/events', 'medium', '2025-09-14 14:21:50', '2025-09-07 14:21:50', '2025-09-07 14:21:50'),
(9, 'info', 'New User Registration', 'A new user \'Tester\' has registered and is waiting for approval.', 'fas fa-bell', NULL, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 14:49:59', '2025-09-07 14:49:59'),
(10, 'success', 'Account Approved', 'Your account has been approved by the administrator. You can now access all features.', 'fas fa-bell', 9, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 14:50:51', '2025-09-07 14:50:51'),
(11, 'success', 'Account Approved', 'Your account has been approved by the administrator. You can now access all features.', 'fas fa-bell', 1, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 14:54:16', '2025-09-07 14:54:16'),
(13, 'success', 'Account Status Changed', 'Your account has been activated by the administrator.', 'fas fa-bell', 8, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 15:30:29', '2025-09-07 15:30:29'),
(14, 'warning', 'Account Status Changed', 'Your account has been deactivated by the administrator.', 'fas fa-bell', 8, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 15:30:39', '2025-09-07 15:30:39'),
(15, 'danger', 'Account Rejected', 'Your account has been rejected. Reason: Rejected', 'fas fa-bell', 5, 0, NULL, NULL, NULL, 'medium', NULL, '2025-09-07 16:20:39', '2025-09-07 16:20:39');

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
('1hikseipppVpy4VSw2mZ0qndCFh1V6L0RpBUO8Qa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSE53RFNLazF1VmxKMlFpTVNocE5zSzFFZlhjQXlReHg4QndjRkFpRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvZXZlbnRzL21vbnRobHktdHJlbmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274824),
('2cgpibnPm5oD0FUn4Y8off4LKdy1CPslh3yvb6Rb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakRMOElmSjZ2NGVXaFVzeElJZE9IQ1Y0TWxOeXhLYmJLZGJkeVU5RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757278636),
('2XojKlKDIx7100lCgyS53h9d3nWj9NnSp3aNu3eX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNmpNcHpyM0FBQmRlMEJuZGM4Ymg5Nlowa0h1Z21FdjlxMWdPZEJociI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275062),
('4zh8J5EEhltOBFPDEO608K8fUexLFtsRZunulDaA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiM1g5aXNjaDByNVVVamYwZW1LSUxzTE8xYUtwRjFmVzNXb1dwNU15QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274972),
('5F3QAi3x6J5bCUXvSQJ0xc3629ky7gHPW8v9HnWs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHdqQ0J0emJUQWJvdFdqUmVteFNZYUNXaU5kbTg2U0VZNVUzdGJaNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277961),
('75jcnhAnkbQUlTyst1kE6tWClwZ5xCzPW3TDN8av', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNDJtdTEwbW8zTnhPdnYyZ1hNS0dSeEVXNVpmek14SklZNmQwMXVBZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274806),
('7FSYwjiZ4CCcXGqbVem4nFhZYtGYUZDviENSUXkA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS21WaG1aRmpEN05rZXJsRzVJTmtsRUFyTWxHaGh3SnRWbHZhMFJMViI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzYxMjc4NDUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzYxMjc4NDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757276129),
('8nGbYVW3fYnHcs1BGZyscb2ZlaEzVzXMT5vcaxzy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUlZ3eU1JWURjREVtTVFvOVhqVlZsQ2FOZmJkbzBCSk1zRFhzM1NrOCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg2MzU3NDEiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg2MzU3NDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757278636),
('8WYOD75XPGCH7c5ILDPFyohb8NzsDlOtwWcEY42s', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnNCWHNJSDk2QXFRY0JuaVI4TXZCcmpSV0t0cmJxYXJnck1XTFhvQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ldmVudHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274805),
('A88tfeSO8bJEEqn7sP6KoiXwj2eaM62sjBZHL2pB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXNsRWxDdVo4cGllWVJuekx6TVBuSDNYa3E2UG96VkgxbWJnVFZ0UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277963),
('BKEjUDfEPGDpuMpu84iK0iIM99FgDnNfAJInMMOe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWXUzUmZ1bUtEWHY0R2FVTDlDcEcwTzZPYlVUR2xjdTV1bUZVY3VocSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275182),
('BKiXiZQK1ZJYeaCEu21WwIiox5WqRDdxW4PQJKsR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3ZTQmM5QzJxcnR0dzBFd2pTR0hZSHNDaHBZV2llMmRqdm9MRDRJUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757279234),
('C5dWa2bvtBq3eJp2ZTl31GImcb4qPIsxm2TmTQ6L', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWm1UWnAyYWpLTFd6dEJ6MEJ6dXh5VWhYdkNUOW5TUGxYMzhjUWNoYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277958),
('CBHLyo3c0cn3qlSnzYMjpeDntZM89sUcRsYj21XE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid2duUzd5ZDBZMnZSZDdCc3pIbkxtQzBqN3JoQmk5aVpVZkdCVG5ndCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275122),
('cGPXG1aW8bRhsTNe1oBBcmfOxanSfM92Gxmu2nFa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1hVUmplb01rSkgydmo1bERIdmttREh1SHY3RnVtN0VpM1dZTGlmNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757276193),
('CQQDW5kqHUI4W20pQoM8luAvBhqEClZlDWf6GN7N', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieGxiS3NqckJocE5hb2h5WndvMG5BbXF5d2ZkMHFEdVVhQkpsYVlTYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo5NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwLz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzc4MTA1MTgiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo5NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwLz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzc4MTA1MTgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757277810),
('cuNia9fqx2RcvEVQ5HSQMyUNTDylcJ8Xr7nyAThF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVXBtNjNhN2NLaXVqdlJIZUR2dWt0WnJpdk40d01jZXZLUmxLMzRmbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg3NDUwNzQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg3NDUwNzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757278745),
('D2poIQvdZ1zU7DPYv6t4Uo8NJp9NYyCQ7vt1363l', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiOHJYWDFWOWw2SXVoZ01NRVk0d3B6WmRQbENiaENMZklBYUpkaXFpUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275152),
('dcrj3qMseVQdbs4Es4z5NjjsDCTZyGWcua0M8MJD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYWo3aGNkU2IzWlVaVFdXY0s3WHhXR0hEWFRUa1RlZUpMV1VtNmF6cSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274852),
('DdiWiPp8mGXuF67Yzfo5JomrUBH3zbJ0sa5w2jem', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZ1plckFwZFRldmpUWUJjaUdTQkhPS2hqUjRNbWlOOTlnZ2laYk5qNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274775),
('e0yQwYFI1GW1Wm9ii9i7gsXKtuewEYMd5tDoiv2d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY21rUVN0c2V5YXc5Y1NvSTZCVnBzTWN4eHd4bmhYUGMyTG1GeXY0USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757278745),
('EOnWQCtFXnoX7roxGAkz3bkWTQpJKoeEwju4Ls80', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibzByU1Qyd2pFemRIenpJdzRhREdNTEd2WGhNTjJld1F2UmEyYnhVciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycy81Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1757284470),
('fe3NkK3IXc8XL4sq2ucy5DYmmHf0t4zpBhNmN0Gv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicWtaWnNUbmp6NzNKdzFsM2trTmxUc1Y3aWhmNm94NE5Jb2M0R3Z1QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274912),
('go5kiAIyxYjUKjYlfafy9ecIkXg5IwBBQNQyV60P', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiakhPOVBVbkVvcG1rdkZOdkpqeDlVUUE1YnNiUTl5MzVRb3NPRFNSUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274812),
('HXu4BX676G8s3pKN1M8GzYed6ihyEtdUYK9jB4CJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialcxSXVHRFNoN24zekdOQVRkV00yVjU2UGpBQVJjNzIxdDduWEsxZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ldmVudHMvY2FsZW5kYXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274803),
('hyX5213ifi8cnaNagI0C8S1jq5Xfqt7C4PH5h6eG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiS2FGd2Vkb1ZnenBYZ3pVQVNwOGZjUHVCUHhhRjdQTU1xclJTN0kzTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757278859),
('J1VnPRBzNk7igmljSk2wtcV5ROO61clOGhCOwjek', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2JaSHNTRURERVROVURSM0F4eUdZM2tCRkk2ZnQ0MEg3Tjh3d3FxZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9M2M0ODI4MTgtYzZkMy00YzFhLTg1M2QtNTVlZjIwNjFlOTU5JnZzY29kZUJyb3dzZXJSZXFJZD0xNzU3Mjc2MDM3NTYyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757276038),
('j1VSB1JSuGLvB3AHF3o4A6gNZgOdATwjVd9vJEJC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVlF5MWg3M0hZdHdXeUhyRVVBRzRyaWVvU2hWaWc5d2hrcW9zNHMwQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274882),
('j8MbUlhDfNnrLyckbkxBX9gV2uz5jQdF27ohnXjg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiek1henp0SHZuT2VnZHlaY20yc1JLbU1lb1psWlQ4dTFyN1hpR0Y0UyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274803),
('JeNfSABLJu3rhIEEtaNISvSrjSgR9Mzhn0syBbMY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3FjenVHWTVlbXhFZEtuaW4ycXF4TFFVeWhmb0VhWmxLMno5djJtTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ldmVudHMvY2FsZW5kYXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274811),
('kja5iC0UpYvzV5J97scYn39YrxPBLQAEFP6ICJae', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEk3Sks3ZmpqS3hSdVJWcmpDZk90RFhpR2ptT1VKWWpDeXVwTXpFaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757274821),
('kx9CA8AahagGsu2GW6iWDLRLFjRIjTb4ayOKKaKE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXhVSDA5cFl2YzVieUx6dFRPWnoyRE02S1Q1aVY0QlA2blVSYm1WYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvZXZlbnRzL2FjdGl2aXR5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274824),
('l2RiRtsbH2JKsLmiTsi2Hsi47QMR59Yb9Y0jq6al', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZ0xOa3hSNThMZnFubkRUWUU5RXRlWWJDNEtsQnpYOWh0R0FKMlRUdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274822),
('lb9fFs6D4Jh4sINmjIqgucKGQ705nfqWgsL0x8Yp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZXp5WkZEY0tNWDNPZ3E1d3lKbFNqUXl1YnVPbmRpbnA1MHdIM0VzSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757279077),
('lFVVXngKfmt232k6nYqI2T4jpWD6haBnHIVNARKX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQjVMSWk1NGEzUk5SVnhnZE1kcG4zT09keThxS2NITjZBREROaENlUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275002),
('lHvTzsMMhuUY9mTLWHIsgEZtl4J3fsDSCMbtLwLr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieWV3bUpVMWFybURMVlVNbmQ0UDlSZ0lDb2gwRkdPdHZVRjAxRW1NRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757278869),
('MigtaNpbTYnAQieFq7IULm48VrXYTcwRTHyeyGHr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWd5NFdvelRjZjZ6RU5RUkRKbkVxMzVNQ2FsdktWUFR2MU12UjhZMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757279193),
('NyTvBYtKdTmWTnqsZD6R7tpdVYD2kJKAnD9lFGsb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGhlN05NalZEa3kxR2MxVVh1NjNwRjZUMXFNNjhDT0ZjTndtVVFvRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0xZTNhNmU1Zi00YmNhLTRhMjktYjg4Ni0zZTdkZTQzZDhlZjEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzkyMzMyNDIiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0xZTNhNmU1Zi00YmNhLTRhMjktYjg4Ni0zZTdkZTQzZDhlZjEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzkyMzMyNDIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757279233),
('pZoyivwZNj55f7ZEV9waflHdSnoQkJyOUj8u8alh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0MwU2ppSU03SDlGQldSbFdYWVo4Qm1Yc1FUU2pPQWUyWGJWcUtBMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTIwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZXZlbnRzL2NhbGVuZGFyLWRhdGE/ZW5kPTIwMjUtMTAtMTJUMDAlM0EwMCUzQTAwJTJCMDUlM0EzMCZzdGFydD0yMDI1LTA4LTMxVDAwJTNBMDAlM0EwMCUyQjA1JTNBMzAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274804),
('qWb0yWcuQ8mNWYHlBegQgGbFQR84Br6gu5mVPrtI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXJOTk5iTkUyNGU4ZWNrdGEzTFBzbkppVGxNY2FQSmhPa1lRRWM4SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757276130),
('qX90PN2YxSE3072hibvSSWdHaQO92edbGmvkzVxz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM05VYmRpRFR1VjdaMlFhc3Nid2hnUkpHZmtmVG5zMURjYmxkdzZJMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277965),
('RXOXE8IN3R43BG1QSTzypG3saeLlTB2fuLu4qa4e', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieDg2b05WTlB5bVZGbnFVU00wVlN2TGM4YjVwdHNJc2M5NmxYWGRQYSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzc5NDcyNDQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzc5NDcyNDQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757277947),
('SKG6tE7x4VMnkUVX7JWJKQKP4yXInhtdw7oZXiLw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGZKcVpFNEdTaG9hS0Rub21vRllxU1Jub3E1dTViQlBHS2NjNnRTdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757278685),
('uaOSjjTNu7Rk1z3XVCun7byKyPvCIfCdm3Xr13AU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXFUb2FOa1lMMVlXOWRLcmZ4SHpGNWhzOHR1VWJRdnE3UVZXQW1YTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvZXZlbnRzL3RpbWVsaW5lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274823),
('v71NBj3QfBEJgEztgpNYdl3g9q5c0HLeYz4Lkqse', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYU5uZEIwc1NaNGRBUU9GcWtJNm9MaDdybXNhQjB4ZnBFWHd6UXZsQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0xZTNhNmU1Zi00YmNhLTRhMjktYjg4Ni0zZTdkZTQzZDhlZjEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzkxOTI1NTIiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0xZTNhNmU1Zi00YmNhLTRhMjktYjg4Ni0zZTdkZTQzZDhlZjEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzkxOTI1NTIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757279192),
('VajazvnhJSTiix0UIunj9DZT6g5tF9Ijo0GeLwyY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicGswZVZjZ1RSNVpGZFB6Tk9PNEhrY0llcUx1M2FEckxrYXNQMlQ2ZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzYxOTI4NTgiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzYxOTI4NTgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757276193),
('VejVY3PYjHtQjnD64KYOCUZLBLBuXq6S6ngEui2e', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUXdHeUEyVXo2d0UzRE1QTXpsQ2t0SFpnQ3lFbzR0dmljRkcwZ2tLNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757274942),
('vNEcnfyW102K1mQjW7YBgtLI1USsZz4NK4NhRO9m', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSWhEUkRkSlNzcVhqWlMzajlrM2FOVnR3NG1YY0QxRThLaXpmMVZXSSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg2ODQwNDEiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycz9pZD0zYzQ4MjgxOC1jNmQzLTRjMWEtODUzZC01NWVmMjA2MWU5NTkmdnNjb2RlQnJvd3NlclJlcUlkPTE3NTcyNzg2ODQwNDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757278684),
('wQtPxi6srslI6NK8r8xlHzqIC4K5W4nCMaV28UDq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2hoSnIwZ1FjekswQUJiQVhpQU9SN2NOd3UyRmpSdDJCRXJnUmY3RCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277811),
('wtua24vHGrxV5UZG9EJrKg1t6DoyhnvfqEx90dVS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVA2bVpnZFFockp5cTlhc2d3dWZJZjU3WlVrWXNTMnJXYXVadjUzNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvcmVnaXN0ZXI/aWQ9M2M0ODI4MTgtYzZkMy00YzFhLTg1M2QtNTVlZjIwNjFlOTU5JnZzY29kZUJyb3dzZXJSZXFJZD0xNzU3Mjc2MDYwODEwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757276061),
('WWPYGZoOAtyZLrNdDOY2URsZwhG2oxQGXUqysMnP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiam5OUjdWSlV6UktQSWhkUWVKN016d0hXYjdBN0lTeUdoMXFqZkZJUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTIwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZXZlbnRzL2NhbGVuZGFyLWRhdGE/ZW5kPTIwMjUtMTAtMTJUMDAlM0EwMCUzQTAwJTJCMDUlM0EzMCZzdGFydD0yMDI1LTA4LTMxVDAwJTNBMDAlM0EwMCUyQjA1JTNBMzAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757274813),
('xJ2xF7ifuau1go2c9X6e9YseDiIQJM8OOxcw3u0I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieXlGaTBKNGZRcVFlM3lNSktxY3hwVzhjdll2UzFBemVIazhwN09tUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275092),
('xNFbunxORJViAFUOjFoCuQUe4ZC78Rmm1SdyIKbE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjFPRkozVXc5ZHdLQkNuTk9lS3RHNlBqRVh6UzMxbG1ieGg4MVM1bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757276044),
('Yk59bqB2rI7syksZs0SfiIIVikhyJmRWJM0bnLj4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXZLQnE2TGpIMTR3cHN0NjBtWnRwaG9nM21xc1RHZmQ0NU9vWDl1cCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757277947),
('zJkVZovnmkiAIBml7wT7rr0O0AQBsSaLg1cQ9TSC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVWVCbnRuMnpKdWFDV3BlcEpxMTdaWXk1UnRNNWtiODBadXNQZ3d3eCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757275032);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `status` enum('pending','approved','rejected','inactive') DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `status`, `approved_at`, `approved_by`, `rejection_reason`, `phone`, `bio`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', 'user', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$kiS6cMixQSq0dBtzPLIhNO8An0DRPG7Gaz2k6iF1ze9EcEpY63PgS', NULL, '2025-09-07 08:39:32', '2025-09-07 14:54:16'),
(2, 'Test User', 'test@example.com', 'user', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 08:40:31', '$2y$12$NY749X4ixqzaUXiFsLBKHOuVz/kNmwXXhNtNwSS8V0Aa04Uy6ydO2', 'MaLjWFKXYy', '2025-09-07 08:40:31', '2025-09-07 15:40:19'),
(4, 'System Administrator', 'admin@eventmanager.com', 'admin', 'approved', '2025-09-07 14:35:07', NULL, NULL, '+1234567890', 'System Administrator with full access to manage users and events.', NULL, '2025-09-07 14:35:07', '$2y$12$.eonoN3a/nrFhgVLRJaYkO42XmMe2c1ogHNqkVUh1i1JjcAS/1ky6', NULL, '2025-09-07 14:35:07', '2025-09-07 15:42:21'),
(5, 'John Doe', 'john.doe@example.com', 'user', 'rejected', NULL, 4, 'Rejected', '+1-555-0123', 'Software Developer with 5 years of experience in web development.', NULL, NULL, '$2y$12$Ia97DQf/WdSoHE3CwnUpOuyH37J1qUkdBpcWYvZjs3rNU0jZ2wEw2', NULL, '2025-09-07 14:45:04', '2025-09-07 16:20:39'),
(6, 'Jane Smith', 'jane.smith@example.com', 'user', 'pending', NULL, NULL, NULL, '+1-555-0124', 'Project Manager specializing in event coordination.', NULL, NULL, '$2y$12$.v7QPUCkPbeNh8r7UH1fVu5EHO9oIDpdHxOVUi3Ms5F30bL3x9WwO', NULL, '2025-09-07 14:45:04', '2025-09-07 14:45:04'),
(8, 'Sarah Wilson', 'sarah.wilson@example.com', 'user', 'inactive', '2025-09-07 15:30:29', 4, 'Insufficient information provided during registration.', '+1-555-0126', 'New graduate looking for opportunities.', NULL, NULL, '$2y$12$ma9e66LScpkiSBTV07iJZO35Ysb0nD6ZwnvYfb3uDeEE3zfyCzHlO', NULL, '2025-09-07 14:45:05', '2025-09-07 15:30:39'),
(9, 'Tester', 'tester1@gmail.com', 'user', 'approved', NULL, NULL, NULL, '+711234567', 'Tester', NULL, NULL, '$2y$12$EuGDnVy3QBDsvFpl0kVONetoc5KmKphpsDa2NXyfPIcDiAyikj/q6', NULL, '2025-09-07 14:49:59', '2025-09-07 14:50:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `event1`
--
ALTER TABLE `event1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`name`),
  ADD KEY `event1_user_id_foreign` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`int`),
  ADD UNIQUE KEY `event_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `notifications_created_at_index` (`created_at`),
  ADD KEY `notifications_type_index` (`type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_approved_by_foreign` (`approved_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event1`
--
ALTER TABLE `event1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `int` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event1`
--
ALTER TABLE `event1`
  ADD CONSTRAINT `event1_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
