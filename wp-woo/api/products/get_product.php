<?php

/**
 * This function created for create products in WooCommerce
 * 
 * Param: target, action, product_id
 * 
 * Version: 0.0.1
 * Created At: 20-08-2021
 * Updated At: 20-08-2021
 */

if( $_POST['target'] == "products" && $_POST['action'] == "get_product" ) {

    /**
     * Get Single Product
     */
    if ( isset($_POST['product_id']) && !empty($_POST['product_id']) ) {
        
        $product_id = $_POST['product_id']; // Product ID
        $count = check_product_exist( $product_id ); // Check Product Existing

        if ( $count > 0 ) {
            $response = $woocommerce->get('products/'.$product_id); // Get
            $error::success( $response ); // Return data
        }

        $error::not_found(); // Product Not Found
    }

    /**
     * Get All Products
     */
    $data = $woocommerce->get('products'); // Query Products
    $error::success( $data ); // Return data
}
else {
    // Bad Request
    $error::bad_request();
}