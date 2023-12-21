<?php
/**
 * Plugin Name: Biz - Slider
 * Description: This plugin using form home slider.
 * Version: 1.0.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Autoloader' ) ) {
    class Biz_Main_Slider {
        
        //Constructor
        public function __construct() {}


        // Main Loader fucntion
        public static function init()
        {
            self::acf_activate_hook();
            add_action('init', array('Biz_Main_Slider', 'enqueue_style_and_script'));
            
            //add_action('init', array('Biz_Main_Slider', 'register_post_type'));
            //add_action('init', array('Biz_Main_Slider', 'register_sub_option_page'));
            //add_action('init', array('Biz_Main_Slider', 'register_taxonomy'));
            add_action('init', array('Biz_Main_Slider', 'acf_field'));
            add_action('init', array('Biz_Main_Slider', 'register_option_page'));
            
            //add_action('after_setup_theme', array('Biz_Main_Slider', 'theme_support_thumbnails'));

            // Shortcode
            add_shortcode( 'biz_main_slider_sc', array('Biz_Main_Slider', 'biz_main_slider_shortcode'));
            add_shortcode( 'biz_special_promo_sc', array('Biz_Main_Slider', 'get_special_promo_shortcode'));
            add_shortcode( 'biz_love_these_item_sc', array('Biz_Main_Slider', 'get_love_these_item_shortcode'));

            add_shortcode( 'biz_seasonal_product_slider_sc', array('Biz_Main_Slider', 'biz_seasonal_product_slider_shortcode'));
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

        public static function enqueue_style_and_script()
        {
            $dir = plugin_dir_url( __DIR__ );

            // Style
            // wp_enqueue_style('slick-slider',    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', '1.8.1', 'all');
            wp_enqueue_style('slick', $dir . '../resources/assets/css/slick.css', '0.0.1', 'all');
            wp_enqueue_style('slick-theme', $dir . '../resources/assets/css/slick-theme.css', '0.0.1', 'all');
            wp_enqueue_style('seasonal-slider', $dir . '../resources/assets/css/seasonal-product-slider.css', '0.0.1', 'all');
            wp_enqueue_style('main-slider',     $dir . '../resources/assets/css/main-slider.css', '0.0.1', 'all');

            // scripts
            // wp_enqueue_script('slick-slider',   'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array(), '1.8.1', true);
            wp_enqueue_script('slick-js',   $dir . '../resources/assets/js/slick.js', array(), '0.0.1', true);
            wp_enqueue_script('slick-min-js',   $dir . '../resources/assets/js/slick.min.js', array(), '0.0.1', true);
            wp_enqueue_script('slider-theme',   $dir . '../resources/assets/js/theme.js', array(), '0.0.1', true);
        }


        /**
         * Registration Post Type
         *
         * Note: Must change label: 'Post_Name'.
         * @return void
         */
        /*public static function register_post_type()
        {
            $labels = array(
                'name' => _x('News & Event', 'post type general name'), // Page Title
                'singular_name' => _x('News & Event', 'post type singular name'),
                'add_new' => _x('Add New', 'post type add new button'), // Button 'Add New' Name
                'add_new_item' => __('Create News & Event'), // Page Create Title
                'edit_item' => __('Edit News & Event'), // Page Edit Title
                'new_item' => __('Create News & Event'), 
                'all_items' => __('All News & Event'), // Menu
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
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', )
            ); 
            register_post_type('news-and-event', $args);
        }*/


        /**
         * Registration Option Page
         *
         * Note: Must change label: 'Page_Name'.
         * @return void
         */
        public static function register_option_page()
        {
            acf_add_options_page(array(
                'page_title' 	=> 'Home', // Page Title
                'menu_title'	=> 'Home', // Menu Name
                'menu_slug' 	=> 'Home', // Slug, Syntax: page-name
                'capability'	=> 'edit_posts',
                'icon_url'      => 'dashicons-admin-home', // Icon
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
        /*public static function register_sub_option_page()
        {
            acf_add_options_sub_page(array(
                'page_title' 	=> 'Landing Page', // Page title
                'menu_title'	=> 'Landing Page', // Menu title
                'parent_slug'	=> 'edit.php?post_type=news_and_event', // Slug: edit.php?post_type=post_name
            ));
        }*/


        /**
         * Registration Taxonomy
         *
         * Note: Must change label: 'Taxonomy_Name'.
         * Note: Must change label: 'post_name' follow by Post Type name.
         * @return void
         */ 
        /*public static function register_taxonomy() {
            $labels = array(
                'name'              => _x( '_____', 'taxonomy general name' ), // Taxonomy Name
                'singular_name'     => _x( '_____', 'taxonomy singular name' ), // Singular Name
                'search_items'      => __( 'Search' ), // Button Search
                'all_items'         => __( 'All _____' ),
                'parent_item'       => __( 'Parent' ), // Label Parent
                'parent_item_colon' => __( 'Parent:' ),
                'edit_item'         => __( 'Edit _____' ), // Page Edit Title
                'update_item'       => __( 'Update _____' ),
                'add_new_item'      => __( 'Add New' ), // Button
                'new_item_name'     => __( 'New Course' ),
                'menu_name'         => __( '_____' ), // Menu Name
            );
            $args   = array(
                'hierarchical'      => true, // make it hierarchical (like categories)
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => '_____' ],
            );
            register_taxonomy( '_____', [ '_____' ], $args );
        }*/


        /**
         * WP Thumbnail Support
         *
         * Note: Must change label: 'post_name', Please copy post type name from URL.
         * Note: array( 'post_name', 'page' ), Can be use with Option Pag.
         * @return void
         */
        /*public static function theme_support_thumbnails()
        {
            add_theme_support( 'post-thumbnails', array( '_____' ));
        }*/


        /**
         * ACF Field
         *
         * Note: Must be import field form ACF (Advance Custom Field) and past in this function.
         * @return void
         */
        public static function acf_field() {
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array(
                    'key' => 'group_6093534fa5428',
                    'title' => 'Slider',
                    'fields' => array(
                        array(
                            'key' => 'field_6093536477b95',
                            'label' => 'Main Slider',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'left',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_6093537877b96',
                            'label' => 'Fill in Information',
                            'name' => 'main_slider_banner_rp',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 1,
                            'max' => 0,
                            'layout' => 'block',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_60935b598ffa5',
                                    'label' => 'Image',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ),
                                array(
                                    'key' => 'field_609353a077b97',
                                    'label' => 'Banner',
                                    'name' => 'banner',
                                    'type' => 'image',
                                    'instructions' => 'The banner dimensions must be width 1920 pixels and height 600 pixels. The file size must be less than or equal to 2 MB.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'return_format' => 'url',
                                    'preview_size' => 'medium',
                                    'library' => 'all',
                                    'min_width' => 1920,
                                    'min_height' => 600,
                                    'min_size' => '',
                                    'max_width' => 1920,
                                    'max_height' => 600,
                                    'max_size' => 2,
                                    'mime_types' => '',
                                ),
                                array(
                                    'key' => 'field_628d881b4cc19',
                                    'label' => 'Start Date',
                                    'name' => 'start_date',
                                    'type' => 'date_picker',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'display_format' => 'd/m/Y',
                                    'return_format' => 'Ymd',
                                    'first_day' => 1,
                                ),
                                array(
                                    'key' => 'field_628d882a4cc1a',
                                    'label' => 'End Date',
                                    'name' => 'end_date',
                                    'type' => 'date_picker',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'display_format' => 'd/m/Y',
                                    'return_format' => 'Ymd',
                                    'first_day' => 1,
                                ),
                                array(
                                    'key' => 'field_60935b3f8ffa4',
                                    'label' => 'Content',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ),
                                array(
                                    'key' => 'field_6093544d77b98',
                                    'label' => 'Subtitle',
                                    'name' => 'subtitle',
                                    'type' => 'text',
                                    'instructions' => '80 maximum character limit.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => 80,
                                ),
                                array(
                                    'key' => 'field_609354d277b99',
                                    'label' => 'Headline',
                                    'name' => 'headline',
                                    'type' => 'text',
                                    'instructions' => '40 maximum character limit.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => 40,
                                ),
                                array(
                                    'key' => 'field_609358516a946',
                                    'label' => 'Description',
                                    'name' => 'description',
                                    'type' => 'textarea',
                                    'instructions' => '100 maximum character limit.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'maxlength' => '',
                                    'rows' => 2,
                                    'new_lines' => '',
                                ),
                                array(
                                    'key' => 'field_60935dae6f04b',
                                    'label' => 'More Detail',
                                    'name' => 'more_detail',
                                    'type' => 'group',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'layout' => 'block',
                                    'sub_fields' => array(
                                        array(
                                            'key' => 'field_60935dbf6f04c',
                                            'label' => 'Label',
                                            'name' => 'label',
                                            'type' => 'text',
                                            'instructions' => '30 maximum character limit.',
                                            'required' => 0,
                                            'conditional_logic' => 0,
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'default_value' => '',
                                            'placeholder' => '',
                                            'prepend' => '',
                                            'append' => '',
                                            'maxlength' => 50,
                                        ),
                                        array(
                                            'key' => 'field_60935df56f04d',
                                            'label' => 'Link',
                                            'name' => 'link',
                                            'type' => 'url',
                                            'instructions' => '',
                                            'required' => 0,
                                            'conditional_logic' => 0,
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'default_value' => '',
                                            'placeholder' => '',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_60935fe091b76',
                            'label' => 'Seasonal Product',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'left',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_60935eae0d53e',
                            'label' => 'Fill in Information',
                            'name' => 'seasonal_product_rp',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 1,
                            'max' => 0,
                            'layout' => 'block',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_60935ec40d53f',
                                    'label' => 'Image',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ),
                                array(
                                    'key' => 'field_60935ee60d540',
                                    'label' => 'Banner',
                                    'name' => 'banner',
                                    'type' => 'image',
                                    'instructions' => 'The banner dimensions must be width 1360 pixels and height 500 pixels. The file size must be less than or equal to 2 MB.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'return_format' => 'url',
                                    'preview_size' => 'medium',
                                    'library' => 'all',
                                    'min_width' => 1360,
                                    'min_height' => 500,
                                    'min_size' => '',
                                    'max_width' => 1360,
                                    'max_height' => 500,
                                    'max_size' => 2,
                                    'mime_types' => '',
                                ),
                                array(
                                    'key' => 'field_60936019ca6f2',
                                    'label' => 'Content',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ),
                                array(
                                    'key' => 'field_6093603fca6f3',
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'type' => 'text',
                                    'instructions' => '50 maximum character limit.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => 50,
                                ),
                                array(
                                    'key' => 'field_60936075ca6f4',
                                    'label' => 'Description',
                                    'name' => 'description',
                                    'type' => 'textarea',
                                    'instructions' => '120 maximum character limit.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'maxlength' => 120,
                                    'rows' => 2,
                                    'new_lines' => '',
                                ),
                                array(
                                    'key' => 'field_609360f0ca6f5',
                                    'label' => 'More Detail',
                                    'name' => 'more_detail',
                                    'type' => 'group',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'layout' => 'block',
                                    'sub_fields' => array(
                                        array(
                                            'key' => 'field_609360ffca6f6',
                                            'label' => 'Label',
                                            'name' => 'label',
                                            'type' => 'text',
                                            'instructions' => '30 maximum character limit.',
                                            'required' => 0,
                                            'conditional_logic' => 0,
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'default_value' => '',
                                            'placeholder' => '',
                                            'prepend' => '',
                                            'append' => '',
                                            'maxlength' => 30,
                                        ),
                                        array(
                                            'key' => 'field_60936138ca6f7',
                                            'label' => 'Link',
                                            'name' => 'link',
                                            'type' => 'url',
                                            'instructions' => '',
                                            'required' => 0,
                                            'conditional_logic' => 0,
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'default_value' => '',
                                            'placeholder' => '',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => 'Home',
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
         * Main Slider
         *
         * @return void
         */
        public static function get_main_slider()
        {
            global $wpdb;
            $main_slider = '';
            $data_display = '';

            $data_banner = $wpdb->get_results('SELECT * FROM `revo_mobile_slider` WHERE `is_deleted` = 0 ORDER BY id DESC');
            foreach($data_banner as $value) {

                $start_date        = date_create($value->start_date);
                $start_date_format = date_format($start_date ,'Ymd');
                $end_date          = date_create($value->end_date);
                $end_date_format   = date_format($end_date ,'Ymd');
                $current_date      = date('Ymd');
                $banner            = $value->images_url_web;
                $explore_position  = $value->explore_position;
                $explore_now       = $value->explore_now_url;
                $color_explore_now = $value ->color_explore_now; 
                if(!empty($color_explore_now)){
                    $color_explore_style='style="color:'.$color_explore_now.'"';
                }                                                                                         

                if(($current_date >= $start_date_format && $current_date <= $end_date_format) || (empty($end_date_format) && empty($end_date_format))) {  

                    $main_slider .= '
                        <div>
                            <div class="background-image" style="background-image: url('. $banner .');
                                                        background-repeat: no-repeat; background-position:center">
                                <div class="slide-body">
                                    <div class="container '.$explore_position.'">
                                        <div class="slide-content-wrapper '.$explore_position.'">
                                            <a href="'.$explore_now.'" '.$color_explore_style.'>Explore Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }

                // <div class="container top-top">
                //     <a href="'.$explore_now.'" '.$color_explore_style.'>Explore Now</a>
                // </div>

                // <div class="container top-center">
                //     <a href="'.$explore_now.'" '.$color_explore_style.'>Explore Now</a>
                // </div>

                // <div class="container center-left">
                //     <a href="'.$explore_now.'" '.$color_explore_style.'>Explore Now</a>
                // </div>

                // <div class="container center-center">
                //     <a href="'.$explore_now.'" '.$color_explore_style.'>Explore Now</a>
                // </div>

            }

            // Result
            echo '
                <div class="biz">
                    <div class="main-slider">'. $main_slider .'</div>
                </div>
            ';
            
            // Check rows exists.
            // if( have_rows('main_slider_banner_rp', 'option') ):



                // Loop through rows.
                // while( have_rows('main_slider_banner_rp', 'option') ) : the_row();

                //     // Load sub field value.
                //     $banner   = get_sub_field('banner');
                //     $subtitle = get_sub_field('subtitle');
                //     $headline = get_sub_field('headline');
                //     $description = get_sub_field('description');
                    
                //     $detail   = get_sub_field('more_detail');
                //     $label    = $detail['label'];
                //     $link     = $detail['link'];

                //     $start_date   = get_sub_field('start_date');
                //     $end_date     = get_sub_field('end_date');
                //     $current_date = date('Ymd');

                //         if(($current_date >= $start_date && $current_date <= $end_date) || (empty($start_date) && empty($end_date))) {    
                //             $main_slider .= '
                //             <div>
                //                 <div class="background-image" style="background-image: url('. $banner .');
                //                                             background-repeat: no-repeat; background-position:center">
                //                     <div class="slide-body">
                //                         <div class="container">
                //                         <h2>'.$subtitle.'</h2>
                //                         <h1>'.$headline.'</h1>
                //                         <p>'.$description.'</p>
                //                         <a href="'.$link.'">'.$label.'</a>
                //                         </div>
                //                     </div>
                //                 </div>
                //             </div>';
                //         }

                // // End loop.
                // endwhile;



            // // No value.
            // else :
            //     // Do something...
            // endif;
        }

        
        /**
         * Shortcode Main Slider
         *
         * @return $query
         */
        public static function biz_main_slider_shortcode()
        {
            ob_start();
            Biz_Main_Slider::get_main_slider();
            return ob_get_clean();

            //include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }


        /**
         * Main Slider
         *
         * @return void
         */
        public static function get_seasonal_product_slider()
        {
            // Check rows exists.
            if( have_rows('seasonal_product_rp', 'option') ):

                $seasonal_product_slider = '';

                // Loop through rows.
                while( have_rows('seasonal_product_rp', 'option') ) : the_row();

                    // Load sub field value.
                    $banner = get_sub_field('banner');
                    $title  = get_sub_field('title');
                    $description = get_sub_field('description');
                    
                    $detail   = get_sub_field('more_detail');
                    $label    = $detail['label'];
                    $link     = $detail['link'];

                    $seasonal_product_slider .= '
                        <div>
                            <div class="background-image" style="background-image: url('. $banner .');
                                                                 background-repeat: no-repeat;">
                                <div class="slide-body">
                                    <div class="detail">
                                        <h1>'.$title.'</h1>
                                        <p>'.$description.'</p>
                                        <a href="'.$link.'">'.$label.'</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';

                // End loop.
                endwhile;

                // Result       
                echo '
                    <div class="biz">
                        <div class="seasonal-product-slider">'. $seasonal_product_slider .'</div>
                    </div>
                ';

            // No value.
            else :
                // Do something...
            endif;
        }

        public static function get_special_promo_shortcode()
        {
           global $wpdb;
           $all_banner = $wpdb->get_results (" SELECT * FROM `revo_list_mini_banner` WHERE type = 'Special Promo' AND is_deleted = 0 ORDER BY id DESC LIMIT 4 "); 

            echo'
                <div class="specail-banner">
                    <div class="row">
            ';
           foreach($all_banner as $banner) {
            $image = $banner->image;
            $prod_id = $banner->product_id; 
            $permalink = get_permalink($prod_id);

              echo '
                <div class="col-sm-3 col-6">
                    <a href="'.$permalink.'"><img src="'.$image.'" alt=""></a>
                </div>
              ';
           }
           echo'</div>
           </div>';
        }

        public static function get_love_these_item_shortcode()
        {
           global $wpdb;
           $all_banner = $wpdb->get_results (" SELECT * FROM `revo_list_mini_banner` WHERE type = 'Love These Items' AND is_deleted = 0 ORDER BY id DESC LIMIT 4 "); 

            echo'
                <div class="specail-banner">
                    <div class="row">
            ';
           foreach($all_banner as $banner) {
            $image = $banner->image;
            $prod_id = $banner->product_id; 
            $permalink = get_permalink($prod_id);

              echo '
                <div class="col-sm-3 col-6">
                    <a href="'.$permalink.'"><img src="'.$image.'" alt=""></a>
                </div>
              ';
           }
           echo'</div>
           </div>';
        }


        
        /**
         * Shortcode Main Slider
         *
         * @return $query
         */
        public static function biz_seasonal_product_slider_shortcode()
        {
            ob_start();
            Biz_Main_Slider::get_seasonal_product_slider();
            return ob_get_clean();

            //include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }
    }
    
    // instantiate the plugin class
    $news_and_event = new Biz_Main_Slider();
    $news_and_event::init();
}