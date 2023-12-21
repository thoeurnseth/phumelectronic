
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_history`
--

CREATE TABLE `ph0m31e_pmxi_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `import_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('manual','processing','trigger','continue','cli','') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `time_run` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `summary` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
