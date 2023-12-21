
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_posts`
--

CREATE TABLE `ph0m31e_pmxi_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `import_id` bigint(20) UNSIGNED NOT NULL,
  `unique_key` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `product_key` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `iteration` bigint(20) NOT NULL DEFAULT 0,
  `specified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
