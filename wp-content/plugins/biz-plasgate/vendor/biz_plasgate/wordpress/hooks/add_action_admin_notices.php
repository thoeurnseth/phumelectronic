<?php



	if ( ! function_exists( 'biz_plasgate_admin_notices_missing_credentials' ) ):
		/**
		 * Adds the version of a package to the $jetpack_packages global array so that
		 * the autoloader is able to find it.
		 */
		function biz_plasgate_admin_notices_missing_credentials()
		{

			$gw_username = get_option( 'biz_plasgate_username' );   
			$gw_password = get_option( 'biz_plasgate_password' );   
			$gw_endpoint = get_option( 'biz_plasgate_endpoint' );   
			

			if ( empty($gw_username) OR empty($gw_password) OR empty($gw_endpoint) ):

				echo '<div class="notice notice-warning is-dismissible">
					<p>
						<strong>Biz PlasGate is almost ready.</strong>
						To complete your configuration, <a href="'. admin_url('/admin.php?page=biz-plasgate-page&tab=credentials') .'">enter the credentials here.</a>
					</p>
				</div>';

			endif;

		}

		add_action('admin_notices', 'biz_plasgate_admin_notices_missing_credentials');

	endif;
	





	if ( ! function_exists( 'biz_plasgate_admin_notices_missing_otp_template' ) ):
		/**
		 * Adds the version of a package to the $jetpack_packages global array so that
		 * the autoloader is able to find it.
		 */
		function biz_plasgate_admin_notices_missing_otp_template()
		{

			$template_sender_id 	= get_option( 'biz_plasgate_template_sender_id' );   
			$template_content 		= get_option( 'biz_plasgate_template_content' );   
			

			if ( empty($template_sender_id) OR empty($template_content) ):

				echo '<div class="notice notice-warning is-dismissible">
					<p>
						<strong>Biz PlasGate is almost ready.</strong>
						To complete your configuration, <a href="'. admin_url('/admin.php?page=biz-plasgate-page&tab=otp-sms-template') .'">enter the OTP SMS Template here.</a>
					</p>
				</div>';

			endif;

		}

		add_action('admin_notices', 'biz_plasgate_admin_notices_missing_otp_template');
		
	endif;