
-- --------------------------------------------------------

--
-- Table structure for table `revo_popular_categories`
--

CREATE TABLE `revo_popular_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(55) NOT NULL,
  `categories` text CHARACTER SET latin1 NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_popular_categories`
--

INSERT INTO `revo_popular_categories` (`id`, `title`, `categories`, `is_deleted`, `created_at`) VALUES
(1, 'The most popular', '[\"14731\",\"14733\",\"14734\"]', 1, '2021-12-04 01:42:18'),
(2, 'Hot Sale 10% OFF', '[\"18780\"]', 1, '2022-02-09 07:50:51');
