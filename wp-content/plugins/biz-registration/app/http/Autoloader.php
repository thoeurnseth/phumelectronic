<?php
/**
 * Plugin Name: Awesome Plugin
 * Version: 1.0
 * Description: This is the best plugin!
 * Author: Andrea Fuggetta <contact@ndevr.io>
 * Author URI: https://www.ndevr.io
 * Plugin URI:  https://www.ndevr.io
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Autoloader' ) ) {
    class Push_Notification_Autoloader {

        //Constructor
        public function __construct() {}


        // Main Loader fucntion
        // public static function init()
        // {
            
        // }
    
        /**
         * Register a custom menu page.
         */
        // public static function register_menu_page() {
        //     add_menu_page( 
        //         __( 'Notifiaction', 'Biz-Solution' ),
        //         'Notifiaction',
        //         'manage_options',
        //         'notification', // Slug
        //         'Push_Notification_Autoloader::page_content',
        //         plugins_url( 'myplugin/images/icon.png' ),
        //         80
        //     ); 
        // }
    
        /**
         * Display a custom menu page
         */
        // public static function page_content(){
        //     include( plugin_dir_path( __DIR__ ) . '../resources/views/notification-page.php' );
        // }

        /**
         * Push Notification
         */
        // public static function push_notification() {

        //     $data = array (
        //         "title" => "Hi",
        //         "text"  => "Hello",
        //         "body"  => "Hello",
        //         "type"  => "1",
        //         "id"    => "877",
        //         "nid"   => "1234523412"
        //     );

        //     $notification = array(
        //         "title" => "Hi",
        //         "text"  => "Hello",
        //         "body"  => "Hello",
        //         "type"  => "1",
        //         "id"    => "877",
        //         "icon"  => "notificationc",
        //         "sound" => "default"
        //     );

        //     $postfields = array(
        //         "condition" => "'ios' in topics || 'android' in topics",
        //         //"registration_ids" => $tokens, // Push multiple device specific token
        //         "priority" => "high",
        //         "data" => $data,
        //         "notification" => $notification
        //     );

        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => json_encode( $postfields ),
        //         CURLOPT_HTTPHEADER => array(
        //             'Authorization: key=AAAAUxq3pL8:APA91bF7_cwWkZQEo8yzb5OkCyUXLY28W34_H5BAuFh2HkxfHP8QDayicPKPDroj8Rx7X8tlYXW1Oy6TCBk9wgbooFFUFJYi6zgTn5nnwEbUfHuCm_trEU4ocOLVA9ktxBxviZ2n4k_Q',
        //             'Content-Type: application/json'
        //         )
        //     ));

        //     $response = curl_exec($curl);

        //     curl_close($curl);
        //     //echo $response;
        // }
    }
    
    // instantiate the plugin class
    $notification = new Push_Notification_Autoloader();
    $notification::init();
}