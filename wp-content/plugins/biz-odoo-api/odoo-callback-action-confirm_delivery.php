<?php

    /**
     * Registering rest route /wp-json/odoo/orders/confirm-delivery
     * @params: id, consumer_key, consumer_secret
     */
	add_action( 'rest_api_init', function(){
		register_rest_route( 'odoo', '/orders/confirm-delivery', [
			'methods'       =>      "POST",
			'callback'      =>      'biz_odoo_api_call_action_on_confirm_delivery',
			'permission_callback' => '__return_true'
		]);
	});



	/**
	 * Rest route callback /wp-json/odoo/orders/confirm-delivery
	 */
	if( !function_exists("biz_odoo_api_call_action_on_confirm_delivery") ):
		function biz_odoo_api_call_action_on_confirm_delivery()
		{
			if( !isset( $_POST['consumer_key'] ) || !isset( $_POST['consumer_secret'] ) || !isset( $_POST['id'] ) )
			{
				return ['status'=>'error', 'text' => 'invalid_route'];
			}
			$odoo_id = $_POST['id'];
			

			global $wpdb;
			$product 		= $wpdb->get_row( 
								$wpdb->prepare( 
									"SELECT ".$wpdb->posts.".ID 
									FROM $wpdb->posts 
									INNER JOIN $wpdb->postmeta 
									ON " . $wpdb->posts . ".ID = ".$wpdb->postmeta.".post_id 
									WHERE meta_key='odoo_id' 
									AND meta_value = '$odoo_id'"
								)
							);



			$woocommerce = new Client(
				site_url(), 
				$_POST['consumer_key'], 
				$_POST['consumer_secret'],
				[
					'wp_api' => true,
					'version' => 'wc/v3',
	                'query_string_auth' => true,
	                'timeout' => 120,
	                'verify_ssl' => false
				]
			);



			if(empty($product))
			{
				return ['status'=>'error', 'text' => 'invalid_route'];
			}
			$odoo_order_id 		= $product->ID;

			return $woocommerce->post('orders/'.$odoo_order_id, ['status' => 'completed']);
		}
	endif;

	


?>