<?php
/**
 * Plugin Name: BIZ Odoo Coupon
 * Plugin URI: 
 *  Description: This plugin use for CRUD coupon between WP & ODOO
 * Author: BIZ Solution Author
 * Author URI: 
 * Text Domain: 
 * Domain Path: /languages/
 * Version: 1.0
 */
defined( 'ABSPATH' ) || exit;

//get token
function get_token() {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL =>  ODOO_URL.'/client/api/oauth2/access_token',
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
function add_coupon_odoo($post_id) {
    global $post_type;
    global $wpdb;
    
    if( $post_type == 'shop_coupon' ) {
        if ( isset($_POST['publish'])) { 
            $date           = get_field('date_expires' ,$post_id);
            if(!empty( $date )){
				$coupon_name    = get_the_excerpt($post_id);
				$coupon_code    = get_the_title($post_id);
				$discount_type  = get_field('discount_type' ,$post_id); 
				$amount         = get_field('coupon_amount' ,$post_id);
				$usage_limit    = get_field('usage_limit' ,$post_id);
				$usage_limit_per_user = get_field('usage_limit_per_user' ,$post_id);
				$date           = get_field('date_expires' ,$post_id);
				$free_shipping  = get_field('free_shipping' ,$post_id);
				$minimum_amount = get_field('minimum_amount' ,$post_id);
				$date_expires = new DateTime('@'.$date);
				
				// $date_expires->modify( "-1 day" );
				$expire = $date_expires->format('Y-m-d');
                $date_expires->modify( "+1 day" );
                $expires = $date_expires->format('Y-m-d');

                // convert date in database (Y-m-d)
                update_post_meta( $post_id, 'date_expires',$expires);
				
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
						"date_expires": "'.$expire.'",
						"minimum_amount":'.$minimum_amount.',
						"portal_id":'.$post_id.',
						"db":"'.ODOO_DB.'"
					}
				}',
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '.get_token().'',
					'Content-Type: application/json',
					'Cookie: session_id=49c1d3cb3a4847f7c4bc009bd9465ecec6619dbe'
				  ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
			}else{
                echo ' ';
            }
        }
    }    
}
add_action('save_post', 'add_coupon_odoo', 10, 4);


//update coupon odoo
function update_coupon_odoo($post_id) {

    if($_POST['post_type'] == 'shop_coupon' && isset($_POST['save']) ) {
        $coupon_name    = get_the_excerpt($post_id);
        $coupon_code    = get_the_title($post_id);
        $discount_type  = get_field('discount_type' ,$post_id);
        $amount         = get_field('coupon_amount' ,$post_id);
        $usage_limit    = get_field('usage_limit' ,$post_id);
        $usage_limit_per_user = get_field('usage_limit_per_user' ,$post_id);
        $date           = get_field('date_expires' ,$post_id);
        $free_shipping  = get_field('free_shipping' ,$post_id);
        $minimum_amount = get_field('minimum_amount' ,$post_id);

        $date_expires = new DateTime('@'.$date);
        $date_expires->modify( "+1 day" );
        $expire = $date_expires->format('Y-m-d');

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
          CURLOPT_URL => ODOO_URL.'/api/sale_coupon/'.$post_id.'/update',
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
                "date_expires": "'.$expire.'",
                "minimum_amount":'.$minimum_amount.',
                "db":"'.ODOO_DB.'"
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.get_token().'',
            'Content-Type: application/json',
            'Cookie: session_id=49c1d3cb3a4847f7c4bc009bd9465ecec6619dbe'
          ),
        ));
        
        $response = curl_exec($curl);
    
        curl_close($curl);

    }
}
add_action('save_post', 'update_coupon_odoo', 10, 3);
