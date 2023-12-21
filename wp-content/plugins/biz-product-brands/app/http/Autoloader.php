<?php
/**
 * Plugin Name: BizWeb- Brand
 * Version: 1.0
 * Description: This is the best plugin!
 * Author: Andrea Fuggetta <contact@ndevr.io>
 * Author URI: https://www.ndevr.io
 * Plugin URI:  https://www.ndevr.io
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Autoloader' ) ) {
    class Brand_Autoloader {
        
        //Constructor
        public function __construct() {}


        // Main Loader fucntion
        public static function init()
        {
            self::acf_activate_hook();
            
            //add_action('init', 'Brand_Autoloader::register_post_type');
            //add_action('init', 'Brand_Autoloader::register_sub_option_page');
            //add_action('init', 'Brand_Autoloader::register_option_page');
            //add_action('init', 'Brand_Autoloader::register_taxonomy');
            add_action('init', 'Brand_Autoloader::acf_field');
            
            //add_action('after_setup_theme', 'Brand_Autoloader::theme_support_thumbnails');
            add_shortcode('biz_brand_page', 'Brand_Autoloader::achive_page_shortcode');
        }

        
        // Check AFC after activate plugin
        protected static function acf_activate_hook()
		{
			if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) and !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) and current_user_can( 'activate_plugins' ) )
			{
				wp_die('Your installation is incomplete. Please activate ACF before activate this plugin or contact to BizSolution for technical supports.');
			}
        }


        /**
         * Registration Post Type
         *
         * Note: Must change label: 'Brand'.
         * @return void
         */
        public static function register_post_type()
        {
            $labels = array(
                'name' => _x('Brand', 'post type general name'), // Page Title
                'singular_name' => _x('Brand', 'post type singular name'),
                'add_new' => _x('Add New', 'post type add new button'), // Button 'Add New' Name
                'add_new_item' => __('Create Brand'), // Page Create Title
                'edit_item' => __('Edit Brand'), // Page Edit Title
                'new_item' => __('New Brand'), 
                'all_items' => __('All Brand'),
                'view_item' => __('View Brand'),
                'search_items' => __('Search'), // Button Search
                'not_found' =>  __('No Data found'), // Label Not Found Data in publish
                'not_found_in_trash' => __('No Data found in Trash'),  // Label Not Found Data in trush
                'parent_item_colon' => '',
                'menu_name' => __('Brand') // Menu Name

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
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'attributes' )
            ); 
            register_post_type('Brand', $args);
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
                'page_title' 	=> 'Brand', // Page Title
                'menu_title'	=> 'Brand', // Menu Name
                'menu_slug' 	=> 'brand', // Slug, Syntax: page-name
                'capability'	=> 'brand',
                'icon_url'      => 'dashicons-wordpress-alt', // Icon
                'position'      => 81,
                'redirect'		=> false
            ));
        }


        /**
         * Registration Sub-Option Page
         *
         * Note: Must change label: 'Option_Page_Name'.
         * Note: Must change label: 'Brand' follow by Post Type name.
         * @return void
         */
        public static function register_sub_option_page()
        {
            acf_add_options_sub_page(array(
                'page_title' 	=> 'Landing Page Brand', // Page title
                'menu_title'	=> 'Landing Page', // Menu title
                'parent_slug'	=> 'edit.php?post_type=newsevent', // Slug: edit.php?post_type=offers
            ));
        }


        /**
         * Registration Taxonomy
         *
         * Note: Must change label: 'Taxonomy_Name'.
         * Note: Must change label: 'Brand' follow by Post Type name.
         * @return void
         */ 
        public static function register_taxonomy() {
            $labels = array(
                'name'              => _x( 'Brand Type', 'taxonomy general name' ), // Taxonomy Name
                'singular_name'     => _x( 'Brand Type', 'taxonomy singular name' ), // Singular Name
                'search_items'      => __( 'Search' ), // Button Search
                'all_items'         => __( 'All Brand Type' ),
                'parent_item'       => __( 'Parent Brand Type' ), // Label Parent
                'parent_item_colon' => __( 'Parent Brand Type:' ),
                'edit_item'         => __( 'Edit Brand Type' ), // Page Edit Title
                'update_item'       => __( 'Update Brand Type' ),
                'add_new_item'      => __( 'Create Brand Type' ), // Button Add New and Sub-Title
                'new_item_name'     => __( 'New Course Brand Type' ),
                'menu_name'         => __( 'Brand Type' ), // Menu Name
            );
            $args   = array(
                'hierarchical'      => true, // make it hierarchical (like categories)
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'Slug_Name' ],
            );
            register_taxonomy( 'Brand_type', [ 'brand' ], $args );
        }


        /**
         * WP Thumbnail Support
         *
         * Note: Must change label: 'Brand', Please copy post type name from URL.
         * Note: array( 'Brand', 'page' ), Can be use with Option Pag.
         * @return void
         */
        public static function theme_support_thumbnails() {
            add_theme_support( 'post-thumbnails', array( 'Brand' ) );
        }


        /**
         * ACF Field
         *
         * Note: Must be import field form ACF (Advance Custom Field) and past in this function.
         * @return void
         */
        public static function acf_field()
        {
            // code here 
        }


        /**
         * Achive Page
         *
         * @return void
        */
        public static function achive_page_shortcode() {
            ob_start();
            include(  plugin_dir_path( __DIR__ ) . '../resources/views/achive-page.php' );
            return ob_get_clean();
        }
        

        // @Query product brand
        public static function brand_query() {

            $brand_logo = [];
            $explode_image_url = [];

            $brands = get_terms( array(
                'taxonomy' => 'pwb-brand',
                'hide_empty' => false,
            ) );

            foreach($brands as $brand) {

                $upload_dir_obj = wp_get_upload_dir();
                
                $term_id = $brand->term_id ;
                $obj_post_id = get_term_meta( $term_id, 'pwb_brand_image', true );
                $post_id = $obj_post_id != null ? $obj_post_id : '';
                $slug = $brand->slug;
                // $uri = 'brands/';
                $uri = '/shop/?swoof=1&pwb-brand=';
                $url_filter = site_url().'/'.$uri.$slug;

                $brand_logo_url = get_post_field( 'guid', $post_id );
                $explode_image_url = explode('/', $brand_logo_url);

                if( count($explode_image_url) >= 7 )
                {
                    $brand_logo = $brand_logo_url;
                }
                else {
                    $brand_logo = $upload_dir_obj['baseurl'] . '/default-images/default-brand.jpg';
                }

                echo '
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <div class="brand-item">
                            <a href="'.$url_filter.'">
                                <div class="thumbnail">
                                        <img src="'.$brand_logo.'">
                                    <div class="brand-name">
                                        <h3>'.$brand->name.'</h3>
                                    </div>      
                                </div>
                            </a>
                        </div>
                    </div>
                ';
            }
        }

    }
    
    // instantiate the plugin class
    // $wp_plugin_template = new Autoloader(); 
}