<?php
/**
 * Plugin Name: BizWeb - Brand
 * Description: This plugin allow user to post news and event on website.
 * Version: 1.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

    defined( 'ABSPATH' ) || exit;

    /**
     * Main Loader
     */
    require __DIR__ . '/app/http/Autoloader.php';
    require __DIR__ . '/function.php';

    $plugin_Name = new Brand_Autoloader();
    $plugin_Name::init();
?>