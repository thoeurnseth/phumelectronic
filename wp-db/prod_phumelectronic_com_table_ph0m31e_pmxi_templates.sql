
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_templates`
--

CREATE TABLE `ph0m31e_pmxi_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `options` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `scheduled` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `title` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_keep_linebreaks` tinyint(1) NOT NULL DEFAULT 0,
  `is_leave_html` tinyint(1) NOT NULL DEFAULT 0,
  `fix_characters` tinyint(1) NOT NULL DEFAULT 0,
  `meta` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
