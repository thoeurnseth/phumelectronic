
-- --------------------------------------------------------

--
-- Table structure for table `revo_extend_products`
--

CREATE TABLE `revo_extend_products` (
  `id` int(11) NOT NULL,
  `type` enum('special','our_best_seller','recomendation') NOT NULL DEFAULT 'special',
  `title` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `products` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_extend_products`
--

INSERT INTO `revo_extend_products` (`id`, `type`, `title`, `description`, `products`, `is_active`, `is_deleted`, `created_at`) VALUES
(1, 'special', 'Special Promotion ', 'Recommendations product for you', '[\"12625\",\"11511\",\"11359\",\"11331\",\"11328\",\"11320\"]', 1, 0, '2021-11-26 08:29:09'),
(2, 'our_best_seller', 'Our Best Selling', 'Get the Best Products for You', '[\"11459\",\"11449\",\"11451\",\"11447\"]', 1, 0, '2021-11-26 08:29:09'),
(3, 'recomendation', 'Recommendations For You ', '', '[\"12625\",\"11438\",\"11437\",\"11418\",\"11419\",\"11390\",\"11388\",\"11381\",\"11377\",\"11378\"]', 1, 0, '2021-11-26 08:29:09');
