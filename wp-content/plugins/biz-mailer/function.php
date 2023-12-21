<?php
/**
 * Description: This file for PHP functionality.
 * Version: 0.0.2
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

 /*
 * Name: Send Mail
 * Description: Mail Configuration
 * Date: 06-09-2022
 */
if ( ! function_exists( 'biz_send_smtp_email' ) ) {
    function biz_send_smtp_email( $receiver_email = '' ) {
        $title          = get_field('mail_subject' ,'option');
        $content        = get_field('mail_content' ,'option');
        $attachments    = get_field('mail_attachment' ,'option');
        $to_email       = $receiver_email;
        $headers        = ['Content-Type: text/html; charset=UTF-8'];

        if(get_field('mail_status' ,'option') == 1)
            wp_mail($to_email, $title, $content, $headers, $attachments );
    }
	add_action( 'biz_send_email', 'biz_send_smtp_email', 10, 2 );
}

/*
 * Name: Mail
 * Description: Mail Configuration
 * Date: 06-09-2022
 */
if ( ! function_exists( 'biz_config_smtp_email' ) ) {
    function biz_config_smtp_email( $phpmailer ) {

        define( 'SMTP_HOST',      get_field('mail_smtp_host' ,'option') );
        define( 'SMTP_AUTH',      true );
        define( 'SMTP_PORT',      get_field('mail_smtp_port' ,'option') );
        define( 'SMTP_SECURE',    'ssl' );
        define( 'SMTP_USERNAME',  get_field('mail_smtp_username' ,'option') );   // Username for SMTP authentication
        define( 'SMTP_PASSWORD',  get_field('mail_smtp_password' ,'option') );   // Password for SMTP authentication
        define( 'SMTP_FROM',      get_field('mail_smtp_from' ,'option') );       // SMTP From address
        define( 'SMTP_FROMNAME',  get_field('mail_smtp_from_name' ,'option') );
        define( 'RECEIVER_EMAIL', get_field('mail_receiver_email' ,'option') );

        $phpmailer->isSMTP();
        $phpmailer->Host       = SMTP_HOST;
        $phpmailer->SMTPAuth   = SMTP_AUTH;
        $phpmailer->Port       = SMTP_PORT;
        $phpmailer->SMTPSecure = SMTP_SECURE;
        $phpmailer->Username   = SMTP_USERNAME;
        $phpmailer->Password   = SMTP_PASSWORD;
        $phpmailer->From       = SMTP_FROM;
        $phpmailer->FromName   = SMTP_FROMNAME;
    }
    add_action( 'phpmailer_init', 'biz_config_smtp_email' );
}