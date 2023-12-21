<?php

/*
 * Register style
 */
function news_and_event_enqueue_style_and_script() {
    
    $dir = plugin_dir_url( __FILE__ );

    // Stle
    wp_enqueue_style('news-event-style', $dir . '/resources/assets/css/theme.css', '1.0', 'all');

    // Scritp
    wp_enqueue_script('news-event-script', $dir . '/resources/assets/js/theme.js', array(), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'news_and_event_enqueue_style_and_script' );