
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_wc_customer_lookup`
--

CREATE TABLE `ph0m31e_wc_customer_lookup` (
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_last_active` timestamp NULL DEFAULT NULL,
  `date_registered` timestamp NULL DEFAULT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `postcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_wc_customer_lookup`
--

INSERT INTO `ph0m31e_wc_customer_lookup` (`customer_id`, `user_id`, `username`, `first_name`, `last_name`, `email`, `date_last_active`, `date_registered`, `country`, `postcode`, `city`, `state`) VALUES
(30, 186, 'pettersmey', 'Petter', 'Smey', 'pettermeng@gmail.com', '2022-05-23 16:00:00', '2021-09-03 00:38:34', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(48, 252, 'sokunthea', 'vann', 'sokunthea', 'vann-sokunthea@ggear.com.kh', '2022-05-15 16:00:00', '2021-12-20 18:23:15', 'KH', '12000', 'Kampot', 'Cambodia'),
(59, 194, 'sophana', 'Sophana', 'Ggear', 'so.sophana@ggear.com.kh', '2022-05-23 16:00:00', '2021-09-07 19:06:56', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(71, 305, 'peara.mn', 'Pheara', 'IT Biz', 'peara.mn@gmail.com', '2022-05-01 16:00:00', '2022-02-25 01:28:14', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(72, 306, 'theany.heng', 'theany', 'heng', 'heng.theany@gmail.com', '2022-02-27 16:00:00', '2022-02-25 02:33:20', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(73, 307, 'heng.theany', 'Heng', 'Theany', 'h_theany@yahoo.com', '2022-05-01 16:00:00', '2022-02-25 02:41:18', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(74, 308, 'petter-sok_6218b39c490c4', 'Petter', 'Sok', 'petter@gmail.com', '2022-05-03 16:00:00', '2022-02-25 02:46:52', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(75, 309, 'touchsambo.khmer', 'touchsambo', 'khmer', 'touchsamboscro@gmail.com', '2022-02-25 16:00:00', '2022-02-25 17:36:03', 'KH', '12000', 'Siem Reap', 'Cambodia'),
(76, 311, 'Yim Sophal', 'Yim', 'Sophal', 'sethika.stg@gmail.com', '2022-02-25 16:00:00', '2022-02-25 17:58:53', 'KH', '12000', 'Stung Treng', 'Cambodia'),
(77, 314, 'pech.pyseth', 'Pech', 'Pyseth', 'pechpyseth@gmail.com', '2022-02-25 16:00:00', '2022-02-25 19:03:41', 'KH', '12000', 'Battambang', 'Cambodia'),
(78, 312, 'Chanthoeun.Touch', 'Chanthoeun', 'Touch', 'touch.chanthoeun@gmail.com', '2022-03-30 16:00:00', '2022-02-25 18:31:37', 'KH', '12000', 'Battambang', 'Cambodia'),
(79, 313, 'hem.sopheary', 'Hem', 'Sopheary', 'h.sopheary@yahoo.com', '2022-02-25 16:00:00', '2022-02-25 18:42:06', 'KH', '12000', 'Battambang', 'Cambodia'),
(80, 310, '.', 'ធឿន', 'ចេង', 'thoeuncheng1111@gmail.com', '2022-02-25 16:00:00', '2022-02-25 17:54:37', 'KH', '12000', 'Battambang', 'Cambodia'),
(81, 318, 'yan.peth', 'yan', 'peth', 'yanpethiphone@gmail.com', '2022-02-27 16:00:00', '2022-02-26 00:59:18', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(84, 324, 'nimol.pon', 'nimol', 'pon', 'nimolsetec@gmail.com', '2022-02-27 16:00:00', '2022-02-27 17:53:14', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(85, 325, 'sourng.sopheap', 'Sourng', 'Sopheap', 'sopheap.socialmedia1108@gmail.com', '2022-02-27 16:00:00', '2022-02-27 17:53:46', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(86, 320, 'sem.songhy.1', 'Sem', 'SongHy', 'semsonghy8@gmail.com', '2022-05-23 16:00:00', '2022-02-26 06:24:02', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(87, 327, 'Developer.Cambodia', 'Developer', 'Cambodia', 'ponnimol944@gmail.com', '2022-03-21 16:00:00', '2022-02-27 18:02:18', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(88, 328, 'Biz_IT', 'IT', 'Test from biz', 'phollydetmoeun@gmail.com', '2022-02-27 16:00:00', '2022-02-27 18:11:25', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(89, 329, 'nimol.pon.1', 'Nimol', 'PON', 'ponnimol168@gmail.com', '2022-03-21 16:00:00', '2022-02-27 18:35:06', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(90, 330, 'GGear.Group', 'GGear', 'Group', 'ggear.biz@gmail.com', '2022-02-27 16:00:00', '2022-02-27 18:35:38', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(91, 332, 'ezra.det', 'Ezra', 'Det', 'detdong725@gmail.com', '2022-05-15 16:00:00', '2022-03-01 01:14:57', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(93, 335, 'sophat.panhasak', 'Sophat', 'Panhasak', 'eachsophat@yahoo.com', '2022-04-20 16:00:00', '2022-03-03 19:42:18', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(94, 326, 'sareth.sophannaroth', 'Sareth', 'SophannaRoth', 'peara_konkhmer@outlook.com', '2022-04-26 16:00:00', '2022-02-27 17:57:44', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(95, 337, 'Yin.Sreyleak', 'Yin', 'Sreyleak', 'sreyleakyin274@gmail.com', '2022-05-09 16:00:00', '2022-03-07 00:22:48', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(97, 319, 'Sem.Songhy', 'Sem', 'Songhy', 'semsonghy071@gmail.com', '2022-05-01 16:00:00', '2022-02-26 06:11:59', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(99, 333, 'somana.pav', 'Somana', 'Pav', 'bomi003sunoo@gmail.com', '2022-03-31 16:00:00', '2022-03-02 22:39:41', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(100, 356, 'nullnull.1', 'null null', 'null null', 'sokuntheavann27@yahoo.com', '2022-03-30 16:00:00', '2022-03-30 17:56:35', 'KH', '12000', 'Kampong Cham', 'Cambodia'),
(101, 357, 'pon-nimol_62450b3c73f0d', 'PON', 'Nimol', 'nimol.p@bizsolution.com.kh', '2022-03-30 16:00:00', '2022-03-30 18:00:28', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(102, 358, 'Meng.Pheakdey', 'Meng', 'Pheakdey', 'jinobong800@gmail.com', '2022-05-04 16:00:00', '2022-03-30 22:23:40', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(103, 355, 'Heng.Chungseng', 'Heng', 'Chungseng', 'sengchungheng@gmail.com', '2022-03-30 16:00:00', '2022-03-30 01:26:27', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(104, 367, 'So.Thea', 'So', 'Thea', 'khasothea98@gmail.com', '2022-04-07 16:00:00', '2022-03-31 02:09:22', 'KH', '12000', 'Preah Sihanouk', 'Cambodia'),
(105, 363, 'Ro.Dara', 'Ro', 'Dara', 'rodara016@gmail.com', '2022-03-30 16:00:00', '2022-03-31 01:56:40', 'KH', '12000', 'Kandal', 'Cambodia'),
(106, 359, 'narith.narith', 'Narith', 'Narith', 'darithvanlentine@yahoo.com', '2022-03-30 16:00:00', '2022-03-31 01:20:45', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(107, 434, 'Mr.Cheat', 'Heng', 'Socheat', 'hengsocheat01@gmail.com', '2022-05-10 16:00:00', '2022-05-10 21:13:36', 'KH', '12000', 'Phnom Penh', 'Cambodia'),
(108, 436, 'biz-developer_6284cb365a21d', 'Biz', 'Developer', 'ponnimol944@gmail.com', '2022-05-19 16:00:00', '2022-05-18 02:32:22', 'KH', '12000', 'Phnom Penh', 'Cambodia');
