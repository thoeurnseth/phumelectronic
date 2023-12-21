
-- --------------------------------------------------------

--
-- Table structure for table `revo_mobile_slider`
--

CREATE TABLE `revo_mobile_slider` (
  `id` int(11) NOT NULL,
  `order_by` int(55) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `title` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `images_url` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `is_active` int(1) DEFAULT 1,
  `is_deleted` int(1) DEFAULT 0,
  `is_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_mobile_slider`
--

INSERT INTO `revo_mobile_slider` (`id`, `order_by`, `product_id`, `title`, `images_url`, `product_name`, `is_active`, `is_deleted`, `is_type`, `created_at`) VALUES
(1, 1, 11581, 'Banner', 'https://phumelectronic.com/wp-content/uploads/revo/ded6d3200f2eb0d731e7d7e5b15738d5.jpg', 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 1, 1, '', '2021-12-03 06:38:10'),
(2, 2, 11537, 'Washing Machine', 'https://phumelectronic.com/wp-content/uploads/revo/ae9e243cfb50880042ddaebd6e78fd8f.jpg', 'PANA Blue Vacuum 1800W', 1, 1, '', '2021-12-04 01:50:11'),
(3, 3, 11511, 'Promo', 'https://phumelectronic.com/wp-content/uploads/revo/aec2e517b6e0cdb2dc917ba4424d5c11.jpg', 'PANA 2Door 286L, Silver REF', 1, 1, '', '2021-12-15 07:40:53'),
(4, 4, 11359, 'Another promo', 'https://phumelectronic.com/wp-content/uploads/revo/5710f70bc0e10511a54e6f16a330145c.jpg', 'LG 2 Doors 471L REF', 1, 1, '', '2021-12-15 07:41:32'),
(5, 1, 11378, 'OLDTV', 'https://phumelectronic.com/wp-content/uploads/revo/c4d8799d848d1b4b075fc0c64d980c3f.jpg', 'LG 43\" UHD 4K Smart LTV', 1, 1, 'product', '2022-03-30 09:44:27'),
(6, 2, 11419, 'LG Front Load 9Kg AI Direct Drive', 'https://phumelectronic.com/wp-content/uploads/revo/091361b48a1998f8197cd373623e5a77.jpg', 'LG Front Load 9Kg AI Direct Drive WM', 1, 1, 'product', '2022-03-30 09:54:29'),
(7, 3, 11337, 'KNY Promotions', 'https://phumelectronic.com/wp-content/uploads/revo/394720606d64786989cc49282e0cd592.jpg', 'LG 1.5HP Dual Inverter RAC', 1, 1, 'product', '2022-03-31 07:06:35'),
(8, 1, 11337, 'KNY Promotions', 'https://phumelectronic.com/wp-content/uploads/revo/65bcbcede6dad8fffdf90fabf71fc7de.jpg', 'LG 1.5HP Dual Inverter RAC', 1, 1, 'product', '2022-03-31 07:08:45'),
(9, 4, 11387, 'Televisions', 'https://phumelectronic.com/wp-content/uploads/revo/5294789521fd7d0c427c4b2a1ae12635.jpg', 'LG 55\" OLED 4K Smart LTV', 1, 1, 'product', '2022-03-31 07:09:44'),
(10, 5, 18773, 'Refrigrators', 'https://phumelectronic.com/wp-content/uploads/revo/0efd08ca97d953e0c01c6010472ea475.jpg', 'Refrigerator', 1, 1, 'category', '2022-03-31 07:10:36'),
(11, 0, 18781, 'Khmer New Year', 'https://phumelectronic.com/wp-content/uploads/revo/f81117c9a0f25cbd6d0417df50ce55e4.jpg', 'Washing Machine', 1, 1, 'category', '2022-04-01 09:41:44'),
(12, 1, 18781, 'KNY Promotion', 'https://phumelectronic.com/wp-content/uploads/revo/e1c944717f2aad1f59c4db1d419dea02.jpg', 'Washing Machine', 1, 1, 'category', '2022-04-01 10:24:49'),
(13, 1, 18781, 'KNY Promotions', 'https://phumelectronic.com/wp-content/uploads/revo/c84f0926fbb169407c7c29754d6e5ae7.jpg', 'Washing Machine', 1, 1, 'category', '2022-04-01 10:35:14'),
(14, 4, 11619, 'kny', 'https://phumelectronic.com/wp-content/uploads/revo/b2913420f71e381c8bad711e4eeccb15.jpg', 'ROSS 36-63 Inches flat to wall LTV Wall Mount', 1, 1, 'product', '2022-04-04 07:23:06'),
(15, 3, 18773, 'Reg', 'https://phumelectronic.com/wp-content/uploads/revo/0efd08ca97d953e0c01c6010472ea475.jpg', 'Washing Machine', 1, 1, 'category', '2022-04-19 07:42:10'),
(16, 3, 11620, 'test', 'https://phumelectronic.com/wp-content/uploads/revo/5294789521fd7d0c427c4b2a1ae12635.jpg', 'UV Case', 1, 1, 'product', '2022-04-19 10:16:27'),
(17, 3, 18762, 'test', 'https://staging.phumelectronic.com/wp-content/uploads/revo/bdb9db3e79e6bc8990b3a9242bf0c751.png', 'Washing Machine', 1, 1, 'category', '2022-04-20 02:11:01'),
(18, 1, 18807, 'soft Launching', 'https://phumelectronic.com/wp-content/uploads/revo/58a509663841a9f6b9571cb9a6f34c53.jpg', 'Soft Launching', 1, 1, 'category', '2022-05-09 04:51:48'),
(19, 1, 18807, 'Soft Launching', 'https://phumelectronic.com/wp-content/uploads/revo/426c287a35ba7fb16e28f7bf72530614.jpg', 'Soft Launching', 1, 1, 'category', '2022-05-09 07:05:13'),
(20, 1, 18807, 'Sofyt Launching', 'https://phumelectronic.com/wp-content/uploads/revo/05020e73c66979f646b8a40f6f49d647.jpg', 'Soft Launching', 1, 1, 'category', '2022-05-09 10:38:35'),
(21, 2, 18799, 'Soft Discount50%', 'https://phumelectronic.com/wp-content/uploads/revo/0525d2f662c7a548f1d2db285ffd528e.jpg', '50% OFF', 1, 1, 'category', '2022-05-10 01:43:14'),
(22, 3, 18798, 'Soft Discount40%', 'https://phumelectronic.com/wp-content/uploads/revo/6022d011940247712c22f573d3bb555d.jpg', '40% OFF', 1, 1, 'category', '2022-05-10 01:43:55'),
(23, 4, 18797, 'Soft Discount 30%', 'https://phumelectronic.com/wp-content/uploads/revo/cf180b10c25a862777db6e4eebb4f364.jpg', '30% OFF', 1, 1, 'category', '2022-05-10 01:44:28'),
(24, 5, 18796, 'Soft Discount20%', 'https://phumelectronic.com/wp-content/uploads/revo/4119f034c0ec1bb9f34143e7b63a9fee.jpg', '20% OFF', 1, 1, 'category', '2022-05-10 01:45:13'),
(25, 1, 18807, 'Soft Launching Promotions', 'https://phumelectronic.com/wp-content/uploads/revo/62993c5128856f0e383da91a2e1389b9.jpg', 'Soft Launching', 1, 1, 'category', '2022-05-10 01:55:54'),
(26, 1, 18807, 'Soft Launching Promotions', 'https://phumelectronic.com/wp-content/uploads/revo/789b32b131c5e33b6baefb28de2575cf.jpg', 'Soft Launching', 1, 1, 'category', '2022-05-10 01:56:45'),
(27, 1, 11387, 'LG OLED TV', 'https://phumelectronic.com/wp-content/uploads/revo/46207bdfa3396a910a4d7742fe1347cc.jpg', 'LG 55\" OLED 4K Smart LTV', 1, 1, 'product', '2022-05-16 04:11:12'),
(28, 2, 11331, 'LG 1.5HP Dual Inverter', 'https://phumelectronic.com/wp-content/uploads/revo/a7fb99ffedab05bcec5379f33d99fbb8.jpg', 'LG 1.5HP Dual Inverter Air Purifying System RAC', 1, 1, 'product', '2022-05-16 04:11:55'),
(29, 1, 18791, 'LG Product', 'https://phumelectronic.com/wp-content/uploads/revo/d4501798136378fc708b801cf3024ede.jpg', 'LG', 1, 0, 'category', '2022-05-18 09:05:29'),
(30, 2, 11369, 'Xboom', 'https://phumelectronic.com/wp-content/uploads/revo/b7cf55c10357d461d46b37ea6af1c8b7.jpg', 'LG 30W XBOOM Bluetooth Speaker AV', 1, 0, 'product', '2022-05-18 09:06:00'),
(31, 3, 11367, 'LG Air con', 'https://phumelectronic.com/wp-content/uploads/revo/d070ca51fb099d37c9434fb18f63061e.jpg', 'LG 2.0HP Dual Inverter RAC', 1, 0, 'product', '2022-05-18 09:06:32'),
(32, 4, 18773, 'Refrigrator', 'https://phumelectronic.com/wp-content/uploads/revo/2ec23aca342a70a8673b05bd2d5638cf.jpg', 'Refrigerator', 1, 1, 'category', '2022-05-18 09:10:17');
