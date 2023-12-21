
-- --------------------------------------------------------

--
-- Table structure for table `revo_list_categories`
--

CREATE TABLE `revo_list_categories` (
  `id` int(11) NOT NULL,
  `order_by` int(55) NOT NULL,
  `image` varchar(500) CHARACTER SET latin1 NOT NULL,
  `category_id` int(55) NOT NULL,
  `category_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `is_active` int(1) DEFAULT 1,
  `is_deleted` int(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_list_categories`
--

INSERT INTO `revo_list_categories` (`id`, `order_by`, `image`, `category_id`, `category_name`, `is_active`, `is_deleted`, `created_at`) VALUES
(1, 1, 'http://localhost/phumelectronic/wp-content/uploads/revo/11200e2a797079258ac5200f2c1736d2.png', 14731, 'Air Conditioner', 1, 1, '2021-12-04 01:53:15'),
(2, 1, 'http://localhost/phumelectronic/wp-content/uploads/revo/63f4366786ecd86d2c2cead113fcb970.png', 14731, 'Air Conditioner', 1, 1, '2021-12-04 02:01:56'),
(3, 2, 'http://localhost/phumelectronic/wp-content/uploads/revo/624fc1800de594d4f27145c0c070d793.png', 14733, 'Audio', 1, 1, '2021-12-04 02:02:15'),
(4, 3, 'http://localhost/phumelectronic/wp-content/uploads/revo/f0fa374ab6f94fefacddcadd40b1b76f.png', 14739, 'Refrigerator', 1, 1, '2021-12-04 02:02:48'),
(5, 4, 'http://localhost/phumelectronic/wp-content/uploads/revo/75f63d1de97a040ab965e62e157dd98c.png', 14743, 'Television', 1, 1, '2021-12-04 02:03:39'),
(6, 5, 'http://localhost/phumelectronic/wp-content/uploads/revo/a8e0b91b208a62f443e8e1bd921fea8a.png', 14745, 'Washing Machine', 1, 1, '2021-12-04 02:04:06'),
(7, 1, 'http://localhost/phumelectronic/wp-content/uploads/revo/a37696b44e11d2fdb2033af4c528b161.png', 14731, 'Air Conditioner', 1, 1, '2021-12-06 02:48:48'),
(8, 2, 'http://localhost/phumelectronic/wp-content/uploads/revo/4bb193aa8f99a976b3efd35066e800ed.png', 14733, 'Audio', 1, 1, '2021-12-06 02:49:19'),
(9, 3, 'http://localhost/phumelectronic/wp-content/uploads/revo/376ed92bda58959d61ee392fa6e12a8c.png', 14739, 'Refrigerator', 1, 1, '2021-12-06 02:49:50'),
(10, 4, 'http://localhost/phumelectronic/wp-content/uploads/revo/35f975ce33b128e07aef068151b6b9cf.png', 14743, 'Television', 1, 1, '2021-12-06 02:50:16'),
(11, 5, 'http://localhost/phumelectronic/wp-content/uploads/revo/470231707d5bacf5e008266cf0e92e62.png', 14745, 'Washing Machine', 1, 1, '2021-12-06 02:50:31'),
(12, 1, 'http://localhost/phumelectronic/wp-content/uploads/revo/55707187d1b9e0898a4e66b6e52aa679.png', 14731, 'Air Conditioner', 1, 1, '2021-12-07 06:59:55'),
(13, 10, 'https://staging.phumelectronic.com/wp-content/uploads/2021/12/P.Audio-2.png', 18766, 'Audio', 1, 0, '2021-12-07 07:00:17'),
(14, 7, 'https://staging.phumelectronic.com//wp-content/uploads/2021/12/P.Refrigerator-2.png', 18773, 'Refrigerator', 1, 0, '2021-12-07 07:00:52'),
(15, 8, 'https://staging.phumelectronic.com/wp-content/uploads/2021/12/P.TV-2.png', 18776, 'Television', 1, 0, '2021-12-07 07:01:09'),
(16, 9, 'https://staging.phumelectronic.com/wp-content/uploads/2021/12/P.Washing-Machine-2.png', 18778, 'Washing Machine', 1, 0, '2021-12-07 07:01:27'),
(17, 11, 'https://staging.phumelectronic.com/wp-content/uploads/2021/12/Group-269.png', 18774, 'Small Home Appliance', 1, 0, '2021-12-23 06:33:12'),
(18, 10, 'https://phumelectronic.com/wp-content/uploads/revo/9f4410e943ab27ac07bb847514ef26b3.png', 18780, '10% OFF', 1, 1, '2022-03-03 03:20:45'),
(19, 1, 'https://phumelectronic.com/wp-content/uploads/revo/1bec7f4b8472d3cfb665e363825b3342.png', 18791, 'LG', 1, 0, '2022-04-20 06:19:01'),
(20, 2, 'https://phumelectronic.com/wp-content/uploads/revo/37bb8df2694b61d4642e8032c8c399d0.png', 18794, 'Panasonic', 1, 0, '2022-04-20 06:19:27'),
(21, 3, 'https://phumelectronic.com/wp-content/uploads/revo/451a3bfe10d70c0b326816da54dbf6a1.png', 18793, 'Midea', 1, 0, '2022-04-20 06:20:22'),
(22, 4, 'https://phumelectronic.com/wp-content/uploads/revo/a24f2fd10818ebcf38601e5e6ebb1ce6.png', 18792, 'Luceco', 1, 0, '2022-04-20 06:21:00'),
(23, 5, 'https://phumelectronic.com/wp-content/uploads/revo/c16f07a369a11598e59aabe1cdfbe959.png', 18771, 'Master Plug', 1, 0, '2022-04-20 06:21:16'),
(24, 6, 'https://phumelectronic.com/wp-content/uploads/revo/642343ee3d4364146e6b64f999a6174e.png', 18790, 'Karcher', 1, 0, '2022-04-20 06:24:47');
