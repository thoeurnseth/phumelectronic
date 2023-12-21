
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_wc_order_coupon_lookup`
--

CREATE TABLE `ph0m31e_wc_order_coupon_lookup` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_amount` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_wc_order_coupon_lookup`
--

INSERT INTO `ph0m31e_wc_order_coupon_lookup` (`order_id`, `coupon_id`, `date_created`, `discount_amount`) VALUES
(10252, 1785, '2022-02-09 14:19:45', 30),
(12119, 12111, '2022-02-28 11:34:21', 14.9),
(12124, 1785, '2022-03-02 09:05:47', 79.5);
