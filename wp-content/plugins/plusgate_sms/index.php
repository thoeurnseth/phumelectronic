<?php
/**
 * Plugin Name: +Gate Integration
 * Description: This plugin using for integration with +Gate send SMS.
 * Version: 0.0.1
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */
   defined( 'ABSPATH' ) || exit;
   date_default_timezone_set('Asia/Bangkok');

   /**
    * Main Loader
    */
   require __DIR__ . '/error_log.php';
   require __DIR__ . '/credential.php';
   require __DIR__ . '/app/Autoloader.php';
   require __DIR__ . '/api/controller.php';
   require __DIR__ . '/api/route.php';
   require __DIR__ . '/function.php';