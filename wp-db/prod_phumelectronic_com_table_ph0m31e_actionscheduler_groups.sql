
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_actionscheduler_groups`
--

CREATE TABLE `ph0m31e_actionscheduler_groups` (
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_actionscheduler_groups`
--

INSERT INTO `ph0m31e_actionscheduler_groups` (`group_id`, `slug`) VALUES
(1, 'action-scheduler-migration'),
(2, 'woocommerce-db-updates'),
(3, 'wc-admin-data'),
(4, 'facebook-for-woocommerce'),
(5, 'wp_mail_smtp');
