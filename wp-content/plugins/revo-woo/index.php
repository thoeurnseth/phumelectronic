<?php
  /*
  Plugin Name: REVO APPS - Flutter Woocommerce Full App Manager
  Plugin URI:
  Description: Mobile App Management. Manage everything from WP-ADMIN.
  Author: Revo Apps
  Version: 2.5.0
  Build 2021
  */ 

  // Testing Performance when put this line bellow:
  define('SHORTINIT', 1);
  // End Test

  if (!defined('WPINC')) {
      die;
  }

  // if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ){
  //   global $wpdb;
  //   $wpdb->query( "
  //                   DROP TABLE IF EXISTS revo_access_key;
  //                   DROP TABLE IF EXISTS revo_extend_products;
  //                   DROP TABLE IF EXISTS revo_flash_sale;
  //                   DROP TABLE IF EXISTS revo_hit_products;
  //                   DROP TABLE IF EXISTS revo_list_categories;
  //                   DROP TABLE IF EXISTS revo_list_mini_banner;
  //                   DROP TABLE IF EXISTS revo_mobile_slider;
  //                   DROP TABLE IF EXISTS revo_mobile_variable;
  //                   DROP TABLE IF EXISTS revo_notification;
  //                   DROP TABLE IF EXISTS revo_popular_categories;
  //                   DROP TABLE IF EXISTS revo_token_firebase;
  //                 " );
  //   delete_option("2.3.2");
  // }

  $upload = wp_upload_dir();
  $upload_dir = $upload['basedir'];
  $upload_dir = $upload_dir . '/revo';
  if (! is_dir($upload_dir)) {
    mkdir( $upload_dir, 0777 );
  }

  $revo_plugin_name = 'revo-apps-setting';
  $revo_plugin_version = '2.5.0';
  global $revo_plugin_version;

  require (plugin_dir_path( __FILE__ ).'helper.php');
  
  add_action('woocommerce_new_order', 'notif_new_order',  10, 1  );
  add_action('woocommerce_order_status_changed', 'notif_new_order', 10, 1  );
  
  $revo_api_url     = 'revo-admin/v1';
  $revo_api_url_v2  = 'revo-admin/v2';

  add_action("admin_menu","routes");
  add_action( 'rest_api_init', function () {

  global $revo_api_url;
  global $revo_api_url_v2;

  security_0auth();

  //Wc - Odoo Add Multi - Customer
  register_rest_route( $revo_api_url, '/add-multi-customer', array(
    'methods' => 'POST',
    'callback' => 'add_multi_customer',
  ));

  //View Cart V2
  register_rest_route( $revo_api_url_v2, 'cart/items', array(
    'methods' => 'POST',
    'callback' => 'rest_cart_by_user_v2',
  ));

  //Add Cart V2
  register_rest_route( $revo_api_url_v2, 'cart/items-add', array(
    'methods' => 'POST',
    'callback' => 'add_cart_by_user_v2',
  ));

  //Add Cart Multi V2
  register_rest_route( $revo_api_url_v2, 'cart/add-multiple', array(
    'methods' => 'POST',
    'callback' => 'multi_cart_add_by_user_v2',
  ));

  //Update - Remove Cart v2
  register_rest_route( $revo_api_url_v2, 'cart/items-update', array(
    'methods' => 'POST',
    'callback' => 'update_cart_by_user_v2',
  ));

  register_rest_route( $revo_api_url, '/home-api', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_home',
  ));

  register_rest_route( $revo_api_url, '/home-api/recommend-product', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_recommend_product',
  ));
  
  register_rest_route( $revo_api_url, '/home-api/slider', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_slider',
  ));
  register_rest_route( $revo_api_url, '/home-api/categories', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_categories',
  ));
  register_rest_route( $revo_api_url, '/home-api/mini-banner', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_mini_banner',
  ));
  register_rest_route( $revo_api_url, '/home-api/flash-sale', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_flash_sale',
  ));
  register_rest_route( $revo_api_url, '/home-api/extend-products', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_extend_products',
  ));
  register_rest_route( $revo_api_url, '/home-api/hit-products', array(
    'methods' => 'POST',
    'callback' => 'rest_hit_products',
  ));
  register_rest_route( $revo_api_url, '/home-api/recent-view-products', array(
    'methods' => 'POST',
    'callback' => 'rest_get_hit_products',
  ));
  register_rest_route( $revo_api_url, '/home-api/intro-page', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_get_intro_page',
  ));
  register_rest_route( $revo_api_url, '/home-api/splash-screen', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_get_splash_screen',
  ));
  register_rest_route( $revo_api_url, '/home-api/general-settings', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_get_general_settings',
  ));
  register_rest_route( $revo_api_url, '/home-api/add-remove-wistlist', array(
    'methods' => 'POST',
    'callback' => 'rest_add_remove_wistlist',
  ));
  register_rest_route( $revo_api_url, '/home-api/list-product-wistlist', array(
    'methods' => 'POST',
    'callback' => 'rest_list_wistlist',
  ));
  register_rest_route( $revo_api_url, '/home-api/popular-categories', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'popular_categories',
  ));
  register_rest_route( $revo_api_url, '/home-api/key-firebase', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'rest_key_firebase',
  ));
  register_rest_route( $revo_api_url, '/home-api/input-token-firebase', array(
    'methods' => 'POST',
    'callback' => 'rest_token_user_firebase',
  ));
  register_rest_route( $revo_api_url, '/home-api/check-produk-variation', array(
    'methods' => 'POST',
    'callback' => 'rest_check_variation',
  ));
  register_rest_route( $revo_api_url, '/home-api/list-orders', array(
    'methods' => 'POST',
    'callback' => 'rest_list_orders',
  ));
  register_rest_route( $revo_api_url, '/home-api/list-review-user', array(
    'methods' => 'POST',
    'callback' => 'rest_list_review',
  ));
  register_rest_route( $revo_api_url, '/home-api/list-notification', array(
    'methods' => 'POST',
    'callback' => 'rest_list_notification',
  ));
  register_rest_route( $revo_api_url, '/home-api/read-notification', array(
    'methods' => 'POST',
    'callback' => 'rest_read_notification',
  ));
  register_rest_route( $revo_api_url, '/list-categories', array(
    'methods' => 'POST',
    'callback' => 'rest_categories_list',
  ));
  register_rest_route( $revo_api_url, '/insert-review', array(
    'methods' => 'POST',
    'callback' => 'rest_insert_review',
  ));
  register_rest_route( $revo_api_url, '/get-barcode', array(
    'methods' => 'POST',
    'callback' => 'rest_get_barcode',
  ));
  register_rest_route( $revo_api_url, '/product/details', array(
    'methods' => 'POST',
    'callback' => 'rest_product_details',
  ));
  register_rest_route( $revo_api_url, '/product/lists', array(
    'methods' => 'GET',
    'callback' => 'rest_product_lists',
  ));

  register_rest_route( $revo_api_url, 'cart/items', array(
    'methods' => 'POST',
    'callback' => 'rest_cart_by_user',
  ));

  register_rest_route( $revo_api_url, 'cart/items-update', array(
    'methods' => 'POST',
    'callback' => 'update_cart_by_user',
  ));

  register_rest_route( $revo_api_url, 'cart/items-add', array(
    'methods' => 'POST',
    'callback' => 'add_cart_by_user',
  ));

  register_rest_route( $revo_api_url, 'cart/clear', array(
    'methods' => 'POST',
    'callback' => 'clear_cart_by_user',
  ));

  register_rest_route( $revo_api_url, 'cart/add-multiple', array(
    'methods' => 'POST',
    'callback' => 'multi_cart_add_by_user',
  ));

  register_rest_route( $revo_api_url, 'delete-account', array(
    'methods' => 'POST',
    'callback' => 'delete_account',
  ));
register_rest_route( $revo_api_url, 'get-popular-search', array(
    'methods' => 'GET',
    'callback' => 'get_popular_search',
  ));

  register_rest_route( $revo_api_url, 'get-search-text', array(
    'methods' => 'POST',
    'callback' => 'get_search_text',
  ));
register_rest_route( $revo_api_url, 'get-province', array(
    'methods' => 'POST',
    'callback' => 'get_all_province',
  ));
  register_rest_route( $revo_api_url, 'get-district', array(
    'methods' => 'POST',
    'callback' => 'get_all_district',
  ));
  register_rest_route( $revo_api_url, 'get-commune', array(
    'methods' => 'POST',
    'callback' => 'get_all_commune',
  ));
register_rest_route( $revo_api_url, 'shipping-address', array(
    'methods' => 'GET',
    'callback' => 'shipping_address',
  ));
register_rest_route( $revo_api_url, 'add-shipping-address', array(
    'methods' => 'POST',
    'callback' => 'add_shipping_address',
  ));
  register_rest_route( $revo_api_url, 'edit-shipping-address', array(
    'methods' => 'POST',
    'callback' => 'edit_shipping_address',
  ));
  register_rest_route( $revo_api_url, 'delete-shipping-address', array(
    'methods' => 'POST',
    'callback' => 'delete_shipping_address',
  ));
  register_rest_route( $revo_api_url, 'set-default-billing-address', array(
      'methods' => 'POST',
      'callback' => 'mobile_default_billing_address',
    ));
  register_rest_route( $revo_api_url, 'invite-code', array(
    'methods' => 'POST',
    'callback' => 'invite_code',
  ));
  register_rest_route( $revo_api_url, 'get-coupon', array(
    'methods' => 'POST',
    'callback' => 'get_coupon',
  ));
  register_rest_route( $revo_api_url, 'apply-coupon', array(
    'methods' => 'POST',
    'callback' => 'apply_coupon',
  ));
  register_rest_route( $revo_api_url, 'list-general-notification', array(
    'methods' => 'POST',
    'callback' => 'list_general_notification',
  ));
  register_rest_route( $revo_api_url, 'detail-notification', array(
    'methods' => 'POST',
    'callback' => 'detail_notification',
  ));
  register_rest_route( $revo_api_url, 'tracking-order', array(
    'methods' => 'POST',
    'callback' => 'tracking_order',
  ));
  register_rest_route( $revo_api_url, 'count-notification', array(
    'methods' => 'POST',
    'callback' => 'count_notification',
  ));
  register_rest_route( $revo_api_url, 'read-general-notification', array(
    'methods' => 'POST',
    'callback' => 'read_general_notification',
  ));
  register_rest_route( $revo_api_url, 'read-order-notification', array(
    'methods' => 'POST',
    'callback' => 'read_order_notification',
  ));
  register_rest_route( $revo_api_url, 'test-coupon1', array(
    'methods' => 'POST',
    'callback' => 'test_coupon1',
  ));
  register_rest_route( $revo_api_url, 'test2', array(
    'methods' => 'POST',
    'callback' => 'test2',
  ));
});

  require (plugin_dir_path( __FILE__ ).'revo-checkout/checkout-api.php');

  function routes(){
    global $submenu;
    global $revo_plugin_name;

    add_menu_page( "Mobile Revo Settings", "REVO APPS <br> Flutter Woocommerce", 0, $revo_plugin_name,"index_settings", get_logo('black_white') );
    add_submenu_page($revo_plugin_name , "Intro Page", "Intro Page", 1, "revo-intro-page", 'revo_intro_page');
    add_submenu_page($revo_plugin_name, "Home Sliding Banner", "Home Sliding Banner", 1, "revo-mobile-banner", 'revo_mobile_banner');
    add_submenu_page($revo_plugin_name, "Home Categories", "Home Categories", 2, "revo-mobile-categories", 'revo_custom_categories');
    add_submenu_page($revo_plugin_name, "Home Additional Banner", "Home Additional Banner", 4, "revo-mini-banner", 'revo_mini_banner');
    add_submenu_page($revo_plugin_name, "Home Flash Sale", "Home Flash Sale", 5, "revo-flash-sale", 'revo_list_flash_sale');
    add_submenu_page($revo_plugin_name, "Home Additional Products", "Home Additional Products", 6, "revo-additional-products", 'revo_list_extend_products');
    add_submenu_page($revo_plugin_name, "Empty Result Image", "Empty Result Image", 7, "revo-empty-result-image", 'revo_empty_result_image');
    add_submenu_page($revo_plugin_name, "Popular Categories", "Popular Categories", 3, "revo-popular-categories", 'revo_popular_categories');
    add_submenu_page($revo_plugin_name, "Push Notification", "Push Notification", 8, "revo-post-notification", 'revo_post_notification');
    add_submenu_page($revo_plugin_name, "splash screen", "splash screen", 8, "revo-post-splash-screen", 'revo_post_splash_screen');
    add_submenu_page($revo_plugin_name, "popular search", "popular search", 8, "revo-post-popular-search", 'revo_post_popular_search');
    $submenu[$revo_plugin_name][0][0] = 'Dashboard';
  }
  
  if (check_exist_database('revo_mobile_variable')) {

      $revo_mobile_variable = "CREATE TABLE `revo_mobile_variable` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `slug` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
              `title` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `image` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
              `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `sort` tinyint(2) NOT NULL DEFAULT 0,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `update_at` timestamp NULL,
              PRIMARY KEY (`id`) USING BTREE)";
        
        $wpdb->query($wpdb->prepare($revo_mobile_variable,[]));

        $wpdb->insert('revo_mobile_variable',data_default_MV('splashscreen'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('kontak_wa'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('kontak_phone'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('kontak_sms'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('about'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('cs'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('privacy_policy'));
        $wpdb->insert('revo_mobile_variable',data_default_MV('logo'));

        $intro_page_1 = data_default_MV('intro_page_1');
        $intro_page_1['sort'] = '1';
        $wpdb->insert('revo_mobile_variable',$intro_page_1);

        $intro_page_2 = data_default_MV('intro_page_2');
        $intro_page_2['sort'] = '2';
        $wpdb->insert('revo_mobile_variable',$intro_page_2);

        $intro_page_3 = data_default_MV('intro_page_3');
        $intro_page_3['sort'] = '3';
        $wpdb->insert('revo_mobile_variable',$intro_page_3);

        for ($i=0; $i < count($data); $i++) { 
          $wpdb->insert('revo_mobile_variable',$data[$i]);
        }

        for ($i=1; $i < 6; $i++) { 
          $wpdb->insert('revo_mobile_variable',data_default_MV('empty_images_'.$i));
        }
  }

   if (check_exist_database('revo_mobile_slider')) {
        $revo_mobile_slider = "CREATE TABLE `revo_mobile_slider` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `order_by` int(55) NOT NULL,
                  `product_id` int(11) NULL DEFAULT NULL,
                  `title` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
                  `images_url` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
                  `product_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
                  `is_active` int(1) NULL DEFAULT 1,
                  `is_deleted` int(1) NULL DEFAULT 0,
                  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                  PRIMARY KEY (`id`) USING BTREE ) ";
        
        $wpdb->query($wpdb->prepare($revo_mobile_slider,[]));
      }

      if (check_exist_database('revo_list_categories')) {
        $revo_list_categories = " CREATE TABLE `revo_list_categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_by` int(55) NOT NULL,
            `image` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
            `category_id` int(55) NOT NULL,
            `category_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
            `is_active` int(1) NULL DEFAULT 1,
            `is_deleted` int(1) NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`) USING BTREE) ";
        
        $wpdb->query($wpdb->prepare($revo_list_categories,[]));
      }

      if (check_exist_database('revo_list_mini_banner')) {
        $revo_list_mini_banner = " CREATE TABLE `revo_list_mini_banner` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_by` int(55) NOT NULL,
            `product_id` int(11) NULL DEFAULT NULL,
            `product_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
            `image` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
            `type` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
            `is_active` int(1) NULL DEFAULT 1,
            `is_deleted` int(1) NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`) USING BTREE) ";
        
        $wpdb->query($wpdb->prepare($revo_list_mini_banner,[]));
      }

      if (check_exist_database('revo_flash_sale')) {
        $revo_flash_sale = "CREATE TABLE `revo_flash_sale` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL,
              `start` timestamp NULL DEFAULT NULL,
              `end` timestamp NULL DEFAULT NULL,
              `products` longtext NOT NULL,
              `image` varchar(500) NOT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT 1,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        
        $wpdb->query($wpdb->prepare($revo_flash_sale,[]));
      }

      if (check_exist_database('revo_extend_products')) {
        $revo_extend_products = 
              "CREATE TABLE `revo_extend_products` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `type` enum('special','our_best_seller','recomendation') NOT NULL DEFAULT 'special',
              `title` varchar(255) NOT NULL,
              `description` varchar(500) DEFAULT NULL,
              `products` longtext NOT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT 1,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_extend_products,[]));
        $wpdb->insert('revo_extend_products',                  
              [
                'type' => 'special',
                'title' => 'Special Promo : App Only',
              ]);

        $wpdb->insert('revo_extend_products',
              [
                'type' => 'our_best_seller',
                'title' => 'Our Best Seller',
                'description' => 'Get the Best Products for You',
              ]);

        $wpdb->insert('revo_extend_products',
              [
                'type' => 'recomendation',
                'title' => 'Recommendations For You',
              ]);
      }

      if (check_exist_database('revo_popular_categories')) {
        $revo_popular_categories = "CREATE TABLE `revo_popular_categories` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(55) NOT NULL,
              `categories` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_popular_categories,[]));
      }

      if (check_exist_database('revo_hit_products')) {
        $revo_hit_products = "CREATE TABLE `revo_hit_products` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `products` varchar(55) NOT NULL,
              `user_id` varchar(55) NULL,
              `ip_address` varchar(55) NOT NULL,
              `type` ENUM('hit','wistlist') NOT NULL DEFAULT 'hit',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_hit_products,[]));
      }

      if (!check_exist_database('revo_hit_products')) {
        //$revo_hit_products = "ALTER TABLE `revo_hit_products` ADD `type` ENUM('hit','wistlist') NOT NULL DEFAULT 'hit' AFTER `user_id`;";
        //$wpdb->query($wpdb->prepare($revo_hit_products,[]));
      }

      if (check_exist_database('revo_access_key')) {
        $revo_access_key = "CREATE TABLE `revo_access_key` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `firebase_api_key` TEXT NULL DEFAULT NULL,
              `firebase_auth_domain` TEXT NULL DEFAULT NULL,
              `firebase_database_url` TEXT NULL DEFAULT NULL,
              `firebase_project_id` TEXT NULL DEFAULT NULL,
              `firebase_storage_bucket` TEXT NULL DEFAULT NULL,
              `firebase_messaging_sender_id` TEXT NULL DEFAULT NULL,
              `firebase_app_id` TEXT NULL DEFAULT NULL,
              `firebase_measurement_id` TEXT NULL DEFAULT NULL,
              `firebase_servey_key` TEXT NULL DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_access_key,[]));

        $wpdb->insert('revo_access_key',['firebase_api_key' => NULL]);
      }

      if (!check_exist_database('revo_access_key')) {
        //$revo_access_key = "ALTER TABLE `revo_access_key` ADD `firebase_servey_key` TEXT NULL DEFAULT NULL AFTER `id`;";
        //$wpdb->query($wpdb->prepare($revo_access_key,[]));
      }

      if (check_exist_database('revo_token_firebase')) {
        $revo_token_firebase = "CREATE TABLE `revo_token_firebase` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `token` TEXT NULL DEFAULT NULL,
              `user_id` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_token_firebase,[]));
      }

      if (check_exist_database('revo_notification')) {
        $revo_notification = "CREATE TABLE `revo_notification` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `target_id` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `type` varchar(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `message` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
              `is_read` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`) USING BTREE)";
        $wpdb->query($wpdb->prepare($revo_notification,[]));
      }

  $cek_sql = "SELECT * FROM `revo_mobile_variable` WHERE `slug` = 'empty_image' AND `title` = 'login_required'";
  $cek_sql = $wpdb->get_row($cek_sql, OBJECT);
  if (empty($cek_sql)) {
    $wpdb->insert('revo_mobile_variable',data_default_MV('empty_images_5'));
  }

  $query_pp = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'privacy_policy'";
  $data_pp = $wpdb->get_row($query_pp, OBJECT);
  if (empty($data_pp)) {
    $wpdb->insert('revo_mobile_variable',data_default_MV('privacy_policy'));
  }

  $query_intro_page_1 = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'intro_page' AND title = 'Manage Everything' ";
  $where_pp_1 = $wpdb->get_row($query_intro_page_1, OBJECT);
  if (!empty($where_pp_1)) {
    $intro_page_1 = data_default_MV('intro_page_1');
  //   print_r($intro_page_1);
  // die();
    $wpdb->update('revo_mobile_variable',$intro_page_1,['id' => $where_pp_1->id]);
  }

  $query_intro_page_2 = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'intro_page' AND title = 'Support All Payments' ";
  $where_pp_2 = $wpdb->get_row($query_intro_page_2, OBJECT);
  if (!empty($where_pp_2)) {
    $intro_page_2 = data_default_MV('intro_page_2');
    $wpdb->update('revo_mobile_variable',$intro_page_2,['id' => $where_pp_2->id]);
  }

  $query_intro_page_3 = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'intro_page' AND title = 'Support All Shipping Methods' ";
  $where_pp_3 = $wpdb->get_row($query_intro_page_3, OBJECT);
  if (!empty($where_pp_3)) {
    $intro_page_3 = data_default_MV('intro_page_3');
    $wpdb->update('revo_mobile_variable',$intro_page_3,['id' => $where_pp_3->id]);
  }

  require (plugin_dir_path( __FILE__ ).'api/index.php');
  require (plugin_dir_path( __FILE__ ).'page/index.php');


  /**
   * Register the Revo URL caching endpoints so they will be cached.
   */
  // function wprc_add_revo_endpoints( $allowed_endpoints ) {
  //     if ( ! isset( $allowed_endpoints[$revo_api_url] ) || ! in_array( 'cache', $allowed_endpoints[$revo_api_url] ) ) {
  //         $allowed_endpoints[$revo_api_url][] = 'cache';
  //     }
  //     return $allowed_endpoints;
  // }
  // add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_revo_endpoints', 10, 1);

  
  // add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'handle_custom_query_var', 10, 2 );
  // function handle_custom_query_var( $query, $query_vars ) {
  //     if ( isset( $query_vars['like_name'] ) && ! empty( $query_vars['like_name'] ) ) {
  //         $query['s'] = esc_attr( $query_vars['like_name'] );
  //     }

  //     return $query;
  // }

  add_action( 'woocommerce_init', 'custom_rest_api_wc_load_cart' );

  function custom_rest_api_wc_load_cart() {

    if(isset($_GET['wc_load_cart']) && $_GET['wc_load_cart']==1){
      if ( ! WC()->is_rest_api_request() ) {
        return;
      }
    
      WC()->frontend_includes();
    
      if ( null === WC()->cart && function_exists( 'wc_load_cart') ) {
        wc_load_cart();
      }
    }
  }

?>