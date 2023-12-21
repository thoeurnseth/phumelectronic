<?php

/*
 * Register style
 */
function notification_style_and_script() {
    
    $dir = plugin_dir_url( __FILE__ );
 
    // Stle
    wp_enqueue_style('boostrapCSS-4-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', '4.0.0', 'all');
    wp_enqueue_style('notification-css', $dir . '/resources/assets/css/theme.css', '1.0', 'all');

    // Scritp
    wp_enqueue_script('jquery-js', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
    wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array(), '1.12.9', true);
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array(), '4.0.0', true);
    wp_enqueue_script('validation-js', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js', array(), '1.19.3', true);
    
    wp_enqueue_script('notification-js', $dir . '/resources/assets/js/theme.js', array(), '1.0', true);
}
add_action( 'init', 'notification_style_and_script' );


/**
 * Filter member by Card Number
 */
add_action( 'wp_ajax_filter_member_by_card_number', 'filter_member_by_card_number' );
function filter_member_by_card_number() {

    $member_info = array(
        'id'   => '1',
        'name' => 'Songly',
        'card' => '2802233923',
        'dob'  => date('Y-m-d'),
        'profile' => 'https://miro.medium.com/max/600/1*PiHoomzwh9Plr9_GA26JcA.png',
    );

    wp_send_json_success( $member_info );
    wp_die();
}

/**
 * Push Notification
 */
function push_notification()
{
    $notification = new Push_Notification_Autoloader();
    $notification->push_notification();
}
//add_action( 'init', 'push_notification' );