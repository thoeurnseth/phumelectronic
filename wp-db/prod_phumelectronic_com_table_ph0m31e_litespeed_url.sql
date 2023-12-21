
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_litespeed_url`
--

CREATE TABLE `ph0m31e_litespeed_url` (
  `id` bigint(20) NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cache_tags` varchar(1000) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=COMPACT;
