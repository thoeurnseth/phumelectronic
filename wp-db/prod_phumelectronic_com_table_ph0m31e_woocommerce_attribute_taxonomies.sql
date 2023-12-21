
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_woocommerce_attribute_taxonomies`
--

CREATE TABLE `ph0m31e_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_label` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_orderby` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_woocommerce_attribute_taxonomies`
--

INSERT INTO `ph0m31e_woocommerce_attribute_taxonomies` (`attribute_id`, `attribute_name`, `attribute_label`, `attribute_type`, `attribute_orderby`, `attribute_public`) VALUES
(1, 'color', 'color', 'color', 'menu_order', 0),
(2, 'size', 'size', 'button', 'menu_order', 0),
(3, 'brands', 'Brands', 'button', 'menu_order', 0),
(4, 'options', 'Options', 'select', 'menu_order', 0),
(7, 'model', 'Model', 'button', 'menu_order', 0),
(10, 'width', '[:en]Width[:]', 'select', 'menu_order', 0);
