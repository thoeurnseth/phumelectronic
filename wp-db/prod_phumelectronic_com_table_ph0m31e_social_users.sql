
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_social_users`
--

CREATE TABLE `ph0m31e_social_users` (
  `ID` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `register_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `social_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_social_users`
--

INSERT INTO `ph0m31e_social_users` (`ID`, `type`, `identifier`, `register_date`, `login_date`, `link_date`, `social_users_id`) VALUES
(2, 'fb', '2833751656938365', '0000-00-00 00:00:00', '2021-03-22 13:16:23', '2021-02-22 16:07:10', 4),
(186, 'fb', '118878690257033', '2021-09-03 15:38:34', '2021-09-03 16:16:22', '2021-09-03 15:38:34', 28),
(186, 'google', '101994796984237173378', '0000-00-00 00:00:00', '2022-02-03 16:08:27', '2021-11-29 15:11:22', 45),
(306, 'google', '117079530613699571547', '0000-00-00 00:00:00', '2022-02-25 17:47:02', '2022-02-25 17:47:02', 55),
(331, 'google', '118223558827926860312', '0000-00-00 00:00:00', '2022-04-01 17:09:46', '2022-03-07 10:20:28', 56),
(326, 'fb', '3053872834940400', '0000-00-00 00:00:00', '2022-04-12 13:34:38', '2022-03-14 12:16:51', 57),
(332, 'google', '101704080322224328352', '0000-00-00 00:00:00', '2022-04-04 10:30:14', '2022-03-17 14:17:57', 58),
(320, 'fb', '2079297528913226', '0000-00-00 00:00:00', '2022-05-20 08:11:13', '2022-03-19 09:24:56', 59),
(346, 'fb', '32202205731776', '2022-03-23 15:30:53', '2022-03-23 15:30:54', '2022-03-23 15:30:53', 60),
(336, 'google', '110047589152935552720', '0000-00-00 00:00:00', '2022-04-04 09:43:58', '2022-04-01 10:48:43', 61),
(386, 'fb', '1388384968247233', '0000-00-00 00:00:00', '2022-05-02 16:54:12', '2022-05-02 14:57:22', 62);
