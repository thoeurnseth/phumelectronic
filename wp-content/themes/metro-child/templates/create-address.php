<?php
    /*
    * Template Name: Create Address
    * Template Post Type: post, page
    */

    global $wpdb;
    $id = $_GET['id'];
    $email = $_GET['email'];

    echo $id;
    echo '<br>'.$email;

    function aes_hash($action, $string) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
         $secret_key = 'sgfudir7ywefivdvbg356776432452324543';
         $secret_iv = 'sgfudir7ywefivdvbg356776432452324543';
       // $secret_key = 'T#3%8j6SpjTnhelaydadgyudf';
      // $secret_iv = 'rpCrFw*39yHAgFh@sZ$Xxk5G@Bfsdfsdf';
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

    $user_details = get_user_by('ID', $cus_id);
    if($user_details != NULL) {
        $email = $user_details->user_email;
        if($email == $cus_email) {
            wp_clear_auth_cookie();
            wp_set_current_user ( $user_details->ID );
            wp_set_auth_cookie  ( $user_details->ID );
            wp_safe_redirect( site_url().'/my-account/edit-address?user_id='.$cus_id.'&webview=true' );
            exit();
        } 
        echo 'user not login';
    }
?>

