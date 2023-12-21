<?php
/*
  Plugin Name: Biz Custom Registration
  Plugin URI: http://bizsolution.com.kh
  Description: Frontend Custom form for registration
  Version: 1.0
  Author: TEP Afril
  Author URI: http://bizsolution.com.kh
 */

use BizSolution\BizPlasGate\Library\PlasGate;

function custom_registration_function()
{
    if( isset($_GET['otp_form']) ){
        if( isset( $_POST['otp_number']) && isset( $_POST['phone']) && isset( $_POST['user_id']) )
        {
            $phone = $_POST['phone'];
            $user_id = $_POST['user_id'];
            $otp_number = $_POST['otp_number'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => site_url()."/wp-json/biz-plasgate/api/v2/verify-otp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('phone' => $phone, 'otp_number' => $otp_number),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic cGg0bTYxNnRyMG4xYzomQjYoN21TemsjNmhRRXYyRSNia0FLcVEtZ2wlZXhpa2RGWGJRR2YlZXB1NnkkJERM',
                    'Cookie: PHPSESSID=1f5u22m4gtlvdkpu6s4r3j5vvv'
                ),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ));

            $response = curl_exec($curl);  
            curl_close($curl); 

            $response = json_decode($response, true);
            biz_write_log($response,"response".date("Y-m-d"));
            
            if($response["code"] == "success")
            {

                // get data user
                global $wpdb;
                $result =  $wpdb->get_results ("SELECT * FROM ph0m31e_user_temporary WHERE otp = '$otp_number'");
                if(count($result) > 0){

                    foreach($result as $value){
                        $first_name         = $value->first_name;
                        $last_name          = $value->last_name;
                        $phone_number       = $value->phone_number;
                        $email              = $value->email_address;
                        $password           = $value->password;
                        $confirm_password   = $value->confirm_password;
                        $invite_code        = $value->invite_code;
                    }

                    // Save User 
                    $user_code = mt_rand(0000,9999);
                    $userdata = array(
                        'user_login'   =>  sanitize_title( $first_name . ' ' . $last_name . '_' . uniqid() ),
                        'nickname'     =>  $first_name . ' ' . $last_name,
                        'display_name' =>  $first_name . ' ' . $last_name,
                        'user_email'   =>  $email,
                        'user_pass'    =>  $password,
                        'first_name'   =>  $first_name,
                        'last_name'    =>  $last_name,
                        'invite_code'  =>  $invite_code,
                        'user_code'    =>  $user_code,
                        'role'         => 'customer'
                    );

                    $user_id = wp_insert_user( $userdata );

                    // @formart text username
                    $text_substr = substr($email,0,3);
                    $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

                    update_user_meta( $user_id, 'first_name', $first_name);
                    update_user_meta( $user_id, 'last_name', $last_name);
                    update_user_meta( $user_id, 'email', $email);
                    update_user_meta( $user_id, 'phone_number', $phone_number);
                    update_user_meta( $user_id, 'invite_code', $invite_code);
                    update_user_meta( $user_id, 'user_code', $invite_code_for_user);


                    // Update status register to complete
                    update_user_meta( $user_id, 'register_status', 1 );

                    // Register user to odoo
                    do_action( 'biz_customer_successfully_registered', $user_id );
                
                
                    // Generate Coupon for new register and inviter 
                    // $invite_code = get_user_meta( $user_id, 'invite_code', true );
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
                            $user_invite_email = $user_invite_value->email;
                        }

                        if(!empty($user_invite_id)){

                            $customer = get_user_by('id', $user_id);
                            $email = $customer->user_email;
        
        
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
        
                            
                            //create coupon for user inviter
                            $coupon_user_invite = rand(0000,9999);
                            // @formart text username
                            $text_substr = substr($user_invite_email,0,3);
                            $coupon_code_user_invite = $text_substr.$user_invite_id.$coupon_user_invite.'Z';
                            $id_coupon_user_invite = wp_insert_post(array (
                                'post_title'  =>  $coupon_code_user_invite,
                                'post_type'   => 'shop_coupon',
                                'post_status' => 'publish',
                                'post_content'=> 'Promocode invite',
                                'discount_type' => $percentage,
                                'post_excerpt'  => $coupon_code_user_invite.'for_invite' //description
                            ));
                            if ( $id_coupon_user_invite ) {
                                update_post_meta($id_coupon_user_invite, 'usage_limit_per_user', '1');
                                update_post_meta($id_coupon_user_invite, 'minimum_amount', '1');
                                update_post_meta($id_coupon_user_invite, 'coupon_amount',$for_inviter);
                                update_post_meta($id_coupon_user_invite, 'discount_type', $percentage); 
                                update_post_meta($id_coupon_user_invite, 'usage_limit', '1');
                                update_post_meta($id_coupon_user_invite, 'Product IDs', '');
                                update_post_meta( $id_coupon_user_invite, 'date_expires', date('2900-01-01'));
        
                                //create coupon to odoo
                                add_coupon_odoo_after_register($id_coupon_user_invite);
                            }
                            //list coupon for user invite
                            $list_coupon_user_invite = wp_insert_post( array(
                                'post_status'  => 'publish',
                                'post_type'    => 'generate-coupon',
                                'post_title'   => 'Generate Coupon',
                                'post_content' => ' '
                                ) 
                            );
                            update_post_meta( $id_coupon_user_invite, 'inviter_id',$user_invite_id);
                            if(!empty($list_coupon_user_invite)) {
                                update_post_meta( $list_coupon_user_invite, 'usage_limit_per_user', '1');
                                update_post_meta( $list_coupon_user_invite, 'user_name', $user_invite_email);  
                                update_post_meta( $list_coupon_user_invite, 'user_id', $user_invite_id );
                                update_post_meta( $list_coupon_user_invite, 'coupon_code', $coupon_code_user_invite);
                                update_post_meta( $list_coupon_user_invite, 'coupon_amount', $for_inviter );
                                update_post_meta( $list_coupon_user_invite, 'discount_type', $percentage );
                                update_post_meta( $list_coupon_user_invite, 'usage_limit', '1' );
                            }
                            //end generate coupon for inviter
        
        
                            //create coupon for new register
                            $coupon_user_owner = rand(0000,9999);
                            $text_substr = substr($email,0,3);
                            $coupon_code_user_owner = $text_substr.$user_id.$coupon_user_owner.'Z';
        
                            $id_coupon_user_owner = wp_insert_post(array (
                                'post_title'  =>  $coupon_code_user_owner,
                                'post_type'   => 'shop_coupon',
                                'post_status' => 'publish',
                                'post_content'=> 'Promocode invite',
                                'discount_type' => $percentage,
                                'post_excerpt' => $coupon_code_user_owner.'For_invitee', //description
                            ));
        
                            if ( $id_coupon_user_owner ) {
                                update_post_meta($id_coupon_user_owner, 'usage_limit_per_user', '1');
                                update_post_meta($id_coupon_user_owner, 'minimum_amount', '1');
                                update_post_meta($id_coupon_user_owner, 'coupon_amount', $for_invitee);
                                update_post_meta($id_coupon_user_owner, 'discount_type', $percentage); 
                                update_post_meta($id_coupon_user_owner, 'usage_limit', '1');
                                update_post_meta($id_coupon_user_owner, 'Product IDs', '');
                                update_post_meta($id_coupon_user_owner, 'date_expires', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))));
                                // update_post_meta($coupon, 'expiry_date', '1650387600');
        
                                add_coupon_odoo_after_register($id_coupon_user_owner);
                            }
        
                            //list coupon for user owner
                            $list_coupon_owner = wp_insert_post( array(
                                'post_status'  => 'publish',
                                'post_type'    => 'generate-coupon',
                                'post_title'   => 'Generate Coupon',
                                'post_content' => ' '
                            ) 
                            );
                            update_post_meta($id_coupon_user_owner, 'inviter_id',$user_id);
                            if(!empty($list_coupon_owner)) {
                                update_post_meta( $list_coupon_owner, 'user_name', $email);  
                                update_post_meta( $list_coupon_owner, 'user_id', $user_id );
                                update_post_meta( $list_coupon_owner, 'coupon_code', $coupon_code_user_owner);
                                update_post_meta( $list_coupon_owner, 'coupon_amount',$for_invitee);
                                update_post_meta( $list_coupon_owner, 'discount_type',$percentage );
                                update_post_meta( $list_coupon_owner, 'usage_limit', '1' );
                            }

                            // End Generate Coupon for new register and inviter
                            
                        }            
                    }
                }

                // delete data user when create to back end success
                if(!empty($otp_number)){
                    $wpdb->delete('ph0m31e_user_temporary' , array( 'otp' => $otp_number ) );
                }


                // Auto Login
				customer_auto_login( $user_id );
				wp_redirect( site_url().'/my-account/edit-account/', 301 );

                $user = new WP_User( $user_id );
                $user->remove_role( 'subscriber' );
                $user->set_role('customer');

                // Set Avatar to cutomer
                do_action( 'save_customer_avatar_', $user_id );
            }
            else {
                echo '
                    <div class="alert alert-warning text-center" role="alert">
                        Wrong OTP number, please login this account for submit phone number and get OTP to verify again. <a class="text-danger" href="'.site_url().'/my-account">Click here to login.</a>
                    </div>
                ';
            }
        }

        return true;
    }

    $user_id = 0;
    global $first_name, $last_name, $password, $confirm_password, $email, $phone, $invite_code;

    if (
        isset($_POST['submit']) &&
        isset($_POST['first_name']) &&
        isset($_POST['last_name']) &&
        isset($_POST['password']) &&
        isset($_POST['confirm_password']) &&
        isset($_POST['email']) &&
        isset($_POST['phone'])&&
        isset($_POST['invite_code'])
    ) { 
        registration_validation(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['password'],
            $_POST['confirm_password'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['invite_code'],
        );
        
        // sanitize user form input
        $first_name         =   sanitize_user($_POST['first_name']);
        $last_name          =   sanitize_user($_POST['last_name']);
        $password           =   esc_attr($_POST['password']);
        $confirm_password   =   esc_attr($_POST['confirm_password']);
        $email              =   sanitize_email($_POST['email']);
        $phone              =   esc_attr($_POST['phone']);
        $invite_code        =   esc_attr($_POST['invite_code']);
        $user_code          =   esc_attr($_POST['user_code']);


        // call @function complete_registration to create the user
        // only when no WP_error is found
        $user_id = complete_registration(
            $first_name,
            $last_name,
            $password,
            $confirm_password,
            $email,
            $phone,
            $invite_code,
        );
    }

    /**
     * This condition will be function when customer verify OTP sencode time.
     */
    if( isset( $_POST['generate_otp'] ) && isset( $_POST['otp_id'] ) && isset( $_POST['otp_phone'] ) )
    {
        $phone = $_POST['otp_phone'];
        $user_id_decrypt = $_POST['otp_id'];

        $user_id = decrypt( $user_id_decrypt );
    } 

    // Validate form
    if ( $user_id > 0 ) {
        otp_form($user_id, $phone);
    }      
    else{
        registration_form(
            $first_name,
            $last_name,
            $password,
            $confirm_password,
            $email,
            $phone,
            $invite_code,
        );
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
 * @param [string] $invite_code
 * @return void
 */
function registration_form( $first_name, $last_name, $password, $confirm_password, $email, $phone , $invite_code) { 
    
    echo '
        <form action="' . $_SERVER['REQUEST_URI'] . '#sign_up" method="post" class="woocommerce-form woocommerce-form-register register">
            <h2 class="text-center" id="title-register">'.esc_html( 'Register', 'woocommerce' ).'</h2>

            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name"><b>'. esc_html( 'First name', 'woocommerce').'</b></label>
                <input 
                type="text" 
                class="woocommerce-Input woocommerce-Input--text input-text" 
                name="first_name" 
                id="reg_first_name" 
                autocomplete="first_name" 
                placeholder=""
                value="' . (isset($_POST['first_name']) ? $first_name : null) . '" />
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name"><b>'.esc_html( 'Last name', 'woocommerce' ).'</b></label>
                <input 
                type="text" 
                class="woocommerce-Input woocommerce-Input--text input-text" 
                name="last_name" 
                id="reg_last_name" 
                autocomplete="last_name" 
                placeholder=""
                value="' . (isset($_POST['last_name']) ? $last_name : null) . '" />
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="phone_number"><b>'.esc_html( 'Phone Number', 'woocommerce' ).'</b></label>
                <input 
                type="text" 
                class="woocommerce-Input woocommerce-Input--text input-text" 
                name="phone" 
                id="reg_phone" 
                autocomplete="phone" 
                placeholder="" 
                value="' . (isset($_POST['phone']) ? $phone : null) . '" />
            </p>
            
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_email"><b>'.esc_html( 'Email address', 'woocommerce' ).'</b></label>
                <input 
                type="text" 
                class="woocommerce-Input woocommerce-Input--text input-text" 
                name="email" 
                id="reg_email" 
                autocomplete="email" 
                placeholder="" 
                value="' . (isset($_POST['email']) ? $email : null) . '" />
            </p>
            

            
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide password-wrapper">
                <label for="phone_number"><b>'.esc_html( 'Password', 'woocommerce' ).'</b></label>
                <input 
                    type="password" 
                    class="woocommerce-Input woocommerce-Input--text input-text" 
                    name="password" 
                    id="reg_password" 
                    autocomplete="password" 
                    placeholder="" 
                    value="' . (isset($_POST['password']) ? $password : null) . '" />
                    <span toggle="#confirm-password-field" class="fa fa-fw fa-eye field_icon hide-show-password"></span>
            </p>
            
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide password-wrapper">
                <label for="phone_number"><b>'.esc_html( 'Confirm Password', 'woocommerce' ).'</b></label>
                <input 
                    type="password" 
                    class="woocommerce-Input woocommerce-Input--text input-text" 
                    name="confirm_password" 
                    id="reg_confirm_password" 
                    autocomplete="confirm_password" 
                    placeholder="" 
                    value="' . (isset($_POST['confirm_password']) ? $confirm_password : null) . '" />
                    <span toggle="#confirm-password-field" class="fa fa-fw fa-eye field_icon hide-show-password"></span>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide password-wrapper">
                <label for="phone_number"><b>'.esc_html( 'invite code', 'woocommerce' ).'</b></label>
                <input 
                    type="text" 
                    class="woocommerce-Input woocommerce-Input--text input-text" 
                    name="invite_code" 
                    id="reg_invite_code" 
                    autocomplete="invite_code" 
                    placeholder="" 
                    value="' . (isset($_POST['invite_code']) ? $invite_code : null) . '" />
                    <span toggle="#confirm-password-field" class="fa fa-fw fa-eye field_icon hide-show-password"></span>
            </p>
            
            
            <input type="submit" name="submit" value="Create"/>
        </form>

        <p class="sign-up-wrap mt-4">
            Already have an account?
            <a href="'.site_url().'/my-account" id="go-sign-in"><span><b>'. esc_html( 'Sign In', 'woocommerce' ) .'</b></span></a>
        </p>
    ';
} 

function otp_form($user_id, $phone){

    echo '
        <div class="container">
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
                        <div class="otp-input">
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

                            <form action="' . $_SERVER['REQUEST_URI'] . '&otp_form=true#sign_up" method="post" class="text-center woocommerce-form woocommerce-form-register register">
                                <input type="hidden" name="otp_number" id="otp_number"/>
                                <input type="hidden" name="phone" value="' . $phone . '"/>
                                <input type="hidden" name="user_id" value="' . $user_id . '"/>
                                <button id="confirm" type="submit" class="otp-submit" disabled>Validate OTP</button> 
                            </form>

                        </div>
                    </div>
                </div>
            </div>       
        </div>


        ';

}

function registration_validation( $first_name, $last_name, $password, $confirm_password, $email, $phone)  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $first_name ) || empty( $last_name ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( !validate_username( $phone ) ) {
        $reg_errors->add('phone_invalid', 'Sorry, the phone number you entered is not valid');
    }
    
    if ( strlen( $first_name ) < 1 || strlen( $last_name ) < 1 ) {
        $reg_errors->add('username_length', 'The first name or last name too short. At least 1 character is required');
    }

    // if ( username_exists( $username ) )
    //     $reg_errors->add('user_name', 'Sorry, that username already exists!');

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
                <div class="woocommerce-error">
                    '.$error_message.'
                </div>
            ';
        }
    }

}

function complete_registration() {       
    global $reg_errors, $first_name, $last_name, $password, $confirm_password, $email, $phone , $invite_code , $woocommerce , $user;
    global $wpdb;
    if ( count($reg_errors->get_error_messages()) < 1 ) { 
          
        if(isset($_POST['submit'])){

            // @register cuopun
            // Send OTP
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => site_url()."/wp-json/biz-plasgate/api/v2/send-otp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('phone' => $phone),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic cGg0bTYxNnRyMG4xYzomQjYoN21TemsjNmhRRXYyRSNia0FLcVEtZ2wlZXhpa2RGWGJRR2YlZXB1NnkkJERM',
                    'Cookie: PHPSESSID=1f5u22m4gtlvdkpu6s4r3j5vvv'
                ),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ));

            $response = curl_exec($curl);
            biz_write_log($response,"response".date("Y-m-d"));
            $response = json_decode($response, true);
            $otp = $response["data"]['otp'];
            curl_close($curl);

            $email             = $_POST['email'];
            $first_name        = $_POST['first_name'];
            $last_name         = $_POST['last_name'];
            $phone             = $_POST['phone'];
            $password          = $_POST['password'];
            $confirm_password  = $_POST['confirm_password'];
            $invite_code       = $_POST['invite_code'];


            $user_id = 	$wpdb->insert('ph0m31e_user_temporary',                  
                [
                    'first_name'        => $first_name,
                    'last_name'         => $last_name,
                    'phone_number'      => $phone,
                    'email_address'     => $email,
                    'password'          => $password,
                    'confirm_password'  => $confirm_password,
                    'invite_code'       => $invite_code,
                    'otp'               => $otp,
                ]);

            // do_action( 'save_customer_avatar_', $user_id );
        }
        return true; 
    }
    return false;





    // global $reg_errors, $first_name, $last_name, $password, $confirm_password, $email, $phone , $invite_code , $woocommerce , $user;

    // if ( count($reg_errors->get_error_messages()) < 1 ) { 
          
    //     // Save User 
    //     $user_code = mt_rand(0000,9999);
    //     $userdata = array(
    //         'user_login'   =>  sanitize_title( $first_name . ' ' . $last_name . '_' . uniqid() ),
    //         'nickname'     =>  $first_name . ' ' . $last_name,
    //         'display_name' =>  $first_name . ' ' . $last_name,
    //         'user_email'   =>  $email,
    //         'user_pass'    =>  $password,
    //         'first_name'   =>  $first_name,
    //         'last_name'    =>  $last_name,
    //         'invite_code'  =>  $invite_code,
    //         'user_code'    =>  $user_code,
    //         'role'         => 'customer'
    //     );

    //     $user_id = wp_insert_user( $userdata );

    //     // @formart text username
    //     $text_substr = substr($email,0,3);
    //     $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

    //     update_user_meta( $user_id, 'first_name', $first_name);
    //     update_user_meta( $user_id, 'last_name', $last_name);
    //     update_user_meta( $user_id, 'email', $email);
    //     update_user_meta( $user_id, 'phone_number', $phone);
    //     update_user_meta( $user_id, 'invite_code', $invite_code);
    //     update_user_meta( $user_id, 'user_code', $invite_code_for_user);
    //     // Set Avatar to cutomer
    //     do_action( 'save_customer_avatar_', $user_id );

    //     // End Save User


    //     // // @send promo code to user by email
    //     // // $receiver_email = $email;
    //     // // $content = "<ul>";
    //     // // $content .= "<li>: Your code is : ".$user_code."</li>";
    //     // // $content .= "<li>: Please apply this code on PHUM ELECTRONIC website or mobile to get promotion</li>";
    //     // // $content .= "</ul>";
    //     // // $headers = ['Content-Type: text/html; charset=UTF-8'];
    //     // // wp_mail($receiver_email, "PHUM ELECTRONIC", $content,$headers);


    //     // @register cuopun
    //     // Send OTP
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => site_url()."/wp-json/biz-plasgate/api/v2/send-otp",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => array('phone' => $phone),
    //         CURLOPT_HTTPHEADER => array(
    //             'Authorization: Basic cGg0bTYxNnRyMG4xYzomQjYoN21TemsjNmhRRXYyRSNia0FLcVEtZ2wlZXhpa2RGWGJRR2YlZXB1NnkkJERM',
    //             'Cookie: PHPSESSID=1f5u22m4gtlvdkpu6s4r3j5vvv'
    //         ),
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false,
    //     ));

    //     $response = curl_exec($curl);
    //     curl_close($curl);

    //     return $user_id;
    // }
    // return false;

}

//get odoo token
function get_odoo_token(){
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
    //CURLOPT_POSTFIELDS => 'client_id=DlusVsqydiYa1HkfBZ4MSQMYxPLaEOPHBIVLZybOnizVXLnZ2kzKQxjzSyPK1f6lVea0FniBxCvda9pEGVr3k347lYAjcETGUrPsDAjvOiGa6LiNQT4xB5RLUHpkLIAM&client_secret=bhkLpwM7TQhrkz6LKIuV8DEEIs9d4AI123DxasZqHYe3kSAHjqOG2jQ62GMzKDq1s0wzHCOQ2FFPrBhV1DqFFf0e0CuUScLnxtiaMdoPTiSwMz6ggWSRR3xXor3AVLQH&db=ljOnboA6uvynfLd3cNLt4FRbVV8BMxE052E0Kc4ua0DtkaksdJ4wIAUsQyRSdpO2qfqq36avtcfkpiBswqvPKMN08rr3mXQfda641jBybdPgOS0DiwKX0Ep2tQlBLOQyizIsUGleBE41iH',
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
function add_coupon_odoo_after_register($post_id){

    $coupon_name    = get_the_excerpt($post_id);
    $coupon_code    = get_the_title($post_id);
    $discount_type  = get_post_meta($post_id ,'discount_type' ,true); 
    $amount         = get_post_meta($post_id ,'coupon_amount' ,true);
    $usage_limit    = get_post_meta($post_id ,'usage_limit' ,true);
    $usage_limit_per_user =get_post_meta($post_id ,'usage_limit_per_user' ,true);
    // $free_shipping  = get_post_meta($post_id ,'free_shipping' ,true);
    $minimum_amount = get_post_meta($post_id ,'minimum_amount' ,true);
    $date = date_create(get_post_meta($post_id ,'date_expires' ,true));
    
    $date->modify("-1 day");
    $expire = $date->format('Y-m-d');
    $expire_compare = $date->format('Y-m-d');


    // biz_write_log($coupon_name .'-'.$coupon_code.'-'.$discount_type.'-'.$amount.'-'.$free_shipping.'-'.$minimum_amount.'-'.$date,'Coupon to odoo 31-10-2022');
    // exit;
    
    if($discount_type == 'percent') {
        $dis_type = 'percentage';
    }
    else if($discount_type == 'fixed_cart') {
        $dis_type = 'fixed_amount';
    }
    else {
        $dis_type = 'fixed_amount';
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => ODOO_URL.'/api/create/sale_coupon',//ODOO_URL = http://124.248.186.204:8071
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
            'Authorization: Bearer '.get_odoo_token().'',
            'Content-Type: application/json',
            'Cookie: session_id=0538fccf8c498db693b1915f13854f8a5d7716e9'
        ),
    ));
    
    $response = curl_exec($curl);
    //biz_write_log($response ,'Coupon to odoo 31-10-2022');
    curl_close($curl);
}

// add_action( 'phpmailer_init', 'send_smtp_email' );
// function send_smtp_email( $phpmailer ) {

//     /* SMTP Setting */
//     define( 'SMTP_HOST', 'smtp.gmail.com' );
//     define( 'SMTP_AUTH', true );
//     define( 'SMTP_PORT', '465' );
//     define( 'SMTP_SECURE', 'ssl' );
//     define( 'SMTP_USERNAME', 'job-no-reply@footprintsschool.edu.kh' );  // Username for SMTP authentication
//     define( 'SMTP_PASSWORD', '28O9d%Ekg9Z^' );          // Password for SMTP authentication
//     define( 'SMTP_FROM',     'job-no-reply@footprintsschool.edu.kh' );  // SMTP From address
//     define( 'SMTP_FROMNAME', 'No-Reply FIS Career' );
//     define( 'RECEIVER_EMAIL', 'job-no-reply@footprintsschool.edu.kh' );

//     $phpmailer->isSMTP();
//     $phpmailer->Host       = SMTP_HOST;
//     $phpmailer->SMTPAuth   = SMTP_AUTH;
//     $phpmailer->Port       = SMTP_PORT;
//     $phpmailer->SMTPSecure = SMTP_SECURE;
//     $phpmailer->Username   = SMTP_USERNAME;
//     $phpmailer->Password   = SMTP_PASSWORD;
//     $phpmailer->From       = SMTP_FROM;
//     $phpmailer->FromName   = SMTP_FROMNAME;
// }

// Register a new shortcode: [biz_custom_registration]
add_shortcode('biz_custom_registration', 'custom_registration_shortcode');
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}



if ( ! function_exists( 'biz_custom_registration_admin_enqueue_scripts' ) ):
    /**
     * Adds the version of a package to the $jetpack_packages global array so that
     * the autoloader is able to find it.
     */
    function biz_custom_registration_admin_enqueue_scripts($hook)
    {
        wp_register_style( 'biz-custom-registration-css', plugins_url('biz-custom-registration/assets/css/styles.css'), false, '1.0.1' );
        wp_enqueue_style( 'biz-custom-registration-css' );

        wp_register_script( 'biz-custom-registration-script', plugins_url('biz-custom-registration/assets/js/app.js'), array('jquery'), '1.0.1' );
        wp_enqueue_script( 'biz-custom-registration-script' );

        wp_register_script( "biz-custom-registration-verify-otp-script", plugins_url('biz-custom-registration/assets/js/verify-otp.js'), array('jquery') );
        wp_enqueue_script(  "biz-custom-registration-verify-otp-script" );
        wp_localize_script( "biz-custom-registration-verify-otp-script", 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
    }
    add_action( 'wp_enqueue_scripts', 'biz_custom_registration_admin_enqueue_scripts' );
endif;


add_action("wp_ajax_verify_otp_action", "verify_otp_action");
add_action("wp_ajax_nopriv_auth_verify_otp", "auth_verify_otp");
function verify_otp_action()
{
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "biz_verify_otp_nonce")) {
        exit("You are not authenticated.");
    }
    $stage = $_POST['stage'];


    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    }
    else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}


function auth_verify_otp() {
    echo "You must log in to vote";
    die();
}


/*
 * WP Insert Avatar
 */
function save_customer_avatar( $user_id ) {
    global $wpdb;

    $uploads_dir = wp_get_upload_dir();
    $avatar_uri  = 'nsl_avatars/avatar-default.png';
    $avatar_url  = $uploads_dir['baseurl'] .'/nsl_avatars/avatar-default.png';

    $avatar_data = array(
        'post_author'       => $user_id,
        'post_date'         => date('Y-m-d h:i:s'),
        'post_date_gmt'     => date('Y-m-d h:i:s'),
        'post_status'       => 'private',
        'post_name'         => $user_id,
        'post_parent'       => 0,
        'guid'              => $avatar_url,
        'post_type'         => 'attachment',
        'post_mime_type'    => 'image/png',
    );

    $avatar_id  = get_user_meta( $user_id, 'ph0m31e_user_avatar', true );

    $post_id = wp_insert_post( $avatar_data );

    // Add post meta
    add_post_meta( $post_id, '_wp_attached_file', $avatar_uri );

    // Addd user meta
    add_user_meta( $user_id, 'ph0m31e_user_avatar', $post_id );

    $upload_uri = wp_get_upload_dir();
    $wpdb->update($wpdb->posts, ['guid' => $avatar_url], ['ID' => $avatar_id]);
}
add_action( 'save_customer_avatar_', 'save_customer_avatar' );


/*
 * Auto Login
 */
function customer_auto_login( $user_id ) {
	
	$user = get_user_by('id',$user_id);
    $username = $user->user_nicename; // user_nicename | user_login
    $user_id = $user->ID;
	
    wp_set_current_user($user_id, $username);
    wp_set_auth_cookie($user_id);	
    do_action('wp_login', $username, $user);
}


/**
 * Check customer authenticate before login
 *
 * @param [string] $username
 * @param [string] $password
 * @return void
 */
function check_authenticate_before_login($username, $password ) {

	if (!empty($username) && !empty($password))
    {
		$user_obj = get_user_by_email($username);
		if(!empty($user_obj->user_login)) {
			$identity = $user_obj->user_login;
		}
		else {
			$identity = $username;
		}
		
		$user = get_user_by('login', $identity );
		if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
			
			$user_id = $user->ID;
			$register_status = get_user_meta( $user_id, 'register_status', true );
			
			if( $register_status == 0 )
			{
				$user_id_encrypt = encrypt( $user_id );
				header('location: '.site_url().'/my-account/edit-account/?action=register&otp='.$user_id_encrypt.'#sign_up');
				die();
			}
		}
	}
}
add_action('wp_authenticate', 'check_authenticate_before_login', 30, 2);

/**
 * Resend OTP when custom not yet complated register
 *
 * @return void
 */
function resend_otp() {
    if( isset($_POST['generate_otp']) && isset( $_POST['otp_phone'] ) && isset( $_POST['otp_id'] ) )
    {
        $phone = $_POST['otp_phone'];

        // Send OTP
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => site_url()."/wp-json/biz-plasgate/api/v2/send-otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('phone' => $phone),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic cGg0bTYxNnRyMG4xYzomQjYoN21TemsjNmhRRXYyRSNia0FLcVEtZ2wlZXhpa2RGWGJRR2YlZXB1NnkkJERM',
                'Cookie: PHPSESSID=1f5u22m4gtlvdkpu6s4r3j5vvv'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        biz_write_log($response, 'send_otp');
        curl_close($curl);  


        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://restapi.plasgate.com/v1/send',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_SSL_VERIFYPEER => false,
        //     CURLOPT_POST => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>'[{
        //         "number":"85510527675",
        //         "senderID":"SMS Info",
        //         "text":"OTP Phum Electronic",
        //         "type":"sms",
        //         "beginDate":"'.date('Y-m-d').'",
        //         "beginTime":"'.date('H:i').'",
        //         "lifetime":555,
        //         "delivery":false
        //     }]',
        //     CURLOPT_HTTPHEADER => array(
        //         'X-Access-Token: 85d4605dddec0ff73e99cc1d29e436ed',
        //         'Content-Type: application/json',
        //     )
        // ));

        // $response = curl_exec($curl);
        // biz_write_log( $phone.'|'.json_encode($response), 'OTP Send' );
        // curl_close($curl);
        //echo $response;
    }
}
add_action( 'init', 'resend_otp' );

/**
 * Encrypt String
 *
 * @param [int, string, etc...] $string_to_encrypt
 * @return $string_encrypt
 */
function encrypt( $string_to_encrypt ) {
    $key = key_encrypt_and_descrypt();
    $string_encrypt = openssl_encrypt($string_to_encrypt, "AES-128-ECB", $key);

    return $string_encrypt;
}

/**
 * Decrypt String
 *
 * @param [int, string, etc...] $string_to_decrypt
 * @return void
 */
function decrypt( $string_to_decrypt ) {
    $key = key_encrypt_and_descrypt();
    $string_decrypt = openssl_decrypt($string_to_decrypt, "AES-128-ECB", $key);

    return $string_decrypt;
}

/**
 * Key Encrypt and Decrypt
 *
 * @return $key
 */
function key_encrypt_and_descrypt() {
    $key="&B6(7mSzk#6hQEv2E#bkAKqQ-gl%exikdFXbQGf%epu6yDL";
    return $key;
}


// add_action( 'wp_ajax_set_sms_opt_expired', 'set_sms_opt_expired' );
// add_action( 'wp_ajax_nopriv_set_sms_opt_expired', 'set_sms_opt_expired' );
// function set_sms_opt_expired(){

//     global $wpdb;

//     $phone = esc_sql($_POST['phone']);
//     $phone = '855'.substr($phone, 1);

//     wp_send_json_success($phone);

    // if(isset($_POST['resend-otp'])) {
    //     echo 1;
    // }

    // $execute = $wpdb->query( 'UPDATE '.$wpdb->prefix.'biz_plasgate_phonenumber SET status = 1 WHERE phone = '.$phone.' ORDER BY id DESC LIMIT 1' );
    // $data = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'biz_plasgate_phonenumber where phone = 85561929595' );

    // if( !$execute ) {
    //     wp_send_json_success(
    //         array(
    //             'code' => 500,
    //             'message' => 'Internal Server Error',
    //             'data' => json_encode($data)
    //         )
    //     );
    //     exit;
    // }

    // wp_send_json_success(
    //     array(
    //         'code' => 200,
    //         'message' => 'Updated',
    //         'data' => array(
    //             'otp' => json_encode($data),
    //             'phone'
    //         )
    //     )
    // );
// }

?>