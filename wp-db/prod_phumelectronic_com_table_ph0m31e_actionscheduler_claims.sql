
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_actionscheduler_claims`
--

CREATE TABLE `ph0m31e_actionscheduler_claims` (
  `claim_id` bigint(20) UNSIGNED NOT NULL,
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
