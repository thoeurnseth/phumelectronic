<?php

/**
 * This function created for update delete in WooCommerce
 * 
 * Param: target, action, data, product_id
 * 
 * Version: 0.0.1
 * Created At: 20-08-2021
 * Updated At: 20-08-2021
 */

if( isset($_POST['product_id']) && !empty($_POST['product_id']) && 
    $_POST['target'] == "products" && $_POST['action'] == "delete_product" ) {

    $product_id = $_POST['product_id'];
    $count = check_product_exist( $product_id ); // Check Product Existing

    /**
     * Delete Product
     */
    if ( $count > 0 ) {
        $response = $woocommerce->delete('products/'.$product_id, array( "force" => true )); // Delete
        $error::success( $response ); // Return data
    }

    $error::not_found(); // Product Not Found
}
else {
    $error::bad_request(); // Bad Request
}