
-- --------------------------------------------------------

--
-- Table structure for table `revo_flash_sale`
--

CREATE TABLE `revo_flash_sale` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `products` longtext NOT NULL,
  `image` varchar(500) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_flash_sale`
--

INSERT INTO `revo_flash_sale` (`id`, `title`, `start`, `end`, `products`, `image`, `is_active`, `is_deleted`, `created_at`) VALUES
(1, 'flash sale', '2022-02-08 16:00:00', '2022-02-10 15:59:00', '[\"10213\",\"10189\",\"10183\"]', 'https://phumelectronic.com/wp-content/uploads/revo/9eb39a08a5422c65f41b86262d56dc07.png', 1, 1, '2022-02-09 07:42:41'),
(2, 'Flash Sale', '2022-02-09 16:00:00', '2022-02-10 07:10:00', '[\"10213\",\"10189\",\"10185\",\"9698\"]', 'https://phumelectronic.com/wp-content/uploads/revo/e659df5f799582ae2cbf6b335dfa8e6b.jpg', 1, 1, '2022-02-10 07:55:47'),
(3, 'Khmer New Year Promotions', '2022-04-30 16:00:00', '2022-06-11 15:59:00', '[\"11620\",\"11621\",\"11611\",\"11609\",\"11608\",\"11601\",\"11597\",\"11598\",\"11590\",\"11591\",\"11589\",\"11587\",\"11588\",\"11578\",\"11580\",\"11571\",\"11560\",\"11558\",\"11548\",\"11540\",\"11541\",\"11539\",\"11530\",\"11520\",\"11521\",\"11518\"]', 'https://phumelectronic.com/wp-content/uploads/revo/7d4e07dfec36f2e028b8a1e64881efc9.jpg', 1, 1, '2022-04-05 02:17:54');
