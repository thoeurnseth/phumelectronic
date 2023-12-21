
-- --------------------------------------------------------

--
-- Table structure for table `revo_mobile_variable`
--

CREATE TABLE `revo_mobile_variable` (
  `id` int(11) NOT NULL,
  `slug` varchar(55) CHARACTER SET latin1 NOT NULL,
  `title` varchar(55) CHARACTER SET latin1 DEFAULT NULL,
  `image` text CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 DEFAULT NULL,
  `sort` tinyint(2) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_mobile_variable`
--

INSERT INTO `revo_mobile_variable` (`id`, `slug`, `title`, `image`, `description`, `sort`, `is_deleted`, `created_at`, `update_at`) VALUES
(1, 'splashscreen', '', 'https://phumelectronic.com/wp-content/uploads/revo/6610886cf8477cc59843b0feed27e8a5.png', '', 0, 0, '2021-11-26 08:29:09', NULL),
(2, 'kontak', 'wa', '', '281774009077390', 0, 0, '2021-11-26 08:29:09', NULL),
(3, 'kontak', 'phone', '', '016888029', 0, 0, '2021-11-26 08:29:09', NULL),
(4, 'sms', 'link sms', '', '', 0, 0, '2021-11-26 08:29:09', NULL),
(5, 'about', 'link about', '', 'https://phumelectronic.com/company-profile?webview=true', 0, 0, '2021-11-26 08:29:09', NULL),
(6, 'cs', 'customer service', '', 'https://phumelectronic.com/phumelectronic/contact/', 0, 0, '2021-11-26 08:29:09', NULL),
(7, 'privacy_policy', 'link Privacy Policy', '', 'https://phumelectronic.com/phumelectronic/coming-soon/', 0, 0, '2021-11-26 08:29:09', NULL),
(8, 'logo', 'Phum Electronic', 'https://phumelectronic.com/wp-content/uploads/revo/6145ea46cee59bd8fb2b0b99081e6cea.png', 'logo', 0, 0, '2021-11-26 08:29:09', NULL),
(9, 'intro_page', 'Manage Everything', 'https://phumelectronic.com/wp-content/plugins/revo-woo/assets/extend/images/revo-woo-onboarding-01.jpg', 'Completely manage your store from the dashboard, including onboarding/intro changes, sliding banners, posters, home, and many more.', 1, 0, '2021-11-26 08:29:09', NULL),
(10, 'intro_page', 'Support All Payments', 'https://phumelectronic.com/wp-content/plugins/revo-woo/assets/extend/images/revo-woo-onboarding-02.jpg', 'Pay for the transaction using all the payment methods you want. Including paypal, razorpay, bank transfer, BCA, Mandiri, gopay, or ovo.', 2, 0, '2021-11-26 08:29:09', NULL),
(11, 'intro_page', 'Support All Shipping Methods', 'https://phumelectronic.com/wp-content/plugins/revo-woo/assets/extend/images/revo-woo-onboarding-03.jpg', 'The shipping method according to your choice, which is suitable for your business. All can be arranged easily.', 3, 0, '2021-11-26 08:29:09', NULL),
(12, 'empty_image', '404_images', 'http://localhost/phumelectronic/wp-content/plugins/revo-woo/assets/extend/images/404.png', '450 x 350px', 0, 0, '2021-11-26 08:29:09', NULL),
(13, 'empty_image', 'thanks_order', 'http://localhost/phumelectronic/wp-content/plugins/revo-woo/assets/extend/images/thanks_order.png', '600 x 420px', 0, 0, '2021-11-26 08:29:09', NULL),
(14, 'empty_image', 'empty_transaksi', 'http://localhost/phumelectronic/wp-content/plugins/revo-woo/assets/extend/images/no_transaksi.png', '260 x 300px', 0, 0, '2021-11-26 08:29:09', NULL),
(15, 'empty_image', 'search_empty', 'http://localhost/phumelectronic/wp-content/plugins/revo-woo/assets/extend/images/search_empty.png', '260 x 300px', 0, 0, '2021-11-26 08:29:09', NULL),
(16, 'empty_image', 'login_required', 'http://localhost/phumelectronic/wp-content/plugins/revo-woo/assets/extend/images/404.png', '260 x 300px', 0, 0, '2021-11-26 08:29:09', NULL),
(17, 'kontak', 'sms', '', '016888029', 0, 0, '2021-12-09 01:29:52', NULL),
(18, 'firebase_notification', 'Happy New Year', 'https://phumelectronic.com/wp-content/uploads/revo/30cbb72009bea1859277d11e0831728c.jpg', '{\"description\":\"Happy new year 2022\",\"link_to\":\"This New Year\\u2019s Eve 2022 is a big one, which means you\\u2019re sure to be taking tons of photos. Whether you\\u2019re celebrating at a friend\\u2019s virtual house party or at home with family, sharing a pic or two of your evening\\u2019s festivities on Instagram, along with a killer photo caption, will help you commemorate this momentous occasion.\"}', 0, 0, '2021-12-31 05:59:48', NULL);
