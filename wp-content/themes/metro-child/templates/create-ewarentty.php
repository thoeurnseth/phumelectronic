<?php
    /*
    * Template Name: Create Ewarentty
    * Template Post Type: post, page
    */

    global $wpdb;
    $id = $_GET['id'];
    $email = $_GET['email'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $id = $_POST['id'];
        $email = $_POST['email'];
    }
    else { 
        $id = $_GET['id'];
        $email = $_GET['email'];

    }

    function aes_hash($action, $string) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'sgfudir7ywefivdvbg356776432452324543';
        $secret_iv = 'sgfudir7ywefivdvbg356776432452324543';
        // hash
        $key = hash('sha256', $secret_key);    
        // iv - encrypt method AES-256-CBC expects 16 bytes 
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    
    $cus_id = aes_hash('decrypt',$id);
    $cus_email = aes_hash('decrypt',$email);
 
    
    // echo "cust_id=".urlencode(aes_hash('encrypt',$id));
    // echo "<br>cus_email=".urlencode(aes_hash('encrypt',$email));exit;

    //echo "cust_id=".$cus_id;
    //echo "<br>cus_email=".$cus_email;
    //exit;


    $user_details = get_user_by('ID', $cus_id);
    if($user_details != NULL) {
        $email = $user_details->user_email;
        if($email == $cus_email) {
			 echo 'user login';
            wp_clear_auth_cookie();
            wp_set_current_user ( $user_details->ID );
            wp_set_auth_cookie  ( $user_details->ID );
            wp_safe_redirect( site_url().'/my-account/device?user_id='.$cus_id.'&webview=true' );
            exit();
        } 
        echo 'user not login';
    }
?>