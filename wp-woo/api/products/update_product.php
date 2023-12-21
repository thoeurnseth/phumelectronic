<?php

/**
 * This function created for update products in WooCommerce
 * 
 * Param: target, action, data, product_id
 * 
 * Version: 0.0.1
 * Created At: 20-08-2021
 * Updated At: 20-08-2021
 */

if( isset($_POST['data']) && !empty($_POST['data']) && 
    isset($_POST['product_id']) && !empty($_POST['product_id']) && 
    $_POST['target'] == "products" && $_POST['action'] == "update_product" ) {

    $product_id = $_POST['product_id'];
    $product = $_POST['data'];
    $product = json_decode( stripslashes( $product ) ); // Remove back slash

    $count = check_product_exist( $product_id ); // Check Product Existing

    /**
     * Update Product
     */
    if ( $count > 0 ) {
        $response = $woocommerce->put('products/'.$product_id, $product); // Update
        $error::success( $response ); // Return data
    }

    $error::not_found(); // Product Not Found
}
else {
    $error::bad_request(); // Bad Request
}