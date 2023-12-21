<?php
/**
 * Plugin Name: BizWeb - Registeration
 * Description: This plugin allow developer to register Post Type, Option Page and Taxonomy.
 * Version: 1.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

    defined( 'ABSPATH' ) || exit;

    /**
     * Main Loader
     */
    //require __DIR__ . '/app/http/Autoloader.php';
    //require __DIR__ . '/registration/register_menu.php';
    require __DIR__ . '/registration/register_post_type.php';
    require __DIR__ . '/registration/register_option_page.php';
    require __DIR__ . '/registration/register_taxonomy.php';
    require __DIR__ . '/registration/register_post_type.php';
    //require __DIR__ . '/function.php';
?>