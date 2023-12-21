<?php
/**
 * Plugin Name: Awesome Plugin
 * Version: 1.0
 * Description: This is the best plugin!
 * Author: Andrea Fuggetta <contact@ndevr.io>
 * Author URI: https://www.ndevr.io
 * Plugin URI:  https://www.ndevr.io
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Autoloader' ) ) {
    class News_And_Event_Autoloader {
        
        //Constructor
        public function __construct() {}


        // Main Loader fucntion
        public static function init()
        {
            //self::acf_activate_hook();
            
            add_action('init', ['News_And_Event_Autoloader', 'register_post_type'] );
            //add_action('init', ['News_And_Event_Autoloader', 'register_sub_option_page'] );
            //add_action('init', ['News_And_Event_Autoloader', 'register_option_page'] );
            add_action('init', ['News_And_Event_Autoloader', 'register_taxonomy'] );
            add_action('init', ['News_And_Event_Autoloader', 'acf_field'] );
            
            // Theme Supports
            add_action('after_setup_theme', ['News_And_Event_Autoloader', 'theme_support_thumbnails'] );

            // Add Shortcode
            add_shortcode( 'biz_archive_news_and_event', ['News_And_Event_Autoloader', 'archive_page'] );
            add_shortcode( 'biz_single_news_and_event', ['News_And_Event_Autoloader', 'single_page'] );
        }

        
        // Check AFC after activate plugin
        protected static function acf_activate_hook()
		{
            // Detect plugin. For use on Front End only.
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) and !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) and current_user_can( 'activate_plugins' ) )
			{
				wp_die('Your installation is incomplete. Please activate ACF before activate this plugin or contact to BizSolution for technical supports.');
                return false;
			}
        }


        /**
         * Registration Post Type
         *
         * Note: Must change label: 'Post_Name'.
         * @return void
         */
        public static function register_post_type()
        {
            $labels = array(
                'name' => _x('News & Event', 'post type general name'), // Page Title
                'singular_name' => _x('News & Event', 'post type singular name'),
                'add_new' => _x('Add New', 'post type add new button'), // Button 'Add New' Name
                'add_new_item' => __('Create News & Event'), // Page Create Title
                'edit_item' => __('Edit News & Event'), // Page Edit Title
                'new_item' => __('Create News & Event'), 
                'all_items' => __('All News & Event'), // Mneu
                'view_item' => __('View News & Event'),
                'search_items' => __('Search'), // Button Search
                'not_found' =>  __('No Data found'), // Label Not Found Data in publish
                'not_found_in_trash' => __('No Data found in Trash'),  // Label Not Found Data in trush
                'parent_item_colon' => '',
                'menu_name' => __('News & Event') // Menu Name

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
                'supports' => array( 'title', 'editor', 'thumbnail')
            ); 
            register_post_type('news_and_event', $args);
        }


        /**
         * Registration Option Page
         *
         * Note: Must change label: 'Page_Name'.
         * @return void
         */
        public static function register_option_page()
        {
            acf_add_options_page(array(
                'page_title' 	=> 'Page_Name', // Page Title
                'menu_title'	=> 'Page_Name', // Menu Name
                'menu_slug' 	=> 'Page_Name', // Slug, Syntax: page-name
                'capability'	=> 'edit_posts',
                'icon_url'      => 'dashicons-wordpress-alt', // Icon
                'position'      => 81,
                'redirect'		=> false
            ));
        }


        /**
         * Registration Sub-Option Page
         *
         * Note: Must change label: 'Option_Page_Name'.
         * Note: Must change label: 'post_name' follow by Post Type name.
         * @return void
         */
        public static function register_sub_option_page()
        {
            acf_add_options_sub_page(array(
                'page_title' 	=> 'Option_Page_Name', // Page title
                'menu_title'	=> 'Option_Page_Name', // Menu title
                'parent_slug'	=> 'edit.php?post_type=post_name', // Slug: edit.php?post_type=post_name
            ));
        }


        /**
         * Registration Taxonomy
         *
         * Note: Must change label: 'Taxonomy_Name'.
         * Note: Must change label: 'post_name' follow by Post Type name.
         * @return void
         */ 
        public static function register_taxonomy() {
            $labels = array(
                'name'              => _x( 'Event Categories', 'taxonomy general name' ), // Taxonomy Name
                'singular_name'     => _x( 'Event Categories', 'taxonomy singular name' ), // Singular Name
                'search_items'      => __( 'Search' ), // Button Search
                'all_items'         => __( 'All Event Categories' ), // Menu 
                'parent_item'       => __( 'Parent' ), // Label Parent
                'parent_item_colon' => __( 'Parent:' ),
                'edit_item'         => __( 'Edit Event Categories' ), // Page Edit Title
                'update_item'       => __( 'Update Event Categories' ),
                'add_new_item'      => __( 'Add New' ), // Button Add New
                'new_item_name'     => __( 'New Course' ),
                'menu_name'         => __( 'Event Categories' ), // Menu Name
            );
            $args   = array(
                'hierarchical'      => true, // make it hierarchical (like categories)
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'event_categories' ],
            );
            register_taxonomy( 'event_categories', [ 'news_and_event' ], $args );
        }


        /**
         * WP Thumbnail Support
         *
         * Note: Must change label: 'post_name', Please copy post type name from URL.
         * Note: array( 'post_name', 'page' ), Can be use with Option Pag.
         * @return void
         */
        public static function theme_support_thumbnails() {
            add_theme_support( 'post-thumbnails', array( 'post_name' ) );
        }


        /**
         * ACF Field
         *
         * Note: Must be import field form ACF (Advance Custom Field) and past in this function.
         * @return void
         */
        public static function acf_field() {
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array(
                    'key' => 'group_6074fb3949912',
                    'title' => '[:en]News and Event[:]',
                    'fields' => array(
                        array(
                            'key' => 'field_6074fb623bf61',
                            'label' => 'Event Date',
                            'name' => 'event-date',
                            'type' => 'date_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'display_format' => 'j F Y',
                            'return_format' => 'j F Y',
                            'first_day' => 1,
                        ),
                        array(
                            'key' => 'field_60750f2cfb639',
                            'label' => 'Event Categories',
                            'name' => 'event_categories',
                            'type' => 'taxonomy',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'taxonomy' => 'event_categories',
                            'field_type' => 'select',
                            'allow_null' => 0,
                            'add_term' => 1,
                            'save_terms' => 0,
                            'load_terms' => 0,
                            'return_format' => 'id',
                            'multiple' => 0,
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'news_and_event',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => '',
                ));
                
                endif;
        }


        /**
         * Add Post New and Event shortcode
         */
        public static function archive_page ()
        {
            ob_start();
            include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php');
            return ob_get_clean();
        }

        /**
         * Add Single Page New and Event shortcode
         */
        public static function single_page()
        {
            ob_start();
            include( plugin_dir_path( __DIR__ ) . '../resources/views/single-page.php');
            return ob_get_clean();
        }

        
        /**
         * 
         */
        public static function get_news_and_event()
        {
            $args = array(
                'post_type' => 'news_and_event',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );

            $query = new WP_Query( $args );

            // retrun data
            return $query;
        }

        // @Latest News-Event
        public static function latest_news_and_event( $id )
        {
            $args = array(
                'post_type' => 'news_and_event',
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'post__not_in'  => array($id),
                'orderby' => 'id',
                'order' => 'DESC',
            );

            $query = new WP_Query( $args );

            // retrun data
            return $query;
        }
        
        /**
         * Related News-Event
         */
        public static function related_news_and_event( $post_id, $category_id )
        {
            // args
            $args = array(
                'post_type'   => 'news_and_event',
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'post__not_in'  => array( $post_id ),
                'orderby' => 'id',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'field' => 'event_categories',
                        'value' => $category_id,
                        'compare' => '='
                    )
                )
            );
            
            // query
            $query = new WP_Query( $args );
            // Return result
            return $query;
        }
    }
    
    // instantiate the plugin class
    $news_and_event = new News_And_Event_Autoloader();
    $news_and_event::init();
}