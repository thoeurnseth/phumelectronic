<?php

	namespace BizSolution\OdooAPI;

	class OdooWoocommerce
	{
		public function __construct()
		{
			//$this->sync_provinces_from_doo();
			//$this->sync_districts_from_doo();
			//$this->sync_communes_from_doo();
			//exit();
		}

		public function sync_provinces_from_doo()
		{
			global $wpdb;

			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_locations('provinces');
			$response = json_decode($response, true);
			$result   = $response;
			
			$location_odoo_ids = array_column($result, 'id');
			
			$query_array = [];
			foreach($location_odoo_ids as $location_odoo_id)
			{
				$query_array[]= (string) $location_odoo_id;
			}
			
			$args = array(
				'taxonomy'  => 'location',
				'hide_empty' => false,
				'meta_query' => array(
					"relation" => "AND",
					array(
						'key'       => 'odoo_id',
						'value'     => $query_array,
						'compare' 	=> 'IN'
					),
					array(
						'key'       => 'taxonomy_type',
						'value'     => 'province',
						'compare' 	=> '='
					)
				),
			);
			$terms = get_terms( $args );
			
			// $to_update_ids = wp_list_pluck($terms, 'term_id');
			$to_create_ids = $location_odoo_ids;

			$to_update_ids = [];
			foreach( $terms as $term )
			{
				$odoo_id = get_term_meta($term->term_id, 'odoo_id', true);
				//$odoo_id = explode('-', $odoo_id);
				//$odoo_id = $odoo_id[1];

				foreach($location_odoo_ids as $key => $location_odoo_id )
				{
					if($location_odoo_id == $odoo_id)
					{
						$to_update_ids[$term->term_id] = $odoo_id;
						unset($to_create_ids[$key]);
						break;
					}
				}
			}

			foreach( $to_create_ids as $key => $to_create_id )
			{
				foreach($result as $province)
				{
					if( $province['id'] == $to_create_id )
					{
						$term = wp_insert_term($province['name'], 'location', array(
							'slug'	=> 'province-'.sanitize_title($province['name'])
						));
						
						if( array_key_exists('term_id', $term) )
						{
							update_field( 'odoo_id', $province['id'], 'location_'.$term['term_id'] );
							update_field( 'name_kh', $province['province_kh'], 'location_'.$term['term_id'] );
							update_field( 'code', $province['province_code'], 'location_'.$term['term_id'] );
							update_field( 'taxonomy_type', 'province', 'location_'.$term['term_id'] );
							unset( $to_create_ids[$key] );
							break;
						}
					}
				}
			}

			// Sync Update location code at 2022-07-05
			foreach($to_update_ids as $term_id => $odoo_id){
				foreach($result as $province)
				{
					if( $province['id'] == $odoo_id )
					{
						update_field( 'code', $province['province_code'], 'location_'.$term_id );
						break;
					}
				}
			}

    		return ['status' => 'success'];
		}

		public function sync_districts_from_doo()
		{
			global $wpdb;

			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_locations('districts');
			$response = json_decode($response, true);

			$result = $response;

			$location_odoo_ids = array_column($result, 'id');

			$query_array = [];
			foreach($location_odoo_ids as $location_odoo_id)
			{
				$query_array[] = (string)$location_odoo_id;
			}


			$args = array(
				'taxonomy'  => 'location',
				'hide_empty' => false,
				'meta_query' => array(
					"relation" => "AND",
					array(
						'key'       => 'odoo_id',
						'value'     => $query_array,
						'compare' 	=> 'IN'
					),
					array(
						'key'       => 'taxonomy_type',
						'value'     => 'district',
						'compare' 	=> '='
					)
				)
			);

			$terms = get_terms( $args );

			// $to_update_ids = wp_list_pluck($terms, 'term_id');
			$to_create_ids = $location_odoo_ids;

			if( $terms )
			{
				$to_update_ids = [];
				foreach( $terms as $term )
				{
					$odoo_id = get_term_meta($term->term_id, 'odoo_id', true);
					foreach($location_odoo_ids as $key => $location_odoo_id )
					{
						if($location_odoo_id == $odoo_id)
						{
							$to_update_ids[$term->term_id] = $odoo_id;
							unset($to_create_ids[$key]);
							break;
						}
					}
				}
			}

			$terms = [];
			foreach($result as $province)
			{
				foreach( $to_create_ids as $key => $to_create_id )
				{
					if( $province['id'] == $to_create_id )
					{
						$terms[] = $province;
						$args = array(
							'taxonomy'  => 'location',
							'hide_empty' => false,
							'meta_query' => array(
								"relation" => "AND",
								array(
									'key'       => 'odoo_id',
									'value'     => (string) $province['province_id'][0]['id'],
									'compare' 	=> '='
								),
								array(
									'key'       => 'taxonomy_type',
									'value'     => 'province',
									'compare' 	=> '='
								)
							)
						);

						$parent_term = get_terms( $args );

						$term = wp_insert_term($province['name'], 'location', array(
							'parent'      	=> $parent_term[0]->term_id,
							'slug'			=> 'district-' . sanitize_title($province['name'])
						));

						
						if( array_key_exists('term_id', $term) )
						{
							update_field( 'odoo_id', $province['id'], 'location_'.$term['term_id'] );
							update_field( 'name_kh', $province['name_kh'], 'location_'.$term['term_id'] );
							update_field( 'code', $province['location_code'], 'location_'.$term['term_id'] );
							update_field( 'taxonomy_type', 'district', 'location_'.$term['term_id'] );
							unset( $to_create_ids[$key] );
							break;	
						}
					}
					else{
					}
				}
			}

			// Sync Update location code at 2022-07-05
			foreach($to_update_ids as $term_id => $odoo_id){
				foreach($result as $province)
				{
					if( $province['id'] == $odoo_id )
					{
						update_field( 'code', $province['location_code'], 'location_'.$term_id );
						break;
					}
				}
			}

    		return $terms;
		}
		
		// Get Commune from ODOO
		public function sync_communes_from_doo($param)
		{
			global $wpdb;

			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_locations($param);
			$response = json_decode($response, true);

			$result = $response;
			
			$location_odoo_ids = array_column($result, 'id');
			$query_array = [];
			foreach($location_odoo_ids as $location_odoo_id)
			{
				$query_array[] = (string)$location_odoo_id;
			}

			$args = array(
				'taxonomy'  => 'location',
				'hide_empty' => false,
				'meta_query' => array(
					"relation" => "AND",
					array(
						'key'       => 'odoo_id',
						'value'     => $query_array,
						'compare' 	=> 'IN'
					),
					array(
						'key'       => 'taxonomy_type',
						'value'     => 'commune',
						'compare' 	=> '='
					)
				)
			);

			$terms = get_terms( $args );

			// $to_update_ids = wp_list_pluck($terms, 'term_id');
			$to_create_ids = $location_odoo_ids;

			if( $terms )
			{
				$to_update_ids = [];
				foreach( $terms as $term )
				{
					$odoo_id = get_term_meta($term->term_id, 'odoo_id', true);
					foreach($location_odoo_ids as $key => $location_odoo_id )
					{
						if($location_odoo_id == $odoo_id)
						{
							$to_update_ids[$term->term_id] = $odoo_id;
							unset($to_create_ids[$key]);
							break;
						}
					}
				}
			}


			$terms = [];
			foreach($result as $province)
			{
				foreach( $to_create_ids as $key => $to_create_id )
				{
					if( $province['id'] == $to_create_id )
					{
						$terms[] = $province;
						$args = array(
							'taxonomy'  => 'location',
							'hide_empty' => false,
							'meta_query' => array(
								"relation" => "AND",
								array(
									'key'       => 'odoo_id',
									'value'     => (string) $province['district_id'][0]['id'],
									'compare' 	=> '='
								),
								array(
									'key'       => 'taxonomy_type',
									'value'     => 'district',
									'compare' 	=> '='
								)
							)
						);

						$parent_term = get_terms( $args );

						$term = wp_insert_term($province['name'], 'location', array(
							'parent'      	=> $parent_term[0]->term_id,
							'slug'			=> 'commune-' . sanitize_title($province['name'])
						));

						
						if( array_key_exists('term_id', $term) )
						{
							update_field( 'odoo_id', $province['id'], 'location_'.$term['term_id'] );
							update_field( 'name_kh', $province['name_kh'], 'location_'.$term['term_id'] );
							update_field( 'code', $province['location_code'], 'location_'.$term['term_id'] );
							update_field( 'taxonomy_type', 'commune', 'location_'.$term['term_id'] );
							unset( $to_create_ids[$key] );
							break;	
						}
					}
					else{
					}
				}
			}

			// Sync Update location code at 2022-07-05
			foreach($to_update_ids as $term_id => $odoo_id){
				foreach($result as $province)
				{
					if( $province['id'] == $odoo_id )
					{
						update_field( 'code', $province['location_code'], 'location_'.$term_id );
						break;
					}
				}
			}
    		return $terms;
		}

		/**
		 * Get All Odoo Prodcuts ID
		 */
		function move_products_to_trash() {
			global $wpdb;

			$odoo_api = new OdooAPI();
			$response = $odoo_api->get_odoo_product_ids();
			$response = json_decode($response, true);

			$woo_publish_product_ids = $this->get_all_woo_product_ids('product', 'publish', 0); // Publish Product
			// $woo_publish_variant_ids = $this->get_all_woo_product_ids('product_variation', 'publish', 1); // Publish Variant
			$woo_trash_product_ids   = $this->get_all_woo_product_ids('product', 'trash', 0); // Trash Product
			// $woo_trash_variant_ids   = $this->get_all_woo_product_ids('product_variation', 'trash', 1); // Trash Variant
			
			$product_id = '';
			$variant_id = '';
			$string_variant_id = '';
			
			foreach( $response as $product) {

				$product_id .= $product['id'].',';
				$variant_ids = $product['variants'];

				foreach( $variant_ids as $variant) {
					$variant_id .= $variant.',';
				}

				$string_variant_id .= $variant_id;

				// Clear catch
				$variant_id = '';
			}

			$odoo_product_ids = explode(",", substr( $product_id, 0, -1));
			$odoo_variant_ids = explode(",", substr( $string_variant_id, 0, -1));

			if( ! empty( $woo_publish_product_ids ) )
			{
				// Main Product Publish
				foreach( $woo_publish_product_ids as $product )
				{
					$woo_product_id = $product->ID;
					$meta_odoo_product_id = get_post_meta( $woo_product_id, 'odoo_id', true );

					if( in_array( $meta_odoo_product_id, $odoo_product_ids ) == false ) {
						$wpdb->update($wpdb->posts, ['post_status' => 'trash'], ['ID' => $woo_product_id]);
					}
				}
			}

			// if( ! empty( $woo_publish_variant_ids ) )
			// {
			// 	// Variant Product Publish
			// 	foreach( $woo_publish_variant_ids as $variant )
			// 	{
			// 		$woo_variant_id = $variant->ID;
			// 		$meta_odoo_variant_id = get_post_meta( $woo_variant_id, 'odoo_id', true );

			// 		if( in_array( $meta_odoo_variant_id, $odoo_variant_ids ) == false ) {
			// 			$wpdb->update($wpdb->posts, ['post_status' => 'trash'], ['ID' => $woo_variant_id]);
			// 		}
			// 	}
			// }
			
			if( ! empty( $woo_trash_product_ids ) )
			{
				// Main Product Trash
				foreach( $woo_trash_product_ids as $trash_product )
				{
					$woo_product_id = $trash_product->ID;
					$meta_trash_odoo_product_id = get_post_meta( $woo_product_id, 'odoo_id', true );

					if( in_array( $meta_trash_odoo_product_id, $odoo_product_ids ) ) {
						$wpdb->update($wpdb->posts, ['post_status' => 'publish'], ['ID' => $woo_product_id]);
					}
				}
			}

			// if( ! empty( $woo_trash_variant_ids ) )
			// {
			// 	// Variant Product Trash
			// 	foreach( $woo_trash_variant_ids as $trash_variant )
			// 	{
			// 		$woo_variant_id = $trash_variant->ID;
			// 		$meta_trash_odoo_variant_id = get_post_meta( $woo_variant_id, 'odoo_id', true );

			// 		if( in_array( $meta_trash_odoo_variant_id, $odoo_variant_ids ) ) {
			// 			$wpdb->update($wpdb->posts, ['post_status' => 'publish'], ['ID' => $woo_variant_id]);
			// 		}
			// 	}
			// }
			
			$product_id = '';
			$string_variant_id = '';
		}		


		/*
		Function Name: check_odoo_products_within_woocommerce
		Get Product and Variant Batch for update in woo
		Return Format:
		$result = [
		 	'total_page' => 5,
			'products' => ['create' => [...], 'update' => [...] ],
			'product_variations' => [13 => ['create' => [...], 13 => 'update' => [...],... ]]
		];
		*/
		public function check_odoo_products_within_woocommerce($take, $page, $categories = [], $brands = [], $age_groups = [])
		{
			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_products($take, $page);
			$response = !empty($response)? json_decode($response, true) : [];
			
			$result = $response["result"];
			
			$odoo_id_list = [];
			$odoo_id_str  = '';

			$uncategorized_cat = get_term_by('slug', 'uncategorized', 'product_cat');

			$inc = 0;
			foreach( $result as $product )
			{
				$odoo_id 		= $product["id"];
				if(!empty($product["id"]))
				{
					$odoo_id_list[] = $product["id"];
					if($inc > 0)
						$odoo_id_str  	.= ', ';
					$odoo_id_str  	.= "'".$odoo_id."'";
					$inc++;
				}
			}

			$existing_products = [];
			$existing_products = $this->get_existing_product_by_odoo_id($odoo_id_str); 

			$to_create_odoo_id = [];
			$to_update_odoo_id = [];
            biz_write_log(json_encode($odoo_id_list) ,'ODOO ID LIST');
            biz_write_log(json_encode($existing_products) ,'ODOO EXIST PRODUCT');
			foreach( $odoo_id_list as $odoo_id_index => $odoo_id_value )
			{
				$exist = false;
				foreach($existing_products as $existing_product)
				{
					$odoo_id = get_post_meta( $existing_product, 'odoo_id', true );
					// if product exists
					if( $odoo_id_value == $odoo_id )
					{   
						$exist = true;
						$to_update_odoo_id[$existing_product] = $odoo_id_value;
						break;
					}
				}
				if(!$exist)
				{
					$to_create_odoo_id[] = $odoo_id_value;
				}
			}
			$product_array = [];

			foreach( $result as $product )
			{
				$group_attr = [];
				$attributes_temp = [];
				$default_attributes_temp = [];
				$variations_temp = [];
				$category_ids = [];
				$brand_ids = [];

				

				foreach($product['woo_product_catg_ids'] as $odoo_cat_id)
				{
					foreach($categories as $cat_id)
					{
						if(!empty($cat_id['odoo_id'])) {
							if( $cat_id['odoo_id'] == $odoo_cat_id["id"] )
							{
								$category_ids[] = ["id" => $cat_id['wp_id']];
							}	
						}
					}
				}
				if(empty($category_ids))
				{
					$category_ids[] = ["id"=>$uncategorized_cat->term_id];
				}

				// foreach($product['brand_id'] as $odoo_brand_id)
				// {
					foreach($brands as $brand_id)
					{
						// if( $brand_id['odoo_id'] == $odoo_brand_id["id"] )
						if( $brand_id['odoo_id'] == $product['brand_id']['id'] )
						{
							
							// $brand_ids[] = ["id" => $brand_id['wp_id']];
							$brand_ids[] =  $brand_id['wp_id'];
						}
					}
				// }

				if( !empty($product["variants"]) )
				{
					foreach($product["variants"] as $variant)
					{
						if(!empty($variant["id"]))
						{
							if( empty($variant["attributes"]) )
							{
								$attributes_temp[] = [
									"id"			=>	4,
									"name"			=>	"Options",
									"visible"		=>	true,
									"variation"		=>	true,
									"options"		=>	[
										"Normal"
									],
								];
								$default_attributes_temp[] = [
						            'id' => 4,
						            'option' => 'Normal'
								];
							}
							else{

								foreach( $variant["attributes"] as $attribute )
								{
									foreach ($attribute as $attr_key => $attr_value)
									{
										$attr_value = ucwords($attr_value);
										$attr_key 	= strtolower($attr_key);
										$term = get_term_by('name', $attr_value, 'pa_' . $attr_key);

										$term_id = 0;
										if(is_null($term) || empty($term))
										{
											$term = wp_insert_term( $attr_value, 'pa_' . $attr_key );

											if( array_key_exists('term_id', $term) )
												$term_id = $term["term_id"];
										}
										else{
											$term_id = $term->term_id;
										}


										$taxonomy_id = wc_attribute_taxonomy_id_by_name('pa_' . $attr_key);

										$group_attr[$attr_key]['id'] = $taxonomy_id;
										if(!array_key_exists('items', $group_attr[$attr_key]))
										{
											$group_attr[$attr_key]['items'] = [];
										}
										if (!in_array($attr_value, $group_attr[$attr_key]['items']))
										{
											$group_attr[$attr_key]['items'][] = $attr_value;
										}
									}
								}
							}
						}
					}
				}
				
				foreach($group_attr as $key => $value)
				{
					$attributes_temp[] = [
						"id"			=>	$value['id'],
						"name"			=>	ucwords($key),
						"visible"		=>	true,
						"variation"		=>	true,
						"options"		=>	$value['items'],
					];
				}
			
				foreach( $to_update_odoo_id as $index => $value )
				{
					if( $product["id"] == $value )
					{
						$product_array["update"][] = [
							"id"					=> $index,
							"name"					=> $product["name"],
							//"sku"				=> (string) $product["default_code"],
							//"sku"				=> 'Hello',
							"regular_price"			=> (string) $product["price_unit"],
							"sale_price"			=> (string) $product["price_discount"],
							// 'cambo_exspire_date'	=> $product["combo_expired_date"],
							"image"					=> $product["image"],
							"manage_stock"			=> false,
							"stock_status "			=> "instock",
							"attributes"			=> $attributes_temp,
							'categories'			=> $category_ids,
							'brands'				=> $brand_ids,
							'default_attributes' 	=> $default_attributes_temp,
							// "description"		=>	""
							'meta_data' => [
								[
									'key' => 'cambo_exspire_date', 
									'value' => $product["combo_expired_date"]
								]
							]
						];
						biz_write_log($product["combo_expired_date"],'combo_expired_date');
						break;
					}
				}
				foreach( $to_create_odoo_id as $index => $value )
				{
					
					if( $product["id"] == $value )
					{
						$product_array["create"][] = [
							"name"					=> str_replace('"', '', $product["name"]),
							//"sku"				=>  (string)$product["default_code"],
							//"sku"				=>  "Hello",
							"regular_price"			=> (string) $product["price_unit"],
                            "sale_price"			=> (string) $product["price_discount"],
							// 'cambo_exspire_date'    => $product["combo_expired_date"],
							'type' 					=> 'variable',
							// "description"		=> "",
							"manage_stock"			=> false,
							"stock_status"			=> "instock",
							"image"					=> $product["image"],
							"attributes"			=> $attributes_temp,
							'default_attributes' 	=> $default_attributes_temp,
							'categories'			=> $category_ids,
							'brands'				=> $brand_ids,
							'meta_data' => [
								[
									'key' => 'odoo_id', 
									'value' => (string) $product["id"]
								],
								[
									'key' => 'cambo_exspire_date', 
									'value' => $product["combo_expired_date"]
								]
							]
						];
						break;
					}
				}
			}


			$odoo_id_list = [];
			$odoo_id_str  = '';

			$inc = 0;
			foreach( $result as $product )
			{
				if( !empty($product["variants"]) )
				{
					foreach($product["variants"] as $variant)
					{
						$odoo_id 		= $variant["id"];
						if(!empty($variant["id"]))
						{
							$odoo_id_list[] = $variant["id"];
							if($inc > 0)
								$odoo_id_str  	.= ', ';
							$odoo_id_str  	.= "'".$odoo_id."'";
							$inc++;
						}
					}
				}
			}
			

			// Get product id as array in woo commerce base on odoo id. 
			// Ex: $existing_products = [21,33,444,..]
			$existing_products = $this->get_existing_product_varian_by_odoo_id($odoo_id_str);


			// Filter product id for create and update
			// Ex: $to_create_odoo_id = [13,43,..]
			// Ex: $to_update_odoo_id = [ 14=> 32, 45=> 33,...]
			$to_create_odoo_id = [];
			$to_update_odoo_id = [];
			foreach( $odoo_id_list as $odoo_id_index => $odoo_id_value )
			{
				$exist = false;
				foreach($existing_products as $existing_product)
				{
					$odoo_id = get_post_meta( $existing_product, 'odoo_id', true );
					// if product exists
					if( $odoo_id_value == $odoo_id )
					{
						$exist = true;
						$to_update_odoo_id[$existing_product] = $odoo_id_value;
						break;
					}
				}
				if(!$exist)
				{
					$to_create_odoo_id[] = $odoo_id_value;
				}
			}

			$product_variation_array = [];

			foreach( $result as $product )
			{
				foreach( $to_update_odoo_id as $index => $value )
				{
					// $index = woo commerce product id
					// $value = odoo id 
					foreach( $product["variants"] as $variant )
					{ 
						$variation_data = [];
						$variation_data["id"] = $variant["id"];
						$variation_data["name"] = $variant["name"];
						$variation_data["default_code"] = $variant["default_code"];
						// $variation_data["sale_price"] = $variant;
						$variation_data["regular_price"] = (string) $variant["price_unit"];
						$variation_data["sale_price"] = (string) $variant["price_discount"];

						$attributes = [];
						if( empty($variant["attributes"]) )
						{
							$attributes = [
						        [
						            'id' => 4,
						            'option' => 'Normal'
						        ]
						    ];
						}
						else{
							foreach( $variant["attributes"] as $attribute )
							{
								foreach ($attribute as $attr_key => $attr_value)
								{
									$attr_value = ucwords($attr_value);
									$attr_key 	= strtolower($attr_key);
									$term = get_taxonomy( 'pa_' . $attr_key );

									$term = wc_attribute_taxonomy_id_by_name('pa_' . $attr_key);

									$attributes[] = 
										[
											"id"			=>	$term,
											'option' 		=>  $attr_value
										]
									;
								}
							}
						}


						if( !empty($variant["brand_id"]) )
						{
							$term = get_taxonomy( 'pa_brands' );
							$term = wc_attribute_taxonomy_id_by_name('pa_brands');

							foreach ($variant["brand_id"] as $brand)
							{
								$attributes[] = 
									[
										"id"			=>	$term,
										'option' 		=>  ucwords($brand['name'])
									]
								;
							}
						}
						if( $variant["id"] == $value )
						{
							$product_variation_array[$product["id"]]["update"][] = [
								"id"				=>	$index,
								"name"				=>	$variant["name"],
								"sku"				=>	$variant["default_code"],
								"regular_price"		=>	(string) $variant["price_unit"],
								"sale_price"		=>	(string) $variant["price_discount"],
								// 'cambo_exspire_date'=> $product["combo_expired_date"],
								"manage_stock"		=>	false,
								"stock_status"		=> 	"instock",
								"image"				=>	$variant["image"],
								"attributes" 		=>	$attributes,
								'meta_data' => [
									[
										'key' => 'odoo_id', 
										'value' => (string) $variant["id"]
									],
									[
										'key' => 'cambo_exspire_date', 
										'value' => $product["combo_expired_date"]
									]
								]
								// "description"		=>	"",
							];

							break;
						}
					}
				}
				foreach( $to_create_odoo_id as $index => $value )
				{
					foreach( $product["variants"] as $variant )
					{
						$variation_data = [];
						$variation_data["id"] = $variant["id"];
						$variation_data["name"] = $variant["name"];
						$variation_data["default_code"] = $variant["default_code"];
						// $variation_data["sale_price"] = $variant;
						$variation_data["regular_price"] = (string) $variant["price_unit"];
						$variation_data["sale_price"] = (string) $variant["price_discount"];
						
						$attributes = [];
						if( empty($variant["attributes"]) )
						{
							$attributes = [
						        [
						            'id' => 4,
						            'option' => 'Normal'
						        ]
						    ];
						}
						else{
							foreach( $variant["attributes"] as $attribute )
							{
								foreach ($attribute as $attr_key => $attr_value)
								{
									$attr_value = ucwords($attr_value);
									$attr_key 	= strtolower($attr_key);
									$term = get_taxonomy( 'pa_' . $attr_key );

									$term = wc_attribute_taxonomy_id_by_name('pa_' . $attr_key);

									$attributes[] = 
										[
											"id"			=>	$term,
											'option' 		=>  $attr_value
										]
									;
								}
							}
						}
						if( $variant["id"] == $value )
						{
							$product_variation_array[$product["id"]]["create"][] = [
								"name"				=>	str_replace('"', '', $variant["name"]),
								"sku"				=>	$variant["default_code"],
								"regular_price"		=>	(string) $variant["price_unit"],
								"sale_price"		=>	(string) $variant["price_discount"],
								// 'cambo_exspire_date'=> $product["combo_expired_date"],
								// "description"		=>	"",
								"manage_stock"		=>	false,
								"stock_status"		=> 	"instock",
								"image"				=>	$variant["image"],
								"attributes" 		=>	$attributes,
								'meta_data' => [
									[
										'key' => 'odoo_id', 
										'value' => (string) $variant["id"]
									],
									[
										'key' => 'cambo_exspire_date', 
										'value' => $product["combo_expired_date"]
									]
								]
							];
						
							break;
						}
					}
				}
			}

			 biz_write_log( [
				'total_page' => $response["total_page"],
				'products' => $product_array,
				'product_variations' => $product_variation_array
			 ], '03-01-2022' );

			return [
				'total_page' => $response["total_page"],
				'products' => $product_array,
				'product_variations' => $product_variation_array
			];
		}

		private function get_existing_product_by_odoo_id( $odoo_id )
		{
			if(empty($odoo_id)) return [];

			global $wpdb;
			$products 		= $wpdb->get_results( $wpdb->prepare( "SELECT ".$wpdb->posts.".ID FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON " . $wpdb->posts . ".ID = ".$wpdb->postmeta.".post_id WHERE " . $wpdb->posts . ".ID <> 'trash' AND post_type = 'product' AND meta_key='odoo_id' AND meta_value IN ($odoo_id)") );

			
			$product_ids 	= wp_list_pluck($products, 'ID');
			$product_ids 	= array_unique($product_ids);

			return $product_ids;
		}

		// private function get_varian_id( $product_id )
		// {
		
		// 	global $wpdb;
		// 	$product_variants 		= $wpdb->get_results( $wpdb->prepare( "SELECT ".$wpdb->posts.".ID FROM $wpdb->posts  WHERE " . $wpdb->posts . ".ID <> 'trash' AND post_type = 'product_variation' AND post_parent='$product_id' limit 1") );
		// 	if(count($product_variants)){
		// 		return $product_variants[0]->ID;
		// 	}
		// 	return "";
		// }

		private function get_existing_product_varian_by_odoo_id( $odoo_id )
		{
			if(empty($odoo_id)) return [];

			global $wpdb;
			$products 		= $wpdb->get_results( $wpdb->prepare( "SELECT ".$wpdb->posts.".ID FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON " . $wpdb->posts . ".ID = ".$wpdb->postmeta.".post_id WHERE " . $wpdb->posts . ".ID <> 'trash' AND post_type = 'product_variation' AND meta_key='odoo_id' AND meta_value IN ($odoo_id)") );

			
			$product_ids 	= wp_list_pluck($products, 'ID');
			$product_ids 	= array_unique($product_ids);

			return $product_ids;
		}

		/**
		 * Get all Woo publish proudct ID
		 */
		private function get_all_woo_product_ids($post_type, $publish_trash, $product_type )
		{
			global $wpdb;
			$products = $wpdb->get_results(
				$wpdb->prepare(
					// "SELECT DISTINCT p.ID FROM ".$wpdb->posts." p JOIN ".$wpdb->postmeta." m1 ON p.ID = m1.post_id WHERE p.post_status = '".$publish_trash."' AND p.post_type = '".$post_type."' AND m1.meta_key = 'odoo_product_type' AND m1.meta_value = $product_type"
					"SELECT DISTINCT p.ID FROM ".$wpdb->posts." p JOIN ".$wpdb->postmeta." m1 ON p.ID = m1.post_id WHERE p.post_status = '".$publish_trash."' AND p.post_type = '".$post_type."'"
				)			
			);
			return $products;
		}
		
		public function sync_categories_from_doo()
		{
			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_categories();
			$response = json_decode($response, true);

			$result = $response;

			$odoo_id_list = [];
			$odoo_id_str  = '';

			$inc = 0;
			foreach( $result as $category )
			{
				$odoo_id = $category["id"];
				if(!empty($category["id"]))
				{
					$odoo_id_list[] = $category["id"];
					if($inc > 0)
						$odoo_id_str .= ', ';
					$odoo_id_str .= "'".$odoo_id."'";
					$inc++;
				}
			}
			$existing_categories = $this->get_existing_category_by_odoo_id($odoo_id_str);

			$to_create_odoo_id = [];
			$to_update_odoo_id = [];
			foreach( $odoo_id_list as $odoo_id_index => $odoo_id_value )
			{
				$exist = false;
				foreach($existing_categories as $existing_category_id)
				{
					$odoo_id = get_term_meta( $existing_category_id, 'odoo_id', true );
					// if category exists
					if( $odoo_id_value == $odoo_id )
					{
						$exist = true;
						$to_update_odoo_id[$existing_category_id] = $odoo_id_value;
						break;
					}
				}
				if(!$exist)
				{
					$to_create_odoo_id[] = $odoo_id_value;
				}
			}

			$json_string = [];

			foreach( $result as $category )
			{
				foreach( $to_update_odoo_id as $index => $value )
				{
					if( $category["id"] == $value )
					{
						// $parent_odoo_id = get_term_meta( $category["parent_id"], 'odoo_id', true );
						wp_update_term( $index, 
							'product_cat', 
							array(
								// 'parent' => $parent_odoo_id,
								'name' => $category["name"],
								// 'description' => 'Description for category',
								
							)
						);

						update_term_meta($index, 'odoo_id', $category["id"]);
						$json_string[] = [
							"wp_id" 	=> $index,
							"odoo_id" 	=> $category["id"],
						];
					}
				}
				foreach( $to_create_odoo_id as $index => $value )
				{
					if( $category["id"] == $value )
					{
						$cat_id = wp_insert_term(
							$category["name"],
							'product_cat',
							array(
								// 'description' => 'Description for category',
								// 'parent' => (int) $category["parent_id"],
							)
						);

						if( !array_key_exists('errors',$cat_id) )
						{
							update_term_meta($cat_id["term_id"], 'odoo_id', $category["id"]);
							$json_string[] = [
								"wp_id" 	=> $cat_id["term_id"],
								"odoo_id" 	=> $category["id"],
							];
						}
					}
				}
			}

			// delete category
			$inc = 0;
			foreach( $result as $cate )
			{
				$odoo_id = $cate["id"];
				if(!empty($cate["id"])){
					$odoo_id_list[] = $cate["id"];
					if($inc > 0)
						$odoo_id_str .= ', ';
					$odoo_id_str .= "'".$odoo_id."'";
					$inc++;
				}
				$id_from_odoo = $odoo_id_list;
			}
			$existing_categorie = $this->get_all_id_category($odoo_id_str);
			$to_delete_odoo_id = [];

			foreach($existing_categorie as $existing_category_id)
			{
				$odoo_id = get_term_meta( $existing_category_id, 'odoo_id', true );
				if(!in_array($odoo_id, $id_from_odoo)){
					biz_write_log($existing_category_id,'existing_category_id');
					wp_delete_term($existing_category_id,'product_cat');
				} 
			}
			return $json_string;
		}

		private function get_existing_category_by_odoo_id( $odoo_id )
		{
			global $wpdb;
		
			$categories = $wpdb->get_results( $wpdb->prepare( 
				"SELECT ".$wpdb->terms.".term_id 
					FROM $wpdb->terms 
					INNER JOIN $wpdb->termmeta ON " . $wpdb->terms . ".term_id = ".$wpdb->termmeta.".term_id 
					INNER JOIN $wpdb->term_taxonomy ON " . $wpdb->terms . ".term_id = ".$wpdb->term_taxonomy.".term_id 
					WHERE ".$wpdb->termmeta.".meta_value IN ($odoo_id) 
					AND ".$wpdb->termmeta.".meta_key='odoo_id'
					AND ".$wpdb->term_taxonomy.".taxonomy='product_cat'"
			));

			$category_ids 	= wp_list_pluck($categories, 'term_id');
			$category_ids 	= array_unique($category_ids);

			return $category_ids;
		}

		private function get_all_id_category($odoo_id){
			$arg = array(
				'taxonomy'=> 'product_cat',
				   'hide_empty'=> false,
		   );
		   $data = [];
			$result_select = get_terms($arg);
			foreach($result_select as $value){
				$term_id = $value->term_id;
				$data[] = $term_id;
			} 
			return $data;
		}


		public function sync_brands_from_doo()
		{
			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_brands();
			$response = json_decode($response, true); 

			$result = $response;

			$odoo_id_list = [];
			$odoo_id_str  = '';

			$inc = 0;
			foreach( $result as $brand )
			{
				$odoo_id 		= $brand["id"];
				if(!empty($brand["id"]))
				{
					$odoo_id_list[] = $brand["id"];
					if($inc > 0)
						$odoo_id_str  	.= ', ';
					$odoo_id_str  	.= "'".$odoo_id."'";
					$inc++;
				}
			}
			$existing_brands = $this->get_existing_brand_by_odoo_id($odoo_id_str);
			// return $existing_brands;

			$to_create_odoo_id = [];
			$to_update_odoo_id = [];
			foreach( $odoo_id_list as $odoo_id_index => $odoo_id_value )
			{
				$exist = false;
				foreach($existing_brands as $existing_brand_id)
				{
					$odoo_id = get_term_meta( $existing_brand_id, 'odoo_id', true );
					// if category exists
					if( $odoo_id_value == $odoo_id )
					{
						$exist = true;
						$to_update_odoo_id[$existing_brand_id] = $odoo_id_value;
						break;
					}
				}
				if(!$exist)
				{
					$to_create_odoo_id[] = $odoo_id_value;
				}
			}

			$json_string = [];

			foreach( $result as $brand )
			{
				foreach( $to_update_odoo_id as $index => $value )
				{
					if( $brand["id"] == $value )
					{
						// $parent_odoo_id = get_term_meta( $brand["parent_id"], 'odoo_id', true );
						wp_update_term( $index, 
							'pwb-brand', 
							array(
								// 'description' => 'Description for brand',
								'name' => $brand["name"],
								// 'parent' => $parent_odoo_id,
							)
						);

						update_term_meta($index, 'odoo_id', $brand["id"]);
						$json_string[] = [
							"wp_id" 	=> $index,
							"odoo_id" 	=> $brand["id"],
						];
					}
				}
				foreach( $to_create_odoo_id as $index => $value )
				{
					if( $brand["id"] == $value )
					{
						$cat_id = wp_insert_term(
							$brand["name"],
							'pwb-brand', // pwb-brand //pa_brands
							array(
								// 'description' => 'Description for brand',
								// 'parent' => (int) $brand["parent_id"],
							)
						);

						if( !array_key_exists('errors',$cat_id) )
						{
							update_term_meta($cat_id["term_id"], 'odoo_id', $brand["id"]);
							$json_string[] = [
								"wp_id" 	=> $cat_id["term_id"],
								"odoo_id" 	=> $brand["id"],
							];
						}
					}
				}
			}

			return $json_string;
		}

		private function get_existing_brand_by_odoo_id( $odoo_id )
		{
			global $wpdb;
		
			$brands 	= $wpdb->get_results( $wpdb->prepare( 
				"SELECT ".$wpdb->terms.".term_id 
					FROM $wpdb->terms 
					INNER JOIN $wpdb->termmeta ON " . $wpdb->terms . ".term_id = ".$wpdb->termmeta.".term_id 
					INNER JOIN $wpdb->term_taxonomy ON " . $wpdb->terms . ".term_id = ".$wpdb->term_taxonomy.".term_id 
					WHERE ".$wpdb->termmeta.".meta_value IN ($odoo_id) 
					AND ".$wpdb->termmeta.".meta_key='odoo_id'
					AND ".$wpdb->term_taxonomy.".taxonomy='pwb-brand'"
			));

			$brand_ids 	= wp_list_pluck($brands, 'term_id');
			$brand_ids 	= array_unique($brand_ids);

			return $brand_ids;
		}

		public function sync_agegroup_from_doo()
		{
			$odoo_api = new OdooAPI();

			$response = $odoo_api->get_age_groups();

			$response = json_decode($response, true);

			$result = $response;

			$odoo_id_list = [];
			$odoo_id_str  = '';

			$inc = 0;
			foreach( $result as $category )
			{
				
				$odoo_id 		= $category["id"];
				if(!empty($category["id"]))
				{
					$odoo_id_list[] = $category["id"];
					if($inc > 0)
						$odoo_id_str  	.= ', ';
					$odoo_id_str  	.= "'".$odoo_id."'";
					$inc++;
				}
			}
			$existing_categories = $this->get_existing_agegroup_by_odoo_id($odoo_id_str);
			
			$to_create_odoo_id = [];
			$to_update_odoo_id = [];
			foreach( $odoo_id_list as $odoo_id_index => $odoo_id_value )
			{
				$exist = false;
				foreach($existing_categories as $existing_category_id)
				{
					$odoo_id = get_term_meta( $existing_category_id, 'odoo_id', true );
					// if category exists
					if( $odoo_id_value == $odoo_id )
					{
						$exist = true;
						$to_update_odoo_id[$existing_category_id] = $odoo_id_value;
						break;
					}
				}
				if(!$exist)
				{
					$to_create_odoo_id[] = $odoo_id_value;
				}
			}

			$json_string = [];

			foreach( $result as $category )
			{
				foreach( $to_update_odoo_id as $index => $value )
				{
					if( $category["id"] == $value )
					{
						// $parent_odoo_id = get_term_meta( $category["parent_id"], 'odoo_id', true );
						wp_update_term( $index, 
							'age-categories', 
							array(
								// 'description' => 'Description for category',
								'name' => $category["name"],
								// 'parent' => $parent_odoo_id,
							)
						);

						update_term_meta($index, 'odoo_id', $category["id"]);
						$json_string[] = [
							"wp_id" 	=> $index,
							"odoo_id" 	=> $category["id"],
						];
					}
				}
				foreach( $to_create_odoo_id as $index => $value )
				{
					if( $category["id"] == $value )
					{
						$cat_id = wp_insert_term(
							$category["name"],
							'age-categories',
							array(
								// 'description' => 'Description for category',
								// 'parent' => (int) $category["parent_id"],
							)
						);

						if( !array_key_exists('errors',$cat_id) )
						{
							update_term_meta($cat_id["term_id"], 'odoo_id', $category["id"]);
							$json_string[] = [
								"wp_id" 	=> $cat_id["term_id"],
								"odoo_id" 	=> $category["id"],
							];
						}
					}
				}
			}

			return $json_string;
		}

		private function get_existing_agegroup_by_odoo_id( $odoo_id )
		{
			global $wpdb;
		
			$categories 	= $wpdb->get_results( $wpdb->prepare( 
				"SELECT ".$wpdb->terms.".term_id 
					FROM $wpdb->terms 
					INNER JOIN $wpdb->termmeta ON " . $wpdb->terms . ".term_id = ".$wpdb->termmeta.".term_id 
					INNER JOIN $wpdb->term_taxonomy ON " . $wpdb->terms . ".term_id = ".$wpdb->term_taxonomy.".term_id 
					WHERE ".$wpdb->termmeta.".meta_value IN ($odoo_id) 
					AND ".$wpdb->termmeta.".meta_key='odoo_id'
					AND ".$wpdb->term_taxonomy.".taxonomy='age-categories'"
			));

			$category_ids 	= wp_list_pluck($categories, 'term_id');
			$category_ids 	= array_unique($category_ids);

			return $category_ids;
		}

		public function insert_featured_image( $image_src )
		{
			include_once( ABSPATH . 'wp-admin/includes/image.php' );
			$imageurl =  $image_src;
			$imagetype = explode('/', getimagesize($imageurl)['mime']);
			$imagetype = end($imagetype);
			$uniq_name = date('dmY').''.(int) microtime(true); 
			$filename = $uniq_name.'.'.$imagetype;

			$uploaddir = wp_upload_dir();
			$uploadfile = $uploaddir['path'] . '/' . $filename;
			$contents= file_get_contents($imageurl);
			$savefile = fopen($uploadfile, 'w');
			fwrite($savefile, $contents);
			fclose($savefile);

			$wp_filetype = wp_check_filetype(basename($filename), null );
			$attachment = array(
			    'post_mime_type' => $wp_filetype['type'],
			    'post_title' => $filename,
			    'post_content' => '',
			    'post_status' => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $uploadfile );
			$imagenew = get_post( $attach_id );
			$fullsizepath = get_attached_file( $imagenew->ID );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			return $attach_id;
		}

		public function get_term_id_by_odoo_id($odoo_id){
			global $wpdb;
			$term_meta = $wpdb->get_results( $wpdb->prepare("SELECT term_id FROM $wpdb->termmeta WHERE meta_key = 'odoo_id' AND meta_value = '".$odoo_id."'") );
			if(count($term_meta)){
				return $term_meta[0]->term_id;
			}
			return "";
		}
	}