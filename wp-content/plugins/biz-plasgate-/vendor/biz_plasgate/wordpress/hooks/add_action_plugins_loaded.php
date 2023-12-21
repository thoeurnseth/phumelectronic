<?php

	if( !function_exists("biz_plasgate_register_setting") ):
		function biz_plasgate_register_setting()
		{
			global $wpdb;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$biz_phonenumber 	= $wpdb->prefix . "biz_plasgate_phonenumber";
			$biz_sms 			= $wpdb->prefix . "biz_plasgate_sms";

			$sql = "CREATE TABLE $biz_phonenumber (
				id 			int(11) unsigned NOT NULL AUTO_INCREMENT,
				prefix 		varchar(255) NULL,
				phone 		varchar(255) NULL,
				otp_number 	varchar(255) NULL,
				status 		varchar(255) NULL,
				created_at 	TIMESTAMP NOT NULL,
				updated_at 	TIMESTAMP NOT NULL,
				PRIMARY KEY  (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

			$sql .= "CREATE TABLE $biz_sms (
				id 						int(11) unsigned NOT NULL AUTO_INCREMENT,
				sender_id 				varchar(255) NULL,
				prefix 					varchar(255) NULL,
				phone 					varchar(255) NULL,
				content 				TEXT NULL,
				plasgate_response 		varchar(255) NULL,
				created_at 				TIMESTAMP NOT NULL,
				updated_at 				TIMESTAMP NOT NULL,
				PRIMARY KEY  (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
			dbDelta( $sql );
		}
		add_action( 'admin_init', 'biz_plasgate_register_setting' );
	endif;