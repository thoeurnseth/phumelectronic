
-- --------------------------------------------------------

--
-- Table structure for table `revo_list_mini_banner`
--

CREATE TABLE `revo_list_mini_banner` (
  `id` int(11) NOT NULL,
  `order_by` int(55) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `image` varchar(500) CHARACTER SET latin1 NOT NULL,
  `type` varchar(55) CHARACTER SET latin1 DEFAULT NULL,
  `is_active` int(1) DEFAULT 1,
  `is_deleted` int(1) DEFAULT 0,
  `is_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_list_mini_banner`
--

INSERT INTO `revo_list_mini_banner` (`id`, `order_by`, `product_id`, `product_name`, `image`, `type`, `is_active`, `is_deleted`, `is_type`, `created_at`) VALUES
(1, 2, 11518, 'PANA 2Door 358L, Silver REF', 'https://phumelectronic.com/wp-content/uploads/revo/1182e2197d126c24d500128eaabaf509.jpg', 'Special Promo', 1, 1, 'product', '2021-12-04 02:23:14'),
(2, 2, 11418, 'LG Front Load 8Kg Direct Drive AI Smart Inverter WM', 'https://phumelectronic.com/wp-content/uploads/revo/3d8fb885b9fa8ce8aca13a5edc9a7580.jpg', 'Special Promo', 1, 1, 'product', '2021-12-04 03:50:49'),
(3, 3, 11327, 'LG 1.0HP Dual Inverter Skin Care RAC', 'https://phumelectronic.com/wp-content/uploads/revo/5085012c74b46f3053739175c6b0ac6b.jpg', 'Special Promo', 1, 1, 'product', '2021-12-04 03:57:56'),
(4, 4, 11380, 'LG 55\" Nano Smart LTV', 'https://phumelectronic.com/wp-content/uploads/revo/da9ac298d050f129ea552ef3655a08d1.jpg', 'Special Promo', 1, 1, 'product', '2021-12-04 04:01:32'),
(5, 5, 7260, 'Replacement Filter for Puricare,All', 'http://localhost/phumelectronic/wp-content/uploads/revo/001a4a8d60083cd3effa843a65a64c05.png', 'Special Promo', 1, 1, '', '2021-12-08 02:25:11'),
(6, 5, 7261, 'UV Case', 'http://localhost/phumelectronic/wp-content/uploads/revo/001a4a8d60083cd3effa843a65a64c05.png', 'Special Promo', 1, 1, '', '2021-12-08 02:27:25'),
(7, 5, 11538, 'PANA Conventional hair Styler', 'https://phumelectronic.com/wp-content/uploads/revo/bbcc76d99b49f50664e085a28295e781.jpg', 'Special Promo', 1, 1, '', '2021-12-08 02:55:23'),
(8, 6, 11540, 'PANA Dry Iron 1000W,1.4Kg', 'https://phumelectronic.com/wp-content/uploads/revo/026f415002b4695312c29268874df94b.jpg', 'Special Promo', 1, 1, '', '2021-12-08 02:55:43'),
(9, 7, 11581, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'https://phumelectronic.com/wp-content/uploads/revo/d295ca858b6d2a8ed0e9df8d10304b03.jpg', 'Special Promo', 1, 1, '', '2021-12-15 07:37:11'),
(10, 8, 11547, 'PANA Green Vacuum 250W 3Kg', 'https://phumelectronic.com/wp-content/uploads/revo/34bea514b46accd85a6c17aca06870a4.jpg', 'Special Promo', 1, 1, '', '2021-12-15 07:38:24'),
(11, 9, 0, '', 'https://phumelectronic.com/wp-content/uploads/revo/e69b81bd12a1040b1d432a36d8d6c68d.jpg', 'Blog Banner', 1, 1, 'coupon', '2021-12-15 07:59:35'),
(12, 1, 18781, 'Washing Machine', 'https://phumelectronic.com/wp-content/uploads/revo/154e9bfae4027143a2342341f1f2ed7e.jpg', 'Blog Banner', 1, 1, 'category', '2022-04-01 10:30:24'),
(13, 5, 11309, 'KARCHER K2.360 Pressure Cleaner', 'https://phumelectronic.com/wp-content/uploads/revo/402aef71a04a2d9bea1e8f4b6c581a61.jpg', 'Love These Items', 1, 1, 'product', '2022-04-04 08:22:30'),
(14, 6, 11310, 'KARCHER Wet &amp; Dry Vacuum Cleaner Machine', 'https://phumelectronic.com/wp-content/uploads/revo/b22063d129856e0df7e9f8f88be6603e.jpg', 'Love These Items', 1, 1, 'product', '2022-04-04 08:23:46'),
(15, 7, 11461, 'LUCECO 700 LUMENS 10W 5000K RECHARGABLE WORK LIGHT', 'https://phumelectronic.com/wp-content/uploads/revo/7c047401456e341a69f9b8ede66f9cb3.jpg', 'Love These Items', 1, 1, 'product', '2022-04-04 08:24:31'),
(16, 8, 11537, 'PANA Blue Vacuum 1800W', 'https://phumelectronic.com/wp-content/uploads/revo/92ecc0edf78370a138950c5be99ee1f8.jpg', 'Love These Items', 1, 1, 'product', '2022-04-04 08:25:14'),
(17, 1, 11541, 'PANA Dry Iron 1000W,2Kg', 'https://phumelectronic.com/wp-content/uploads/revo/72e2d584e6df707aa107983f3afecb62.jpg', 'Love These Items', 1, 0, 'product', '2022-04-20 03:47:59'),
(18, 2, 11537, 'PANA Blue Vacuum 1800W', 'https://phumelectronic.com/wp-content/uploads/revo/2e647d35cf39d5ced7952f54f4d2c428.jpg', 'Love These Items', 1, 0, 'product', '2022-04-20 03:49:15'),
(19, 3, 11450, 'LUCECO 1800 LUMENS 30W 5000K WORK LIGHT', 'https://phumelectronic.com/wp-content/uploads/revo/738de05b24539f2260aa99e123bde6c3.jpg', 'Love These Items', 1, 0, 'product', '2022-04-20 03:49:34'),
(20, 4, 11481, 'MASTERPLUG 5 GANG 5.0M 1.0MM2 PLUG', 'https://phumelectronic.com/wp-content/uploads/revo/1de629280848c92a63c7208967f7d4d3.jpg', 'Love These Items', 1, 0, 'product', '2022-04-20 03:49:56');
