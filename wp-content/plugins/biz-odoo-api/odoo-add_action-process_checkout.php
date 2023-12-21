<?php

	use BizSolution\OdooAPI\OdooAPI;
	use BizSolution\OdooAPI\OdooWoocommerce;

	// woocommerce_order_status_changed
	add_action('woocommerce_order_status_completed', 'process_checkout_with_aba_payway_aim'); 
	add_action('woocommerce_order_status_processing', 'process_checkout_with_aba_payway_aim');  
	function process_checkout_with_aba_payway_aim($order_id)
	{
		$order = wc_get_order( $order_id );
		
		$odoo_confirm_sale_order = get_post_meta($order_id ,'odoo_confirm_sale_order' ,true);
        if(!empty($odoo_confirm_sale_order) && $odoo_confirm_sale_order == 'success') {
    		return true;
        }

		$items_to_checkout = [];
		$product_list_id 	= [];
		foreach ($order->get_items() as $item_key => $item ):
			
			$product        	= $item->get_product();
			$product_price  	= $product->get_price();
			$item_data    		= $item->get_data();
			$product_id   		= $item_data['variation_id'];
			$quantity     		= $item_data['quantity'];
			$sale_price 		= $product->get_sale_price();
			$date_on_sale_from 	= $product->get_date_on_sale_from();
			$date_on_sale_to 	= $product->get_date_on_sale_to();
			
			$odoo_id = get_post_meta( $product_id, 'odoo_id', true );
			// $odoo_id = wc_get_order_item_meta( $product_id, 'odoo_id', true );
			 
			$items_to_checkout[] = [
				'product_id' 	=> (int) $odoo_id,
				'qty' 			=> $quantity,
				'price_unit' 	=> $product_price,
				'discount' 		=> 0,
			];
			if(isset($sale_price) && $sale_price != ''){
				if(is_null($date_on_sale_from) || ( date('Y/m/d') >= date_format($date_on_sale_from,"Y/m/d")) ) {
					if(is_null($date_on_sale_to) || ( date("Y/m/d") <= date_format($date_on_sale_to,"Y/m/d"))){
						continue;
					}
				}
			}
			$product_list_id[] = (int) $odoo_id;
		endforeach;

		biz_write_log(json_encode($product_list_id),'Log product not discount when checkout');

		update_post_meta( $order_id, 'odoo_sale_order', 'pending' );
		update_post_meta( $order_id, 'odoo_confirm_sale_order', 'success' );
		
		$user_id = get_post_meta($order_id, '_customer_user', true);
		$user_odoo_id = get_user_meta( $user_id, 'odoo_id', true );
		$odoo_api = new OdooAPI();

		// Check default billing location
		//$author_ID = get_current_user_id();
		$args = array(
			'post_type'     => 'user-address',
			'post_status'   => 'publish',
			'author'        => $user_id,
			'meta_query' => array(
				array(
					'key' => 'default_address',
					'value' => 1,
					'compare' => '='
				)
			)
		);

		$query = get_posts( $args );
		$post_id = $query[0]->ID;
		$address_id = get_post_meta( $post_id, 'odoo_id', true );

		$msg = $odoo_api->process_checkout($user_odoo_id, $address_id, $items_to_checkout);


		update_post_meta( $order_id, 'odoo_sale_order_log', $msg );
		$response = json_decode($msg, true);
		$_payment_method = get_post_meta( $order_id, '_payment_method', true );
		if ( 
			!empty($response) && 
			isset($response["result"]) &&
			isset($response["result"]["success"]) &&
			$response["result"]["success"] == true
		){

			$order_odoo_id 	= $response["result"]["id"];
			$odoo_so_number = $response["result"]["number"];

			update_post_meta( $order_id, 'odoo_id', $order_odoo_id );
			update_post_meta( $order_id, 'odoo_sale_order', 'success' );
			update_post_meta( $order_id, 'odoo_so_number', $odoo_so_number );

			//check if user apply coupon 
			$order = wc_get_order( $order_id );
			$data_coupon =  $order->get_coupon_codes();//WC()->cart->get_coupons();

			$order->update_status('processing', 'order_note'); 

		
			if(!empty($data_coupon)) {
	  
				biz_write_log("has coupon!!!" ,'woocommerce_order_status_completed_'.date("Ymd"));

				// foreach($data_coupon as $key => $value) {
				// 	$coupon_code = (string)$key;
				// }	

				foreach( $order->get_coupon_codes() as $coupon_code ){
					$coupon_code = (string)$coupon_code;
				}
				biz_write_log($coupon_code,'coupon_code'.date("Ymd"));
				// @get coupon info
				$coupon_data = array(
					'post_type'      => 'shop_coupon',
					"s"              => $coupon_code ,
					'posts_status'   => 'publish',
					'posts_per_page' => 1,
				);

				$query = new WP_Query($coupon_data);
				if($query->have_posts())
				{   
					while($query->have_posts())
					{
						$query->the_post();
						$coupon_id  = get_the_id();
					}
				}


				biz_write_log($coupon_id,'coupon_id'.date("Ymd"));
			  $curl = curl_init();
			  curl_setopt_array($curl, array(
				CURLOPT_URL => ODOO_URL.'/api/apply_coupon/sale_order',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
				  "params":{
					"order_id":'.$order_odoo_id.',
					"portal_id":'.$coupon_id.',
					"products":'.json_encode($product_list_id).',
					"db":"'.ODOO_DB.'"
					}
				}',
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '.$odoo_api->get_odoo_token().'',
					'Content-Type: application/json',
					'Cookie: session_id=49c1d3cb3a4847f7c4bc009bd9465ecec6619dbe'
				  ),
				));	
				biz_write_log('{
					"params":{
					  "order_id":'.$order_odoo_id.',
					  "portal_id":'.$coupon_id.',
					  "products":'.json_encode($product_list_id).',
					  "db":"'.ODOO_DB.'"
					  }
				  }','2022-12-14 all param to odoo');	
				  
				  biz_write_log('get_odoo_token='.$odoo_api->get_odoo_token(),'2022-12-14 all param to odoo');
				$response = curl_exec($curl);
				biz_write_log($response ,'Apply Coupon when checkout 11-11-2022');
				curl_close($curl);
			biz_write_log('{
				"params":{
				  "order_id":'.$order_odoo_id.',
				  "portal_id":'.$coupon_id.',
				  "products":'.json_encode($product_list_id).',
				  "db":"'.ODOO_DB.'"
				  }
			  }' ,'Apply Coupon when checkout 23-01-2023');
			}
			else {
				biz_write_log("no have apply coupon!!!" ,'woocommerce_order_status_completed_'.date("Ymd"));
				biz_write_log(json_encode($data_coupon) ,'No coupon');
			}
			

			if($_payment_method == 'aba_payway_aim')
			{	
				$aba_transaction_id = get_post_meta( $order_id, 'aba_transaction_id', true );
				$aba_tran = aba_PAYWAY_AIM::getTransaction( $order_id );

				if( $aba_tran["status"] == 0 && $aba_tran["amount"] == $order->get_total() ){
					$odoo_api 	= new OdooAPI();
					$msg	 	= $odoo_api->confirm_sale_order( $order_odoo_id, $aba_transaction_id );
					update_post_meta( $order_id, 'online_payment', 'paid' );
					update_post_meta( $order_id, 'odoo_confirm_sale_order_log', $msg );
					$response 	= json_decode($msg, true);

					if ( 
						!empty($response) && 
						isset($response["result"]) && 
						isset($response["result"]["success"]) && 
						$response["result"]["success"] == true
					){
						update_post_meta( $order_id, 'odoo_confirm_sale_order', 'success' );
						$order->update_status('processing', 'order_note'); 
						return true;
					}
					else{
						wc_add_notice( __( 'Payment was not successful, please contact our support.', 'textdomain' ), 'error' );
						return false;
					}
				}

			}
			else
			{	
				return true;
			}
		}
		else{
			wc_add_notice( __( 'Checkout was not successful, please contact our support.', 'textdomain' ), 'error' );
			return false;
		}
	}
