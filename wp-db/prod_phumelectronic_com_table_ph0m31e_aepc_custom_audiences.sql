
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_aepc_custom_audiences`
--

CREATE TABLE `ph0m31e_aepc_custom_audiences` (
  `ID` mediumint(9) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `retention` tinyint(1) UNSIGNED NOT NULL DEFAULT 14,
  `rule` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fb_id` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `approximate_count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
