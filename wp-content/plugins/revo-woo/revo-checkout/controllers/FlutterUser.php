<?php
require_once( __DIR__ . '/FlutterBase.php');

class FlutterUserController extends FlutterBaseController {

    public function __construct() {
        global $revo_api_url;
        $this->namespace = $revo_api_url;
    }
 
    public function register_routes() {
        register_rest_route( $this->namespace, '/register', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'register' )
            ),
        ));

        register_rest_route( $this->namespace, '/generate_auth_cookie', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'generate_auth_cookie' )
            ),
        ));

        register_rest_route( $this->namespace, '/fb_connect', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'fb_connect' )
            ),
        ));

        register_rest_route( $this->namespace, '/sms_login', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'sms_login' )
            ),
        ));

        register_rest_route( $this->namespace, '/firebase_sms_login', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'firebase_sms_login' )
            ),
        ));

        register_rest_route( $this->namespace, '/apple_login', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'apple_login' )
            ),
        ));

        register_rest_route( $this->namespace, '/google_login', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'google_login' )
            ),
        ));

        register_rest_route( $this->namespace, '/post_comment', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'post_comment' )
            ),
        ));

        register_rest_route( $this->namespace, '/get_currentuserinfo', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'get_currentuserinfo' )
            ),
        ));

        register_rest_route( $this->namespace, '/update_user_profile', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'update_user_profile' )
            ),
        ));

        register_rest_route( $this->namespace, '/send-email-forgot-password', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'send_email_forgot_password' )
            ),
        ));

        // @mobile force login
        register_rest_route( $this->namespace, '/force_login', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'force_login' )
            ),
        ));
    }

    //Mobile Force Login
    public function force_login() {
        if(!empty($_GET['oauth_consumer_key'] && !empty($_GET['oauth_consumer_secret']))) {
            if($_GET['oauth_consumer_key'] == CONSUMER_KEY && $_GET['oauth_consumer_secret'] == CONSUMER_SECRET) {

                $user_id     = cek_raw('user_id');
                if(!empty($user_id)) { 
                    
                    $json   = file_get_contents('php://input');
                    $params = json_decode($json);
                    if(!isset($params->user_id)){
                        return parent::sendError("invalid_login","Invalid params", 400);
                    }
                    $user_id = $params->user_id;

                    if ($params->seconds) {
                        $seconds = (int) $params->seconds;
                    } else {
                        $seconds = 1209600;
                    }

                    $user = get_user_by('id', $user_id);
                    if (empty($user)) {
                        return array(
                                "code" => "incorrect_user",
                                "message" => "Invalid user.",
                                "data" => array(
                                    "status" => 404
                                )
                            );
                    }

                    $expiration = time() + apply_filters('auth_cookie_expiration', $seconds, $user->ID, true);
                    $cookie = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');
                    preg_match('|src="(.+?)"|', get_avatar($user->ID, 512), $avatar);

                    // get coupon
                    $user_id = $user->ID;
                    $email = $user->user_email;
                    $user_code = get_user_meta($user_id, 'user_code', true);

                    if(!$user_code){
                        $coupon_user_invite = rand(0000,9999);
                        // @formart text username
                        $text_substr = substr($email,0,3);
                        $user_code = $text_substr.$user_id.$coupon_user_invite.'Z';
                        update_user_meta( $user_id, 'user_code',$user_code);
                    }

                    $phone_number    = $user->phone_number;
                    $register_status = $user->register_status;
                    if($register_status == 1) {
                        $send_opt_or_not = true;
                    } else{
                        $send_opt_or_not = false;
                    }

                    return array(
                        "cookie" => $cookie,
                        "cookie_name"     => LOGGED_IN_COOKIE,
                        "session"         => LOGGED_IN_COOKIE.'='.$cookie,
                        "user"            => array(
                            "id"          => $user->ID,
                            "username"    => $user->user_login,
                            "nicename"    => $user->user_nicename,
                            "email"       => $user->user_email,
                            "url"         => $user->user_url,
                            "registered"  => $user->user_registered,
                            "displayname" => $user->display_name,
                            "firstname"   => $user->user_firstname,
                            "lastname"    => $user->last_name,
                            "nickname"    => $user->nickname,
                            "description" => $user->user_description,
                            "capabilities"=> $user->wp_capabilities,
                            "role"        => $user->roles,
                            "avatar"      => $avatar[1] != null ? $avatar[1] : '',
                            "user_code"   => $user_code,
                            "phone"       => $phone_number,
                            "status"      => $send_opt_or_not
                        ),
                    );  
                }
            }
            else {
                $result = array(
                    'code' => 401,
                    'status' => 'Unauthorized'
                );
            } 
        }
        else {
            $result = array(
                'code' => 401,
                'status' => 'Unauthorized'
            );
        } 
        return $result;
    }
 
    //mobile register user
    public function register() {

        $user_code   = mt_rand(0000,9999);
        $json        = file_get_contents('php://input');
        $params      = json_decode($json);
        $usernameReq = $params->username;
        $emailReq    = $params->email;
        $secondsReq  = $params->seconds;
        $nonceReq    = $params->nonce;
        $roleReq     = $params->role;
        $phone       = $params ->phone_number;
        $invite_code      = $params->invite_code;
        $user_invite_code = $params->user_code;

        // @formart text username
        $text_substr = substr($usernameReq,0,3);

        if ($roleReq && $roleReq != "subscriber" && $roleReq != "wcfm_vendor" && $roleReq != "seller") {
            return parent::sendError("invalid_role","Role is invalid.", 400);
        }
        $userPassReq  = $params->user_pass;
        $userLoginReq = $params->user_login;
        $userEmailReq = $params->user_email;
        $notifyReq    = $params->notify;
        
        $username = sanitize_user($usernameReq);
        $email    = sanitize_email($emailReq);

        if ($secondsReq) {
            $seconds = (int) $secondsReq;
        } else {
            $seconds = 120960000;
        }
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
                $user_id = wp_insert_user($user);
               
                $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';
                  
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
                        // @formart text username
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
                            update_post_meta( $id_coupon_user_invite, 'date_expires', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))));
                            update_post_meta( $id_coupon_user_invite, 'coupon_amount', $for_inviter);
                            update_post_meta( $id_coupon_user_invite, 'discount_type',$percentage); 
                            update_post_meta( $id_coupon_user_invite, 'description', $user_id); 
                            update_post_meta( $id_coupon_user_invite, 'usage_limit', '1');
                            // update_post_meta($coupon, 'expiry_date', '1650387600');

                            //create coupon to odoo
                            $this->add_coupon_odoo($id_coupon_user_invite);
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
                            // update_post_meta( $list_coupon_user_invite, 'description', $user_id);
                            update_post_meta( $list_coupon_user_invite, 'discount_type', $percentage );
                            // update_post_meta( $list_coupon_user_invite, 'usage_limit', '1' );
                            // update_post_meta($id_coupon_user_invite, 'inviter_id',$inviter_id);
                        }
                        
                        //create coupon for owner
                        $coupon_user_owner = rand(0000,9999);
                        // @formart text username
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
                            // update_post_meta($coupon, 'expiry_date', '1650387600');

                            $this->add_coupon_odoo($id_coupon_user_owner);
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
                            // update_post_meta( $list_coupon_owner, 'description', $user_id );
                            update_post_meta( $list_coupon_owner, 'discount_type',$percentage );
                            update_post_meta( $list_coupon_owner, 'usage_limit', '1' );
                        }
                    }
                }

                $userID = $this->getOdooID($username.'_'.$user_id , $email);
                
                if($userID){
                    $is_success = add_user_meta( $user_id, 'register_status', 1, true );
                    $is_success = add_user_meta( $user_id, 'invite_code', $invite_code, true );
                    $is_success = add_user_meta( $user_id, 'user_code',  $invite_code_for_user, true );
                    $is_success = add_user_meta( $user_id, 'phone_number', $params->phone_number, true );
                    $is_success = add_user_meta( $user_id, 'odoo_id', $userID, true );
                }else{
                    return parent::sendError("existed_username","Username already exists.", 400);
                }
                if(is_wp_error($user_id)){
                    return parent::sendError($user_id->get_error_code(),$user_id->get_error_message(), 400);
                }
            }
        }

        $expiration = time() + apply_filters('auth_cookie_expiration', $seconds, $user_id, true);
        $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

        //get user cookie
        $data_user = get_user_by('id', $user_id);
        preg_match('|src="(.+?)"|', get_avatar($data_user->ID, 512), $avatar);

        $data_user = array(
            "cookie" => $cookie,
            "cookie_name" => LOGGED_IN_COOKIE,
            "session" => LOGGED_IN_COOKIE.'='.$cookie,
            "user" => array(
                "id" => $data_user->ID,
                "username" => $data_user->user_login,
                "nicename" => $data_user->user_nicename,
                "email" => $data_user->user_email,
                "url" => $data_user->user_url,
                "registered" => $data_user->user_registered,
                "displayname" => $data_user->display_name,
                "firstname" => $data_user->user_firstname,
                "lastname" => $data_user->last_name,
                "nickname" => $data_user->nickname,
                "description" => $data_user->user_description,
                "capabilities" => $data_user->wp_capabilities,
                "role" => $data_user->roles,
                "avatar" => $avatar[1] != '' ? $avatar[1] : '',
            ),
        );
        return $data_user;
    }

    //get odoo token
    public function get_odoo_token(){
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
    public function add_coupon_odoo($post_id) {

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
                'Authorization: Bearer '.$this->get_odoo_token().'',
                'Content-Type: application/json',
                'Cookie: session_id=0538fccf8c498db693b1915f13854f8a5d7716e9'
            ),
        ));
        
        $response = curl_exec($curl);
        //biz_write_log($response ,'Coupon to odoo 31-10-2022');
        curl_close($curl);
    }

    //Generate Cookie for User
    public function generate_auth_cookie() {
        $json = file_get_contents('php://input');
        $params = json_decode($json);
        if(!isset($params->username) || !isset($params->password) ){
            return parent::sendError("invalid_login","Invalid params", 400);
        }
        if($params->force_login){
            $user_id = $params->user_id;
            $user = get_user_by('id', $user_id);
            if (empty($user)) {
                return array(
                        "code" => "incorrect_user",
                        "message" => "Invalid user.",
                        "data" => array(
                            "status" => 404
                        )
                    );
            }
        } else {
            $username = $params->username;
            $password = $params->password;
    
            $user = wp_authenticate($username, $password);
    
            if (is_wp_error($user)) {
                return parent::sendError($user->get_error_code(),"Invalid username/email and/or password.", 401);
            }
        }
    
        if ($params->seconds) {
            $seconds = (int) $params->seconds;
        } else {
            $seconds = 1209600;
        }
    
        $expiration = time() + apply_filters('auth_cookie_expiration', $seconds, $user->ID, true);
        $cookie = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');
        preg_match('|src="(.+?)"|', get_avatar($user->ID, 512), $avatar);
    
        // get coupon
        $user_id = $user->ID;
        $email = $user->user_email;
        $user_code = get_user_meta($user_id, 'user_code', true);
    
        if(!$user_code){
            $coupon_user_invite = rand(0000,9999);
            // @formart text username
            $text_substr = substr($email,0,3);
            $user_code = $text_substr.$user_id.$coupon_user_invite.'Z';
            update_user_meta( $user_id, 'user_code',$user_code);
        }
    
        $phone_number    = $user->phone_number;
        $register_status = $user->register_status;
        if($register_status==1){
            $send_opt_or_not = true;
        }else{
            $send_opt_or_not = false;
        }
    
        return array(
            "cookie" => $cookie,
            "cookie_name" => LOGGED_IN_COOKIE,
            "session" => LOGGED_IN_COOKIE.'='.$cookie,
            "user" => array(
                "id" => $user->ID,
                "username" => $user->user_login,
                "nicename" => $user->user_nicename,
                "email" => $user->user_email,
                "url" => $user->user_url,
                "registered" => $user->user_registered,
                "displayname" => $user->display_name,
                "firstname" => $user->user_firstname,
                "lastname" => $user->last_name,
                "nickname" => $user->nickname,
                "description" => $user->user_description,
                "capabilities" => $user->wp_capabilities,
                "role" => $user->roles,
                "avatar" => $avatar[1],
                "user_code" => $user_code,
                "phone"     => $phone_number,
                "status"    => $send_opt_or_not
            ),
        );
    }

    //Generate Odoo ID for User
    public function getOdooID($userName, $userEmail){
        $url     = defined( 'ODOO_URL' ) ? ODOO_URL : '';
        $client_id     = defined( 'ODOO_CLIENT_ID' ) ? ODOO_CLIENT_ID : '';
        $client_secret     = defined( 'ODOO_CLIENT_SECRET' ) ? ODOO_CLIENT_SECRET : '';
        $db     = defined( 'ODOO_DB' ) ? ODOO_DB : '';


        $accessTokenUrl  = $url.'/client/api/oauth2/access_token';
		$postfields = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&db=' . $db;
		$response = $this->post( $accessTokenUrl, $postfields );
		$token = json_decode($response, true);

        
        $createUserUrl = $url."/api/customer/create";
		$postfields = '{
			"params": {
				"name": 	"'.$userName.'",
				"phone": 	"09954545",
				"email": 	"'.$userEmail.'",
				"db": 		"'.$db.'"
			}
		}';

        $response = $this->post( $createUserUrl, $postfields, $token['access_token'],'Content-Type: application/json');
        //echo $response;
        $user = json_decode($response, true);

       return  $user['result']['id'];
    }

    //Login with facebook
    public function fb_connect($request)
    {
        //return $this->getOdooID('Hounou1','hounou@gmail.com');
        $fields = 'id,name,first_name,last_name,email';
		$enable_ssl = true;
        $access_token = $request["access_token"];
        if (!isset($access_token)) {
            return parent::sendError("invalid_login","You must include a 'access_token' variable. Get the valid access_token for this app from Facebook API.", 400);
        }
        $url='https://graph.facebook.com/me/?fields='.$fields.'&access_token='.$access_token;
                
        //  Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $enable_ssl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
                
        if(isset($result["name"])) {

            $user_email   = strtolower($result['first_name'].'_'.$result['last_name']).substr($result["id"], 0, 3).'@gmail.com';//$result["email"];
            $email_exists = email_exists($user_email);
            
            if($email_exists) {
                $user      = get_user_by( 'email', $user_email );
                $user_id   = $user->ID;
                $email     = $user->user_email;
                $user_name = $user->user_login;

                $user_code = get_user_meta($user_id, 'user_code', true);
                if(!$user_code){
                    $coupon_user_invite = rand(0000,9999);
                    $text_substr = substr($email,0,3);
                    $user_code = $text_substr.$user_id.$coupon_user_invite.'Z';
                    update_user_meta( $user_id, 'user_code',$user_code);
                }
            }                
            
            if ( !$user_id && $email_exists == false ) {

                $user_name = strtolower($result['first_name'].'.'.$result['last_name']).'.'.substr($result["id"], 0, 3);
                // while(username_exists($user_name)){		        
                //     $i++;
                //     $user_name = strtolower($result['first_name'].'.'.$result['last_name']).'.'.$i;	
                // }

                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $userdata = array(
                    'user_login'    => $user_name,
                    'user_email'    => $user_email,
                    'user_pass'     => $random_password,
                    'display_name'  => $result["name"],
                    'first_name'    => $result['first_name'],
                    'last_name'     => $result['last_name'],
                    'user'          => $result
                );
                $user_id = wp_insert_user( $userdata );

                // @format text username
                $user_code = mt_rand(0000,9998);
                $text_substr = substr($user_email,0,3);
                $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

                $userID = $this->getOdooID($result["name"].'_'.$user_id , $user_email);	
                $is_success = add_user_meta( $user_id, 'user_code',$invite_code_for_user, true );

                if($userID) {
                    $is_success = add_user_meta( $user_id, 'odoo_id', $userID, true );
                } else {
                    return parent::sendError("existed_username","Username already exists.", 400);
                }	

                if($user_id) $user_account = 'user registered.';
                    
            } else {
                if($user_id) $user_account = 'user logged in.';
            }

            $expiration = time() + apply_filters('auth_cookie_expiration', 1209600, $user_id, true);
            $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
        
            $response['msg'] = $user_account;
            $response['wp_user_id'] = $user_id;
            $response['cookie'] = $cookie;
            $response['user_login'] = $user_name;	
            $response['user'] = $result;
            $response['session'] = LOGGED_IN_COOKIE.'='.$cookie;
        }
        else {
            return parent::sendError("invalid_login","Your 'access_token' did not return email of the user. Without 'email' user can't be logged in or registered. Get user email extended permission while joining the Facebook app.", 400);
        }
        return $response;
    }

    public function sms_login($request)
    {
        $access_token = $request["access_token"];
        if (!isset($access_token)) {
            return parent::sendError("invalid_login","You must include a 'access_token' variable. Get the valid access_token for this app from Facebook API.", 400);
        }
        $url = 'https://graph.accountkit.com/v1.3/me/?access_token=' . $access_token;

            $WP_Http_Curl = new WP_Http_Curl();
            $result = $WP_Http_Curl->request( $url, array(
                'method'      => 'GET',
                'timeout'     => 5,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'body'        => null,
                'cookies'     => array(),
            ));

            $result = json_decode($result, true);

            if (isset($result["phone"])) {
                $user_name = $result["phone"]["number"];
                $user_email = $result["phone"]["number"] . "@flutter.io";
                $email_exists = email_exists($user_email);

                if ($email_exists) {
                    $user = get_user_by('email', $user_email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $i = 1;
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($user_name) . '.' . $i;

                    }
                    $random_password = wp_generate_password();
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $user_email,
                        'user_pass' => $random_password,
                        'display_name' => $user_name,
                        'first_name' => $user_name,
                        'last_name' => "",
                    );

                    $user_id = wp_insert_user($userdata);
                    if ($user_id) {
                        $user_account = 'user registered.';
                    }

                } else {
                    if ($user_id) {
                        $user_account = 'user logged in.';
                    }
                }
                $expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

                $response['msg'] = $user_account;
                $response['wp_user_id'] = $user_id;
                $response['cookie'] = $cookie;
                $response['user_login'] = $user_name;
                $response['user'] = $result;
                $response['session'] = LOGGED_IN_COOKIE.'='.$cookie;
            } else {
                return parent::sendError("invalid_login","Your 'access_token' did not return email of the user. Without 'email' user can't be logged in or registered. Get user email extended permission while joining the Facebook app.", 400);
            }
        return $response;

    }

    public function firebase_sms_login($request)
    {
        $phone = $request["phone"];
        if (!isset($phone)) {
            return parent::sendError("invalid_login","You must include a 'phone' variable.", 400);
        }
        $domain = $_SERVER['SERVER_NAME'];
            if (count(explode(".",$domain)) == 1) {
                $domain = "flutter.io";
            }
            $user_name = $phone;
            $user_email = $phone."@".$domain;
            $email_exists = email_exists($user_email);
            $user_pass = wp_generate_password($length = 12, $include_standard_special_chars = false);
            if ($email_exists) {
                $user = get_user_by('email', $user_email);
                $user_id = $user->ID;
                $user_name = $user->user_login;
                wp_update_user( array( 'ID' => $user_id, 'user_pass' => $user_pass ) );
            }


            $result = "User OTP";

            if (!$user_id && $email_exists == false) {

                while (username_exists($user_name)) {
                    $i++;
                    $user_name = strtolower($user_name) . '.' . $i;

                }

                $userdata = array(
                    'user_login' => $user_name,
                    'user_email' => $user_email,
                    'user_pass' => $user_pass,
                    'display_name' => $user_name,
                    'first_name' => $user_name,
                    'last_name' => ""
                );

                $user_id = wp_insert_user($userdata);
                if ($user_id) $user_account = 'user registered.';

            } else {

                if ($user_id) $user_account = 'user logged in.';

                $user = get_userdata($user_id);

                if ($user->first_name != $user_name) {
                    $result = $user->first_name;

                    if ($user->last_name) {
                        $result .= " ".$user->last_name;
                    }
                }
            }

            $expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user_id, true);
            $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

            $response['msg'] = $user_account;
            $response['wp_user_id'] = $user_id;
            $response['cookie'] = $cookie;
            $response['user_login'] = $user_name;
            $response['user'] = $result;
            $response['user_pass'] = $user_pass;
            $response['session'] = LOGGED_IN_COOKIE.'='.$cookie;

        return $response;

    }

    public function apple_login($request)
    {
        //return "1";
        $email = $request["email"];
        if (!isset($email)) {
            return parent::sendError("invalid_login","You must include a 'email' variable.", 400);
        }
        $display_name = $request["display_name"];
            $user_name = $request["user_name"];
            $user_email = $email;
            $email_exists = email_exists($user_email);

            if ($email_exists) {
                $user = get_user_by('email', $user_email);
                $user_id = $user->ID;
                $user_name = $user->user_login;
            }


            if (!$user_id && $email_exists == false) {

                while (username_exists($user_name)) {
                    $i++;
                    $user_name = strtolower($user_name) . '.' . $i;

                }

                $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                $userdata = array(
                    'user_login' => $user_name,
                    'user_email' => $user_email,
                    'user_pass' => $random_password,
                    'display_name' => $display_name,
                    'first_name' => $display_name,
                    'last_name' => $display_name
                );

                $user_id = wp_insert_user($userdata);
                $userID = $this->getOdooID($display_name.'_'.$user_id , $user_email);
                if($userID){
                    $is_success = add_user_meta( $user_id, 'odoo_id', $userID, true );
                }else{
                    return parent::sendError("existed_username","Username already exists.", 400);
                }

                if ($user_id) $user_account = 'user registered.';

            } else {

                if ($user_id) $user_account = 'user logged in.';
            }

            $expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user_id, true);
            $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

            $response['msg'] = $user_account;
            $response['wp_user_id'] = $user_id;
            $response['cookie'] = $cookie;
            $response['user_login'] = $user_name;
            $response['user'] = $user;
            $response['session'] = LOGGED_IN_COOKIE.'='.$cookie;

        return $response;

    }

    //Login with Google
    public function google_login($request)
    {
        
        $access_token = $request["access_token"];
        if (!isset($access_token)) {
            return parent::sendError("invalid_login","You must include a 'access_token' variable. Get the valid access_token for this app from Google API.", 400);
        }

        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $access_token;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            if (isset($result["email"])) {
                $firstName = $result["given_name"];
                $lastName = $result["family_name"];
                $email = $result["email"];
                $avatar = $result["picture"];
				$user_code = mt_rand(0000,9999);
				
                $display_name = $firstName." ".$lastName;
                $user_name = $firstName.".".$lastName;
                $user_email = $email;
                $email_exists = email_exists($user_email);
                if ($email_exists) {
                    $user = get_user_by('email', $user_email);
                    $user_id = $user->ID;
                    $email = $user->user_email;
                    $user_name = $user->user_login;

                    $user_code = mt_rand(0000,9999);
                    $status_user_code = $user->user_code ? $user->user_code : null;
                    if(!$status_user_code){
                        $text_substr = substr($email,0,3);
                        update_user_meta( $user_id, 'user_code',$text_substr.$user_id.$user_code.'Z');
                    }
                }
  
                if (!$user_id && $email_exists == false) {
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($user_name) . '.' . $i;
                    }
    
                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $user_email,
                        'user_pass' => $random_password,
                        'display_name' => $display_name,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    );
                    $user_id = wp_insert_user($userdata);

                    // @formart text username
                    $text_substr = substr($user_email,0,3);
                    $invite_code_for_user = $text_substr.$user_id.$user_code.'Z';

                    $userID = $this->getOdooID($display_name .'_'. $user_id, $user_email);  
					 $is_success = add_user_meta( $user_id, 'user_code',$invite_code_for_user, true );
                    if($userID){
                       $is_success = add_user_meta( $user_id, 'odoo_id', $userID, true );
                    }else{
                        return parent::sendError("existed_username","Username already exists.", 400);
                    }
                    if ($user_id) $user_account = 'user registered.';
    
                } else {
                    if ($user_id) $user_account = 'user logged in.';
                }
    
                $expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
    
                $response['msg'] = $user_account;
                $response['wp_user_id'] = $user_id;
                $response['cookie'] = $cookie;
                $response['user_login'] = $user_name;
                $response['user'] = $result;
                $response['session'] = LOGGED_IN_COOKIE.'='.$cookie;
                return $response;
            }else{
                return parent::sendError("invalid_login","Your 'token' did not return email of the user. Without 'email' user can't be logged in or registered. Get user email extended permission while joining the Google app.", 400);
            }  
    }

    /*
     * Post commment function
     */
    public function post_comment()
    {
        global $json_api;
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $cookie = $params->cookie;
        $user_id = wp_validate_auth_cookie($cookie, 'logged_in');
        if (!$user_id) {
            return parent::sendError("invalid_login","Invalid cookie. Use the `generate_auth_cookie` method.", 401);
        }
        if (!$params->post) {
            return parent::sendError("invalid_data","No post specified. Include 'post_id' var in your request.", 400);
        } elseif (!$params->comment) {
            return parent::sendError("invalid_data","Please include 'content' var in your request.", 400);
        }

        $comment_approved = 1;
        $user_info = get_userdata($user_id);
        $time = current_time('mysql');
        $agent = filter_has_var(INPUT_SERVER, 'HTTP_USER_AGENT') ? filter_input(INPUT_SERVER, 'HTTP_USER_AGENT') : 'Mozilla';
        $ips = filter_has_var(INPUT_SERVER, 'REMOTE_ADDR') ? filter_input(INPUT_SERVER, 'REMOTE_ADDR') : '127.0.0.1';
        $data = array(
            'comment_post_ID' => $params->post,
            'comment_author' => $user_info->user_login,
            'comment_author_email' => $user_info->user_email,
            'comment_author_url' => $user_info->user_url,
            'comment_content' => $params->comment,
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_info->ID,
            'comment_author_IP' => $ips,
            'comment_agent' => $agent,
            'comment_date' => $time,
            'comment_approved' => $comment_approved,
        );
        $comment_id = wp_insert_comment($data);
        return array(
            "code" => "insert_comment_success",
            "message" => "$comment_id",
            "data" => ['status' => 200],
        );
    }

    public function get_currentuserinfo() {
        global $json_api;
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $cookie = $params->cookie;
        if (!isset($cookie)) {
            return parent::sendError("invalid_login","You must include a 'cookie' var in your request. Use the `generate_auth_cookie` method.", 401);
        }

		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		if (!$user_id) {
			return parent::sendError("invalid_login","You must include a 'cookie' var in your request. Use the `generate_auth_cookie` method.", 401);
		}
		$user = get_userdata($user_id);
		preg_match('|src="(.+?)"|', get_avatar( $user->ID, 32 ), $avatar);
		$data = array(
			"user" => array(
				"id" => $user->ID,
				"username" => $user->user_login,
				"nicename" => $user->user_nicename,
				"email" => $user->user_email,
				"url" => $user->user_url,
				"registered" => $user->user_registered,
				"displayname" => $user->display_name,
				"firstname" => $user->user_firstname,
				"lastname" => $user->last_name,
				"nickname" => $user->nickname,
				"description" => $user->user_description,
                "capabilities" => $user->wp_capabilities,
                "role" => $user->roles,
				"avatar" => $avatar[1]
			)
		);

        global $wc_points_rewards;
        if (isset($wc_points_rewards)) {
            $points_balance = WC_Points_Rewards_Manager::get_users_points( $user_id );
            $points_label   = $wc_points_rewards->get_points_label( $points_balance );
            $count        = apply_filters( 'wc_points_rewards_my_account_points_events', 5, $user_id );
            $current_page = empty( $current_page ) ? 1 : absint( $current_page );
            
            $args = array(
                'calc_found_rows' => true,
                'orderby' => array(
                    'field' => 'date',
                    'order' => 'DESC',
                ),
                'per_page' => $count,
                'paged'    => $current_page,
                'user'     => $user_id,
            );
            $total_rows = WC_Points_Rewards_Points_Log::$found_rows;
            $events = WC_Points_Rewards_Points_Log::get_points_log_entries( $args );
            
            $data['poin'] = array(
                'points_balance' => $points_balance,
                'points_label'   => $points_label,
                'total_rows'     => $total_rows,
                'page'   => $current_page,
                'count'          => $count,
                'events'         => $events
            );
        }

        return $data;
    }

    function update_user_profile() {
        global $json_api;
        $json = file_get_contents('php://input');
        $params = json_decode($json);
        $cookie = $params->cookie;
        
        if (!isset($cookie)) {
            return parent::sendError("invalid_login","You must include a 'cookie' var in your request. Use the `generate_auth_cookie` method.", 401);
        }
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		if (!$user_id) {
			return parent::sendError("invalid_login","You must include a 'cookie' var in your request. Use the `generate_auth_cookie` method.", 401);
		}

        $user_update = array( 'ID' => $user_id);
        if ($params->user_pass) {
            $user_update['user_pass'] = $params->user_pass;
        }
        if ($params->user_nicename) {
            $user_update['user_nicename'] = $params->user_nicename;
        }
        if ($params->user_email) {
            $user_update['user_email'] = $params->user_email;
        }
        if ($params->user_url) {
            $user_update['user_url'] = $params->user_url;
        }
        if ($params->display_name) {
            $user_update['display_name'] = $params->display_name;
        }
        if ($params->first_name) {
            $user_update['first_name'] = $params->first_name;
        }
        if ($params->last_name) {
            $user_update['last_name'] = $params->last_name;
        }
        
        $user_data = wp_update_user($user_update);

        if ( is_wp_error( $user_data ) ) {
          // There was an error; possibly this user doesn't exist.
            $return['is_success'] = false; 
            $return['cookie'] = $token;
        }else{
            $expiration = time() + apply_filters('auth_cookie_expiration', 120960000, $user_id, true);
            $return['is_success'] = true; 
            $return['cookie'] = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
        }

        return $return;
    }

    public function send_email_forgot_password(){
        include '../wp-load.php';

        $json = file_get_contents('php://input');
        $params = json_decode($json);
        $login = $params->email; 

        if ( empty( $login ) ) {
            $json = array( 'status' => 'error', 'message' => 'Please enter login user detail' );
            echo json_encode( $json );
            exit;     
        }

        $userdata = get_user_by( 'email', $login); 

        if ( empty( $userdata ) ) {
            $userdata = get_user_by( 'login', $login );
        }

        if ( empty( $userdata ) ) {
            $json = array( 'code' => '101', 'msg' => 'User not found' );
            echo json_encode( $json );
            exit;
        }

        $user      = new WP_User( intval( $userdata->ID ) ); 
        $reset_key = get_password_reset_key( $user ); 
        $wc_emails = WC()->mailer()->get_emails(); 
        $wc_emails['WC_Email_Customer_Reset_Password']->trigger( $user->user_login, $reset_key );

        $result = ['status' => 'success','message' => 'Password reset link has been sent to your registered email !'];
        echo json_encode( $result );
        exit;
    }

    // @get province
    public function query_province(){
        $get_province = new WP_Query();
    }

    protected function get( $url, $postfields, $bearer_token = '')
	{
		return $this->curl( 'GET', $url, $postfields, $bearer_token );
	}

	protected function post($url, $postfields, $bearer_token = '', $content_type = "Content-Type: application/x-www-form-urlencoded")
	{
		return $this->curl( 'POST', $url, $postfields, $bearer_token, $content_type);
	}

    private function curl( $method, $url, $postfields, $bearer_token = '', $content_type = "Content-Type: application/x-www-form-urlencoded" )
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
}
