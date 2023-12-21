
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_woocommerce_api_keys`
--

CREATE TABLE `ph0m31e_woocommerce_api_keys` (
  `key_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_key` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_secret` char(43) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nonces` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `truncated_key` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_access` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_woocommerce_api_keys`
--

INSERT INTO `ph0m31e_woocommerce_api_keys` (`key_id`, `user_id`, `description`, `permissions`, `consumer_key`, `consumer_secret`, `nonces`, `truncated_key`, `last_access`) VALUES
(9, 186, 'sync odoo', 'read_write', '96cf5df5f7db8a6f18a75ce4f9084bb01daf44861bc063c8c619ecdeab91114f', 'cs_f6d2e307b045b0d1ab6ace50f1207062ec0bad0e', NULL, 'cee9d5e', '2022-01-25 13:38:21'),
(10, 186, 'mobile', 'read_write', 'eefa5b24ddd0fd03a15efc24c08353a31b55773b3391362e87a482c644d1327f', 'cs_899ac51d5070a261b684797786d2b8a5cde45bf8', 'a:4:{i:1651803837;s:10:\"uvwsulyfsd\";i:1651803840;s:10:\"yoxahvdbuf\";i:1651803844;s:10:\"privnvhlcd\";i:1651803846;s:10:\"dotubyengu\";}', '7b8279d', '2022-05-24 13:45:54'),
(11, 186, 'production key', 'read_write', 'd3631c2536ef51ae6ec00f44fcf082b50fd697814a649b0850566456e03b41e4', 'cs_f6c5cc0ca335f9bb2d1bde04df4437d3fc15be8c', 'a:1:{i:1648523878;s:11:\"cVJUCLCvJIo\";}', '3ff0549', '2022-05-20 15:59:00'),
(12, 186, 'Mobile Key', 'read_write', '46e53a5d3d59d505984308ed49c39cb743193265cbb1ce62a9515bd29d4ab631', 'cs_b8d6b58ceff88f537f9b1ed84dc9f88f83f02a89', NULL, '63ab2bb', NULL);
