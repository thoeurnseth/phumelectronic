
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_alm_unlimited`
--

CREATE TABLE `ph0m31e_alm_unlimited` (
  `id` mediumint(9) NOT NULL,
  `name` text NOT NULL,
  `repeaterDefault` longtext NOT NULL,
  `alias` text NOT NULL,
  `pluginVersion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ph0m31e_alm_unlimited`
--

INSERT INTO `ph0m31e_alm_unlimited` (`id`, `name`, `repeaterDefault`, `alias`, `pluginVersion`) VALUES
(1, 'template_1', '<div class=\"col-lg-3 col-md-4 col-sm-4 col-xs-12\">\n	<div class=\"brand-item\">\n		<a href=\"#\">\n			<div class=\"thumbnail\">\n				<img src=\"\'.$brand_logo.\'\">\n\n				<div class=\"brand-name\">\n					<h3>\'.$brand->name.\'</h3>\n				</div>      \n			</div>\n		</a>\n	</div>\n</div>', 'Product Brand', '2.5');
