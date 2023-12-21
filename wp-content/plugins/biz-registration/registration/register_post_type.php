<?php
/*
 * Function Name: Post_Type_Name__
 * Description: Description__
 * Version: 1.0
 * Author: WEB ACE
 * Author URI: https://webace.com
 */
/*
if (! function_exists('create_post_type_name__')) {
    function create_post_type_name__()
    {
        // name__
        register_post_type( 'name__',
            array(
                'labels' => array(
                    'name' => __('name__'),
                    'singular_name' => __('name__'),
                ),
                'public'        => true,
                'hierarchical'  => true,
                'supports'      => array( 'title', 'editor', 'author', 'page-attributes', 'thumbnail' ),
                'taxonomies'    => array( 'category', 'post_tag' ),
                'menu_icon'     => 'dashicons-chart-line',
                'rewrite'       => array( 'slug' => __('name__') )
            )
        );
    }
    add_action('init', 'create_post_type_name__');
}
*/

if (! function_exists('create_post_type_company_profile')) {
    function create_post_type_company_profile()
    {
        // Company Profile
        register_post_type( 'company-profile',
            array(
                'labels' => array(
                    'name' => __('Company Profile'),
                    'singular_name' => __('Company Profile'),
                ),
                'public'        => true,
                'hierarchical'  => true,
                'supports'      => array( 'title', 'editor', 'author', 'page-attributes', 'thumbnail' ),
                //'taxonomies'    => array( 'category', 'post_tag' ),
                'menu_icon'     => 'dashicons-chart-line',
                'rewrite'       => array( 'slug' => __('company-profile') )
            )
        );

          //generate coupon 
          register_post_type( 'generate-coupon',
          array(
              'labels' => array(
                  'name' => __('Generate Coupon'),
                  'singular_name' => __('Generate Coupon'),
              ),
              'public'        => true,
              'hierarchical'  => true,
              'supports'      => array( 'user_id', 'coupon_code', 'coupon_amount', 'discount_type', 'usage_limit' ),
            //   'taxonomies'    => array( 'category', 'post_tag' ),
            //   'menu_icon'     => 'dashicons-chart-line',
              'rewrite'       => array( 'slug' => __('generate-coupon') )
          )
        );

        // SubMenu Company Profile
        register_post_type( 'company-branch', array(
            'labels' => array(
                'name' => 'Branch',
                'singular_name' => 'Branch',
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=company-profile',
            'supports' => array( 'title' ,'thumbnail', 'editor' ),
        ) );

        // SubMenu Archivement
        register_post_type( 'archivement', array(
            'labels' => array(
                'name' => 'Archivement',
                'singular_name' => 'Archivement',
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=company-profile',
            'supports' => array( 'title' ,'thumbnail', 'editor' ),
        ) );
    }
    add_action('init', 'create_post_type_company_profile');
}

?>