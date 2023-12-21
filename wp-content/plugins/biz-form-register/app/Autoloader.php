<?php
/**
 * Class Name: Class-Name
 * Description: -Type description here-.
 * Version: 0.0.3
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

use AC\Asset\Location;

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Woocommerce_Register_Account' ) ) {
    class Woocommerce_Register_Accounts{
        // Main Loader fucntion
        public static function init()
        { 
            self::acf_activate_hook();
            // WP | ACF Registration
            // add_action('init', array('Plush_Gate_SMS', 'register_post_type'));
            // add_action('init', array('Plush_Gate_SMS', 'register_sub_option_page'));
            // add_action('init', array('Plush_Gate_SMS', 'register_taxonomy'));
            add_action('init', array('Woocommerce_Register_Accounts', 'field_acf'));
            add_action('init', array('Woocommerce_Register_Accounts', 'register_option_page'));
            add_action('init', array('Woocommerce_Register_Accounts', 'create_table'));
            add_action('init', array('Woocommerce_Register_Accounts', 'create_table_otp_failed'));
            // call register style
            add_action('init', array('Woocommerce_Register_Accounts', 'register_style_and_script'));
            //created shortcode 
            add_shortcode( 'woocommerce_register_account', array('Woocommerce_Register_Accounts', 'woocommerce_register_account_shortcode'));
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

        public static function register_style_and_script()
        {
            $dir = plugin_dir_url( __DIR__ );

            // register Style
            wp_enqueue_style('main-register',     $dir . './resources/assets/css/style-from.css', '0.0.1', 'all');
            // script
        }
        
        /**
         * custom registration function
         *
         * @return void
         */
        public static function custom_registrations_function()
        {
            global $reg_errors, $first_name, $last_name, $password, $confirm_password, $email, $phone, $woocommerce, $user;
            $reg_errors = new WP_Error;
            if ( count($reg_errors->get_error_messages()) < 1 ) {
                $get_result = [];
                if(isset($_POST['submit'])){
                    $check_verify_otp   = get_field('verify_otp','option');
                    $first_name         = $_POST['first_name'];
                    $last_name          = $_POST['last_name'];
                    $password           = $_POST['password'];
                    $confirm_password   = $_POST['confirm_password'];
                    $email              = $_POST['email'];
                    $phones             = $_POST['phone'];
                    $invite_code        = $_POST['invite_code'];

                    // Save User 
                    $add_user = array(
                        'user_login'   =>  sanitize_title( $first_name . ' ' . $last_name . '_' . uniqid() ),
                        'nickname'     =>  $first_name . ' ' . $last_name,
                        'display_name' =>  $first_name . ' ' . $last_name,
                        'user_email'   =>  $email,
                        'user_pass'    =>  $password,
                        'first_name'   =>  $first_name,
                        'last_name'    =>  $last_name,
                        'role'         => 'customer'
                    );
                    if(empty($check_verify_otp)){
                        // call function validation form
                        Woocommerce_Register_Accounts::registrations_validation( $first_name, $last_name, $password, $confirm_password, $email, $phones);
                        $_user_email = get_user_by('email',$email);
                        if(empty($_user_email)){
                            // sanitize user form input
                            $first_name        =   sanitize_user($_POST['first_name']);
                            $last_name         =   sanitize_user($_POST['last_name']);
                            $password          =   esc_attr($_POST['password']);
                            $confirm_password  =   esc_attr($_POST['confirm_password']);
                            $email             =   sanitize_email($_POST['email']);
                            $phone             =   esc_attr($_POST['phone']);
                            if(!empty($first_name) && !empty($email) &&  !empty($last_name) &&  !empty($phone) &&  !empty($password) &&  !empty($confirm_password) && $password == $confirm_password){
                                $user_id = wp_insert_user( $add_user );
                            }
                            
                            // generet code for user code
                            $user_code   = mt_rand(0000,9999);
                            $text_substr = substr($email,0,3);
                            $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

                            update_user_meta( $user_id, 'register_status', 1 );
                            $odoo_id = Woocommerce_Register_Accounts::getOdooID($first_name.''.$last_name.'-'.$user_id,$phone,$email);
                            add_user_meta( $user_id, 'odoo_id', $odoo_id, true );
                            add_user_meta( $user_id, 'invite_code', $invite_code, true);
                            add_user_meta( $user_id, 'user_code',  $invite_code_for_user, true );
                            add_user_meta( $user_id, 'phone_number', $phones, true);

                            Woocommerce_Register_Accounts::coupon_code($user_id, $invite_code,$email);
                            if(!empty($user_id)){
                                // redirect when create success
                                Woocommerce_Register_Accounts::customers_auto_login( $user_id );
                                wp_redirect( site_url().'/my-account/edit-account/', 301 );
                            }
                        }

                    }
                }

                $check_verify_otp = get_field('verify_otp','option');
                if(!empty($check_verify_otp)){
                    $user_id = 0;
                    global $first_name, $last_name, $password, $confirm_password, $email, $phone;
                    if (isset($_POST['submit']) &&isset($_POST['first_name']) &&isset($_POST['last_name']) &&isset($_POST['password']) &&isset($_POST['confirm_password']) &&isset($_POST['email']) &&isset($_POST['phone']))
                    { 
                        // call function validation form
                        Woocommerce_Register_Accounts::registrations_validation( $first_name, $last_name, $password, $confirm_password, $email, $phones);
                        // sanitize user form input
                        $first_name        =   sanitize_user($_POST['first_name']);
                        $last_name         =   sanitize_user($_POST['last_name']);
                        $password          =   esc_attr($_POST['password']);
                        $confirm_password  =   esc_attr($_POST['confirm_password']);
                        $email             =   sanitize_email($_POST['email']);
                        $phone             =   esc_attr($_POST['phone']);
                        
                    }
                    $user_email = get_user_by('email',$email);
                    if(!empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password) && !empty($email) && !empty($phone) && empty($user_email) && $password == $confirm_password){
                        // call plugin send otp 
                        $phone_otp = '855'.substr($phones,1);
                        $response  = send_otp_plusgate($phone_otp);
                        $otp       = $response['otp'];
                        // $otp = 333333;
                        Woocommerce_Register_Accounts::validate_otp($otp);
                        
                        global $wpdb;
                        // store data temporarey
                        $wpdb->insert('ph0m31e_form_register_temporary',                  
                            [
                                'first_name'        => $first_name,
                                'last_name'         => $last_name,
                                'phone'             => $phones,
                                'email'             => $email,
                                'password'          => $password,
                                'confirm_password'  => $confirm_password,
                                'invite_code'       => $invite_code,
                                'otp'               => $otp,
                            ]
                        );
                    }
                }

                // check condition form register and form otp
                global $wpdb;
                $user_email = get_user_by('email',$email);
                $get_result = $wpdb->get_results("SELECT * FROM ph0m31e_form_register_temporary WHERE otp = '$otp'");
                if(( count($get_result) < 1 || !empty($user_email) || ($password != $confirm_password)) && !isset($_GET['otp']) == true){
                    Woocommerce_Register_Accounts::form_register($first_name, $last_name, $password, $confirm_password, $email, $phones, $invite_code);
                }
                elseif(!isset($_GET['otp']) == true){
                    Woocommerce_Register_Accounts::verify_otp_form($user_id,$phone);
                }
    
                // submit otp
                if( isset( $_POST['otp_submit']) ){
                    $otp_number     = $_POST['otp_number'];
                    $phone_number   = $_POST['phone'];
                    $user_id        = $_POST['user_id'];

                    //get data by otp 
                    $result_id =  $wpdb->get_results("SELECT * FROM ph0m31e_form_register_temporary WHERE otp = '$otp_number'");
                    foreach( $result_id as $value){
                        $first_name     = $value->first_name;
                        $last_name      = $value->last_name;
                        $phone          = $value->phone;
                        $email          = $value->email;
                        $password       = $value->password;
                        $invite_code    = $value->invite_code;
                    }

                    // generet user code
                    $user_code_rand = mt_rand(0000,9998);
                    $text_substr = substr($email,0,3);
                    $user_code = $text_substr.$user_id.$user_code_rand.'Z';

                    $userdata = array(
                        'user_login'   =>  sanitize_title( $first_name . ' ' . $last_name . '_' . uniqid() ),
                        'nickname'     =>  $first_name . ' ' . $last_name,
                        'display_name' =>  $first_name . ' ' . $last_name,
                        'user_email'   =>  $email,
                        'user_pass'    =>  $password,
                        'first_name'   =>  $first_name,
                        'last_name'    =>  $last_name,
                        'role'         => 'customer'
                    );
                    // save data to woo
                    $user_id = wp_insert_user( $userdata );

                    // generet code for user code
                    $user_code   = mt_rand(0000,9999);
                    $text_substr = substr($email,0,3);
                    $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

                    update_user_meta( $user_id, 'register_status', 1 );
                    $add_user_odoo = get_field('add_user_odoo','option');
                    $username = $first_name.''.$last_name.'-'.$user_id;

                    $odoo_id = Woocommerce_Register_Accounts::getOdooID($username,$phone,$email);
                    add_user_meta( $user_id, 'odoo_id', $odoo_id, true );
                    add_user_meta( $user_id, 'invite_code', $invite_code, true);
                    add_user_meta( $user_id, 'user_code',  $invite_code_for_user, true );
                    add_user_meta( $user_id, 'phone_number', $phone_number, true);

                    // delete user in table taporarey
                    $wpdb->delete('ph0m31e_form_register_temporary' , array( 'otp' => $otp_number));

                    Woocommerce_Register_Accounts::coupon_code($user_id, $invite_code,$email);

                    if(count($result_id)=='0'){
                        Woocommerce_Register_Accounts::verify_otp_not_seccess();
                        Woocommerce_Register_Accounts::verify_otp_form($user_id,$phone);
                    } else{
                        // redirect when verify otp success
                        Woocommerce_Register_Accounts::customers_auto_login( $user_id );
                        wp_redirect( site_url().'/my-account/edit-account/', 301 );
                    }
                }
            
                // call function create user to odoo
                $username = $first_name.''.$last_name.'-'.$user_id;
                $add_user_odoo = get_field('add_user_odoo','option');
                // if(!empty($add_user_odoo)){
                    Woocommerce_Register_Accounts::register_user_odoo($username,$phone,$email);
                // }
            }
        }
        
        // getneret coupon
        public static function coupon_code($user_id, $invite_code,$email){
            // get option page percentage
            $percentage_amount  = get_field('amount_and_percentage','option');
            $for_inviter = get_field('for_inviter','option');
            $for_invitee = get_field('for_invitee','option');
            if($percentage_amount==0){
                $percentage = 'percent';
            }
            else{
                $percentage = 'fixed_cart';
            }
            if($for_inviter != '' && $for_inviter != '0' && $for_invitee !='' && $for_invitee !='0'){
                if(!empty($invite_code)) {
                    // @find user who give invite code
                    $user_invite =  get_users(
                        array(
                            'meta_key'  => 'user_code',
                            'meta_value'=> $invite_code,
                            'number'    => 1
                        )
                    );
                    foreach($user_invite as $user_invite_value) {
                        $user_invite_id    = $user_invite_value->ID;
                        $user_invite_email = $user_invite_value->user_email;
                    }
                    
                    if(!empty($user_invite_id)){

                        //create coupon for user invite
                        $coupon_user_invite = rand(0000,9999);
                        $text_substr = substr($user_invite_email,0,3);
                        $coupon_code_user_invite = $text_substr.$user_invite_id.$coupon_user_invite.'Z';

                        $id_coupon_user_invite = wp_insert_post(array (
                            'post_title'    =>  $coupon_code_user_invite,
                            'post_type'     => 'shop_coupon',
                            'post_status'   => 'publish',
                            'post_content'  => 'Promocode invite',
                            'discount_type' => 'percent',
                            'post_excerpt'  => $coupon_code_user_invite.'for_invite' //description
                        ));

                        if ( $id_coupon_user_invite ) {
                            update_post_meta( $id_coupon_user_invite, 'usage_limit_per_user', '1');
                            update_post_meta( $id_coupon_user_invite, 'minimum_amount', '1');
                            update_post_meta( $id_coupon_user_invite, 'date_expires', date('2900-01-01'));
                            update_post_meta( $id_coupon_user_invite, 'coupon_amount', $for_inviter);
                            update_post_meta( $id_coupon_user_invite, 'discount_type',$percentage); 
                            update_post_meta( $id_coupon_user_invite, 'description', $user_id); 
                            update_post_meta( $id_coupon_user_invite, 'usage_limit', '1');

                            // create coupon user invte to odoo
                            add_coupons_odoo($id_coupon_user_invite);
                        }

                        //list coupon for user invite
                        $list_coupon_user_invite = wp_insert_post( array(
                            'post_status'  => 'publish',
                            'post_type'    => 'generate-coupon',
                            'post_title'   => 'Generate Coupon',
                            'post_content' => ' ',
                            )  
                        );
                        update_post_meta( $id_coupon_user_invite, 'inviter_id',$user_invite_id);
                        if(!empty($list_coupon_user_invite)) {
                            update_post_meta( $list_coupon_user_invite, 'user_name', $user_invite_email);  
                            update_post_meta( $list_coupon_user_invite, 'user_id', $user_invite_id );
                            update_post_meta( $list_coupon_user_invite, 'coupon_code',$coupon_user_invite);
                            update_post_meta( $list_coupon_user_invite, 'coupon_amount',$for_inviter );
                            update_post_meta( $list_coupon_user_invite, 'discount_type', $percentage );
                        }
                        
                        //create coupon for owner
                        $coupon_user_owner = rand(0000,9999);
                        $text_substr = substr($email,0,3);
                        $coupon_code_user_owner = $text_substr.$user_id.$coupon_user_owner.'Z';
                        $id_coupon_user_owner = wp_insert_post(array (
                            'post_title'  =>  $coupon_code_user_owner,
                            'post_type'   => 'shop_coupon',
                            'post_status' => 'publish',
                            'post_content'=> 'Promocode invite',
                            'discount_type' => 'percent',
                            'post_excerpt' => $coupon_code_user_owner.'For_invitee', //description
                        ));
                        update_post_meta($id_coupon_user_owner, 'inviter_id',$user_id);
                        if ( $id_coupon_user_owner ) {
                            update_post_meta($id_coupon_user_owner, 'coupon_amount',$for_invitee);
                            update_post_meta($id_coupon_user_owner, 'discount_type',$percentage );
                            update_post_meta($id_coupon_user_owner, 'usage_limit', '1');
                            update_post_meta($id_coupon_user_owner, 'usage_limit_per_user', '1');
                            update_post_meta($id_coupon_user_owner, 'minimum_amount', '1');
                            update_post_meta($id_coupon_user_owner, 'Product IDs', '');
                            update_post_meta($id_coupon_user_owner, 'date_expires', date('2900-01-01'));

                            //create coupon user owner to odoo
                            add_coupons_odoo($id_coupon_user_owner);
                        }

                        //list coupon for user owner
                        $list_coupon_owner = wp_insert_post( array(
                            'post_status'  => 'publish',
                            'post_type'    => 'generate-coupon',
                            'post_title'   => 'Generate Coupon',
                            'post_content' => ' '
                        ) 
                        );
                        if(!empty($list_coupon_owner)) {
                            update_post_meta( $list_coupon_owner, 'user_name', $email);  
                            update_post_meta( $list_coupon_owner, 'user_id', $user_id );
                            update_post_meta( $list_coupon_owner, 'coupon_code',$id_coupon_user_owner);
                            update_post_meta( $list_coupon_owner, 'coupon_amount', $for_invitee );
                            update_post_meta( $list_coupon_owner, 'discount_type',$percentage );
                            update_post_meta( $list_coupon_owner, 'usage_limit', '1' );
                        }
                    }
                }
            }
        }

        /**
         * Register Form
         *
         * @param [string] $first_name
         * @param [string] $last_name
         * @param [string] $password
         * @param [string] $confirm_password
         * @param [string] $email
         * @param [string] $phone
         * @return void
         */

        // form register
        public static function form_register( $first_name, $last_name, $password, $confirm_password, $email, $phones , $invite_code) {
            echo '
                <div class="form_register_" style="display: flex;justify-content: center;align-items: center;">
                    <form action="" method="post" class="woocommerce-form woocommerce-form-register register">
                        <h2 class="text-center" id="title-register-form">'.esc_html( 'Register', 'woocommerce' ).'</h2>

                        <div class="row form-Register-Input-text">
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="form-group" style="">
                                            <label for="account_first_name"><b>'. esc_html( 'First name', 'woocommerce').' <span class="require-star">*</span></b></label><br>
                                            <input 
                                            type="text" 
                                            class="woocommerce-form-Register-Input" 
                                            name="first_name" 
                                            id="reg_first_name" 
                                            autocomplete="first_name" 
                                            placeholder=""
                                            value="' . (isset($_POST['first_name']) ? $first_name : null) . '" />
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p class=""style="">
                                            <label for="account_last_name"><b>'.esc_html( 'Last name', 'woocommerce' ).' <span class="require-star">*</span></b></label><br>
                                            <input 
                                            type="text" 
                                            class="woocommerce-form-Register-Input" 
                                            name="last_name" 
                                            id="reg_last_name" 
                                            autocomplete="last_name" 
                                            placeholder=""
                                            value="' . (isset($_POST['last_name']) ? $last_name : null) . '" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <p class=""style="">
                                    <label for="phone_number"><b>'.esc_html( 'Phone Number', 'woocommerce' ).' <span class="require-star">*</span></b></label><br>
                                    <input 
                                    type="text" 
                                    class="woocommerce-form-Register-Input" 
                                    name="phone" 
                                    id="reg_phone" 
                                    autocomplete="phone" 
                                    placeholder="" 
                                    value="' . (isset($_POST['phone']) ? $phones : null) . '" />
                                </p>
                            </div>
                            <div class="col-12 mt-2">
                                <p class=""style="">
                                    <label for="account_email"><b>'.esc_html( 'Email address', 'woocommerce' ).' <span class="require-star">*</span></b></label><br>
                                    <input 
                                    type="text" 
                                    class="woocommerce-form-Register-Input" 
                                    name="email" 
                                    id="reg_email" 
                                    autocomplete="email" 
                                    placeholder="" 
                                    value="' . (isset($_POST['email']) ? $email : null) . '" />
                                </p>
                            </div>
                            <div class="col-12 mt-2">
                                <p class=""style="">
                                    <label for="password"><b>'.esc_html( 'Password', 'woocommerce' ).' <span class="require-star">*</span></b></label><br>
                                    <input 
                                        type="password" 
                                        class="woocommerce-form-Register-Input" 
                                        name="password" 
                                        id="reg_password" 
                                        autocomplete="password" 
                                        placeholder="" 
                                        value="' . (isset($_POST['password']) ? $password : null) . '" />
                                      
                                </p>
                            </div>
                            <div class="col-12 mt-2">
                                <p class="" style="">
                                    <label for="confirm_password"><b>'.esc_html( 'Confirm Password', 'woocommerce' ).' <span class="require-star">*</span></b></label><br>
                                    <input 
                                        type="password" 
                                        class="woocommerce-form-Register-Input" 
                                        name="confirm_password" 
                                        id="reg_confirm_password" 
                                        autocomplete="confirm_password" 
                                        placeholder="" 
                                        value="' . (isset($_POST['confirm_password']) ? $confirm_password : null) . '" />
                                       
                                </p> 
                            </div>
                            <div class="col-12 mt-2">
                                <p class="" style="">
                                    <label for="invite code"><b>'.esc_html( 'Invite Code', 'woocommerce' ).'</b></label><br>
                                    <input 
                                        type="text" 
                                        class="woocommerce-form-Register-Input" 
                                        name="invite_code" 
                                        id="invite_code" 
                                        autocomplete="invite_code" 
                                        placeholder="" 
                                        value="' . (isset($_POST['invite_code']) ? $invite_code : null) . '" />
        
                                </p> 
                            </div>
                        </div>  
                        <input class="mt-3" type="submit" name="submit" value="Create"/>
                    </form>
                </div>  
                <div class="wp-sign-in">
                    <p class="sign-up-wrap mt-4">
                        Already have an account?
                        <a href="'.site_url().'/my-account" id="go-sign-in"><span><b>'. esc_html( 'Sign In', 'woocommerce' ) .'</b></span></a>
                    </p>
                </div>
                  
            ';
        } 

        // complete data register
        public static function complete_register(){
        }

        // validate form register
        public static function registrations_validation( $first_name, $last_name, $password, $confirm_password, $email, $phones)  {
            global $reg_errors;
            $reg_errors = new WP_Error;
        
            if ( empty( $first_name ) || empty( $last_name ) || empty( $password ) || empty( $email ) ) {
                $reg_errors->add('field', 'Required form field is missing');
            }

            if ( strlen( $first_name ) < 1 || strlen( $last_name ) < 1 ) {
                $reg_errors->add('username_length', 'The first name or last name too short. At least 1 character is required');
            }
        
            // if ( username_exists( $username ) )
            //     $reg_errors->add('user_name', 'Sorry, that username already exists!');
        
            if ( !validate_username( $phones ) ) {
                $reg_errors->add('phone_invalid', 'Sorry, the phone number you entered is not valid');
            }

            if ( !validate_username( $first_name . $last_name ) ) {
                $reg_errors->add('username_invalid', 'Sorry, the first name or last name you entered is not valid');
            }
        
            if ( strlen( $password ) < 5 || strlen( $confirm_password ) < 5 ) {
                $reg_errors->add('password', 'Password length must be greater than 5');
            }
        
            if ( $password != $confirm_password ) {
                $reg_errors->add('password', 'Password does not match!');
            }
        
            if ( !is_email( $email ) ) {
                $reg_errors->add('email_invalid', 'Email is not valid');
            }
        
            if ( email_exists( $email ) ) {
                $reg_errors->add('email', 'Email Already in use');
            }
        
            if ( is_wp_error( $reg_errors ) ) {
                $error_message = '';
                foreach ( $reg_errors->get_error_messages() as $error ) {
        
                    $error_message .= '
                        <div>
                            <strong>Erorr:</strong> '.$error .'<br/>
                        </div>
                    ';
                }
        
                if ( count($reg_errors->get_error_messages()) > 0 ) {
                    echo '
                        <div class="woo woocommerce-error-form-validate"> 
                            <div class="woo woocommerce-error" style="border-top-color: #b81c23;">
                                '.$error_message.'
                            </div>
                        </div>
                    ';
                }
            }
        }

        // validate otp
        public static function validate_otp($otp){
            global $reg_errors;
            $reg_errors = new WP_Error;
            if ( empty( $otp ) ) {
                $reg_errors->add('Otp_Error', 'Sorry, Please Check Otp');
            }

            if ( is_wp_error( $reg_errors ) ) {
                $error_message = '';
                foreach ( $reg_errors->get_error_messages() as $error ) {
        
                    $error_message .= '
                        <div>
                            <strong>Erorr:</strong> '.$error .'<br/>
                        </div>
                    ';
                }
        
                if ( count($reg_errors->get_error_messages()) > 0 ) {
                    echo '
                        <div class="woo woocommerce-error-form-validate"> 
                            <div class="woo woocommerce-error" style="border-top-color: #b81c23;">
                                '.$error_message.'
                            </div>
                        </div>
                    ';
                }
            }
        }

        /*
        * Auto Login
        */
        public static function customers_auto_login( $user_id ) {
            
            $user = get_user_by('id',$user_id);
            $username = $user->user_nicename; // user_nicename | user_login
            $user_id = $user->ID;
            
            wp_set_current_user($user_id, $username);
            wp_set_auth_cookie($user_id);	
            do_action('wp_login', $username, $user);
        }
        // Wrong OTP number, please login this account for submit phone number and get OTP to verify again. <a class="text-danger" href="'.site_url().'/register_account">Click here to login.</a>

        public static function verify_otp_not_seccess(){
            echo '
                <div class="alert alert-warning text-center" role="alert">
                    Wrong OTP number, please verify OTP again Or create account for get OTP to verify again. <a class="text-danger" href="'.site_url().'/register_account">Click here to login.</a>
                </div>
            ';
        }

        //verify otp
        public static function verify_otp_form($user_id = "", $phone = ""){

            echo '
                <div class="container woo-otp">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="otp-card">
                                <div class="otp-title">
                                    <h2>Enter OTP</h2>
                                </div>
                                <div class="otp-sumrize">
                                    <p>A OTP ( One Time Passcode ) has been sent to <label> '.$phone.'</label></p>
                                </div>
                                <div class="otp-sumrize-1">
                                    <p>Please enter the OTP below to verify your phone number.</p>
                                </div>
                                <div class="otp-input woocommerce-error-form-validate-otp">
                                    <div class="otp-wrapper otp-event">
                                        <div class="otp-container">
                                            <input type="tel" id="otp-number-input-1" class="otp-number-input" maxlength="1" autocomplete="off">
                                            <input type="tel" id="otp-number-input-2" class="otp-number-input" maxlength="1" autocomplete="off">
                                            <input type="tel" id="otp-number-input-3" class="otp-number-input" maxlength="1" autocomplete="off">
                                            <input type="tel" id="otp-number-input-4" class="otp-number-input" maxlength="1" autocomplete="off">
                                            <input type="tel" id="otp-number-input-5" class="otp-number-input" maxlength="1" autocomplete="off">
                                            <input type="tel" id="otp-number-input-6" class="otp-number-input" maxlength="1" autocomplete="off">
                                        </div>
                                        <div class="otp-resend">
                                            <p>Resent OTP : ( <span id="timer"></span> )</p>
                                        </div>
                                    <div>
        
                                    <form action="' . $_SERVER['REQUEST_URI'] . '?otp=true" method="post" class="text-center woocommerce-form woocommerce-form-register register">
                                        <input type="hidden" name="otp_number" id="otp_number"/>
                                        <input type="hidden" name="phone" value="' . $phone . '"/>
                                        <input type="hidden" name="user_id" value="' . $user_id . '"/>
                                        <button id="confirm" name="otp_submit" type="submit" class="otp-submit" disabled>Validate OTP</button> 
                                    </form>
        
                                </div>
                            </div>
                        </div>
                    </div>       
                </div>
        
        
            ';
        }

        /**
         * Shortcode Main register
         *
         * @return $query
        */
        public static function woocommerce_register_account_shortcode()
        {
            ob_start();
            Woocommerce_Register_Accounts::custom_registrations_function();
            return ob_get_clean();

            //include( plugin_dir_path( __DIR__ ) . '../resources/views/archive-page.php' );
        }

        // create user to odoo
        //CURL Data
        public static function curl_data($url_odoo ,$post_field ,$token) {
            // biz_write_log($token,'token');
            // echo ',post_field=='.$post_field.' ,token=='.$token;
            // biz_write_log($token,'0000');
            // biz_write_log($url_odoo,'00001');
            // biz_write_log($post_field,'00002');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_odoo,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_field,
                CURLOPT_HTTPHEADER => array(
                    // 'Authorization: Bearer '.$token.'',
                    'Authorization: Bearer '.$token.'',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: session_id=82253d36d0574e188cc715036c91716973d2b40b'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode( $response, true);
            return $data;

        }

        public static function register_user_odoo($username,$phone,$email) {
            $url            = defined( 'ODOO_URL' ) ? ODOO_URL : '';
            $client_id      = defined( 'ODOO_CLIENT_ID' ) ? ODOO_CLIENT_ID : '';
            $client_secret  = defined( 'ODOO_CLIENT_SECRET' ) ? ODOO_CLIENT_SECRET : '';
            $db             = defined( 'ODOO_DB' ) ? ODOO_DB : '';

            //Access token from ODOO
            $post_field   = 'client_id='.$client_id.'&client_secret='.$client_secret.'&db='.$db;
            $token        = '';
            $token_url    = $url.'/client/api/oauth2/access_token';
            $access_token = Woocommerce_Register_Accounts::curl_data($token_url ,$post_field ,$token);
            $data_token   = $access_token['access_token'];

            //Create user to ODOO
            $url_odoo = $url.'/api/customer/create';
            $post_field = array(
                "params"=> array(
                    "name"  => "'.$username.'",
                    "phone" => "'.$phone.'",
                    "email" => "'.$email.'",
                    "db"    => "'.$db.'",
                ),
            );
            $create_user_odoo = Woocommerce_Register_Accounts::curl_data($url_odoo ,$post_field ,$data_token);
            
            $user = json_decode($create_user_odoo, true);

        }

        public static function getOdooID($username,$phone,$email){
            $url     = defined( 'ODOO_URL' ) ? ODOO_URL : '';
            $client_id     = defined( 'ODOO_CLIENT_ID' ) ? ODOO_CLIENT_ID : '';
            $client_secret     = defined( 'ODOO_CLIENT_SECRET' ) ? ODOO_CLIENT_SECRET : '';
            $db     = defined( 'ODOO_DB' ) ? ODOO_DB : '';
            $accessTokenUrl  = $url.'/client/api/oauth2/access_token';
            $postfields = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&db=' . $db;
            $response = Woocommerce_Register_Accounts::post( $accessTokenUrl, $postfields );
            $token = json_decode($response, true);
            $createUserUrl = $url."/api/customer/create";
            $postfields = '{
                "params": {
                    "name": 	"'.$username.'",
                    "phone": 	"'.$phone.'",
                    "email": 	"'.$email.'",
                    "db": 		"'.$db.'"
                }
            }';
            $response = Woocommerce_Register_Accounts::post( $createUserUrl, $postfields, $token['access_token'],'Content-Type: application/json');
            $user = json_decode($response, true);
            $id =  $user['result']['id'];
            return $id;
        }

        public static function odoo_id(){
            $username =''; $phone= '' ;$email = '';
            $id = Woocommerce_Register_Accounts::getOdooID($username,$phone,$email);
            // biz_write_log($id,'333333333');
        }

        public static function post($url, $postfields, $bearer_token = '', $content_type = "Content-Type: application/x-www-form-urlencoded")
        {
            return Woocommerce_Register_Accounts::curl( 'POST', $url, $postfields, $bearer_token, $content_type);
        }

        public function curl( $method, $url, $postfields, $bearer_token = '', $content_type = "Content-Type: application/x-www-form-urlencoded" )
        {
            $curl = curl_init();
    
            $http_header = array($content_type);
    
            if( !empty($bearer_token) )
            {
                $http_header = array(
                    "Authorization: Bearer " . $bearer_token,
                    $content_type
                );
            }
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => $http_header,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
    
            return $response;
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
                'page_title' 	=> '+Check Register Otp',     // Page Title
                'menu_title'	=> '+Check Register Otp',     // Menu Name
                'menu_slug' 	=> 'register-otp', // Slug, Syntax: page-name
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
         * ACF Field
         *
         * Note: Must be import field form ACF (Advance Custom Field) and past in this function.
         * @return void
         */
        public static function field_acf() {
            acf_add_local_field_group(array(
                'key' => 'group_6417d099370a8',
                'title' => 'Check Verify Otp',
                'fields' => array(
                    array(
                        "key" => "field_6417d0f7d3b48",
                        "label" => "Verify Otp",
                        "name"=>  "verify_otp",
                        "type"=>  "checkbox",
                        "instructions"=>  "",
                        "required"=>  0,
                        "conditional_logic"=> 0,
                        "wrapper"=> array(
                            "width"=> "",
                            "class"=> "",
                            "id"=>  ""
                        ),
                        "choices"=>  array(
                            "1"=> "Verify Otp"
                        ),
                        "allow_custom"=> 0,
                        "default_value"=> false,
                        "layout"=> "vertical",
                        "toggle"=> 0,
                        "return_format"=> "value",
                        "save_custom"=> 0
                    ),
                    array(
                        "key"=> "field_6417d1480feef",
                        "label"=> "Add User Odoo",
                        "name"=> "add_user_odoo",
                        "type"=> "checkbox",
                        "instructions"=> "",
                        "required"=> 0,
                        "conditional_logic"=> 0,
                        "wrapper"=> array(
                            "width"=> "",
                            "class"=> "",
                            "id"=> ""
                        ),
                        "choices"=> array(
                            "1"=> "Add User Odoo"
                        ),
                        "allow_custom"=> 0,
                        "default_value"=> false,
                        "layout"=> "vertical",
                        "toggle"=> 0,
                        "return_format"=> "value",
                        "save_custom"=> 0
                    )
                   
                ),
                "location"=> array(
                    array(
                        array(
                            "param"=> "options_page",
                            "operator"=> "==",
                            "value"=> "register-otp"
                        ),
                    ),
                ),
                "menu_order"=> 0,
                "position"=> "normal",
                "style"=> "default",
                "label_placement"=> "top",
                "instruction_placement"=> "label",
                "hide_on_screen"=> "",
                "active"=> true,
                "description"=> ""
            ));

        }
    }

    // instantiate the plugin class
    $news_and_event = new Woocommerce_Register_Accounts();
    $news_and_event::init();
    // finish register in website
}

class RegisterBaseController {
    /**
     * Check permissions for the posts.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function sendError( $code, $message, $statusCode ) {
        return new WP_Error( $code, $message, array( 'status' => $statusCode ) );
    }
}

class ReisterUserController extends RegisterBaseController {
    public function __construct() {
        global $revo_api_url;
        $this->namespace = $revo_api_url;
    }

    // @register route
    public function register_route() {

        register_rest_route( $this->namespace, '/registers', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'registers' )
            ),
        ));

        register_rest_route( $this->namespace, '/check-verify-otp', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'check_verify_otp' )
            ),
        ));
    }

    public function check_verify_otp(){
        $verify_otp = get_field('verify_otp','option');
        if(!empty($verify_otp)){
            $result[] = array(
                'code'=>200,
				'messege'=>'seccess',
                'verify_otp' => true,
            );
        }else{
            $result[] = array(
                'code'=>200,
				'messege'=>'seccess',
                'verify_otp' => false,
            );
        }
        echo json_encode($result);
    }

    function username_exists( $username ) {
        $user = get_user_by( 'login', $username );
        if ( $user ) {
            $user_id = $user->ID;
        } else {
            $user_id = false;
        }
    
        /**
         * Filters whether the given username exists.
         *
         * @since 4.9.0
         *
         * @param int|false $user_id  The user ID associated with the username,
         *                            or false if the username does not exist.
         * @param string    $username The username to check for existence.
         */
        return apply_filters( 'username_exists', $user_id, $username );
    }

    function email_exists( $email ) {
        $user = get_user_by( 'email', $email );
        if ( $user ) {
            $user_id = $user->ID;
        } else {
            $user_id = false;
        }
    
        /**
         * Filters whether the given email exists.
         *
         * @since 5.6.0
         *
         * @param int|false $user_id The user ID associated with the email,
         *                           or false if the email does not exist.
         * @param string    $email   The email to check for existence.
         */
        return apply_filters( 'email_exists', $user_id, $email );
    }

    // API for register in mobile
    public function registers() {
        $json           = file_get_contents('php://input');
        $params         = json_decode($json);
        $usernameReq    = $params->username;
        $emailReq       = $params->email;
        $first_name     = $params->first_name;
        $last_name      = $params->last_name;
        $password       = $params->user_pass;
        $secondsReq     = $params->seconds;
        $userLoginReq   = $params->user_login;
        $roleReq        = $params->role;
        $userEmailReq   = $params->user_email;
        $phone          = $params->phone_number;
        $invite_code    = $params->invite_code;
        $add_user = array(
            'user_login'   =>  sanitize_title( $first_name . ' ' . $last_name . '_' . uniqid() ),
            'nickname'     =>  $first_name . ' ' . $last_name,
            'display_name' =>  $first_name . ' ' . $last_name,
            'user_email'   =>  $emailReq,
            'user_pass'    =>  $password,
            'first_name'   =>  $first_name,
            'last_name'    =>  $last_name,
            'role'         => 'customer'
        );

        if ($roleReq && $roleReq != "subscriber" && $roleReq != "wcfm_vendor" && $roleReq != "seller") {
            return parent::sendError("invalid_role","Role is invalid.", 400);
        }
        $userPassReq  = $params->user_pass;
        $username = sanitize_user($usernameReq);
        $email    = sanitize_email($emailReq);

        if ($secondsReq) {
            $seconds = (int) $secondsReq;
        } else {
            $seconds = 120960000;
        }
        // $exist_email   = get_user_by( 'email', $email );
        // $exis_username = get_user_by( 'login', $username );
        if (!validate_username($username)) {
            return parent::sendError("invalid_username","Username is invalid.", 400);
        } elseif (username_exists($username)) {
            return parent::sendError("existed_username","Username already exists.", 400);
        } else {
            if (!is_email($email)) {
                return parent::sendError("invalid_email","E-mail address is invalid.", 400);
            } elseif (email_exists($email)) {
                return parent::sendError("existed_email","E-mail address is already in use.", 400);
            } else {
                if (!$userPassReq) {
                    $params->user_pass = wp_generate_password();
                }
                $allowed_params = array('user_login', 'user_email', 'user_pass', 'display_name', 'user_nicename', 'user_url', 'nickname', 'first_name',
                'last_name', 'description', 'rich_editing', 'user_registered', 'role', 'jabber', 'aim', 'yim',
                'comment_shortcuts', 'admin_color', 'use_ssl', 'show_admin_bar_front',
                );

                $dataRequest = $params;
                foreach ($dataRequest as $field => $value) {
                    if (in_array($field, $allowed_params)) {
                        $user[$field] = trim(sanitize_text_field($value));
                    }
                }
            
                $user['role'] = $roleReq ? sanitize_text_field($roleReq) : get_option('default_role');
                $user_id = wp_insert_user($add_user);

                // generet code for user code
                $user_code   = mt_rand(0000,9999);
                $text_substr = substr($usernameReq,0,3);
                $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';
                $username = $first_name.$last_name.'-'.$user_id;
                $add_user_odoo = get_field('add_user_odoo','option');
                if(!empty($add_user_odoo)){
                    Woocommerce_Register_Accounts::register_user_odoo($username,$phone,$emailReq);
                }
                $odoo_id = Woocommerce_Register_Accounts::getOdooID($username,$phone,$emailReq);
                add_user_meta( $user_id, 'odoo_id', $odoo_id, true );
                add_user_meta( $user_id, 'invite_code', $invite_code, true);
                add_user_meta( $user_id, 'user_code',  $invite_code_for_user, true );
                update_user_meta( $user_id, 'register_status', 1 );

                // call function create coupon to woo
                Woocommerce_Register_Accounts::coupon_code($user_id, $invite_code,$emailReq);
            }
        }

        $expiration = time() + apply_filters('auth_cookie_expiration', $seconds, $user_id, true);
        $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

        return array(
            "cookie" => $cookie,
            "user_id" => $user_id,
        );
    }
}

//get odoo token
function gets_odoo_token(){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => ODOO_URL.'/client/api/oauth2/access_token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'client_id='.ODOO_CLIENT_ID.'&client_secret='.ODOO_CLIENT_SECRET.'&db='.ODOO_DB.'',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: session_id=49c1d3cb3a4847f7c4bc009bd9465ecec6619dbe'
    ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $data = json_decode( $response ,true);
    return $data['access_token'];
}

//add coupon odoo
function add_coupons_odoo($post_id) {
    $coupon_name    = get_the_excerpt($post_id);
    $coupon_code    = get_the_title($post_id);
    $discount_type  = get_post_meta($post_id ,'discount_type' ,true); 
    $amount         = get_post_meta($post_id ,'coupon_amount' ,true);
    $usage_limit    = get_post_meta($post_id ,'usage_limit' ,true);
    $usage_limit_per_user =get_post_meta($post_id ,'usage_limit_per_user' ,true);
    $minimum_amount = get_post_meta($post_id ,'minimum_amount' ,true);
    $date = date_create(get_post_meta($post_id ,'date_expires' ,true));
   
    $date->modify("-1 day");
    $expire = $date->format('Y-m-d');
    $expire_compare = $date->format('Y-m-d');
    
    if($discount_type == 'percent') {
        $dis_type = 'percentage';
    }else if($discount_type == 'fixed_cart') {
        $dis_type = 'fixed_amount';
    }else {
        $dis_type = 'fixed_amount';
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => ODOO_URL.'/api/create/sale_coupon',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "params":{
                "name": "'.$coupon_name.'",
                "coupon_code": "'.$coupon_code.'",
                "discount_type": "'.$dis_type.'",
                "amount": '.$amount.',
                "usage_limit": '.$usage_limit.',
                "usage_limit_per_user": '.$usage_limit_per_user.',
                "date_expires": "'.$expire_compare.'",
                "minimum_amount":'.$minimum_amount.',
                "portal_id":'.$post_id.',
                "db":"'.ODOO_DB.'"
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.gets_odoo_token().'',
            'Content-Type: application/json',
            'Cookie: session_id=0538fccf8c498db693b1915f13854f8a5d7716e9'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}


//custom rest api
function user_routes() {
    $controller = new ReisterUserController();
    $controller->register_route();
} 
add_action( 'rest_api_init', 'user_routes' );
add_action( 'rest_api_init', 'my_register_route' );

// Set title Phum Electronic
function custom_html_title( $title ) {
    return array(
      'title' => 'My Account',
      'site'  => 'Phum Electronic'
    );
}
add_filter( 'document_title_parts', 'custom_html_title', 10 );