
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_yith_wcwl_lists`
--

CREATE TABLE `ph0m31e_yith_wcwl_lists` (
  `ID` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `wishlist_slug` varchar(200) NOT NULL,
  `wishlist_name` text DEFAULT NULL,
  `wishlist_token` varchar(64) NOT NULL,
  `wishlist_privacy` tinyint(1) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `dateadded` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiration` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ph0m31e_yith_wcwl_lists`
--

INSERT INTO `ph0m31e_yith_wcwl_lists` (`ID`, `user_id`, `session_id`, `wishlist_slug`, `wishlist_name`, `wishlist_token`, `wishlist_privacy`, `is_default`, `dateadded`, `expiration`) VALUES
(1, 326, NULL, '', NULL, '', 0, 1, '2022-03-17 19:03:50', NULL),
(23, 194, NULL, '', '', 'MT4UNJ2PS2ON', 0, 1, '2022-03-31 21:04:56', NULL),
(30, 332, NULL, '', '', 'QD46RDBZBD1O', 0, 1, '2022-04-03 20:30:33', NULL),
(31, 190, NULL, '', '', '5QQXEYXWC7R0', 0, 1, '2022-04-03 21:02:42', NULL),
(47, 308, NULL, '', NULL, '6253db9c14d44', 0, 1, '2022-04-10 23:04:16', NULL),
(48, 186, NULL, '', NULL, '6253dc3513d80', 0, 1, '2022-04-10 23:04:49', NULL),
(49, 320, NULL, '', NULL, '626130b40c59d', 0, 1, '2022-04-21 02:04:48', NULL),
(50, 382, NULL, '', NULL, '62620997b41d2', 0, 1, '2022-04-21 17:04:11', NULL),
(51, NULL, 'f004eb1192bad563477714cc66115a5d', '', '', 'JOOYVTM5P9VN', 0, 1, '2022-04-21 19:52:51', '2022-05-22 02:52:51'),
(52, NULL, '0100d88ec3034ab5328073f555626ff0', '', '', '1YZ8NJ2KTYDN', 0, 1, '2022-04-28 01:16:42', '2022-05-28 08:16:42'),
(53, NULL, '1f93cd2fee500111739a99c196426bc7', '', '', 'PWQA0BSZ1JA8', 0, 1, '2022-04-28 01:16:43', '2022-05-28 08:16:43'),
(54, NULL, '5bc723a2b0b241f8a34d3e7776122a16', '', '', 'MSDW6X2AM8Y6', 0, 1, '2022-04-28 01:16:46', '2022-05-28 08:16:46'),
(55, NULL, 'f6702584523157e6ccec14c01d1c51b8', '', '', 'NROTR2VMRVXS', 0, 1, '2022-04-28 01:16:47', '2022-05-28 08:16:47'),
(56, NULL, '1023c23374054073d8a9f22086dccf06', '', '', 'GNUAYXJ9KE4A', 0, 1, '2022-04-28 01:16:48', '2022-05-28 08:16:48'),
(57, 398, NULL, '', NULL, '6279cac630b8a', 0, 1, '2022-05-09 18:05:34', NULL),
(58, 383, NULL, '', NULL, '62810ede46e35', 0, 1, '2022-05-15 06:05:58', NULL);
