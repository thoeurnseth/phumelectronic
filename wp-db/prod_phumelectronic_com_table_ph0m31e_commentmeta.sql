
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_commentmeta`
--

CREATE TABLE `ph0m31e_commentmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_commentmeta`
--

INSERT INTO `ph0m31e_commentmeta` (`meta_id`, `comment_id`, `meta_key`, `meta_value`) VALUES
(5, 242, 'rating', '5'),
(6, 243, 'rating', '5'),
(7, 242, 'verified', '0'),
(8, 243, 'verified', '0'),
(9, 363, 'rating', '4'),
(10, 363, 'verified', '0'),
(11, 369, 'rating', '5'),
(12, 372, 'rating', '3'),
(13, 372, 'verified', '0'),
(14, 369, 'verified', '0'),
(15, 406, 'rating', '5'),
(16, 406, 'verified', '0'),
(17, 454, 'rating', '5'),
(18, 454, 'verified', '0'),
(19, 455, 'rating', '5'),
(20, 455, 'verified', '0'),
(21, 456, 'rating', '4'),
(22, 456, 'verified', '0');
