
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_admin_columns`
--

CREATE TABLE `ph0m31e_admin_columns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `list_id` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `list_key` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `columns` mediumtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `settings` mediumtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `ph0m31e_admin_columns`
--

INSERT INTO `ph0m31e_admin_columns` (`id`, `list_id`, `list_key`, `title`, `columns`, `settings`, `date_created`, `date_modified`) VALUES
(1, '6021f979bf47b', 'shop_order', 'Orders', 'a:11:{s:12:\"order_number\";a:4:{s:4:\"type\";s:12:\"order_number\";s:5:\"label\";s:5:\"Order\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:13:\"6022148a47afe\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:14:\"Payment Method\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:21:\"_payment_method_title\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"6022148a47b00\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:7:\"Odoo SO\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:14:\"odoo_so_number\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"6022148a47b01\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:14:\"Create Odoo SO\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:15:\"odoo_sale_order\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"602215b2bac2f\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:15:\"Confirm Odoo SO\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:23:\"odoo_confirm_sale_order\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:12:\"order_status\";a:4:{s:4:\"type\";s:12:\"order_status\";s:5:\"label\";s:15:\"Delivery Status\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:15:\"billing_address\";a:4:{s:4:\"type\";s:15:\"billing_address\";s:5:\"label\";s:7:\"Billing\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:16:\"shipping_address\";a:4:{s:4:\"type\";s:16:\"shipping_address\";s:5:\"label\";s:7:\"Ship to\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:13:\"602215b2bac31\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:14:\"Invoice (Paid)\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:14:\"online_payment\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:30:\"<span style=\"color:#5b841b;\">$\";s:5:\"after\";s:7:\"</span>\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:11:\"order_total\";a:4:{s:4:\"type\";s:11:\"order_total\";s:5:\"label\";s:5:\"Total\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:10:\"order_date\";a:4:{s:4:\"type\";s:10:\"order_date\";s:5:\"label\";s:4:\"Date\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}}', NULL, '2021-02-09 04:47:27', '2021-08-18 04:50:37'),
(2, '602215deb2ad8', 'product', 'Products', 'a:9:{s:5:\"thumb\";a:4:{s:4:\"type\";s:5:\"thumb\";s:5:\"label\";s:57:\"<span class=\"wc-image tips\" data-tip=\"Image\">Image</span>\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:4:\"name\";a:4:{s:4:\"type\";s:4:\"name\";s:5:\"label\";s:4:\"Name\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:13:\"60221602763c3\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:7:\"Odoo ID\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:7:\"odoo_id\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:3:\"sku\";a:4:{s:4:\"type\";s:3:\"sku\";s:5:\"label\";s:3:\"SKU\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:11:\"is_in_stock\";a:4:{s:4:\"type\";s:11:\"is_in_stock\";s:5:\"label\";s:5:\"Stock\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:5:\"price\";a:4:{s:4:\"type\";s:5:\"price\";s:5:\"label\";s:5:\"Price\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:11:\"product_cat\";a:4:{s:4:\"type\";s:11:\"product_cat\";s:5:\"label\";s:10:\"Categories\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:8:\"featured\";a:4:{s:4:\"type\";s:8:\"featured\";s:5:\"label\";s:73:\"<span class=\"wc-featured parent-tips\" data-tip=\"Featured\">Featured</span>\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:4:\"date\";a:4:{s:4:\"type\";s:4:\"date\";s:5:\"label\";s:4:\"Date\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}}', NULL, '2021-02-09 04:56:34', '2021-03-12 02:01:05'),
(3, '60221610e8a38', 'wp-users', 'Users', 'a:6:{s:8:\"username\";a:4:{s:4:\"type\";s:8:\"username\";s:5:\"label\";s:8:\"Username\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:13:\"6022162b15ae3\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:7:\"Odoo ID\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:7:\"odoo_id\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:4:\"name\";a:4:{s:4:\"type\";s:4:\"name\";s:5:\"label\";s:4:\"Name\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:5:\"email\";a:4:{s:4:\"type\";s:5:\"email\";s:5:\"label\";s:5:\"Email\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:4:\"role\";a:4:{s:4:\"type\";s:4:\"role\";s:5:\"label\";s:4:\"Role\";s:5:\"width\";s:2:\"15\";s:10:\"width_unit\";s:1:\"%\";}s:5:\"posts\";a:4:{s:4:\"type\";s:5:\"posts\";s:5:\"label\";s:5:\"Posts\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}}', NULL, '2021-02-09 04:57:15', '2021-02-09 04:57:59'),
(4, '608a312a44cdb', 'e-warranty', 'E-Warranty', 'a:5:{s:5:\"title\";a:4:{s:4:\"type\";s:5:\"title\";s:5:\"label\";s:5:\"Title\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}s:13:\"615531b06251f\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:8:\"Username\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:8:\"username\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"615531b062524\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:12:\"Phone Number\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:12:\"phone_number\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"615531b062525\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:14:\"Invoice Number\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:14:\"invoice_number\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"615531b062526\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:4:\"Date\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:10:\"order_date\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}}', NULL, '2021-04-29 04:09:02', '2021-09-30 03:40:32'),
(5, '60d167666a670', 'wpcf7s', 'Contact Form Submissions', 'a:6:{s:13:\"60d167f8a9d48\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:4:\"Name\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:23:\"wpcf7s_posted-your-name\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"60d167f8a9d4e\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:7:\"Subject\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:26:\"wpcf7s_posted-your-subject\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"60d167f8a9d4f\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:5:\"Phone\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:24:\"wpcf7s_posted-your-phone\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"60d167f8a9d50\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:5:\"Email\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:24:\"wpcf7s_posted-your-email\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:13:\"60d167f8a9d51\";a:13:{s:4:\"type\";s:11:\"column-meta\";s:5:\"label\";s:7:\"Message\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";s:5:\"field\";s:26:\"wpcf7s_posted-your-message\";s:10:\"field_type\";s:0:\"\";s:6:\"before\";s:0:\"\";s:5:\"after\";s:0:\"\";s:4:\"sort\";s:3:\"off\";s:11:\"inline-edit\";s:3:\"off\";s:9:\"bulk-edit\";s:3:\"off\";s:15:\"smart-filtering\";s:3:\"off\";s:6:\"export\";s:3:\"off\";}s:4:\"date\";a:4:{s:4:\"type\";s:4:\"date\";s:5:\"label\";s:4:\"Date\";s:5:\"width\";s:0:\"\";s:10:\"width_unit\";s:1:\"%\";}}', NULL, '2021-06-22 04:32:51', '2021-06-22 04:32:56');
