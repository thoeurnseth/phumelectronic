<?php
function biz_registration_menu()
{
    add_menu_page( 
        __( 'Company Profile', 'Biz-Solution' ),
        'Company Profile',
        'manage_options',
        'company-profile', // Slug
        plugins_url( 'myplugin/images/icon.png' ),
        80
    );
}
add_action( 'admin_menu', 'biz_registration_menu' );