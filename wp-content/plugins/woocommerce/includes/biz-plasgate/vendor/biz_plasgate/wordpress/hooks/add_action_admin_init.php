<?php

    if( !function_exists("biz_plasgate_register_setting") ):
        
        function biz_plasgate_register_setting()
        {

            $options = array(
                'biz_plasgate_credentials_settings' 		=>	['biz_plasgate_username', 'biz_plasgate_password', 'biz_plasgate_endpoint'],
                'biz_plasgate_otp_settings_settings' 		=>	['biz_plasgate_pin_code_digit_number', 'biz_plasgate_interval', 'biz_plasgate_failed_attempt_limit', 'biz_plasgate_failed_attempt_period_of_suspension', 'biz_plasgate_blocked_prefixes', 'biz_plasgate_disable_plasgate', 'biz_plasgate_auth_plugin', 'biz_plasgate_php_auth_user', 'biz_plasgate_php_auth_pw'],
                'biz_plasgate_message_template_settings'	=>	['biz_plasgate_template_sender_id', 'biz_plasgate_template_content']
            );

            foreach( $options as $option_group => $option_names ):

                foreach( $option_names as $option_name ):

                    register_setting( $option_group, $option_name );

                endforeach;

            endforeach;

        }

        add_action( 'admin_init', 'biz_plasgate_register_setting' );

    endif;
