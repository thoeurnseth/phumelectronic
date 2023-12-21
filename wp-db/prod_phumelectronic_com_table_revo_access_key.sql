
-- --------------------------------------------------------

--
-- Table structure for table `revo_access_key`
--

CREATE TABLE `revo_access_key` (
  `id` int(11) NOT NULL,
  `firebase_servey_key` text DEFAULT NULL,
  `firebase_api_key` text DEFAULT NULL,
  `firebase_auth_domain` text DEFAULT NULL,
  `firebase_database_url` text DEFAULT NULL,
  `firebase_project_id` text DEFAULT NULL,
  `firebase_storage_bucket` text DEFAULT NULL,
  `firebase_messaging_sender_id` text DEFAULT NULL,
  `firebase_app_id` text DEFAULT NULL,
  `firebase_measurement_id` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `revo_access_key`
--

INSERT INTO `revo_access_key` (`id`, `firebase_servey_key`, `firebase_api_key`, `firebase_auth_domain`, `firebase_database_url`, `firebase_project_id`, `firebase_storage_bucket`, `firebase_messaging_sender_id`, `firebase_app_id`, `firebase_measurement_id`, `created_at`) VALUES
(1, 'AAAALfcBv9I:APA91bHcKybyF7gJyQPYfjO1VrYHpfQfmPt34Av9Xy8Ea7kyC5aH6sY2qv8AYmDx0pKYDMzP7UL97THmPgB2R3NfLMupPGwtVUSTEaEMne4l_xgsyu7ZL8ePE-3KYfZuHnQVKi1Y5r0P', '', 'https://pe-staging-335009.firebaseapp.com/__/auth/handler', '', '', '', '197417615314', '', '', '2021-11-26 08:29:10');
