
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_imports`
--

CREATE TABLE `ph0m31e_pmxi_imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_import_id` bigint(20) NOT NULL DEFAULT 0,
  `name` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `friendly_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `feed_type` enum('xml','csv','zip','gz','') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `path` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `xpath` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `options` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `registered_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `root_element` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `processing` tinyint(1) NOT NULL DEFAULT 0,
  `executing` tinyint(1) NOT NULL DEFAULT 0,
  `triggered` tinyint(1) NOT NULL DEFAULT 0,
  `queue_chunk_number` bigint(20) NOT NULL DEFAULT 0,
  `first_import` timestamp NOT NULL DEFAULT current_timestamp(),
  `count` bigint(20) NOT NULL DEFAULT 0,
  `imported` bigint(20) NOT NULL DEFAULT 0,
  `created` bigint(20) NOT NULL DEFAULT 0,
  `updated` bigint(20) NOT NULL DEFAULT 0,
  `skipped` bigint(20) NOT NULL DEFAULT 0,
  `deleted` bigint(20) NOT NULL DEFAULT 0,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `canceled_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `failed` tinyint(1) NOT NULL DEFAULT 0,
  `failed_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_update_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `iteration` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
