
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_alm`
--

CREATE TABLE `ph0m31e_alm` (
  `id` mediumint(9) NOT NULL,
  `name` text NOT NULL,
  `repeaterDefault` longtext NOT NULL,
  `repeaterType` text NOT NULL,
  `pluginVersion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ph0m31e_alm`
--

INSERT INTO `ph0m31e_alm` (`id`, `name`, `repeaterDefault`, `repeaterType`, `pluginVersion`) VALUES
(1, 'default', '<?php\n	$code = get_the_title();\n	$coupon = new WC_Coupon($code);\n	$coupon_post = get_post($coupon->id);\n	$thumbnail = get_field(\'coupon_thumbnail_for_desktop\', get_the_ID());\n	\n	if( !empty($thumbnail[\'url\']) ):?>\n\n	<div class=\"coupon-container\">\n  		<img src=\"<?php echo $thumbnail[\'url\']; ?>\" alt=\"Snow\" style=\"width:100%\">\n  		<button class=\"btn\">Available</button>\n	</div>\n\n<?php endif; ?>', 'default', '5.4.1');
