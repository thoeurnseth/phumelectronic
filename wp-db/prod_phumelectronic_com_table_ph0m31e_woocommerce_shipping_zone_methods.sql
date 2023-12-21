
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_woocommerce_shipping_zone_methods`
--

CREATE TABLE `ph0m31e_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `instance_id` bigint(20) UNSIGNED NOT NULL,
  `method_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_order` bigint(20) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_woocommerce_shipping_zone_methods`
--

INSERT INTO `ph0m31e_woocommerce_shipping_zone_methods` (`zone_id`, `instance_id`, `method_id`, `method_order`, `is_enabled`) VALUES
(0, 2, 'free_shipping', 1, 1),
(0, 3, 'free_shipping', 2, 1);
