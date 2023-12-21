<?php
/**
 * Class Name: Plus Gate SMS
 * Description: Send SMS by +Gate.
 * Version: 0.0.2
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Plus_Gate_SMS' ) ) {
    class Plush_Gate_SMS {
        
        // Constructor
        public function __construct() {}

        // Main Loader fucntion
        public static function init()
        {
            self::acf_activate_hook();

            // WP | ACF Registration
            // add_action('init', array('Plush_Gate_SMS', 'register_post_type'));
            // add_action('init', array('Plush_Gate_SMS', 'register_sub_option_page'));
            // add_action('init', array('Plush_Gate_SMS', 'register_taxonomy'));
            add_action('init', array('Plush_Gate_SMS', 'acf_field'));
            add_action('init', array('Plush_Gate_SMS', 'register_option_page'));
            add_action('init', array('Plush_Gate_SMS', 'create_table'));
            add_action('init', array('Plush_Gate_SMS', 'create_table_otp_failed'));
            // add_action('init', array('Plush_Gate_SMS', 'enqueue_style_and_script'));
            
            // Theme Support
            // add_action('after_setup_theme', array('Plush_Gate_SMS', 'theme_support_thumbnails'));

            // Shortcode
            // add_shortcode( 'do_shortcode_name', array('Plush_Gate_SMS', 'shortcode_function_name'));

            // Add script handles to the array below
            // add_filter('script_loader_tag', array('Biz_Woo_Distance_Delivery', 'add_attribute_to_script_tag'), 10, 2);

            // Rest API
            // add_action( 'rest_api_init', array('Plush_Gate_SMS', 'register_route_api'));
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
         * Find replace tage "defer" to CDN javascript.
         * Add script handles to the array below
         */
        public static function add_attribute_to_script_tag($tag, $handle) {
            $scripts_to_defer = array('script-id');
        
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
                'page_title' 	=> '+Gate SMS',     // Page Title
                'menu_title'	=> '+Gate SMS',     // Menu Name
                'menu_slug' 	=> 'plusgate-sms', // Slug, Syntax: page-name
                'capability'	=> 'edit_posts',    // Capability
                'icon_url'      => '_____',         // Icon
                'position'      => 80,              // Sample: 85,90,95
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
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array(
                    'key' => 'group_63f2f97407fc0',
                    'title' => '+Gate',
                    'fields' => array(
                        array(
                            'key' => 'field_63f2f9e0706ad',
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
                            'placement' => 'left',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_63f2f978706ac',
                            'label' => 'SMS Content',
                            'name' => 'sms_content',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Dear customer, your OTP for registration is [otp_number]. Use this OTP to validate your phone number.',
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'br',
                        ),
                        array(
                            'key' => 'field_63f2fa26d7ef9',
                            'label' => 'Credential',
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
                            'key' => 'field_63f2fa41d7efa',
                            'label' => '',
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
                            'message' => '<b class="acf-required">Note:</b> Make sure you have created with +Gate and have an incorrect credential to complete in boxes below.',
                            'new_lines' => 'br',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_63f2fa7ef9049',
                            'label' => 'Sender',
                            'name' => 'sms_sender',
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
                            'key' => 'field_63f2faa8f904a',
                            'label' => 'Secret Key',
                            'name' => 'sms_secret_key',
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
                            'key' => 'field_63f2fad2f904b',
                            'label' => 'Private Key',
                            'name' => 'sms_private_key',
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
                            'key' => 'field_63f2fb2896433',
                            'label' => 'OTP Setting',
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
                            'key' => 'field_63f2fb4d96434',
                            'label' => 'Status',
                            'name' => 'sms_status',
                            'type' => 'button_group',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                0 => 'Off',
                                1 => 'On',
                            ),
                            'allow_null' => 0,
                            'default_value' => '',
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'field_63f2fb9199e72',
                            'label' => 'Number of digits',
                            'name' => 'sms_number_of_digits',
                            'type' => 'number',
                            'instructions' => 'The OTP number of digits is allowed minimum 4 and maximum 8.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 4,
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => 4,
                            'max' => 8,
                            'step' => '',
                        ),
                        array(
                            'key' => 'field_63f2fbd9c05cb',
                            'label' => 'Interval (Seconds)',
                            'name' => 'sms_interval_second',
                            'type' => 'number',
                            'instructions' => 'The interval is allowed maximun 300 sencodes. Unlimited interval set defualt 0.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 0,
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => '',
                            'max' => 300,
                            'step' => '',
                        ),
                        array(
                            'key' => 'field_63f2fc1ddbf3c',
                            'label' => 'Failed Attempt Limit',
                            'name' => 'sms_failed_attempt_limit',
                            'type' => 'number',
                            'instructions' => 'The number of failed attempt limit. Set 0 for unlimited SMS.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 0,
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => '',
                            'max' => '',
                            'step' => '',
                        ),
                        array(
                            'key' => 'field_63f2fc56e28db',
                            'label' => 'Failed Attempt Period of Suspension',
                            'name' => 'sms_failed_attempt_period',
                            'type' => 'number',
                            'instructions' => 'The phone number will be suspended for the period of time (in days), that they already reach failed attempt limit. Set 0 (days) for unlimited.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 0,
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => '',
                            'max' => '',
                            'step' => '',
                        ),
                        array(
                            'key' => 'field_63f2fc8cf6f72',
                            'label' => 'Test SMS',
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
                            'key' => 'field_63f2fd048cdd7',
                            'label' => 'Test',
                            'name' => 'sms_test_status',
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
                                0 => 'Off',
                                1 => 'On',
                            ),
                            'allow_null' => 0,
                            'default_value' => '',
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'field_63f2fc9ff6f73',
                            'label' => 'Send To',
                            'name' => 'sms_test_send_to',
                            'type' => 'text',
                            'instructions' => 'Fill in the phone number that you want to send OTP.',
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
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_63f2fccf12ae5',
                            'label' => 'Content',
                            'name' => 'sms_test_content',
                            'type' => 'textarea',
                            'instructions' => 'Fill in the content that you want to send OTP.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'OTP From Biz Solution.',
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => 4,
                            'new_lines' => 'br',
                        ),
                        array(
                            'key' => 'field_63f2fd3ea5319',
                            'label' => 'How to user?',
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
                            'key' => 'field_63f2fd4aa531a',
                            'label' => '',
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
                            'message' => 'The plugin +Gate SMS is provided for using send SMS by sim card number, which you can call by Web Application or API. Before you this plugin I suggest you read the introduction below.
                
                <b class="acf-required">Note</b>
                – This plugin can use this +Gate SMS only.
                – Please follow all the steps of the introduction below we will tell you, how to use this plugin.
                
                <b class="acf-required">Warning</b>
                1. Fill in all of the requested information in the tab credential.
                2. Fill in the description that you want to send to those who are registering with the keyword “[otp number]”. Ex: Here is the OTP number: [otp number].
                3. Turn status to on in tab OTP Setting.
                4. After completing all of steps above you can test send OTP in the tab Test SMS, just fill in the information required.
                
                <b class="acf-required">Functionality</b>
                -----------------
                
                <b class="acf-required">- Web and API</b>
                For the Web and API, we provide tows functions to call. Click here to <a href="'.plugin_dir_url( __DIR__ ).'sample-api/OTP-PlusGate.zip" download>Download</a> sample.
                1. For send OTP.
                2. For confirm OTP.',
                            'new_lines' => 'wpautop',
                            'esc_html' => 0,
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => 'plusgate-sms',
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
            Plush_Gate_SMS::_____();
            return ob_get_clean();

            //include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }
        
        /**
         * Create Table OTP
         * Note: Init create table OTP
         */
        public static function create_table() {
            global $wpdb;
            $dbname = DB_NAME;
            $username = DB_USER;
            $password = DB_PASSWORD;
            $servername = DB_HOST;

            try {
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "
                    CREATE TABLE ".$wpdb->prefix."plusgate_otp (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        phone varchar(15) NOT NULL,
                        otp int(11) NOT NULL,
                        status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:Pending, 1:Confirmed',
                        duration time NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime DEFAULT NULL,
                        PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ";

                if ($conn->query($sql) === TRUE) {
                    sms_error_log('Table OTP created successfully');
                } else {
                    //sms_error_log("Error creating table OTP: " . $conn->error);
                }
            } catch(PDOException $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Create Table OTP Failed
         * Note: Init create table OTP Failed
         */
        public static function create_table_otp_failed() {
            global $wpdb;
            $dbname = DB_NAME;
            $username = DB_USER;
            $password = DB_PASSWORD;
            $servername = DB_HOST;

            try {
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "
                    CREATE TABLE ".$wpdb->prefix."plusgate_otp_failed (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        phone varchar(15) NOT NULL,
                        otp int(11) NOT NULL,
                        status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:blocked, 1:unblock',
                        created_at datetime NOT NULL,
                        updated_at datetime DEFAULT NULL,
                        PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ";

                if ($conn->query($sql) === TRUE) {
                    sms_error_log('Table OTP Failed created successfully');
                } else {
                    // sms_error_log("Error creating table OTP Failed: " . $conn->error);
                }
            } catch(PDOException $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Rest API
         * Note: You can call function or include page.
         */
        // public function register_route_api()
        // {
        //     add_action( 'rest_api_init', function () {
        //         register_rest_route( 'plusgate_sms/api', '/send_otp', array(
        //             'methods' => 'POST',
        //             'callback' => array($this, 'send_otp_plusgate'),
        //             'permission_callback' => function() {
        //                 return true;
        //             }
        //         ));
        //     });
        // }
    }
    
    // Instantiate the plugin class
    $plusGateOTP = new Plush_Gate_SMS();
    $plusGateOTP::init();
}