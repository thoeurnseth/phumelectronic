<?php
/**
 * Plugin Name: Biz Woocommerce - Multiple Address
 * Description: This plugin is allow customer create multiple address for their shipping address.
 * Version: 1.0.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */


    defined( 'ABSPATH' ) || exit;


    require __DIR__ . '/config.php';




    /**
     * Load core classes and the autoloader.
     */
    require BIZ_WMA_PLUGIN_DIR . '/app/http/Autoloader.php';
    // require BIZ_PLASGATE_PLUGIN_DIR . '/app/classes/SMS.php';
    // require BIZ_PLASGATE_PLUGIN_DIR . '/app/classes/BasicAuth.php';





    /**
     * Init Autoloader
     */
    // if ( ! \BizSolution\BizWMA\Autoloader::init() )
    // {
    //     return;
    // }




?>
