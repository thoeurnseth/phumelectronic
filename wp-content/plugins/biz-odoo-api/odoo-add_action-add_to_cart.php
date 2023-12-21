<?php

use BizSolution\OdooAPI\OdooAPI;
use BizSolution\OdooAPI\OdooWoocommerce;

/**
 * Check Stock
 */
add_filter( 'woocommerce_add_to_cart_validation', 'validate_inventory_add_to_cart', 10, 5 );
function validate_inventory_add_to_cart( $passed, $product_id, $quantity, $variation_id='', $variations='' )
{
	if( is_user_logged_in() )
	{
		$user_id = get_current_user_id();
		$post_args = array(
			'post_type' => 'user-address',
			'post_status' => 'publish',
			'author__in'     => array( $user_id ),
		);

		$query = new WP_Query( $post_args );
		$found_post = $query->found_posts; 

		if( $found_post > 0 )
		{	
			if( empty($product_id) || empty($variation_id) )

				$woo_product_id = get_post_meta( $product_id, 'odoo_id', true );
				$woo_variant_id = get_post_meta( $variation_id, 'odoo_id', true );

				$odoo_product_id = !empty($variation_id) ? $woo_variant_id : $woo_product_id;
				$product_title	 = get_the_title( $product_id );

				$odoo_api = new OdooAPI();
				$msg = $odoo_api->check_stock( array(['id' => $odoo_product_id, 'qty' => $quantity]) );

				$response = json_decode( $msg, true);

			// do your validation, if not met switch $passed to false
			if ( 
				!empty($response) && 
				isset($response["result"]) && 
				isset($response["result"]["success"]) && 
				$response["result"]["success"] == true 
			){
				$passed = true;
			}
			else{
				$passed = false;
				wc_add_notice( __( '"'. $product_title . '" is out of stock.', 'textdomain' ), 'error' );
			}
		}else { 
			$passed = false;
			wc_add_notice( __( 'you have no address, please create your address before add product to cart <a class="text-danger" href="'.site_url().'/my-account/edit-address/?action=new&type=view_product">Create address here</a>.', 'textdomain' ), 'error' );
		}
	}

	return $passed;
}


/**
 * Check Out
 */
add_action('woocommerce_checkout_process', 'validate_inventory_process_checkout');
function validate_inventory_process_checkout()
{
	$passed = true;
	$cart = WC()->cart->get_cart();
	$items = [];
	$items_to_checkout = [];

	$userId = get_current_user_id();

	$args = array(
		'post_type'     => 'user-address',
		'post_status'   => 'publish',
		'author'        => $userId,
	);

	$query = get_posts( $args );
	if(!empty($query)) {
		foreach ($cart as $item => $values)
		{
			$_product 	= $values['data']->post;
			$product_id = $_product->ID;

			$product 	= wc_get_product( $product_id );
			$product_title = $product->get_title();
			
			$qty 	 = $values['quantity'];
			$odoo_id = get_post_meta( $product_id, 'odoo_id', true );
			$items[] = ['id' => $odoo_id, 'qty' => $qty];
		}

		$odoo_api = new OdooAPI();
		$msg = $odoo_api->check_stock( $items );
		$response = json_decode($msg, true);

		// do your validation, if not met switch $passed to false
		if (
			!empty($response) && 
			isset($response["result"]) && 
			isset($response["result"]["success"]) && 
			$response["result"]["success"] == true 
		){
			return true;
		}
		else{
			wc_add_notice( __( '"'. $product_title . '" is out of stock.', 'textdomain' ), 'error' );
			return false;
		}
	}
	else{
		wc_add_notice( __( 'There is no existing billing address! Please add new address first.', 'textdomain' ), 'error' );
		return false;
	}
}