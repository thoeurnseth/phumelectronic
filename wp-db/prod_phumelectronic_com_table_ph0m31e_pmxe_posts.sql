
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxe_posts`
--

CREATE TABLE `ph0m31e_pmxe_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `export_id` bigint(20) UNSIGNED NOT NULL,
  `iteration` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
