<?php
/**
 * File Name: Authentication
 * Description: This file created for purpose setup integration with WooCommerce.
 * Version: 1.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

// Setup:
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    site_url(), 
    CONSUMER_KEY,
    CONSUMER_SECRET,
    [
        'wp_api' => true,
        'version' => 'wc/v3',
        'query_string_auth' => true,
        'verify_ssl' => false,
    ]
);