<?php
/**
 * Class Name: Class-Name
 * Description: -Type description here-.
 * Version: 0.0.3
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Biz_Woo_Distance_Delivery' ) ) {
    class Biz_Woo_Distance_Delivery {
        
        // Constructor
        public function __construct() {}

        // Main Loader fucntion
        public static function init()
        {
            self::acf_activate_hook();

            // WP | ACF Registration
            // add_action('init', array('Biz_Woo_Distance_Delivery', 'register_post_type'));
            // add_action('init', array('Biz_Woo_Distance_Delivery', 'register_sub_option_page'));
            // add_action('init', array('Biz_Woo_Distance_Delivery', 'register_taxonomy'));
            // add_action('init', array('Biz_Woo_Distance_Delivery', 'acf_field'));
            // add_action('init', array('Biz_Woo_Distance_Delivery', 'register_option_page'));
            add_action('init', array('Biz_Woo_Distance_Delivery', 'enqueue_style_and_script'));
            
            // Theme Support
            // add_action('after_setup_theme', array('Biz_Woo_Distance_Delivery', 'theme_support_thumbnails'));

            // Shortcode
            add_shortcode( 'biz_woo_distance_delivery', array('Biz_Woo_Distance_Delivery', 'shortcode_function_name'));

            // Add script handles to the array below
            add_filter('script_loader_tag', array('Biz_Woo_Distance_Delivery', 'add_attribute_to_script_tag'), 10, 2);
        }

        /**
         * Check AFC after activate plugin
         */
        protected static function acf_activate_hook()
		{
            // Detect plugin. For use on Front End only.
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) and !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) and current_user_can( 'activate_plugins' ) )
			{
				wp_die('Your installation is incomplete. Please activate ACF before activate this plugin or contact to Biz Solution for technical supports.');
                return false;
			}
        }

        /**
         * Register Style and Script
         */
        public static function enqueue_style_and_script()
        {
            $dir = plugin_dir_url( __DIR__ );

            // Style
            wp_enqueue_style('distance-delivery', $dir . '/resources/assets/css/distance_delivery.css', '0.0.1', 'all');

            // scripts
            wp_enqueue_script('polyfill-io', 'https://polyfill.io/v3/polyfill.min.js?features=default', array(), '3.0.0', false);
            wp_enqueue_script('distance-delivery', $dir . '/resources/assets/js/distance_delivery.js', array('polyfill-io', 'google-map'), '0.0.1', false);
        }

        /**
         * Find replace tage "defer" to CDN javascript.
         * Add script handles to the array below
         */
        public static function add_attribute_to_script_tag($tag, $handle) {
            $scripts_to_defer = array('google-map');
        
            foreach($scripts_to_defer as $defer_script) {
                if ($defer_script === $handle) {
                    return str_replace(' src', '  async defer data-pin-hover="true" src', $tag);
                }
            }
            return $tag;
        }

        /**
         * Registration Post Type
         * 
         * Note: Must change symbol: _____.
         * @return void
         */
        public static function register_post_type()
        {
            $labels = array(
                'name' => _x('_____', 'post type general name'),            // Page Title
                'singular_name' => _x('_____', 'post type singular name'),  // Singular Name
                'add_new' => _x('Add New', 'post type add new button'),     // Button 'Add New' Name
                'add_new_item' => __('Create _____'),                       // Page Create Title
                'edit_item' => __('Edit _____'),                            // Page Edit Title
                'new_item' => __('Create _____'),                           // Create Label
                'all_items' => __('All _____'),                             // Menu
                'view_item' => __('View _____'),                            // Search Label
                'search_items' => __('Search'),                             // Button Search
                'not_found' =>  __('No Data found'),                        // Label Not Found Data in publish
                'not_found_in_trash' => __('No Data found in Trash'),       // Label Not Found Data in trush
                'parent_item_colon' => '',
                'menu_name' => __('_____')                                  // Menu Name

            );
            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true, 
                'show_in_menu' => true, 
                'query_var' => true,
                'rewrite' => true,
                'capability_type' => 'post',
                'has_archive' => true, 
                'hierarchical' => false,
                'menu_position' => 80,
                'menu_icon' => 'dashicons-wordpress-alt',
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', )
            ); 
            register_post_type('news-and-event', $args);
        }

        /**
         * Registration Option Page
         *
         * Note: Must change symbol: _____.
         * @return void
         */
        public static function register_option_page()
        {
            acf_add_options_page(array(
                'page_title' 	=> '_____',         // Page Title
                'menu_title'	=> '_____',         // Menu Name
                'menu_slug' 	=> '_____',         // Slug, Syntax: page-name
                'capability'	=> 'edit_posts',    // Capability
                'icon_url'      => '_____',         // Icon
                'position'      => 81,              // Sample: 85,90,95
                'redirect'		=> false
            ));
        }

        /**
         * Registration Sub-Option Page
         *
         * Note: Must change symbol: '_____'.
         * @return void
         */
        public static function register_sub_option_page()
        {
            acf_add_options_sub_page(array(
                'page_title' 	=> '_____',                     // Page title
                'menu_title'	=> '_____',                     // Menu title
                'parent_slug'	=> 'edit.php?post_type=_____',  // Sample: edit.php?post_type=post_name
            ));
        }

        /**
         * Registration Taxonomy
         *
         * Note: Must change symbol: _____.
         * @return void
         */ 
        public static function register_taxonomy() {
            $labels = array(
                'name'              => _x( '_____', 'taxonomy general name' ),  // Taxonomy Name
                'singular_name'     => _x( '_____', 'taxonomy singular name' ), // Singular Name
                'search_items'      => __( 'Search' ),          // Button Search
                'all_items'         => __( 'All _____' ),       // Label For Listing Items
                'parent_item'       => __( 'Parent' ),          // Parent Label
                'parent_item_colon' => __( 'Parent:' ),         // Parent Label
                'edit_item'         => __( 'Edit _____' ),      // Page Edit Title
                'update_item'       => __( 'Update _____' ),    // Update Label
                'add_new_item'      => __( 'Add New' ),         // Button
                'new_item_name'     => __( 'New Course' ),      // Item Name
                'menu_name'         => __( '_____' ),           // Menu Name
            );
            $args   = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => '_____' ],
            );
            register_taxonomy( '_____', [ '_____' ], $args );
        }

        /**
         * WP Thumbnail Support
         *
         * Note: Must change label: 'post_name', Please copy post type name from URL.
         * Note: array( 'post_name', 'page' ), Can be use with Option Pag.
         * @return void
         */
        public static function theme_support_thumbnails()
        {
            add_theme_support( 'post-thumbnails', array( '_____' ));
        }

        /**
         * ACF Field
         *
         * Note: Must be import field form ACF (Advance Custom Field) and past in this function.
         * @return void
         */
        public static function acf_field() {
            // code here
        }

        /**
         * Shortcode
         * Note: You can call function or include page.
         */
        public static function shortcode_function_name()
        {
            ob_start();
            include( plugin_dir_path( __DIR__ ) . '/resources/views/distance_delivery.php' );
            return ob_get_clean();

            // Biz_Woo_Distance_Delivery::_____();
            // include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }
    }
    
    // Instantiate the plugin class
    $distance_delivery = new Biz_Woo_Distance_Delivery();
    $distance_delivery::init();
}