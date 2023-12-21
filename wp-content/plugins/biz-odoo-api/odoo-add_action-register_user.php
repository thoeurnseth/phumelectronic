<?php

use BizSolution\OdooAPI\OdooAPI;
use BizSolution\OdooAPI\OdooWoocommerce;

// add_action('user_register', 'register_odoo_user');
add_action('biz_customer_successfully_registered','register_odoo_user');
function register_odoo_user($user_id)
{
	$customer = get_user_by('id', $user_id);
	
	$phone = get_user_meta( $user_id, 'phone_number', true );
	$customer_odoo_id = get_user_meta( $user_id, 'odoo_id', true );

	// Update User Code 
	$user_code = get_user_meta( $user_id, 'user_code', true );
	if(empty($user_code)){
		$email = $customer->user_email;
		$random = mt_rand(0000,9999);
		$text_substr = substr($email,0,3);
		$user_code = $text_substr.$user_id.$random.'Z';
		update_user_meta( $user_id, 'user_code', $user_code);
	}
	
	
	// Register User to Odoo
	$odoo_api = new OdooAPI();
	$msg = $odoo_api->create_customer( $customer->nickname.'_'.$user_id, $phone, $customer->user_email, $customer_odoo_id );
	$response = json_decode($msg, true);

	if ( 
		!empty($response) && 
		isset($response["result"]) && 
		isset($response["result"]["success"]) && 
		$response["result"]["success"] == true
	){
		
		if( $customer_odoo_id == null ) {
			update_user_meta( $user_id, 'odoo_id', $response["result"]["id"] );
		}
		
		return true;
	}
	else{
		return false;
	}
}