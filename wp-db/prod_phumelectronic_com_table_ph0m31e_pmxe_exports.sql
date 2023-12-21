
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxe_exports`
--

CREATE TABLE `ph0m31e_pmxe_exports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT 0,
  `attch_id` bigint(20) NOT NULL DEFAULT 0,
  `options` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `scheduled` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `registered_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `friendly_name` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `exported` bigint(20) NOT NULL DEFAULT 0,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `canceled_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_update_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `processing` tinyint(1) NOT NULL DEFAULT 0,
  `executing` tinyint(1) NOT NULL DEFAULT 0,
  `triggered` tinyint(1) NOT NULL DEFAULT 0,
  `iteration` bigint(20) NOT NULL DEFAULT 0,
  `export_post_type` text COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
