
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_wc_category_lookup`
--

CREATE TABLE `ph0m31e_wc_category_lookup` (
  `category_tree_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_wc_category_lookup`
--

INSERT INTO `ph0m31e_wc_category_lookup` (`category_tree_id`, `category_id`) VALUES
(15, 15),
(37, 37),
(39, 39),
(39, 69),
(39, 70),
(40, 40),
(42, 42),
(45, 45),
(46, 46),
(50, 50),
(51, 51),
(52, 52),
(53, 53),
(55, 55),
(60, 60),
(61, 61),
(62, 62),
(66, 66),
(69, 69),
(70, 70),
(2175, 2175);
