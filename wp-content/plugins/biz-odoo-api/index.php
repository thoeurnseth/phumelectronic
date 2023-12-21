<?php
/**
 * Plugin Name: Biz Odoo API
 * Description: This plugin is designed for WooCommerce app that sync data from/to Odoo through API
 * Version: 1.0.0
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

// WooCommerce Rest API Client
require_once ('vendor/autoload.php');

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

define( 'BIZ_ODOO_API_PLUGIN_DIR', __DIR__ );
define( 'BIZ_ODOO_API_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once "inc/OdooAPI.php";
require_once "inc/OdooWoocommerce.php";
require_once "odoo-add_action-add_to_cart.php";
require_once "odoo-add_action-process_checkout.php";
require_once "odoo-add_action-register_user.php";
require_once "odoo-add_action-customer_address.php";
require_once "odoo-callback-action-confirm_delivery.php";
require_once "wp-admin_enqueue_scripts.php";
require_once "wp-add_submenu_page.php";


use BizSolution\OdooAPI\OdooAPI;
use BizSolution\OdooAPI\OdooWoocommerce;

/**
 * Registering rest route /wp-json/odoo/products
 */
add_action( 'rest_api_init', function(){
	register_rest_route( 'odoo', '/products', [
		'methods'       =>      "GET",
		'callback'      =>      'biz_odoo_api_products_callback',
		'permission_callback' => '__return_true'
	]);
});


if( !function_exists("biz_odoo_api_products_callback") ):
	function biz_odoo_api_products_callback()
	{
		$odoo = new OdooWoocommerce();
		// $odoo->sync_provinces_from_doo();
		// $odoo->sync_districts_from_doo();
		$result = $odoo->sync_communes_from_doo('communes?take=200&page=5');
		return $result;
	}
endif;



/**
 * Rest route callback /wp-json/odoo/products
 */
if( !function_exists("biz_odoo_api_products_sync") ):
	function biz_odoo_api_products_sync($take = 5, $page = 1, $categories = [], $brands = [], $age_groups = [])
	{		
		$odoo_woo = new OdooWoocommerce();
		$result = $odoo_woo->check_odoo_products_within_woocommerce($take, $page, $categories, $brands, $age_groups);
		
		$total_page = $result["total_page"];

		$woocommerce = new Client(
			site_url(), 
			CONSUMER_KEY,
			CONSUMER_SECRET,
			[
				'wp_api' => true,
				'version' => 'wc/v3',
				'query_string_auth' => true,
				'verify_ssl' => false,
			]
		);

		biz_write_log($result['products'], '04-01-2022' );

		$woocommerce_result = $woocommerce->post('products/batch', $result['products']);
		$images_to_insert = [];

		if(!empty($result["products"])):

			if(!empty($result["products"]["create"])):
				biz_write_log($result['products'], '08-01-2022' );
				foreach( $result["products"]["create"] as $product ):
					if( $product["image"] != false ):
					
						if( !empty($product["meta_data"]) ):

							$meta_data = $product["meta_data"][0];
							$odoo_id = $meta_data['value'];


							if( array_key_exists('create', $woocommerce_result) ):
								biz_write_log($result['products'], '07-01-2022' );
								foreach( $woocommerce_result->create as $woocommerce_product ):
									if(!empty($woocommerce_product->meta_data)):
										$meta_data 				= $woocommerce_product->meta_data[0];
										$woocommerce_odoo_id 	= $meta_data->value;


										if( $odoo_id == $woocommerce_odoo_id ):
											$odoo_woocommerce = new OdooWoocommerce();
											$attach_id = $odoo_woocommerce->insert_featured_image($product["image"]);
											update_post_meta ($woocommerce_product->id, '_thumbnail_id', $attach_id);
										endif;
										
									endif;
									if($woocommerce_product->id == 0):
										$error = json_encode([
											'odoo_id' 	=> $odoo_id,
											'sku'		=> $product["sku"],
											'error' 	=> $woocommerce_product->error,
										]);
									endif;

								endforeach;

							endif;



						endif;
					endif;
				endforeach;
			endif;
		endif;


		$woocommerce_variation_result = [];
		if(!empty($result["product_variations"])):
			foreach( $result["product_variations"] as $key => $product ):
				$product_variation_odoo_id = $key;

				// if( array_key_exists('create', $woocommerce_result) ):
				if( property_exists($woocommerce_result, 'create') ):
					biz_write_log( $product, '05-01-2022' );
					foreach( $woocommerce_result->create as $woocommerce_product ):
						if(!empty($woocommerce_product->meta_data)):
							$meta_data 				= $woocommerce_product->meta_data[0];
							$woocommerce_odoo_id 	= $meta_data->value;

							if( $product_variation_odoo_id == $woocommerce_odoo_id ):
								$product_id = $woocommerce_product->id;
								$woocommerce_variation_result[] = $woocommerce->post('products/'.$product_id.'/variations/batch', $product);
							endif;
						endif;
					endforeach;
				endif;

				// if( array_key_exists('update', $woocommerce_result) ):				
				if( property_exists($woocommerce_result, 'update') ):						
					foreach( $woocommerce_result->update as $woocommerce_product ):
						if(!empty($woocommerce_product->meta_data)):
							$meta_data 				= $woocommerce_product->meta_data[0];
							$woocommerce_odoo_id 	= $meta_data->value;

							if( $product_variation_odoo_id == $woocommerce_odoo_id ):
								$product_id = $woocommerce_product->id;

								// Replace Variant ID of odoo to Variant ID of woocommerce for update
								// Date: 2022-03-29
								// Developer: Nimol	
								
								$vairant_id = "";
								global $wpdb;
								$product_variants 		= $wpdb->get_results( $wpdb->prepare( "SELECT ".$wpdb->posts.".ID FROM $wpdb->posts  WHERE " . $wpdb->posts . ".ID <> 'trash' AND post_type = 'product_variation' AND post_parent='$product_id' limit 1") );
								if(count($product_variants)){
									$vairant_id = $product_variants[0]->ID;
								}
								

								if($vairant_id){	
									if(count($product['update'])>0){
										$product['update'][0]['id'] = $vairant_id;
										biz_write_log($product, "log_product_varint_".date("Y_m_d"));								
										$woocommerce_variation_result[] = $woocommerce->post('products/'.$product_id.'/variations/batch', $product);
									}																	
								}
								
							endif;
						endif;
					endforeach;
				endif;
			endforeach;
		endif;

		return ["woocommerce_result"=>$woocommerce_result, "total_page" => $total_page, "page" => $page];
	}
endif;


if (!function_exists('biz_write_log')) {

	function biz_write_log($log, $type = 'debug') {
		ini_set( 'error_log', WP_CONTENT_DIR . '/'. $type .'.log' );
		if (true === WP_DEBUG) {
			if (is_array($log) || is_object($log)) {
				error_log(print_r($log, true));
			} else {
				error_log($log);
			}
		}
	}
}

function filter_woocommerce_rest_suppress_image_upload_error( $false, $upload, $product_get_id, $images ) { 
	return true;
};

add_filter( 'woocommerce_rest_suppress_image_upload_error', 'filter_woocommerce_rest_suppress_image_upload_error', 10, 4 ); 


	

	//Display Fields
add_action( 'woocommerce_product_after_variable_attributes', 'variable_fields', 10, 2 );
//JS to add fields for new variations
add_action( 'woocommerce_product_after_variable_attributes_js', 'variable_fields_js' );
//Save variation fields
add_action( 'woocommerce_process_product_meta_variable', 'variable_fields_process', 10, 1 );

function variable_fields( $loop, $variation_data ) {
?>	
	<p class="form-field form-row form-row-first">
		<label><?php _e( 'Odoo ID', 'woocommerce' ); ?></label>
		<input class="short" type="text" size="5" name="my_custom_field[<?php echo $loop; ?>]" value="<?php echo $variation_data['odoo_id'][0]; ?>"/>
	</p>
<?php
}

function variable_fields_js() {
?>
	<p class="form-field form-row form-row-first">\
		<label><?php _e( 'Odoo ID', 'woocommerce' ); ?></label>\
		<input class="short" type="text" size="5" name="my_custom_field[' + loop + ']" />\
	</p>\
<?php
}

function variable_fields_process( $post_id ) {
	if (isset( $_POST['variable_sku'] ) ) :
		$variable_sku = $_POST['variable_sku'];
		$variable_post_id = $_POST['variable_post_id'];
		$variable_custom_field = $_POST['odoo_id'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $variable_custom_field[$i] ) ) {
				update_post_meta( $variation_id, 'odoo_id', stripslashes( $variable_custom_field[$i] ) );
			}
		endfor;
	endif;
}

/**
 * Custom Field in product variant
 */
// regular variable products
add_action( 'woocommerce_product_after_variable_attributes', 'add_to_variations_metabox', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'save_product_variation', 20, 2 );

/*
* Add new inputs to each variation
*
* @param string $loop
* @param array $variation_data
* @return print HTML
*/
function add_to_variations_metabox( $loop, $variation_data, $variation ){

	$odoo_product_type = get_post_meta( $variation->ID, 'odoo_product_type', true );
	$checked = $odoo_product_type == 1 ? 'checked' : ''; ?>

		<div class="variable_custom_field">
			<p class="form-row form-row-full options border-0">
				<label class="selectit">
					<input type="radio" name="odoo_product_type[<?php echo $loop; ?>]" value="1" <?php echo $checked; ?>> Variant Product
				</label>
			</p>
		</div>
	<?php 
}