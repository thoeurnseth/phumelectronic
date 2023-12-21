<?php
/*
 * Function Name: Register Option Pages
 * Description: All option are register here.
 * Version: 1.0
 * Author: WEB ACE
 * Author URI: https://webace.com
 */
if( function_exists('acf_add_options_page') ) {

    // Phone Contact
    acf_add_options_page(array(
        'page_title' 	=> 'Phone Contact',
        'menu_title'	=> 'Phone Contact',
        'menu_slug' 	=> 'phone-contact',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin-phone',
        'position'      => 80,
        'redirect'		=> false
    ));

    acf_add_options_page(array(
        'page_title' 	=> 'Payment ID',
        'menu_title'	=> 'Payment ID',
        'menu_slug' 	=> 'payment-id',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));

    acf_add_options_page(array(
        'page_title' 	=> 'Term & Policy',
        'menu_title'	=> 'Term & Policy',
        'menu_slug' 	=> 'term-policy',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));

    // @Return Policy
    acf_add_options_page(array(
        'page_title' 	=> 'Return Policy',
        'menu_title'	=> 'Return Policy',
        'menu_slug' 	=> 'return-policy',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));        
    // @Delivery Policy
    acf_add_options_page(array(
        'page_title' 	=> 'Delivery Policy',
        'menu_title'	=> 'Delivery Policy',
        'menu_slug' 	=> 'delivery-policy',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));
    //Asign Percentage
    acf_add_options_page(array(
        'page_title' 	=> 'Percentage Coupon',
        'menu_title'	=> 'Invite friend Coupon',
        'menu_slug' 	=> 'percentage-coupon',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));

    //check otp verify or not
    acf_add_options_page(array(
        'page_title' 	=> 'Check Verify Otp',
        'menu_title'	=> 'Check Verify Otp',
        'menu_slug' 	=> 'check-verify-otp',
        'capability'	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin',
        'position'      => 79,
        'redirect'		=> false
    ));


    // About Us
    // acf_add_options_page(array(
    //     'page_title' 	=> 'About Us',
    //     'menu_title'	=> 'About Us',
    //     'menu_slug' 	=> 'about-us',
    //     'capability'	=> 'edit_posts',
    //     'icon_url'      => 'dashicons-editor-help',
    //     'position'      => 85,
    //     'redirect'		=> false
    // ));

    // Contact Us
    // acf_add_options_page(array(
    //     'page_title' 	=> 'Contact Us',
    //     'menu_title'	=> 'Contact Us',
    //     'menu_slug' 	=> 'contact-us',
    //     'capability'	=> 'edit_posts',
    //     'icon_url'      => 'dashicons-phone',
    //     'position'      => 90,
    //     'redirect'		=> false
    // ));
    
    acf_add_options_sub_page(array(
        'page_title' 	=> 'Growing Together', // Sub Page
        'menu_title'	=> 'Growing Together', // Menu Page
        'parent_slug'	=> 'edit.php?post_type=company-profile', // edit.php?post_type=offers
    ));    

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Growing Together', // Sub Page
        'menu_title'	=> 'Growing Together', // Menu Page
        'parent_slug'	=> 'edit.php?post_type=company-profile', // edit.php?post_type=offers
    ));
    
    
}
?>