<?php
/**
 * Plugin Name: Biz PlasGate_
 * Plugin URI: https://bizsolution.com.kh
 * Description: This plugin is designed for OTP SMS, Bulk SMS using PlasGate SMS Solution Provider.
 * Version: 1.3.0
 * Author: Tep Afril
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

	defined( 'ABSPATH' ) || exit;


	require __DIR__ . '/config.php';




	/**
	 * Load core classes and the autoloader.
	 */
	require BIZ_PLASGATE_PLUGIN_DIR . '/autoloader.php';





	/**
	 * Init Autoloader
	 */
	if ( ! \BizSolution\BizPlasGate\Autoloader::init() )
	{
		return;
	}




?>
