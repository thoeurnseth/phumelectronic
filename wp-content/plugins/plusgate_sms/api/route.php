<?php
/**
 * Description: This file for register route API.
 * Version: 0.0.1
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

add_action( 'rest_api_init', function () {
    /**
     * Name: API request send OTP
     * Date: 14-02-2023
     */
    register_rest_route( 'plusgate_sms/api', '/send_otp', array(
    // register_rest_route( 'biz-plasgate/api/v2', '/send-otp', array(
        'methods' => 'POST',
        'callback' => array(new PlusGateSMS_Controller(),'send_otp'),
        'permission_callback' => function() {
            return true;
        }
    ));

    /**
     * Name: API request Verify OTP
     * Date: 14-02-2023
     */
    register_rest_route( 'plusgate_sms/api', '/verify_otp', array(
    // register_rest_route( 'biz-plasgate/api/v2', '/verify-otp', array(
        'methods' => 'POST',
        'callback' => array(new PlusGateSMS_Controller(),'verify_otp'),
        'permission_callback' => function() {
            return true;
        }
    ));
} );