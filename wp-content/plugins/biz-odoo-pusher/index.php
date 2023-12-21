<?php
/**
 * Plugin Name: Biz Odoo Pusher
 * Description: This plugin is designed for WooCommerce app that pushes data to Odoo through API
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
	require BIZ_ODOO_PUSHER_PLUGIN_DIR . '/app/http/Autoloader.php';





	/**
	 * Init Autoloader
	 */
	if ( ! \BizSolution\BizOdooPusher\Autoloader::init() )
	{
		return;
	}




?>
