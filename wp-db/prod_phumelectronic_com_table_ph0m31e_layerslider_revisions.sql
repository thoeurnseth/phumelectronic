
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_layerslider_revisions`
--

CREATE TABLE `ph0m31e_layerslider_revisions` (
  `id` int(10) NOT NULL,
  `slider_id` int(10) NOT NULL,
  `author` int(10) NOT NULL DEFAULT 0,
  `data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_c` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
