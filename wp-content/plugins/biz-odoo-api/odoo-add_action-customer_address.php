<?php

use BizSolution\OdooAPI\OdooAPI;
use BizSolution\OdooAPI\OdooWoocommerce;

/**
 * Push create customer address to Odoo
 */
function odoo_create_customer_address( $post_id ) {

	$post_name = get_post_type($post_id);

	if ($post_name == 'user-address') {

		// Set Default Address
		$count = check_billing_address();
		if ($count <= 1) {
			update_post_meta($post_id, 'default_address', 1);
		}

		// Create address on odoo
		do_action('biz_create_odoo_customer_address', $post_id);
	}
}
add_action( 'acf/save_post', 'odoo_create_customer_address', 10, 4);
  
  
/**
 * Check Billing Address
 */
function check_billing_address()
{
	$author_ID = get_current_user_id();

	// Update other default to number 0
	$args = array(
		'post_type'      => 'user-address',
		'posts_per_page' => -1,
		'author'         => $author_ID
	);

	$query = new WP_Query( $args );
	$count = $query->post_count;

	return $count;
}

// Add action create address on odoo
add_action('biz_create_odoo_customer_address', 'create_odoo_customer_address');
function create_odoo_customer_address( $post_id )
{
	$user_id 	= get_current_user_id();
	$post_type  = get_post_type( $post_id );

	if ( $post_type == 'user-address' ) {

		$address = get_field( 'address', $post_id );
		$street  = get_field( 'street', $post_id );

		$province_id	  	= get_field('province_2', $post_id);
		$province_odoo_id 	= get_term_meta( $province_id, 'odoo_id', true );

		$district_id 		= get_field('district_2', $post_id);
		$district_odoo_id	= get_term_meta( $district_id, 'odoo_id', true );

		$commune_id 		= get_field('commune_2', $post_id);
		$commune_odoo_id	= get_term_meta( $commune_id, 'odoo_id', true );

		$phone_id 		    = get_field('phone', $post_id);

		$data = array(
			'post_id' 		=> $post_id,
			'province_obj' 	=> $province_id,
			'province_id' 	=> $province_odoo_id,
			'district_obj' 	=> $district_id,
			'district_id' 	=> $district_odoo_id,
			'commune_obj' 	=> $commune_id,
			'commune_id' 	=> $commune_odoo_id,
			'phone_obj' 	=> $phone_id,
			// 'phone_id' 		=> $phone_odoo_id,
		);

		$post_code = '12000';
		$odoo_user_id = get_user_meta( $user_id, 'odoo_id', true );
		$odoo_address_id = get_post_meta( $post_id, 'odoo_id', true );

		$odoo_api = new OdooAPI();
		$msg = $odoo_api->create_customer_address( $address, $street, $province_odoo_id, $district_odoo_id, $commune_odoo_id, $post_code, $odoo_user_id, $odoo_address_id ,$phone_id );
		$response = json_decode($msg, true);

		if ( 
			!empty($response) && 
			isset($response["result"]) && 
			isset($response["result"]["success"]) && 
			$response["result"]["success"] == true
		){

			if( $odoo_address_id == null ) {
				update_post_meta( $post_id, 'odoo_id', $response["result"]["id"] );
			}

			return true;
		}
		else{
			//biz_write_log( 'OdooID: ' . $msg, 'address');
			return false;
		}
	}
	else {
		return false;
	}
}