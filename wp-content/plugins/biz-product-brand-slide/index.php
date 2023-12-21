<?php
/**
 * Plugin Name: Biz Solution | WooCommerce - CMA
 * Plugin URI: http://www.bizsolution.biz/
 * Description: This plugin for frontend Custom form: change password, add field date of birth and favorite toys, custom form customer address, billing address, News & Event, Product Brands.
 * Author: Biz Solution (inspire . satisfy . trust)
 * Author URI: http://www.bizsolution.biz/
 * Text Domain: 
 * Domain Path: /languages/
 * Version: 1.2
 */

/**
 * Check plugin activate
 */
/*
register_activation_hook( __FILE__, 'plugin_activate' );
function plugin_activate()
{
    if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) and !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) and current_user_can( 'activate_plugins' ) )
    {
        wp_die( 'Please activate ACF before active this plugin.' );
    }
}
*/

/*
 * Function Name: Enqueue style and scripts
 * Description: Enqueue style and scripts for font-end.
 * Version: 1.0
 * Author: WEB ACE
 * Author URI: https://webace.com
 */
function enqueue_style_and_script() {
    
    $dir = plugin_dir_url( __FILE__ );

    //@style
    wp_enqueue_style('brand', $dir . 'assets/css/brand.css', '1.0', 'all');
    wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', '1.8.1', 'all');
    wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.1.0/css/all.css', '1.8.1', 'all');
    
    //@script
    wp_enqueue_script( 'slick',    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array(), '1.8.1', true );
    wp_enqueue_script( 'theme', $dir . 'assets/script/theme.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_style_and_script' );

include( 'functions/brand.php' ); // brand