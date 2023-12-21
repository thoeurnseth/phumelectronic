<?php

/*
 * Register style
 */
function brand_enqueue_style_and_script() {
    
    $dir = plugin_dir_url( __FILE__ );

    // Stle
    wp_enqueue_style('brand-style', $dir . '/resources/assets/css/theme.css', '1.0', 'all');

    // Scritp
    wp_enqueue_script('brand-script', $dir . '/resources/assets/js/theme.js', array(), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'brand_enqueue_style_and_script' );