
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_images`
--

CREATE TABLE `ph0m31e_pmxi_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(600) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `image_filename` varchar(600) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
