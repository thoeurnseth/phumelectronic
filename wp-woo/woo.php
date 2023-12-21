<?php
/**
 * File Name: Biz WooDoo Data Integration
 * Description: This plugin created for purpose integrate data from Odoo to WooCommerce such as: Products, Categoris, Attribute, Pricelist.
 * Version: 1.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

// Validation File
if( !file_exists('error.php') )
{
    header('WWW-Authenticate: Basic realm="Biz"');
    header('HTTP/1.0 502 Bad Gateway');

    $response = array(
        'code' => 502,
        'message' => 'Bad Gateway',
        'data' => []
    );

    echo json_encode( $response );
    exit;
}

// Request Header Repsonse Error
require_once '../wp-load.php';
require_once __DIR__ . '/error.php';
require_once __DIR__ . '/credential.php';
$error = new Header_Response();

// Validate User Authentication
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != USER_AUTH || $_SERVER['PHP_AUTH_PW'] != PWD_AUTH)
{
    $error::unauthorized();
}

// Validate Action Request
if( !isset($_GET['target']) || !isset($_GET['action']) || empty($_GET['target']) || empty($_GET['action']) )
{
    $error::bad_request();
}

// Target Request
$target = $_GET['target'];
$action = $_GET['action'];
$parth  = 'api/'. $target .'/'. $action .'.php';

if( !file_exists( $parth ) )
{
    $error::bad_request();
}

// Main Loader
require_once __DIR__ . '/error_log.php';
require_once __DIR__ . '/authentication.php';
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/'. $parth;