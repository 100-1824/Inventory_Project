-- SQLite to MySQL Export
-- Generated: 2026-01-21 16:59:05

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `migration` VARCHAR(255) NOT NULL, `batch` INT NOT NULL);

-- Dumping data for table `migrations`

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_01_18_103505_create_products_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_01_18_153902_add_rack_number_to_products_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_01_18_154129_create_sales_tables', '4');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `name` VARCHAR(255) NOT NULL, `email` VARCHAR(255) NOT NULL, `email_verified_at` DATETIME, `password` VARCHAR(255) NOT NULL, `remember_token` VARCHAR(255), `created_at` DATETIME, `updated_at` DATETIME);

-- --------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (`email` VARCHAR(255) NOT NULL, `token` VARCHAR(255) NOT NULL, `created_at` DATETIME, primary key (`email`));

-- --------------------------------------------------------
-- Table structure for table `sessions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (`id` VARCHAR(255) NOT NULL, `user_id` INT, `ip_address` VARCHAR(255), `user_agent` TEXT, `payload` TEXT NOT NULL, `last_activity` INT NOT NULL, primary key (`id`));

-- Dumping data for table `sessions`

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('3pYKfHsuSCI2saPTnTdxpKnUOwzVFMHcwosMAgZq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZENBQTYxT2psU0xQZzluZkROZ0pYc3dZZTlqQ2NabDg2QTF2YmlnZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1768745499');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('73oDbLuXnsINqVyWnUzAzPbQHs6NvMMI91uZ5XHQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzZVbDB3ZE0xQW1Dald2UGZ2WjdwNmQ5REtTWkh4cEFRNGV1MWIwTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1768752117');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('arZfxOvFdr1MTOOLbMw85esQxL6r1g2qsDR1nRU8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaUpIMk5FWW5Kcm9JSUkyN3BTNWxIakFPa2JlQW5RcEZSZms1U1I3eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1768748924');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('B54uzq97xHNoXVJrMplOZkFr1WXGAVBjUhmiFalT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicjlsY0V1R1FCRFhWWFNwYllNcUR2S2pLSkQySFA2YmFtRlFlaW1jRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1769011827');

-- --------------------------------------------------------
-- Table structure for table `cache`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (`key` VARCHAR(255) NOT NULL, `value` TEXT NOT NULL, `expiration` INT NOT NULL, primary key (`key`));

-- --------------------------------------------------------
-- Table structure for table `cache_locks`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (`key` VARCHAR(255) NOT NULL, `owner` VARCHAR(255) NOT NULL, `expiration` INT NOT NULL, primary key (`key`));

-- --------------------------------------------------------
-- Table structure for table `jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `queue` VARCHAR(255) NOT NULL, `payload` TEXT NOT NULL, `attempts` INT NOT NULL, `reserved_at` INT, `available_at` INT NOT NULL, `created_at` INT NOT NULL);

-- --------------------------------------------------------
-- Table structure for table `job_batches`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (`id` VARCHAR(255) NOT NULL, `name` VARCHAR(255) NOT NULL, `total_jobs` INT NOT NULL, `pending_jobs` INT NOT NULL, `failed_jobs` INT NOT NULL, `failed_job_ids` TEXT NOT NULL, `options` TEXT, `cancelled_at` INT, `created_at` INT NOT NULL, `finished_at` INT, primary key (`id`));

-- --------------------------------------------------------
-- Table structure for table `failed_jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `uuid` VARCHAR(255) NOT NULL, `connection` TEXT NOT NULL, `queue` TEXT NOT NULL, `payload` TEXT NOT NULL, `exception` TEXT NOT NULL, `failed_at` DATETIME NOT NULL default CURRENT_TIMESTAMP);

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `name` VARCHAR(255) NOT NULL, `sku` VARCHAR(255) NOT NULL, `description` TEXT, `category` VARCHAR(255) NOT NULL default 'Hardware', `price` DECIMAL(10,2) NOT NULL, `quantity` INT NOT NULL default '0', `unit` VARCHAR(255) NOT NULL default 'pcs', `supplier_name` VARCHAR(255), `supplier_contact` VARCHAR(255), `supplier_address` VARCHAR(255), `created_at` DATETIME, `updated_at` DATETIME, `rack_number` VARCHAR(255));

-- --------------------------------------------------------
-- Table structure for table `sales`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `invoice_no` VARCHAR(255) NOT NULL, `total_amount` DECIMAL(10,2) NOT NULL, `created_at` DATETIME, `updated_at` DATETIME);

-- --------------------------------------------------------
-- Table structure for table `sale_items`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items` (`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `sale_id` INT NOT NULL, `product_id` INT NOT NULL, `product_name` VARCHAR(255) NOT NULL, `quantity` INT NOT NULL, `price` DECIMAL(10,2) NOT NULL, `total` DECIMAL(10,2) NOT NULL, `created_at` DATETIME, `updated_at` DATETIME, FOREIGN KEY(`sale_id`) REFERENCES `sales`(`id`) ON DELETE CASCADE, FOREIGN KEY(`product_id`) REFERENCES `products`(`id`));

SET FOREIGN_KEY_CHECKS=1;
