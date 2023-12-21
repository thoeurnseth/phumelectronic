
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_layerslider`
--

CREATE TABLE `ph0m31e_layerslider` (
  `id` int(10) NOT NULL,
  `group_id` int(10) DEFAULT NULL,
  `author` int(10) NOT NULL DEFAULT 0,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_c` int(10) NOT NULL,
  `date_m` int(10) NOT NULL,
  `schedule_start` int(10) NOT NULL DEFAULT 0,
  `schedule_end` int(10) NOT NULL DEFAULT 0,
  `flag_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `flag_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `flag_popup` tinyint(1) NOT NULL DEFAULT 0,
  `flag_group` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
