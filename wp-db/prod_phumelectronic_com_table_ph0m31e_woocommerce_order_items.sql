
-- --------------------------------------------------------

--
-- Table structure for table `ph0m31e_woocommerce_order_items`
--

CREATE TABLE `ph0m31e_woocommerce_order_items` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_item_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ph0m31e_woocommerce_order_items`
--

INSERT INTO `ph0m31e_woocommerce_order_items` (`order_item_id`, `order_item_name`, `order_item_type`, `order_id`) VALUES
(302, 'ROSS 32-70 inches Flat to Wall with tilt LTV Wallmount - Normal', 'line_item', 9239),
(303, 'Free shipping', 'shipping', 9239),
(312, 'ROSS 32-70 inches Flat to Wall with tilt LTV Wallmount - Normal', 'line_item', 9244),
(313, 'Free shipping', 'shipping', 9244),
(314, 'Replacement Filter for Puricare,All - Normal', 'line_item', 9250),
(315, 'Free shipping', 'shipping', 9250),
(316, 'ROSS 32-70 inches Flat to Wall with tilt LTV Wallmount - Normal', 'line_item', 9260),
(317, 'Free shipping', 'shipping', 9260),
(318, 'PANA Rice Cooker 1L,400W,Steam 5H - Normal', 'line_item', 9264),
(319, 'Free shipping', 'shipping', 9264),
(320, 'PANA Rice Cooker 1.8L,650W,Steam 4H - Normal', 'line_item', 9265),
(321, 'Free shipping', 'shipping', 9265),
(322, 'PANA Rice Cooker 1.8L,650W,Steam 4H - Normal', 'line_item', 9272),
(323, 'Free shipping', 'shipping', 9272),
(324, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 9275),
(325, 'Free shipping', 'shipping', 9275),
(326, 'PANA Water Boiler 2200W 1.6L - Normal', 'line_item', 9278),
(327, 'Free shipping', 'shipping', 9278),
(328, 'MASTERPLUG SMALL CASSETTE REEL 5M, 4 UNIVERSAL SOCKET - Normal', 'line_item', 9280),
(329, 'Free shipping', 'shipping', 9280),
(330, 'MASTERPLUG 6 GANG 3.0M USB PLUG - Normal', 'line_item', 9386),
(331, 'Free shipping', 'shipping', 9386),
(332, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 9412),
(333, 'Free shipping', 'shipping', 9412),
(334, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 9415),
(335, 'Free shipping', 'shipping', 9415),
(336, 'MASTERPLUG 6 GANG 5.0M USB PLUG - Normal', 'line_item', 9419),
(337, 'Free shipping', 'shipping', 9419),
(338, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 9495),
(339, 'Free shipping', 'shipping', 9495),
(340, 'ROSS 32-70 inches Flat to Wall with tilt LTV Wallmount - Normal', 'line_item', 9508),
(341, 'PANA Rice Cooker 1.8L,650W,Steam 4H - Normal', 'line_item', 9509),
(342, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 9510),
(343, 'LG 65\" 4K OLED Smart LTV - Normal', 'line_item', 9587),
(344, 'LUCECO 1000 LUMENS 10W 6500K CCT TORCH LIGHT - Normal', 'line_item', 9587),
(345, 'Free shipping', 'shipping', 9587),
(346, 'LG Front Load 9Kg AI Direct Drive WM - Normal', 'line_item', 9629),
(347, 'LG 55\" 4K UHD Smart LTV - Normal', 'line_item', 9629),
(348, 'PANA Home Shower Non Jet Pump 3.6KW,S:420mm x 185mm x 85mm - Normal', 'line_item', 9629),
(349, 'Free shipping', 'shipping', 9629),
(350, 'MIDEA 1HP Inverter Wall Mounted RAC - Normal', 'line_item', 9980),
(351, 'Free shipping', 'shipping', 9980),
(352, 'PANA micom rice cooker 1.8L - Normal', 'line_item', 10178),
(353, 'Free shipping', 'shipping', 10178),
(354, 'PANA MWO 1270W Mechanical - Normal', 'line_item', 10181),
(355, 'Free shipping', 'shipping', 10181),
(356, 'PANA micom rice cooker 1.8L - Normal', 'line_item', 10198),
(357, 'Free shipping', 'shipping', 10198),
(358, 'LG 1HP Dual Inverter Air Purifying System RAC - Normal', 'line_item', 10201),
(359, 'LG 2 Doors 411L REF - Normal', 'line_item', 10201),
(360, 'Free shipping', 'shipping', 10201),
(361, 'LG 65\" 4K OLED Smart LTV - Normal', 'line_item', 10202),
(362, 'Free shipping', 'shipping', 10202),
(363, 'LG 65\" 4K OLED Smart LTV - Normal', 'line_item', 10203),
(364, 'Free shipping', 'shipping', 10203),
(365, 'PANA micom rice cooker 1.8L - Normal', 'line_item', 10205),
(366, 'Free shipping', 'shipping', 10205),
(367, 'PANA White Tube Juicer 220W,3.9Kg - Normal', 'line_item', 10207),
(368, 'Free shipping', 'shipping', 10207),
(369, 'LUCECO 500mA 30,000HOURS USB CHARGABLE DESK LAMP - Normal', 'line_item', 10212),
(370, 'Free shipping', 'shipping', 10212),
(371, 'LUCECO 500mA 30,000HOURS USB CHARGABLE DESK LAMP - Normal', 'line_item', 10249),
(372, 'Free shipping', 'shipping', 10249),
(373, 'LUCECO 500mA 30,000HOURS USB CHARGABLE DESK LAMP - Normal', 'line_item', 10250),
(374, 'Free shipping', 'shipping', 10250),
(375, 'LUCECO 500mA 30,000HOURS USB CHARGABLE DESK LAMP - Normal', 'line_item', 10252),
(376, 'LUCECO 30,000HOURS DESK LAMP - Normal', 'line_item', 10252),
(377, 'Free shipping', 'shipping', 10252),
(378, 'discoupon80', 'coupon', 10252),
(379, 'LUCECO 1800 LUMENS 30W 5000K TWIN HEAD WORK LIGHT - Normal', 'line_item', 10256),
(380, 'Free shipping', 'shipping', 10256),
(381, 'PANA Rice Cooker 1.8L,800W,Steam 5H - Normal', 'line_item', 10961),
(382, 'Free shipping', 'shipping', 10961),
(387, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'line_item', 12017),
(388, 'Free shipping', 'shipping', 12017),
(389, 'PANA Vacuum 2200W - Normal', 'line_item', 12018),
(390, 'Free shipping', 'shipping', 12018),
(391, 'PANA Vacuum 2200W - Normal', 'line_item', 12019),
(392, 'Free shipping', 'shipping', 12019),
(393, 'PANA Rice Cooker 1.8L,800W,Steam 5H - Normal', 'line_item', 12020),
(394, 'Free shipping', 'shipping', 12020),
(395, 'PANA Conventional hair Styler', 'line_item', 12023),
(396, 'Free shipping', 'shipping', 12023),
(397, 'PANA Rice Cooker 1.8L,800W,Steam 5H', 'line_item', 12024),
(398, 'Free shipping', 'shipping', 12024),
(399, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT', 'line_item', 12026),
(400, 'Free shipping', 'shipping', 12026),
(401, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12027),
(402, 'Free shipping', 'shipping', 12027),
(403, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12028),
(404, 'Free shipping', 'shipping', 12028),
(405, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'line_item', 12030),
(406, 'Free shipping', 'shipping', 12030),
(407, 'PANA micom rice cooker 1.8L - Normal', 'line_item', 12033),
(408, 'Free shipping', 'shipping', 12033),
(409, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12035),
(410, 'Free shipping', 'shipping', 12035),
(411, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'line_item', 12037),
(412, 'Free shipping', 'shipping', 12037),
(413, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'line_item', 12038),
(414, 'Free shipping', 'shipping', 12038),
(415, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H', 'line_item', 12041),
(416, 'Free shipping', 'shipping', 12041),
(417, 'PANA White Tube Juicer 220W,3.9Kg', 'line_item', 12042),
(418, 'Free shipping', 'shipping', 12042),
(419, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12046),
(420, 'Free shipping', 'shipping', 12046),
(421, 'PANA White Tube Juicer 220W,3.9Kg - Normal', 'line_item', 12047),
(422, 'Free shipping', 'shipping', 12047),
(423, 'ROSS 32-50 Inches ceiling LTV Wall Mount - Normal', 'line_item', 12048),
(424, 'Free shipping', 'shipping', 12048),
(425, 'ROSS 36-63 Inches flat to wall LTV Wall Mount', 'line_item', 12050),
(426, 'Free shipping', 'shipping', 12050),
(427, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT', 'line_item', 12052),
(428, 'Free shipping', 'shipping', 12052),
(429, 'LG 55\" Nano Smart LTV', 'line_item', 12059),
(430, 'Free shipping', 'shipping', 12059),
(431, 'LG Top Load 13Kg Smart Inverter Smart Diagnoisis WM', 'line_item', 12060),
(432, 'Free shipping', 'shipping', 12060),
(433, 'MASTERPLUG 5 GANG 5.0M 1.0MM2 PLUG', 'line_item', 12062),
(434, 'Free shipping', 'shipping', 12062),
(435, 'LG Top Load 13Kg Smart Inverter Smart Diagnoisis WM - Normal', 'line_item', 12063),
(436, 'Free shipping', 'shipping', 12063),
(437, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12064),
(438, 'LG Ceiling Fan Air Circulation - Normal', 'line_item', 12064),
(439, 'Free shipping', 'shipping', 12064),
(440, 'PANA micom rice cooker 1.8L', 'line_item', 12068),
(441, 'Free shipping', 'shipping', 12068),
(442, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12071),
(443, 'Free shipping', 'shipping', 12071),
(444, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12073),
(445, 'Free shipping', 'shipping', 12073),
(446, 'PANA White Tube Juicer 220W,3.9Kg - Normal', 'line_item', 12075),
(447, 'Free shipping', 'shipping', 12075),
(448, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12078),
(449, 'Free shipping', 'shipping', 12078),
(450, 'PANA Dry Iron 1000W,0.8Kg', 'line_item', 12079),
(451, 'Free shipping', 'shipping', 12079),
(452, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12082),
(453, 'Free shipping', 'shipping', 12082),
(454, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12083),
(455, 'Free shipping', 'shipping', 12083),
(456, 'PANA micom rice cooker 1.8L', 'line_item', 12084),
(457, 'Free shipping', 'shipping', 12084),
(458, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12085),
(459, 'Free shipping', 'shipping', 12085),
(460, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12087),
(461, 'Free shipping', 'shipping', 12087),
(462, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12088),
(463, 'Free shipping', 'shipping', 12088),
(464, 'PANA 2Door 288L REF - Normal', 'line_item', 12090),
(465, 'Free shipping', 'shipping', 12090),
(466, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12091),
(467, 'Free shipping', 'shipping', 12091),
(468, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12092),
(469, 'Free shipping', 'shipping', 12092),
(470, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12093),
(471, 'Free shipping', 'shipping', 12093),
(472, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12101),
(473, 'Free shipping', 'shipping', 12101),
(474, 'PANA micom rice cooker 1.8L', 'line_item', 12103),
(475, 'Free shipping', 'shipping', 12103),
(476, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12105),
(477, 'Free shipping', 'shipping', 12105),
(478, 'PANA Rice Cooker 7.2L,2500W,Stearm 5H - Normal', 'line_item', 12107),
(479, 'Free shipping', 'shipping', 12107),
(480, 'UV Case - Normal', 'line_item', 12108),
(481, 'Free shipping', 'shipping', 12108),
(482, '[AP300AWFA] LG Puricare,All Mask', 'line_item', 12109),
(483, 'Free shipping', 'shipping', 12109),
(484, 'PANA Dry Iron 1000W,0.8Kg', 'line_item', 12110),
(485, 'Free shipping', 'shipping', 12110),
(486, 'PANA Dry Iron 1000W,0.8Kg', 'line_item', 12112),
(487, 'Free shipping', 'shipping', 12112),
(488, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12113),
(489, 'Free shipping', 'shipping', 12113),
(490, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12114),
(491, 'Free shipping', 'shipping', 12114),
(492, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12116),
(493, 'Free shipping', 'shipping', 12116),
(496, 'PANA Vacuum 2200W - Normal', 'line_item', 12118),
(497, 'Free shipping', 'shipping', 12118),
(498, 'PANA Vacuum 2200W - Normal', 'line_item', 12119),
(499, 'Free shipping', 'shipping', 12119),
(500, 'phumelectronics', 'coupon', 12119),
(501, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12120),
(502, 'Free shipping', 'shipping', 12120),
(503, 'LG Wireless Bluetooth Headphones - White AV - Normal', 'line_item', 12124),
(504, 'Free shipping', 'shipping', 12124),
(505, 'discoupon80', 'coupon', 12124),
(516, 'PANA Top Load 16Kg WM - Normal', 'line_item', 12156),
(517, 'Free shipping', 'shipping', 12156),
(526, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12163),
(527, 'Free shipping', 'shipping', 12163),
(528, 'LUCECO RECHARGEABLE LED CLAMP DESK LAMP, 1.5W, 100LUMENS(MAX), 6000K CCT - Normal', 'line_item', 12168),
(529, 'Free shipping', 'shipping', 12168),
(530, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12171),
(531, 'Free shipping', 'shipping', 12171),
(532, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12172),
(533, 'Free shipping', 'shipping', 12172),
(534, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12173),
(535, 'Free shipping', 'shipping', 12173),
(536, 'LG 1.5HP Dual Inverter Skin Care RAC - Normal', 'line_item', 12175),
(537, 'Free shipping', 'shipping', 12175),
(538, 'Ecommerce product testing - Normal', 'line_item', 12179),
(539, 'Free shipping', 'shipping', 12179),
(540, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12320),
(541, 'Free shipping', 'shipping', 12320),
(542, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12321),
(543, 'Free shipping', 'shipping', 12321),
(544, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12322),
(545, 'Free shipping', 'shipping', 12322),
(546, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12324),
(547, 'Free shipping', 'shipping', 12324),
(548, 'PANA Top Load 12.5Kg, Black Silver WM - Normal', 'line_item', 12325),
(549, 'Free shipping', 'shipping', 12325),
(550, 'Ecommerce product testing - Normal', 'line_item', 12338),
(551, 'Free shipping', 'shipping', 12338),
(552, 'Ecommerce product testing - Normal', 'line_item', 12339),
(553, 'Free shipping', 'shipping', 12339),
(554, 'Ecommerce product testing - Normal', 'line_item', 12340),
(555, 'Free shipping', 'shipping', 12340),
(556, 'Ecommerce product testing - Normal', 'line_item', 12341),
(557, 'Free shipping', 'shipping', 12341),
(558, 'Ecommerce product testing - Normal', 'line_item', 12342),
(559, 'Free shipping', 'shipping', 12342),
(562, 'Ecommerce product testing - Normal', 'line_item', 12343),
(563, 'Free shipping', 'shipping', 12343),
(564, 'Ecommerce product testing - Normal', 'line_item', 12344),
(565, 'Free shipping', 'shipping', 12344),
(566, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12345),
(567, 'Free shipping', 'shipping', 12345),
(568, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12346),
(569, 'Free shipping', 'shipping', 12346),
(570, 'LG 1.0HP Dual Inverter Skin Care RAC - Normal', 'line_item', 12348),
(571, 'Free shipping', 'shipping', 12348),
(572, 'PANA Top Load 16Kg WM - Normal', 'line_item', 12350),
(573, 'Free shipping', 'shipping', 12350),
(574, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12386),
(575, 'Free shipping', 'shipping', 12386),
(576, 'LG 65\" OLED 4K Smart LTV - Normal', 'line_item', 12388),
(577, 'Free shipping', 'shipping', 12388),
(702, 'Ecommerce product testing - Normal', 'line_item', 12464),
(703, 'Free shipping', 'shipping', 12464),
(704, 'Ecommerce product testing - Normal', 'line_item', 12466),
(705, 'Free shipping', 'shipping', 12466),
(706, 'Ecommerce product testing - Normal', 'line_item', 12467),
(707, 'Free shipping', 'shipping', 12467),
(708, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12468),
(709, 'Free shipping', 'shipping', 12468),
(710, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12470),
(711, 'Free shipping', 'shipping', 12470),
(712, 'Ecommerce product testing - Normal', 'line_item', 12471),
(713, 'Free shipping', 'shipping', 12471),
(714, 'Ecommerce product testing - Normal', 'line_item', 12472),
(715, 'Free shipping', 'shipping', 12472),
(716, 'Ecommerce product testing - Normal', 'line_item', 12473),
(717, 'Free shipping', 'shipping', 12473),
(718, 'Ecommerce product testing - Normal', 'line_item', 12474),
(719, 'Free shipping', 'shipping', 12474),
(720, 'Ecommerce product testing - Normal', 'line_item', 12475),
(721, 'Free shipping', 'shipping', 12475),
(722, 'PANA Water Boiler 2200W 1.7L - Normal', 'line_item', 12476),
(723, 'Free shipping', 'shipping', 12476),
(724, 'Ecommerce product testing - Normal', 'line_item', 12477),
(725, 'Free shipping', 'shipping', 12477),
(726, 'UV Case - Normal', 'line_item', 12478),
(727, 'Free shipping', 'shipping', 12478),
(728, 'Ecommerce product testing - Normal', 'line_item', 12479),
(729, 'Free shipping', 'shipping', 12479),
(730, 'Ecommerce product testing - Normal', 'line_item', 12480),
(731, 'Free shipping', 'shipping', 12480),
(732, 'Ecommerce product testing - Normal', 'line_item', 12482),
(733, 'Free shipping', 'shipping', 12482),
(734, 'Ecommerce product testing - Normal', 'line_item', 12484),
(735, 'Free shipping', 'shipping', 12484),
(736, 'Ecommerce product testing - Normal', 'line_item', 12486),
(737, 'Free shipping', 'shipping', 12486),
(738, 'Ecommerce product testing - Normal', 'line_item', 12492),
(739, 'Free shipping', 'shipping', 12492),
(740, 'Ecommerce product testing - Normal', 'line_item', 12494),
(741, 'Free shipping', 'shipping', 12494),
(742, 'Ecommerce product testing - Normal', 'line_item', 12501),
(743, 'Free shipping', 'shipping', 12501),
(744, 'Ecommerce product testing - Normal', 'line_item', 12502),
(745, 'Free shipping', 'shipping', 12502),
(746, 'Ecommerce product testing - Normal', 'line_item', 12503),
(747, 'Free shipping', 'shipping', 12503),
(748, 'Ecommerce product testing - Normal', 'line_item', 12504),
(749, 'Free shipping', 'shipping', 12504),
(750, 'Ecommerce product testing - Normal', 'line_item', 12505),
(751, 'Free shipping', 'shipping', 12505),
(752, 'Ecommerce product testing - Normal', 'line_item', 12506),
(753, 'Free shipping', 'shipping', 12506),
(754, 'Ecommerce product testing - Normal', 'line_item', 12507),
(755, 'Free shipping', 'shipping', 12507),
(756, 'Ecommerce product testing - Normal', 'line_item', 12508),
(757, 'Free shipping', 'shipping', 12508),
(758, 'Ecommerce product testing - Normal', 'line_item', 12510),
(759, 'Free shipping', 'shipping', 12510),
(760, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12511),
(761, 'Free shipping', 'shipping', 12511),
(762, 'Ecommerce product testing - Normal', 'line_item', 12517),
(763, 'Free shipping', 'shipping', 12517),
(764, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12518),
(765, 'Free shipping', 'shipping', 12518),
(766, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12519),
(767, 'Free shipping', 'shipping', 12519),
(768, 'Ecommerce product testing - Normal', 'line_item', 12522),
(769, 'Free shipping', 'shipping', 12522),
(770, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12524),
(771, 'Free shipping', 'shipping', 12524),
(772, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12529),
(773, 'Free shipping', 'shipping', 12529),
(774, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12531),
(775, 'Free shipping', 'shipping', 12531),
(776, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12532),
(777, 'Free shipping', 'shipping', 12532),
(778, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12533),
(779, 'Free shipping', 'shipping', 12533),
(780, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12538),
(781, 'Free shipping', 'shipping', 12538),
(782, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12539),
(783, 'Free shipping', 'shipping', 12539),
(784, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12540),
(785, 'Free shipping', 'shipping', 12540),
(786, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12542),
(787, 'Free shipping', 'shipping', 12542),
(788, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12543),
(789, 'Free shipping', 'shipping', 12543),
(790, 'LUCECO RECHARGEABLE LED COLOR CHANGE DESK LAMP, 4W, 200LUMENS(MAX), 3000K/4000K/6000K CCT - Normal', 'line_item', 12547),
(791, 'Free shipping', 'shipping', 12547),
(792, 'UV Case - Normal', 'line_item', 12592),
(793, 'Free shipping', 'shipping', 12592),
(794, 'PANA Dry Iron 1000W,0.8Kg - Normal', 'line_item', 12603),
(795, 'Free shipping', 'shipping', 12603),
(796, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12605),
(797, 'Free shipping', 'shipping', 12605),
(798, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12606),
(799, 'Free shipping', 'shipping', 12606),
(800, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12607),
(801, 'LG Top Load 12Kg Smart Inverter WM - Normal', 'line_item', 12607),
(802, 'Free shipping', 'shipping', 12607),
(803, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12615),
(804, 'Free shipping', 'shipping', 12615),
(805, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12638),
(806, 'UV Case - Normal', 'line_item', 12638),
(807, 'LG Front Load 9Kg AI Direct Drive WM - Normal', 'line_item', 12638),
(808, 'Free shipping', 'shipping', 12638),
(809, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12639),
(810, 'UV Case - Normal', 'line_item', 12639),
(811, 'LG Front Load 9Kg AI Direct Drive WM - Normal', 'line_item', 12639),
(812, 'Free shipping', 'shipping', 12639),
(813, '[AP300AWFA] LG Puricare,All Mask - Normal', 'line_item', 12642),
(814, 'UV Case - Normal', 'line_item', 12642),
(815, 'LG Front Load 9Kg 6 Motion Direct Drive WM - Normal', 'line_item', 12642),
(816, 'Free shipping', 'shipping', 12642),
(817, 'LG 1.5HP Dual Art Cool Inverter RAC - Normal', 'line_item', 12644),
(818, 'Free shipping', 'shipping', 12644),
(821, 'LG Front Load 9Kg 6 Motion Direct Drive WM - Normal', 'line_item', 12648),
(822, 'Free shipping', 'shipping', 12648),
(823, 'LG Front Load 9Kg 6 Motion Direct Drive WM - Normal', 'line_item', 12649),
(824, 'Free shipping', 'shipping', 12649),
(825, 'ROSS 32-70 Inches Flat to Wall with tilt LTV Wall Mount - Normal', 'line_item', 12650),
(826, 'Free shipping', 'shipping', 12650),
(827, 'LG 75\" UHD 4K Smart LTV - Normal', 'line_item', 12651),
(828, 'Free shipping', 'shipping', 12651),
(829, 'LG 75\" UHD 4K Smart LTV - Normal', 'line_item', 12652),
(830, 'Free shipping', 'shipping', 12652);
