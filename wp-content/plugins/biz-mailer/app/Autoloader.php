<?php
/**
 * Class Name: Class-Name
 * Description: -Type description here-.
 * Version: 0.0.2
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Biz_Mailer' ) ) {
    class Biz_Mailer {
        
        // Constructor
        public function __construct() {}

        // Main Loader fucntion
        public static function init()
        {
            self::acf_activate_hook();

            // WP | ACF Registration
            // add_action('init', array('Biz_Mailer', 'enqueue_style_and_script'));
            // add_action('init', array('Biz_Mailer', 'register_post_type'));
            // add_action('init', array('Biz_Mailer', 'register_sub_option_page'));
            // add_action('init', array('Biz_Mailer', 'register_taxonomy'));
            add_action('init', array('Biz_Mailer', 'acf_field'));
            add_action('init', array('Biz_Mailer', 'register_option_page'));
            // add_action('admin_menu', array('Biz_Mailer', 'add_main_menu_admin_pages'));
            
            // Theme Support
            // add_action('after_setup_theme', array('Biz_Mailer', 'theme_support_thumbnails'));

            // Shortcode
            // add_shortcode( 'do_shortcode_name', array('Biz_Mailer', 'shortcode_function_name'));
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
            wp_enqueue_style('seasonal-slider', $dir . '_____', '0.0.1', 'all');

            // scripts
            wp_enqueue_script('slider-theme',   $dir . '_____', array(), '0.0.1', true);
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
                'page_title' 	=> 'Biz Mailer',         // Page Title
                'menu_title'	=> 'Biz Mailer',         // Menu Name
                'menu_slug' 	=> 'biz-mailer',         // Slug, Syntax: page-name
                'capability'	=> 'edit_posts',    // Capability
                'icon_url'      => 'dashicons-email-alt',         // Icon
                'position'      => 105,              // Sample: 85,90,95
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
         * Create admin Page to menu.
         * Adds a new top-level page to the administration menu
         */
        public static function add_main_menu_admin_pages() {
           
            add_menu_page(
                __( 'Biz Mailer', 'AYEC' ),
                __( 'Biz Mailer', 'AYEC' ),
                'manage_options',
                'biz-mailer',                                           // Slug
                array( 'Biz_Mailer', 'biz_main_menu_page_callback'),    // Call Function
                'dashicons-email-alt'                                   // Icon
            );
        }

        /**
         * Disply callback for the Unsub page.
         */
        public static function biz_main_menu_page_callback() {
            echo 'Unsubscribe Email List';
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
                    'key' => 'group_6316f14879e58',
                    'title' => 'Biz Mailer',
                    'fields' => array(
                        array(
                            'key' => 'field_6316f1505bba8__trashed',
                            'label' => 'Mail',
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
                            'key' => 'field_6316f3653f11f__trashed',
                            'label' => 'Subject',
                            'name' => 'mail_subject',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f3723f120__trashed',
                            'label' => 'Content',
                            'name' => 'mail_content',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'tabs' => 'visual',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'delay' => 0,
                        ),
                        array(
                            'key' => 'field_6316f4acd4e72__trashed',
                            'label' => 'Attachment',
                            'name' => 'mail_attachment',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array(
                            'key' => 'field_6316f1665bba9__trashed',
                            'label' => 'Configuration',
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
                            'key' => 'field_6316f52be427f__trashed',
                            'label' => 'SMTP Host',
                            'name' => 'mail_smtp_host',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f54fe4280__trashed',
                            'label' => 'SMTP Port',
                            'name' => 'mail_smtp_port',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f67fe4281__trashed',
                            'label' => 'SMTP Username',
                            'name' => 'mail_smtp_username',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f69ee4282',
                            'label' => 'SMTP Password',
                            'name' => 'mail_smtp_password',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f6c4e4283__trashed',
                            'label' => 'SMTP From',
                            'name' => 'mail_smtp_from',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f6dde4284__trashed',
                            'label' => 'SMTP From Name',
                            'name' => 'mail_smtp_from_name',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6316f6ffe4285__trashed',
                            'label' => 'RECEIVER Email',
                            'name' => 'mail_receiver_email',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_63170ae37c20a__trashed',
                            'label' => 'Setting',
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
                            'key' => 'field_63170b34f4c2e__trashed',
                            'label' => 'Mail Status',
                            'name' => 'mail_status',
                            'type' => 'button_group',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                1 => 'ON',
                                0 => 'OFF',
                            ),
                            'allow_null' => 0,
                            'default_value' => 0,
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'field_63170bd45c765__trashed',
                            'label' => 'Norted',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => 'Please make sure you have created mail server, allow port for webmail and config SMTP.',
                            'new_lines' => 'br',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_63170cb45121c__trashed',
                            'label' => 'How to use?',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '1. Turn on <b>Mail Status</b>.
                2. Config SMTP in tab <b>Configuration</b>.
                3. Call function name <b>biz_send_mail( $receiver_email )</b> that the value of param is an email that you want to send to.',
                            'new_lines' => 'br',
                            'esc_html' => 0,
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => 'biz-mailer',
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
         * Shortcode
         * Note: You can call function or include page.
         */
        public static function shortcode_function_name()
        {
            ob_start();
            Biz_Mailer::_____();
            return ob_get_clean();

            //include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }
    }
    
    // Instantiate the plugin class
    $biz_mailer = new Biz_Mailer();
    $biz_mailer::init();
}