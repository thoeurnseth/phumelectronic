
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_pmxi_hash`
--

CREATE TABLE `ph0m31e_pmxi_hash` (
  `hash` binary(16) NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `import_id` smallint(5) UNSIGNED NOT NULL,
  `post_type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
