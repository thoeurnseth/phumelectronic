<?php
/**
 * Plugin Name: Biz Web Push
 * Plugin URI: https://bizsolution.com.kh
 * Description: This plugin is designed for Received Notification Web when has new order from clients.
 * Version: 1.0.0
 * Author: Biz
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

function web_push_notification_enqueue_script()
{   
    $dir = plugin_dir_url( __FILE__ );
    wp_enqueue_script('firebase-script', 'https://www.gstatic.com/firebasejs/7.16.1/firebase-app.js', array(), '7.16.1', true);
    wp_enqueue_script('firebase-messaging-script', 'https://www.gstatic.com/firebasejs/7.16.1/firebase-messaging.js', array('firebase-script'), '7.16.1', true);
    wp_enqueue_script('web-push-script', $dir.'/js/script.js', array('firebase-messaging-script'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'web_push_notification_enqueue_script');


add_action( 'woocommerce_before_thankyou', 'success_message_after_payment' );
function success_message_after_payment( $order_id ){
    
    // Get the WC_Order Object
    $order = wc_get_order( $order_id );

    if ( $order->has_status('processing') ){
        global $wpdb;
        $orderNumber    = $order->get_order_number();
        $cust_name      = $order->get_billing_first_name()." ".$order->get_billing_last_name();
        $table_name     = $wpdb->prefix . "web_token";
        $tokens         = $wpdb->get_results( "SELECT * FROM $table_name" );
        foreach($tokens as $token){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "data": {
                    "title": "[Phum Electronic]: New order # #'.$orderNumber.'",
                    "body": "You\'ve received the following order from '.$cust_name.'"
                },
                "to": "'.$token->token.'"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: key=AAAALfcBv9I:APA91bHcKybyF7gJyQPYfjO1VrYHpfQfmPt34Av9Xy8Ea7kyC5aH6sY2qv8AYmDx0pKYDMzP7UL97THmPgB2R3NfLMupPGwtVUSTEaEMne4l_xgsyu7ZL8ePE-3KYfZuHnQVKi1Y5r0P',
                    'Content-Type: application/json'
                ),
            ));  
            $response = curl_exec($curl);
            curl_close($curl);
        }
    }
}

function register_web_token() {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . "web_token";
    $token = $_POST['token'];

    if(empty($token)){
        $array_result = array(
            'status' => '0',
            'message' => 'Token empty!'
        );
        wp_send_json($array_result);
    }

    $tokens = $wpdb->get_results( "SELECT * FROM $table_name WHERE token='$token'" );
    if(count($tokens)==0){
        $wpdb->insert($table_name, array('token' => $token,'user_id' => $user_id, 'created_at' => date("Y-m-d H:i:s"))); 
        $array_result = array(
            'status' => '1',
            'message' => 'Insert token successfully'
        );
        wp_send_json($array_result);
    }else{
        $array_result = array(
            'status' => '0',
            'message' => 'Token Existing!'
        );
        wp_send_json($array_result);
    }

    wp_die();
}
add_action('wp_ajax_register_web_token', 'register_web_token' );
